<?php

namespace Pi2\Fractalia\SGSDWebMonitorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Pi2\Fractalia\SmsBundle\Manager\NotifyVieverManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Pi2\Fractalia\Entity\SGSD\Incidencia;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Pi2\Fractalia\SGSDReportBundle\Entity\Rechazada;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $request = $this->getRequest();

        $services = $this->container->getParameter('sgsd_web_monitor');

        $servicios = array();

        foreach ($services['servicios'] as $key => $value)
        {
            $servicios[$key] = $key;
        }

        $formServicios = $this->createFormBuilder()
            ->add('servicioAfectado', 'choice', array('choices' => $servicios))
            ->add('buscar', 'submit', array('label' => 'Ir al Servicio'))
            ->getForm();

        if ($request->getMethod() == 'POST')
        {
            $formServicios->bind($request);

            if ($formServicios->isValid())
            {
                return $this->redirect($this->generateUrl('pi2_frac_sgsd_web_monitor_categorias', array('servicio' => $formServicios->get('servicioAfectado')->getData())));
            }
        }

        return $this->render('Pi2FracSGSDWebMonitorBundle:Default:index.html.twig', array('formServicios' => $formServicios->createView()));
    }

    public function monitorCategoriasAction()
    {
        //date_default_timezone_set("Europe/Madrid");  
        $incidencias = array();
        $services = $this->container->getParameter('sgsd_web_monitor');
        $request = $this->getRequest();
        try
        {

            $em = $this->getDoctrine()->getManager();

            $usuariosArray = array();
            $serviciosArray = $services['servicios'][$request->get('servicio')]['buzones'];

            //Intervención para implementación historia SGSD-220
            foreach ($serviciosArray as $arrayIndex)
            {
                foreach ($arrayIndex as $servicio => $arrayUsuarios)
                {
                    $buzones[] = $servicio;
                    if (count($arrayUsuarios) > 0)
                    {
                        foreach ($arrayUsuarios as $user)
                        {
                            $usuariosArray[$user][] = $servicio;
                        }
                    }
                    else
                    {
                        continue;
                    }
                }
            }

            //FIX bug
            if (isset($services['servicios'][$request->get('servicio')]['categorias']['TICKETS OPEN']['filter_buzones']) and
                null !== $services['servicios'][$request->get('servicio')]['categorias']['TICKETS OPEN']['filter_buzones'] and
                count($services['servicios'][$request->get('servicio')]['categorias']['TICKETS OPEN']['filter_buzones']) > 0)
            {
                $filter_buzones = $services['servicios'][$request->get('servicio')]['categorias']['TICKETS OPEN']['filter_buzones'];
                $buzones = array_intersect($buzones, $filter_buzones);
            }

            //Se obtiene el tiempo transcurrido desde la ultima inserción en el servicio
            $fechaUltimoTicket = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')->getUltimaInsercion($buzones);

            //Se comprueba que exista registro
            if (is_array($fechaUltimoTicket) and count($fechaUltimoTicket) == 1 and ! is_null($fechaUltimoTicket['fechaInsercion']))
            {
                if (is_string($fechaUltimoTicket['fechaInsercion']))
                {
                    $date = (new \DateTime());
                    $lastInsertionDateTime = $date->createFromFormat("Y-m-d H:i:s", $fechaUltimoTicket['fechaInsercion']);
                    $fechaUltimoTicket['fechaInsercion'] = $lastInsertionDateTime;
                }
                $now = (new \DateTime('NOW'));
                $interval = $now->diff($fechaUltimoTicket['fechaInsercion']);

                $tiempoUltimoTicket['minutos'] = $interval->format('%H') * 60 + $interval->format('%i');
                $tiempoUltimoTicket['segundos'] = $interval->format('%s');
            }
            else
            {
                $tiempoUltimoTicket['minutos'] = null;
                $tiempoUltimoTicket['segundos'] = null;
            }

            $tickets = array();
            $categorias = $services['servicios'][$request->get('servicio')]['categorias'];

            $numCategorias = 0;
            $numTickets = 0;
            $numAlertas = 0;
            $alarma = array();
            $errorConexionDB = false;
            $mensajeErrorDB = "";

            //Se recorren todas las categorias del servicio seleccionado
            foreach ($categorias as $key => $categoria)
            {
                $numAlertasCategoria = 0;
                $campos = array();

                //Se pone el primer caracter en minuscula porque Symfony trabaja de esta manera
                foreach ($services['servicios'][$request->get('servicio')]['categorias'][$key]['campos'] as $campo)
                {
                    $campos[] = lcfirst($campo);
                }

                //valida que no exista configuracion dia/hora en categoria o si existe se encuentre en los parametros establecidos en la conf
                $confHorarioCorrecto = false;

                //Existe intervalo horario en la configuracion
                if (!empty($services['servicios'][$request->get('servicio')]['categorias'][$key]['intervalo_horario']))
                {
                    $diaHoy = $this->getDiaEsp();
                    $intervalosHorario = $services['servicios'][$request->get('servicio')]['categorias'][$key]['intervalo_horario'];

                    $now = (new \DateTime('NOW'));

                    foreach ($intervalosHorario as $intervalo)
                    {
                        if (($diaHoy == strtolower($intervalo['dia'])) && ($now->format('H:i') >= $intervalo['desde']) && ($now->format('H:i') <= $intervalo['hasta']))
                        {
                            $confHorarioCorrecto = true;
                        }
                    }
                }
                else
                {
                    $confHorarioCorrecto = true;
                }

                //No se entra si una categoria tiene configuracion horaria y no cumple los requisitos de la configuración
                if ($confHorarioCorrecto)
                {
                    //Se modifica el formato de los campos para la realizacion de la consulta
                    $camposString = array();

                    for ($i = 0; $i < count($campos); $i++)
                    {
                        $camposString[$i] = 'i.' . $campos[$i];
                    }

                    $camposString = implode(",", $camposString);

                    //Se obtienen las condiciones para la realización de la consulta
                    $condiciones = $services['servicios'][$request->get('servicio')]['categorias'][$key]['condiciones'];
                    $condicion = $this->getCondiciones($condiciones);

                    //Se obtienen los registros
                    $tickets[$key]['menu'] = $campos;

                    $start = "";
                    $end = "";
                    // INTERVENCION HISTORIA SGSD-163
                    if (null !== $services['servicios'][$request->get('servicio')]['categorias'][$key]['filter_buzones'] and count($services['servicios'][$request->get('servicio')]['categorias'][$key]['filter_buzones']) > 0)
                    {
                        $filter_buzones = $services['servicios'][$request->get('servicio')]['categorias'][$key]['filter_buzones'];
                        $intersec_buzones = array_intersect($buzones, $filter_buzones);

                        if (isset($services['servicios'][$request->get('servicio')]['intervalo_meses_mostrar']))
                        {
                            if (isset($services['servicios'][$request->get('servicio')]['intervalo_meses_mostrar']['mes_inicio']) and isset($services['servicios'][$request->get('servicio')]['intervalo_meses_mostrar']['mes_final']))
                            {
                                $startyear = $this->processDate($services['servicios'][$request->get('servicio')]['intervalo_meses_mostrar']['mes_inicio']);
                                $startmonth = $this->processDate($services['servicios'][$request->get('servicio')]['intervalo_meses_mostrar']['mes_inicio'], false);
                                $endyear = $this->processDate($services['servicios'][$request->get('servicio')]['intervalo_meses_mostrar']['mes_final']);
                                $endmonth = $this->processDate($services['servicios'][$request->get('servicio')]['intervalo_meses_mostrar']['mes_final'], false);

                                $tickets[$key]['datos'] = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')
                                    ->getTicketsFilterByDates($intersec_buzones, $camposString, $condicion, $endyear, $endmonth, $startyear, $startmonth);
                            }
                            else
                            {
                                $tickets[$key]['datos'] = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')
                                    ->getTickets($intersec_buzones, $camposString, $condicion);
                            }
                        }
                        else
                        {
                            $tickets[$key]['datos'] = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')
                                ->getTickets($intersec_buzones, $camposString, $condicion);
                        }
                    }
                    else
                    {
                        if (isset($services['servicios'][$request->get('servicio')]['intervalo_meses_mostrar']))
                        {
                            if (isset($services['servicios'][$request->get('servicio')]['intervalo_meses_mostrar']['mes_inicio']) and isset($services['servicios'][$request->get('servicio')]['intervalo_meses_mostrar']['mes_final']))
                            {
                                $startyear = $this->processDate($services['servicios'][$request->get('servicio')]['intervalo_meses_mostrar']['mes_inicio']);
                                $startmonth = $this->processDate($services['servicios'][$request->get('servicio')]['intervalo_meses_mostrar']['mes_inicio'], false);
                                $endyear = $this->processDate($services['servicios'][$request->get('servicio')]['intervalo_meses_mostrar']['mes_final']);
                                $endmonth = $this->processDate($services['servicios'][$request->get('servicio')]['intervalo_meses_mostrar']['mes_final'], false);

                                $tickets[$key]['datos'] = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')
                                    ->getTicketsFilterByDates($buzones, $camposString, $condicion, $endyear, $endmonth, $startyear, $startmonth);
                            }
                            else
                            {
                                $tickets[$key]['datos'] = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')
                                    ->getTickets($buzones, $camposString, $condicion);
                            }
                        }
                        else
                        {
                            $tickets[$key]['datos'] = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')
                                ->getTickets($buzones, $camposString, $condicion);
                        }
                    }

                    $incidencias[$request->get('servicio')][$key] = array_column($tickets[$key]['datos'], 'numeroCaso');

                    //Se comprueba si hay un error en la semantica de los campos introducidos en el archivo de configuracion web_monitor.yml
                    if (!is_object($tickets[$key]['datos']) && property_exists("Doctrine\ORM\Query\QueryException", "message"))
                    {

                        //Se pasa los valores de la prioridad establecidos en la configuracion a mayusculas
                        $prioridades = array();
                        foreach ($services['servicios'][$request->get('servicio')]['categorias'][$key]['prioridad'] as $prioridad)
                        {
                            $prioridades[] = strtoupper($prioridad);
                        }
                        $tickets[$key]['prioridad'] = $prioridades;

                        //Se comprueba que la categoria tenga alarmas
                        //Se comprueban las alertas recorriendose cada registro de cada categoria si la categoria contiene alarmas
                        if (array_key_exists('alarma', $services['servicios'][$request->get('servicio')]['categorias'][$key]))
                        {
                            $alarma['maxtime'] = $services['servicios'][$request->get('servicio')]['categorias'][$key]['alarma']['max_time'];

                            foreach ($tickets[$key]['datos'] as $indice => $campo)
                            {
                                //Se obtiene un registro perteneciente a una categoria con alarma y a continuacion se comprueba el intervalo horario
                                //Se comprueba si la alarma tiene buzones excluyentes
                                if (!empty($services['servicios'][$request->get('servicio')]['categorias'][$key]['alarma']['exclude_buzones']))
                                {
                                    $alarma['exclude'] = $services['servicios'][$request->get('servicio')]['categorias'][$key]['alarma']['exclude_buzones'];
                                    $fecha = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')
                                        ->getFechaInsercionTicketConBuzon($campo['numeroCaso'], $alarma['exclude']);
                                }
                                else
                                {
                                    $fecha = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')
                                        ->getFechaInsercionTicketSinBuzon($campo['numeroCaso']);
                                }

                                $tickets[$key]['datos'][$indice]['alarma'] = 'NO';

                                if (!is_null($fecha) && !is_null($fecha['fechaInsercion']))
                                {
                                    $now = (new \DateTime('NOW'));
                                    $interval = $now->diff($fecha['fechaInsercion']);

                                    //$mes = $interval->format('%m') * 30 * 1440;
                                    //Se pasa la diferencia a minutos
                                    $diferencia = $interval->format('%d') * 1440 + $interval->format('%H') * 60 + $interval->format('%i');

                                    if ($diferencia >= $alarma['maxtime'])
                                    {
                                        $tickets[$key]['datos'][$indice]['alarma'] = 'SI';
                                        $numAlertas++;
                                        $numAlertasCategoria++;
                                    }
                                }
                            }
                        }

                        //Se comprueba si la categoria tiene clientes críticos
                        if (!empty($services['servicios'][$request->get('servicio')]['categorias'][$key]['clientes_criticos']))
                        {
                            //Recuperamos el listado de clientes criticos en el array en mayusculas
                            $clientesCriticos = $services['servicios'][$request->get('servicio')]['categorias'][$key]['clientes_criticos'];
                            foreach ($tickets[$key]['datos'] as $indice => $campo)
                            {
                                //Fix History 154
                                $incidenciaOb = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')->findOneBy(array('numeroCaso' => $campo['numeroCaso']));
                                $existeClienteCritico = $this->getCliente($incidenciaOb, $clientesCriticos);

                                if ($existeClienteCritico)
                                {
                                    $tickets[$key]['datos'][$indice]['clienteCritico'] = 'SI';
                                    $tickets[$key]['datos'][$indice]['titulo'] = $existeClienteCritico . ' - ' . $tickets[$key]['datos'][$indice]['titulo'];
                                }
                                else
                                {
                                    $tickets[$key]['datos'][$indice]['clienteCritico'] = 'NO';
                                }
                            }
                        }

                        $numTickets += count($tickets[$key]['datos']);
                        $numCategorias++;
                        $tickets[$key]['alertasCat'] = $numAlertasCategoria;
                    }
                    else
                    {
                        $numCategorias = null;
                        $numAlertas = null;
                        $numTickets = null;
                        $errorConexionDB = true;
                        $mensajeErrorDB = $tickets[$key]['datos']->getMessage();
                        break;
                    }
                }
            }
        }
        catch (\PDOException $e)
        {
            $numCategorias = null;
            $numAlertas = null;
            $numTickets = null;
            $errorConexionDB = true;
            $mensajeErrorDB = "ERROR: No se puede conectar con la Base de Datos";
        }

        //Segmento de Codigo insertado para implementar la historia sgsd-30
        //Se utilizan las traducciones de tipo de caso sms_manager 
        $traducciones = $this->container->getParameter('fractalia_sms.envio_sms.traduccion_tipo_caso');
        //Definimos los 2 indices involucrados
        $datos = "datos";
        $tipo = "tipoCaso";
//        $dataArray = array();
        $arrayIncidencias = array();
        foreach ($tickets as $tipoTicket => $array)
        {
            if (array_key_exists($datos, $array) and count($array[$datos]) > 0)
            {
                foreach ($array[$datos] as $key => $ticket)
                {
                    if (array_key_exists($tipo, $ticket))
                    {
                        $tickets[$tipoTicket][$datos][$key][$tipo] = strtoupper($traducciones[$ticket[$tipo]]);
                        //Guardamos los numero de casos en un array para procesar y borrar si hay duplicados
                        $arrayIncidencias[] = $ticket['numeroCaso'];
                    }
                }
            }
            elseif (!array_key_exists($datos, $array) or count($array[$datos]) == 0)
            {
                continue;
            }
        }

        $arrayResult = array();
        if (count($usuariosArray) > 0)
        {
            foreach ($usuariosArray as $usuario => $arrayBuzones)
            {
                $arrayTmp = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')->findTicketsByUserInBuzones($usuario, $arrayBuzones);
                if ($arrayTmp == 0)
                    continue;
                if (is_array($arrayTmp) and count($arrayTmp) > 0)
                {
                    $arrayResult[$usuario] = array_column($arrayTmp, 'numeroCaso');
                }
            }
        }

        $notifier = $this->get('incidencia.mail.notifier');
        $notifier->processNotifications($incidencias);

        //work around for remove duplicates
        if (count($arrayIncidencias) > 0)
        {
            foreach ($arrayIncidencias as $numcaso)
            {
                $this->findDuplicatesAndFix($numcaso);
            }
        }

        return $this->render('Pi2FracSGSDWebMonitorBundle:Default:listado_categorias.html.twig', array('incidenciasCat' => $tickets,
                'numCategorias' => $numCategorias,
                'numAlertas' => $numAlertas,
                'numTickets' => $numTickets,
                'recarga' => $services['actualiza_web_segundos'] * 1000,
                'servicio' => $request->get('servicio'),
                'tiempoUltimoTicket' => $tiempoUltimoTicket,
                'errorConexionDB' => $errorConexionDB,
                'mensajeErrorDB' => $mensajeErrorDB,
                'table' => $arrayResult,
        ));
    }

    public function hideTicketAction(Request $request)
    {
        $params = array();
        $content = $this->get("request")->getContent();
        if (!empty($content))
        {
            $params = json_decode($content, true);

            if ($params['numerocaso'] != "" and ! is_null($params['numerocaso']))
            {
                $em = $this->getDoctrine()->getManager();
                $result = $em->getRepository("\Pi2\Fractalia\Entity\SGSD\Incidencia")->findBy(array('numeroCaso' => $params['numerocaso']));

                if (is_array($result) and count($result) > 0)
                {
                    $entity = array_shift($result);
                    if ($entity instanceof Incidencia)
                    {
                        $entity->setHideInMonitor($params['hide']);
                        $em->persist($entity);
                        $em->flush();
                        return new Response("0", 200);
                    }
                }
                else
                {
                    return new Response("1", 200);
                }
            }
        }
        else
        {
            return new Response("400", 400);
        }
    }

