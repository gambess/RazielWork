<?php

namespace Pi2\Fractalia\SGSDWebMonitorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class DefaultController extends Controller 
{

public function indexAction() 
{
    $request = $this->getRequest();
    $services = $this->container->getParameter('sgsd_web_monitor');

    $servicios = array();

    foreach ($services['servicios'] as $key => $value) {
        $servicios[$key] = $key;
    }

    $formServicios = $this->createFormBuilder()
            ->add('servicioAfectado', 'choice', array('choices' => $servicios))
            ->add('buscar', 'submit', array('label' => 'Ir al Servicio'))
            ->getForm();

    if ($request->getMethod() == 'POST') {
        $formServicios->bind($request);

        if ($formServicios->isValid()) {
            return $this->redirect($this->generateUrl('pi2_frac_sgsd_web_monitor_categorias', array('servicio' => $formServicios->get('servicioAfectado')->getData())));
        }
    }

    return $this->render('Pi2FracSGSDWebMonitorBundle:Default:index.html.twig', array('formServicios' => $formServicios->createView()));
}


public function monitorCategoriasAction() 
{
    //date_default_timezone_set("Europe/Madrid");   
    $services = $this->container->getParameter('sgsd_web_monitor');
    $request = $this->getRequest();
   
    try 
    {
        
    $em = $this->getDoctrine()->getEntityManager();

    $buzones = $services['servicios'][$request->get('servicio')]['buzones'];

    //Se obtiene el tiempo transcurrido desde la ultima inserción en el servicio
    $fechaUltimoTicket = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')->getTiempoUltimoTicket($buzones);

    //Se comprueba que exista registro
    if (!is_null($fechaUltimoTicket))
    {
        $now = (new \DateTime('NOW'));
        $interval = $now->diff($fechaUltimoTicket['fechaInsercion']);

        $tiempoUltimoTicket['minutos'] = $interval->format('%H') * 60 + $interval->format('%i');
        $tiempoUltimoTicket['segundos'] = $interval->format('%s') ;
    }else{
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
        foreach ($services['servicios'][$request->get('servicio')]['categorias'][$key]['campos'] as $campo) {
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

           foreach ($intervalosHorario as $intervalo) {
               if (($diaHoy == strtolower($intervalo['dia'])) && ($now->format('H:i') >= $intervalo['desde']) 
                                                              && ($now->format('H:i') <= $intervalo['hasta'])){
                   $confHorarioCorrecto = true;
               }
           }
        }else{
            $confHorarioCorrecto = true;
        }

        //No se entra si una categoria tiene configuracion horaria y no cumple los requisitos de la configuración
        if ($confHorarioCorrecto)
        {
            //Se modifica el formato de los campos para la realizacion de la consulta
            $camposString = array();

            for ($i = 0; $i < count($campos); $i++) {
                $camposString[$i] = 'i.'.$campos[$i];
            }

            $camposString = implode(",", $camposString);

            //Se obtienen las condiciones para la realización de la consulta
            $condiciones = $services['servicios'][$request->get('servicio')]['categorias'][$key]['condiciones'];     
            $condicion = $this->getCondiciones($condiciones);

            //Se obtienen los registros
            $tickets[$key]['menu'] = $campos;
            $tickets[$key]['datos'] = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')
                                         ->getTickets($buzones, $camposString, $condicion);
 
            //Se comprueba si hay un error en la semantica de los campos introducidos en el archivo de configuracion web_monitor.yml
            if (!is_object($tickets[$key]['datos']) && property_exists("Doctrine\ORM\Query\QueryException", "message"))
            {

                //Se pasa los valores de la prioridad establecidos en la configuracion a mayusculas
                $prioridades = array();
                foreach ($services['servicios'][$request->get('servicio')]['categorias'][$key]['prioridad'] as $prioridad) {
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
                        if (!empty($services['servicios'][$request->get('servicio')]['categorias'][$key]['alarma']['exclude_buzones'])) {
                            $alarma['exclude'] = $services['servicios'][$request->get('servicio')]['categorias'][$key]['alarma']['exclude_buzones'];
                            $fecha = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')
                                        ->getFechaInsercionTicketConBuzon($campo['numeroCaso'], $alarma['exclude']);
                        } else {
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

                            if ($diferencia >= $alarma['maxtime']) {
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
                    $clientesCriticos = $services['servicios'][$request->get('servicio')]['categorias'][$key]['clientes_criticos'];

                    foreach($clientesCriticos as $cliente){
                        $sql[] = "i.cliente LIKE '%[%".$cliente."%]%'";
                    }

                    $sql = implode(" OR ", $sql);

                    //Se comprueba si el ticket es de un cliente crítico
                    foreach ($tickets[$key]['datos'] as $indice => $campo) 
                    {          
                        $existeClienteCritico = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')
                                                   ->getExisteClienteCritico($campo['numeroCaso'], $sql);

                        if ($existeClienteCritico){
                            $tickets[$key]['datos'][$indice]['clienteCritico'] = 'SI';    
                            $tickets[$key]['datos'][$indice]['titulo'] = $existeClienteCritico.' - '.$tickets[$key]['datos'][$indice]['titulo']; 
                        }else{
                            $tickets[$key]['datos'][$indice]['clienteCritico'] = 'NO';  
                        }
                    }
                }

                $numTickets += count($tickets[$key]['datos']);
                $numCategorias++;
                $tickets[$key]['alertasCat'] = $numAlertasCategoria;     

            }else{             
              $numCategorias = null;
              $numAlertas = null;
              $numTickets = null;           
              $errorConexionDB = true;
              $mensajeErrorDB = $tickets[$key]['datos']->getMessage();
              break;
            }
        }
    }
} catch (\PDOException $e) {
    $numCategorias = null;
    $numAlertas = null;
    $numTickets = null;           
    $errorConexionDB = true;
    $mensajeErrorDB = "ERROR: No se puede conectar con la Base de Datos";
}

    return $this->render('Pi2FracSGSDWebMonitorBundle:Default:listado_categorias.html.twig', 
                   array('incidenciasCat' => $tickets,
                         'numCategorias' => $numCategorias,
                         'numAlertas' => $numAlertas,
                         'numTickets' => $numTickets,
                         'recarga' => $services['actualiza_web_segundos'] * 1000,
                         'servicio' => $request->get('servicio'),
                         'tiempoUltimoTicket' => $tiempoUltimoTicket,
                         'errorConexionDB' => $errorConexionDB,
                         'mensajeErrorDB' => $mensajeErrorDB,
    ));
}
    

//Se obtienen las condiciones para la realizacion de la query
private function getCondiciones($condiciones)
{
    $condicion = '';
            
    foreach ($condiciones as $i=>$condicionSimple)
    {
        //si ultima iteración
        if ($i == count($condiciones)-1){
            $condicion = $condicion.' i.'.lcfirst($condicionSimple['campo']).' '.$condicionSimple['operacion'].' '."'".$condicionSimple['valor']."')";
        }else{     
            //Se comprueba la existencia de un operador logico OR en el operador de la configuracion
            $opLog = substr($condicionSimple['operacion'], strlen($condicionSimple['operacion']) - 4, 4);
            
            $opLog = (strtoupper($opLog) == '[OR]') ? 'OR': 'AND';

            if ($opLog == 'OR'){
                $condicionSimple['operacion'] = substr($condicionSimple['operacion'], 0, -4);
            }
            $condicion = $condicion.' i.'.lcfirst($condicionSimple['campo']).' '.$condicionSimple['operacion'].' '."'".$condicionSimple['valor']."' ".$opLog." ";
        }
    }

    return $condicion;
}
    
//Se obtiene el dia de hoy en idioma español
private function getDiaEsp()
{
   $now = (new \DateTime('NOW'));

   switch (strtolower($now->format('D'))) {
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

}