//Se obtienen las condiciones para la realizacion de la query
    private function getCondiciones($condiciones)
    {
        $condicion = '';

        foreach ($condiciones as $i => $condicionSimple)
        {
            //si ultima iteración
            if ($i == count($condiciones) - 1)
            {
                $condicion = $condicion . ' i.' . lcfirst($condicionSimple['campo']) . ' ' . $condicionSimple['operacion'] . ' ' . "'" . $condicionSimple['valor'] . "')";
            }
            else
            {
                //Se comprueba la existencia de un operador logico OR en el operador de la configuracion
                $opLog = substr($condicionSimple['operacion'], strlen($condicionSimple['operacion']) - 4, 4);

                $opLog = (strtoupper($opLog) == '[OR]') ? 'OR' : 'AND';

                if ($opLog == 'OR')
                {
                    $condicionSimple['operacion'] = substr($condicionSimple['operacion'], 0, -4);
                }
                $condicion = $condicion . ' i.' . lcfirst($condicionSimple['campo']) . ' ' . $condicionSimple['operacion'] . ' ' . "'" . $condicionSimple['valor'] . "' " . $opLog . " ";
            }
        }

        return $condicion;
    }

//Se obtiene el dia de hoy en idioma español
    private function getDiaEsp()
    {
        $now = (new \DateTime('NOW'));

        switch (strtolower($now->format('D')))
        {
            case 'mon': $dia = 'lunes';
                break;
            case 'tue': $dia = 'martes';
                break;
            case 'wed': $dia = 'miercoles';
                break;
            case 'thu': $dia = 'jueves';
                break;
            case 'fri': $dia = 'viernes';
                break;
            case 'sat': $dia = 'sabado';
                break;
            case 'sun': $dia = 'domingo';
                break;
            default:
                $dia = null;
                break;
        }

        return ($dia);
    }

    //TODO: WE NEED SEPARATE THIS FUNCTIONALITY IS USED IN ALL APP

    /*
     * Se Obtiene el Cliente del campo titulo de incidencia comparandolo con los clientes de la configuración
     * Si no se encuentra el patron en la incidencia se busca un patron el en titulo
     * @Param $incidencia Incidencia
     * @Param $confCliente array
     */

    protected function getCliente($incidencia, $confCliente)
    {
        //patron a buscar en el primer intento
        //[SDnumerocaso][otrodato][nombrecortocliente]
        $pattern = "/^(\[(\w+)*\]){3}/";
        $matches = array();
        $matches2 = array();

        if (method_exists($incidencia, 'getTitulo') and null != $incidencia->getTitulo())
        {

            $result = preg_match($pattern, $incidencia->getTitulo(), $matches);
            if ($result == 1 and count($matches) == 3)
            {
                if (in_array(strtoupper($matches[2]), $confCliente))
                {
                    return strtolower($matches[2]);
                }
                else
                {
                    $result = 0;
                }
            }
            if ($result == 0)
            {
                foreach ($confCliente as $cliente)
                {
                    $pattern2 = $this->processClientesConfig($cliente);
                    $result2 = preg_match($pattern2, strtolower($incidencia->getTitulo()), $matches2);
                    if ($result2 > 0)
                    {
                        return strtolower($cliente);
                    }
                    if ($result2 == 0)
                    {
                        $result3 = preg_match("/" . strtolower($cliente) . "/", strtolower($incidencia->getTitulo()), $matches3);
                        if ($result3 > 0)
                        {
                            return strtolower($cliente);
                        }
                    }
                }
            }
        }
        else
        {
            return null;
        }
    }

    /*
     * Transformar las palabras del fichero de configuracion
     * en Patron regEXP, para comparar
     */

    private function processClientesConfig($string)
    {
        if (!is_null($string))
        {
            return '^\[' . strtolower($string) . '\]^';
        }
        else
        {
            return null;
        }
    }

    /*
     * Retira los square brackets del nombre corto del cliente encontrado
     * en Patron regEXP, para comparar
     */

    private function cleanCliente($string)
    {

        if (!is_null($string))
        {
            return trim($string, "[]");
        }
        else
        {
            return null;
        }
    }

    protected function processDate($string, $year = true)
    {
        $now = (new \DateTime("NOW"));
        $anho = "Y";
        $mes = "m";
        if (is_string($string) and $string == "actual")
        {
            if ($year)
            {
                return $now->format($anho);
            }
            else
            {
                return $now->format($mes);
            }
        }
        elseif (strpos($string, '-') !== false)
        {
            $elements = explode('-', $string);
            if ($elements[0] == "actual")
            {
                $now->modify("-{$elements[1]} month");
                if ($year)
                {
                    return $now->format($anho);
                }
                else
                {
                    return $now->format($mes);
                }
            }
        }
        else
        {
            return false;
        }
    }

    protected function findDuplicatesAndFix($numerocaso)
    {
        $em = $this->getDoctrine()->getManager();
        $incidenciaRep = $em->getRepository('Pi2\Fractalia\Entity\SGSD\Incidencia');
        $resultado = $incidenciaRep->findBy(array('numeroCaso' => $numerocaso), array('fechaActualizacion' => 'DESC'));
        if (is_array($resultado))
        {
            if (count($resultado) > 1)
            {
                $entity = array_shift($resultado);
                $this->moveToRejectAndDelete($resultado);
                return true;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return true;
        }
    }

    private function moveToRejectAndDelete($array)
    {
        $em = $this->getDoctrine()->getManager();
        $rem = $this->getDoctrine()->getManager('report');

        if (is_array($array))
        {
            foreach ($array as $incidenciaaBorrar)
            {
                //Rechazar las ducplicadas y borrar
                $reject = new Rechazada();
                $hydrator = new DoctrineObject($em);
                $hydratorReject = new DoctrineObject($rem);
                $incidenciaaBorrar->setHideInMonitor(true);

                $arraySaveIncidencia = $hydrator->extract($incidenciaaBorrar);
                $arraySaveIncidencia['id'] = null;
                $hydratorReject->hydrate($arraySaveIncidencia, $reject);
                $reject->setFechaInsercion(new \DateTime('NOW'));
                $rem->persist($reject);
                $rem->flush();
                $em->remove($incidenciaaBorrar);
                $em->flush();
            }
        }
        else
        {
            return true;
        }
    }

}
