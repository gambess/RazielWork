<?php

namespace Pi2\Fractalia\SGSDReportBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Reports controller.
 *
 * @Route("/reports")
 */
class DefaultController extends Controller
{

    /**
     * Muestra La pagina principal de reports.
     *
     * @Route("/", name="reports_main")
     * @Method("GET")
     * @Template("ReportBundle:Default:index.html.twig")
     */
    public function indexAction()
    {
        $form = $this->createDatesForm();
        return array(
            'form' => $form->createView(),
            'counter' => 0,
            'error' => 0,
        );
    }

    /**
     * Creates a form to edit a Sms entity.
     *
     * @param string $string The string to search in sms and message
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDatesForm()
    {
        $servicios = array('SOC' => 'SOC ', 'OIT' => 'OIT ');
        return $this->createFormBuilder()
                ->setMethod('POST')
                ->add('startdate', 'text', array('label' => 'Fecha Inicio:', 'required' => true))
                ->add('enddate', 'text', array('label' => 'Fecha Fin:', 'required' => true))
                ->add('resolucion', 'checkbox', array('label' => 'Resolución?', 'required' => false,))
                ->add('servicio', 'choice', array(
                    'label' => 'Servicio:',
                    'choices' => $servicios,
                    'required' => true,
                    )
                )
                ->add('buscar', 'submit', array(
                    'label' => 'Obtener Reporte',
                    'attr' => array(
                        'class' => "ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only",
                        'role' => "button")
                    )
                )
                ->getForm();
    }

    /**
     * Creates a form to edit a Sms entity.
     *
     * @param string $string The string to search in sms and message
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createHiddenForm()
    {
        return $this->createFormBuilder()
                ->setMethod('POST')
                ->add('startdate', 'hidden', array('required' => true))
                ->add('enddate', 'hidden', array('required' => true))
                ->add('servicio', 'hidden', array('required' => true))
                ->add('resolucion', 'hidden', array('required' => false))
                ->add('recuperar', 'submit', array('label' => 'Exportar a csv'))
                ->getForm();
    }

    /**
     * Muestra La pagina principal de reports.
     *
     * @Route("/", name="reports_generate")
     * @Method("POST")
     */
    public function postReportAction(Request $request)
    {
        $arrayServices = array('SOC', 'OIT');
        $form = $this->createDatesForm();
        $form->handleRequest($request);

        $postService = $form->getData()['servicio'];
        if (!in_array($postService, $arrayServices))
        {
            return $this->render('ReportBundle:Default:index.html.twig', array(
                    'form' => $form->createView(),
                    'counter' => 0,
                    'error' => "No se encuentra habilitado el Servicio: {$postService} para obtener reportes, Los Servicion habilitados son: {$arrayServices[0]} y {$arrayServices[1]}",
            ));
        }

        if ($form->getData()['resolucion'])
        {
            $resolucion = 1;
        }
        else
        {
            $resolucion = 0;
        }

        if ($form->isValid())
        {
            return $this->redirect($this->generateUrl('reports_render', array('f1' => $this->processDateFromForm($form->getData()['startdate']), 'f2' => $this->processDateFromForm($form->getData()['enddate']), 'resolucion' => $resolucion, 'service' => $postService)));
        }
        return $this->render('ReportBundle:Default:index.html.twig', array(
                'form' => $form->createView(),
                'counter' => 0,
                'error' => 0,
        ));
    }

    /**
     * Procesa el formulario de Descarga del csv y redirije.
     *
     * @Route("/export", name="csv_generate")
     * @Method("POST")
     */
    public function postExportAction(Request $request)
    {

        $form = $this->createHiddenForm();
        $form->handleRequest($request);

        if ($form->isValid())
        {
            return $this->redirect($this->generateUrl('reports_export', array('f1' => $this->processDateFromForm($form->getData()['startdate']), 'f2' => $this->processDateFromForm($form->getData()['enddate']), 'resolucion' => $form->getData()['resolucion'], 'service' => $form->getData()['servicio'])));
        }
        return $this->redirect($this->generateUrl('reports_render', array('f1' => $this->processDateFromForm($form->getData()['startdate']), 'f2' => $this->processDateFromForm($form->getData()['enddate']), 'resolucion' => $form->getData()['resolucion'])));
    }

    /**
     * 
     * @param string $date
     * @return string
     */
    private function processDateFromForm($date, $inv = false)
    {
        $dateTimeObj = (new \DateTime());
        $formatIn = "d/m/Y";
        $formatOut = "Y-m-d";
        if (!is_null($date))
        {
            if ($inv == false)
            {
                $new = $dateTimeObj->createFromFormat($formatIn, $date);
                return $new->format($formatOut);
            }
            elseif ($inv == true)
            {
                $new = $dateTimeObj->createFromFormat($formatOut, $date);
                return $new->format($formatIn);
            }
        }
    }

    /**
     * Calcula los numeros y los devuelve en un array
     * 
     * @param string $startdate
     * @param type $enddate
     * @param type $resolucion
     * @param type $usuarios
     * @return Array
     */
    private function getCounters($startdate, $enddate, $resolucion, $usuarios = null, $servicio = 'SOC')
    {
        $em = $this->getDoctrine()->getManager('report');
        $buzones = array("SOC SEGURIDAD", "SEGEST SOC", "SEGEST MON", "SOPORTE SEGURIDAD", "SEGEST SEGUIMIENTO");
        $results = array();
        //a)
        $results['servicio'] = "{$servicio}";
        $results['tratadasN1'] = $em->getRepository('ReportBundle:Notificacion')->getNewTotalesTratadasN1($startdate, $enddate, $resolucion, $usuarios, $servicio);
        //b)
        $results['cerradasN1'] = $em->getRepository('ReportBundle:Notificacion')->getNewCerradasN1($startdate, $enddate, $resolucion, $usuarios, $servicio);
        //c      
        $results['transferidas'] = $em->getRepository('ReportBundle:Notificacion')->getTrasferidasN2($startdate, $enddate, $resolucion, $usuarios, $servicio);
        //d)
        $results['incidencias'] = $em->getRepository('ReportBundle:Notificacion')->getIncidencias($startdate, $enddate, $resolucion, $usuarios, $servicio);
        //e)
        $results['peticiones'] = $em->getRepository('ReportBundle:Notificacion')->getPeticion($startdate, $enddate, $resolucion, $usuarios, $servicio);
        //f)
        $results['proviciones'] = $em->getRepository('ReportBundle:Notificacion')->getProvicion($startdate, $enddate, $resolucion, $usuarios, $servicio);
        //g)
        $results['quejas'] = $em->getRepository('ReportBundle:Notificacion')->getQuejaConsulta($startdate, $enddate, $resolucion, $usuarios, $servicio);

        //BUZONES SOC
        if ($servicio == "SOC")
        {
            //h)
            $results['segestsoc'] = $em->getRepository('ReportBundle:Notificacion')->getSubConjuntoCerradas("SEGEST SOC", $startdate, $enddate, $resolucion, $usuarios, $servicio);
            //i)
            $results['segestmon'] = $em->getRepository('ReportBundle:Notificacion')->getSubConjuntoCerradas("SEGEST MON", $startdate, $enddate, $resolucion, $usuarios, $servicio);
            //j)
            $results['soporteseg'] = $em->getRepository('ReportBundle:Notificacion')->getSubConjuntoCerradas("SOPORTE SEGURIDAD", $startdate, $enddate, $resolucion, $usuarios, $servicio);
            //k)
            $results['segestseg'] = $em->getRepository('ReportBundle:Notificacion')->getSubConjuntoCerradas("SEGEST SEGUIMIENTO", $startdate, $enddate, $resolucion, $usuarios, $servicio);
            //l)
            $results['otros'] = $em->getRepository('ReportBundle:Notificacion')->getSubConjuntoCerradas($buzones, $startdate, $enddate, $resolucion, $usuarios);

            $prom = $this->getPromedios($results['tratadasN1'], $startdate, $enddate, $resolucion);
            //m      
            $results['mt1'] = $prom['tn1'];
            //n
            $results['asistencia'] = $prom['ta'];
            //ñ)
            if (count($this->getArrayNumcasoFromResults($results['tratadasN1'])) > 0)
            {
                $results['tratada'] = round((float)count($this->getArrayNumcasoFromResults($results['tratadasN1'])) / $em->getRepository('ReportBundle:Notificacion')->getVecesTratada($startdate, $enddate, $resolucion, $usuarios), 2);
            }
            else
            {
                $results['tratada'] = round((float)$em->getRepository('ReportBundle:Notificacion')->getVecesTratada($startdate, $enddate, $resolucion, $usuarios), 2);
            }
        }
        elseif ($servicio == "OIT")
        {
            $results['alarma'] = $em->getRepository('ReportBundle:Notificacion')->getAlarma($startdate, $enddate, $resolucion, $usuarios);
            //g
            $results['iberia'] = $em->getRepository('ReportBundle:Notificacion')->getIberia($startdate, $enddate, $resolucion, $usuarios);
            //h
            $tiempoiberia = $em->getRepository('ReportBundle:Notificacion')->getMediaTiempoN1Oit($results['iberia'], $startdate, $enddate, $resolucion);
            $results['mtiberia'] = $this->getMedia($tiempoiberia);
            //i
            $results['icm'] = $em->getRepository('ReportBundle:Notificacion')->getIcm($startdate, $enddate, $resolucion, $usuarios, $servicio);
            //j
            $tiempoicm = $em->getRepository('ReportBundle:Notificacion')->getMediaTiempoN1Oit($results['icm'], $startdate, $enddate, $resolucion, $usuarios);
            $results['mticm'] = $this->getMedia($tiempoicm);

            //m
            $tiempoN1 = $em->getRepository('ReportBundle:Notificacion')->getMediaTiempoN1Oit($results['tratadasN1'], $startdate, $enddate, $resolucion, $usuarios);
            $results['mt1'] = $this->getMedia($tiempoN1);

            //n
            $tiempoAsistencia = $em->getRepository('ReportBundle:Notificacion')->getTiempoAsistenciaOit($results['tratadasN1'], $startdate, $enddate, $resolucion, $usuarios);
            $results['asistencia'] = $this->getMedia($tiempoAsistencia);

            //ñ)
            $results['tratada'] = round((float)$em->getRepository('ReportBundle:Notificacion')->getVecesTratadaOit($startdate, $enddate, $resolucion, $usuarios), 2);
        }

        return $results;
    }

    /**
     * Obtiene la media y la devuelve en el formato adecuado
     * 
     * @param type $tiempo
     * @return type
     */
    protected function getMedia($tiempo)
    {
        $media = 0;

        if (!is_null($tiempo) and is_array($tiempo) and count($tiempo) > 0)
        {
            $counter = count($tiempo);
            if ($counter == 0)
            {
                $media = 0;
            }
            elseif (is_array($tiempo) and count($tiempo) == 1 and is_null($tiempo[0]))
            {
                $media = 0;
            }
            else
            {
                $tiempo = array_column($tiempo, 'diferencia');
                $acum = $media = 0;
                foreach ($tiempo as $time)
                {
                    $acum += strtotime($time);
                }
                $media = date('H:i:s', $acum / $counter);
            }
        }
        return $media;
    }

    /**
     * Muestra La pagina principal con el resultado del reporte.
     *
     * @Route("/showReport/servicio/{service}/stardate/{f1}/enddate/{f2}/{resolucion}", defaults={"resolucion": 0, "service": "SOC"}, requirements={"resolucion": "\d+", "service":"\w+"}, name="reports_render")
     * @Method("GET")
     * @Template("ReportBundle:Default:index.html.twig")
     * @param string $service servicio del reporte
     * @param string $f1 fecha de inicio
     * @param string $f2 fecha de fin
     * @param int $resolucion flag para utilizar una distinta fecha en este caso fecha de resolucion
     * @return Array
     */
    public function reportAction($service = 'SOC', $f1 = null, $f2 = null, $resolucion = 0)
    {
        $arrayServices = array('SOC', 'OIT');

        $usuarios = null;
        $results = array();
        $service = strtoupper($service);

        $form = $this->createDatesForm();
        $hiddenForm = $this->createHiddenForm();

        if (is_null($f1) and is_null($f2))
        {
            return array(
                'form' => $form->createView(),
                'counter' => 0,
                'error' => 'Alguna de las fechas enviadas no son correctas',
            );
        }

        $form->get('startdate')->setData($this->processDateFromForm($f1, true));
        $form->get('enddate')->setData($this->processDateFromForm($f2, true));

        if (!in_array($service, $arrayServices))
        {
            return $this->render('ReportBundle:Default:index.html.twig', array(
                    'form' => $form->createView(),
                    'counter' => 0,
                    'error' => "No se encuentra habilitado el Servicio: {$service} para obtener reportes, Los Servicion habilitados son: {$arrayServices[0]} y {$arrayServices[1]}",
            ));
        }
        $form->get('servicio')->setData($service);
        $form->get('resolucion')->setData((bool) $resolucion);

        $hiddenForm->get('startdate')->setData($this->processDateFromForm($f1, true));
        $hiddenForm->get('enddate')->setData($this->processDateFromForm($f2, true));
        $hiddenForm->get('servicio')->setData($service);
        $hiddenForm->get('resolucion')->setData($resolucion);


        $results = $this->getCounters($f1, $f2, $resolucion, $usuarios, $service);

        if (count($results) > 0)
        {
            $results['counter'] = 1;
            $results['form'] = $form->createView();
            $results['hiddenForm'] = $hiddenForm->createView();
        }
        elseif (count($results) == 0)
        {
            $results['counter'] = 0;
            $results['form'] = $form->createView();
        }
        return $results;
    }

    /**
     * Export Report Counters.
     *
     * @Route("/export/servicio/{service}/stardate/{f1}/enddate/{f2}/{resolucion}", defaults={"resolucion": 0, "service": "SOC"}, requirements={"resolucion": "\d+", "service":"\w+"}, name="reports_export")
     * @Method("GET")
     * @param string $service servicio del reporte
     * @param string $f1 fecha de inicio
     * @param string $f2 fecha de fin
     * @param int $resolucion flag para utilizar una distinta fecha en este caso fecha de resolucion
     * @return Array|csv
     */
    public function exportAction($service = 'SOC', $f1 = null, $f2 = null, $resolucion = 0, $usuarios = null)
    {
        $em = $this->getDoctrine()->getManager('report');
        $form = $this->createDatesForm();

        if (is_null($f1) and is_null($f2))
        {
            return array(
                'form' => $form->createView(),
                'counter' => 0,
            );
        }

        $results = $this->getCounters($f1, $f2, $resolucion, $usuarios, $service);

        $handle = fopen('php://memory', 'r+');
        $header = array();
        $resultado = 0;
        foreach ($results as $key => $counter)
        {
            if ($key == "mt1" or $key == "asistencia" or $key == "tratada" or $key == "mtiberia" or $key == "servicio")
            {
                $resultado = $counter;
            }
            else
            {
                $resultado = count($counter);
            }
            fputcsv($handle, array($this->translateExportKeys($key)));
            fputcsv($handle, array($resultado));
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response($content, 200, array(
            'Content-Type' => 'application/force-download; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="report.csv"'
        ));
    }

    protected function translateExportKeys($key)
    {
        $array = array
            (
            'servicio' => 'Reportes del Servicio: ',
            'tratadasN1' => 'Incidencias Totales Tratadas en N1: ',
            'cerradasN1' => 'Cerradas en N1: ',
            'transferidas' => 'Transferidas a N2: ',
            'incidencias' => 'Incidencias: ',
            'peticiones' => 'Peticion: ',
            'proviciones' => 'Provision: ',
            'quejas' => 'Consulta: ',
            'segestsoc' => 'Segest SOC: ',
            'segestmon' => 'Segest MON: ',
            'soporteseg' => 'Soporte SEGURIDAD: ',
            'segestseg' => 'Segest SEGUIMIENTO: ',
            'otros' => 'Otros: ',
            'mt1' => 'Tiempo N1: ',
            'asistencia' => 'Tiempo asistencia: ',
            'tratada' => 'Veces tratada: ',
            'alarma' => 'Alarma: ',
            'iberia' => 'Iberia: ',
            'icm' => 'Icm: ',
            'mtiberia' => 'Tiempo Iberia: ',
            'mticm' => 'Tiempo ICM: ',
        );

        if (array_key_exists($key, $array))
        {
            return $array[$key];
        }
        else
        {
            return $key;
        }
    }

    /**
     * Lists all Notificacion entities.
     *
     * @Route("/notificationlist", name="notificacion_list")
     * @Method("GET")
     * @Template("ReportBundle:Default:renderList.html.twig")
     */
    public function renderListAction()
    {
        return array(
            'entities' => $entities,
        );
    }

    protected function getEntitiesFromNumcaso($arrayNumCas)
    {
        $em = $this->getDoctrine()->getManager('report');

        $array = array_column($arrayNumCas, 'numerocaso');
        return $em->getRepository('ReportBundle:Notificacion')->findByNumerocaso(array_values($array));
    }

    protected function getArrayNumcasoFromResults($arrayResult)
    {
        $array = array();
        if (is_array($arrayResult) and count($arrayResult) > 0)
        {
            foreach ($arrayResult as $notification)
            {
                $array[] = $notification->getNumeroCaso();
            }
        }
        return $array;
    }

    /**
     * Finds and displays a Notificacion entity.
     *
     * @Route("/notificationshow/{id}", name="notificacion_mostrar")
     * @Method("GET")
     * @Template("ReportBundle:Default:showNotification.html.twig")
     */
    public function showNotificacionAction($id)
    {
        $em = $this->getDoctrine()->getManager('report');

        $entity = $em->getRepository('ReportBundle:Notificacion')->find($id);


        if (!$entity)
        {
            throw $this->createNotFoundException('Unable to find Notificacion entity.');
        }

        return array(
            'entity' => $entity,
        );
    }

    /**
     * Muestra La historia de una notificacion.
     *
     * @param string $sd numerocaso
     * @param string $fin fechainicial Y-m-d
     * @param string $fout fechafinal Y-m-d
     * @param bool $res resolucion
     * @return Array
     */
    public function getTiempoFromNotificacion($sd, $fin, $fout, $res)
    {

        $open = ['Open' => ['ALTA', 'CAMBIOGRUPO', 'MODIFICACION', 'REAPERTURA']];
        $wip = ['Work in progress' => ['ALTA', 'ASIGNACION', 'CAMBIOGRUPO', 'MODIFICACION', 'REACTIVACION', 'REAPERTURA']];
        $susp = ['Suspended' => ['CAMBIOGRUPO', 'MODIFICACION', 'PARADA']];
        $close = ['Closed' => ['CANCELACION', 'CIERRE']];
        $resol = ['Resolved' => ['MODIFICACION', 'RESOLUCION']];

        $tiempo['tn1'] = $tiempo['ta'] = '00:00:00';

        $em = $this->getDoctrine()->getManager('report');
        $entities = $em->getRepository('ReportBundle:Notificacion')->getTimeNotifications($sd, $fin, $fout, $res);

        if (!$entities)
        {
            return $tiempo;
        }

        $arrayTimes = array();

        foreach ($entities as $key => $notificacion)
        {
            if ($notificacion['Estado'] == key($open) or $notificacion['Estado'] == key($wip))
            {
                if ($notificacion['Estado'] == key($open))
                {
                    $arrayTimes[$key][key($open)] = $notificacion['FechaActualizacion'];
                }
                if ($notificacion['Estado'] == key($wip))
                {
                    $arrayTimes[$key][key($wip)] = $notificacion['FechaActualizacion'];
                }
            }
            if ($notificacion['Estado'] == key($susp))
            {
                $arrayTimes[$key][key($susp)] = $notificacion['FechaActualizacion'];
            }
            if ($notificacion['Estado'] == key($resol))
            {
                $arrayTimes[$key][key($resol)] = $notificacion['FechaActualizacion'];
            }
            else
            {
                continue;
            }
        }

        $elementos = count($arrayTimes);
        $estadoAnterior = '';
        $interval = 0;
        $horas = 0;
        $minutos = 0;
        $segundos = 0;
        $horastn1 = 0;
        $minutostn1 = 0;
        $segundostn1 = 0;
        $horasta = 0;
        $minutosta = 0;
        $segundosta = 0;
        
        if ($elementos == 0 or $elementos == 1)
        {
            return $tiempo;
        }
        elseif ($elementos > 1)
        {
            $primeraFecha = \DateTime::createFromFormat('Y-m-d H:i:s', $arrayTimes[0][key($arrayTimes[0])]);
            $estadoAnterior = key($arrayTimes[0]);

            for ($i = 1; $i < $elementos; $i++)
            {
                if ($estadoAnterior != key($susp))
                {
                    if (( key($arrayTimes[$i]) == key($open) ) or ( key($arrayTimes[$i]) == key($wip) ))
                    {
                        $siguiente = \DateTime::createFromFormat('Y-m-d H:i:s', $arrayTimes[$i][key($arrayTimes[$i])]);
                        if ($primeraFecha instanceof \DateTime and $siguiente instanceof \Datetime)
                        {
                            $interval = date_diff($siguiente, $primeraFecha);
                            if (isset($interval) and $interval instanceof \DateInterval)
                            {
                                $horas = $interval->h;
                                $minutos = $interval->i;
                                $segundos = $interval->s;
                            }
                            $horastn1 += $horas;
                            $minutostn1 += $minutos;
                            $segundostn1 += $segundos;

                            $estadoAnterior = key($arrayTimes[$i]);
                            unset($primeraFecha);
                            $primeraFecha = $siguiente;
                            continue;
                        }
                    }
                    if (key($arrayTimes[$i]) == key($susp))
                    {
                        $primeraFecha = \DateTime::createFromFormat('Y-m-d H:i:s', $arrayTimes[$i][key($arrayTimes[$i])]);
                        $estadoAnterior = key($arrayTimes[$i]);
                        continue;
                    }
                    if (key($arrayTimes[$i]) == key($resol) or ( key($arrayTimes[$i]) == key($close)))
                    {
                        $siguiente = \DateTime::createFromFormat('Y-m-d H:i:s', $arrayTimes[$i][key($arrayTimes[$i])]);
                        if ($primeraFecha instanceof \DateTime and $siguiente instanceof \Datetime)
                        {
                            $interval = date_diff($siguiente, $primeraFecha);
                            if (isset($interval) and $interval instanceof \DateInterval)
                            {
                                $horas = $interval->h;
                                $minutos = $interval->i;
                                $segundos = $interval->s;
                            }
                            $horasta += $horas;
                            $minutosta += $minutos;
                            $segundosta += $segundos;
                            $estadoAnterior = key($arrayTimes[$i]);
                            continue;
                        }
                    }
                }
                elseif ($estadoAnterior == key($susp))
                {
                    if (key($arrayTimes[$i]) == key($susp))
                    {
                        $primeraFecha = \DateTime::createFromFormat('Y-m-d H:i:s', $arrayTimes[$i][key($arrayTimes[$i])]);
                        $estadoAnterior = key($arrayTimes[$i]);
                        continue;
                    }

                    if (( key($arrayTimes[$i]) == key($open) ) or ( key($arrayTimes[$i]) == key($wip) ))
                    {

                        $siguiente = \DateTime::createFromFormat('Y-m-d H:i:s', $arrayTimes[$i][key($arrayTimes[$i])]);
                        if ($primeraFecha instanceof \DateTime and $siguiente instanceof \Datetime)
                        {
                            $interval = date_diff($siguiente, $primeraFecha);
                            if (isset($interval) and $interval instanceof \DateInterval)
                            {
                                $horas = $interval->h;
                                $minutos = $interval->i;
                                $segundos = $interval->s;
                            }

                            $horastn1 += $horas;
                            $minutostn1 += $minutos;
                            $segundostn1 += $segundos;

                            $estadoAnterior = key($arrayTimes[$i]);
                            unset($primeraFecha);
                            $primeraFecha = $siguiente;
                            continue;
                        }
                    }
                    if (key($arrayTimes[$i]) == key($resol))
                    {
                        $siguiente = \DateTime::createFromFormat('Y-m-d H:i:s', $arrayTimes[$i][key($arrayTimes[$i])]);
                        if ($primeraFecha instanceof \DateTime and $siguiente instanceof \Datetime)
                        {
                            $interval = date_diff($siguiente, $primeraFecha);
                            if (isset($interval) and $interval instanceof \DateInterval)
                            {
                                $horas = $interval->h;
                                $minutos = $interval->i;
                                $segundos = $interval->s;
                            }
                            $estadoAnterior = key($arrayTimes[$i]);
                            $horasta += $horas;
                            $minutosta += $minutos;
                            $segundosta += $segundos;
                            continue;
                        }
                    }
                    if(key($arrayTimes[$i]) == key($close)){
                        break;
                    }
                }
                elseif ($estadoAnterior == key($resol) and key($arrayTimes[$i]) == key($resol))
                {
                    continue;
                }
                elseif ($estadoAnterior == key($close) and key($arrayTimes[$i]) == key($close))
                {
                    continue;
                }
            }
        }
        $tiempo['tn1'] = $this->fixAndGetTime($horastn1, $minutostn1, $segundostn1);
        if ($segundosta > 0 or $minutosta > 0 or $horasta > 0)
        {
            $horasta += $horastn1;
            $minutosta += $minutostn1;
            $segundosta += $segundostn1;
            $tiempo['ta'] = $this->fixAndGetTime($horasta, $minutosta, $segundosta);
        }
        if(strcmp($tiempo['tn1'],'00:00:00') == 0 and strcmp($tiempo['ta'],'00:00:00') > 0){
            $tiempo['tn1'] = $tiempo['ta'];
        }
        return $tiempo;
    }

    public function fixAndGetTime($hours, $minutes, $seconds)
    {
        $extraminutes = 0;
        $extrahours = 0;
        //fix seconds
        if (is_int($seconds) and $seconds > 59)
        {
            $extraminutes += (int) floor($seconds / 60);
            $seconds = (int) ceil($seconds % 60);
        }
        if ($extraminutes > 0)
        {
            $minutes += $extraminutes;
        }
        if (is_int($minutes) and $minutes > 59)
        {
            $extrahours += (int) floor($minutes / 60);
            $minutes = (int) ceil($minutes % 60);
            $hours += $extrahours;
        }
        if ($seconds >= 0 and $seconds < 10)
        {
            $seconds = '0' . $seconds;
        }
        if ($minutes >= 0 and $minutes < 10)
        {
            $minutes = '0' . $minutes;
        }
        if ($hours >= 0 and $hours < 10)
        {
            $hours = '0' . $hours;
        }

        return $hours . ':' . $minutes . ':' . $seconds;
    }

    /**
     * Obtiene la media o medias la devuelve en el formato adecuado
     * 
     * @param type $tiempo
     * @return type
     */
    protected function getPromedios($arrayNotifications, $fin, $fout, $resol)
    {
        $media = array();
        $contadorA = 0;
        $contadorN1 = 0;
        $ta = 0;
        $tn1 = 0;
        $valortn1 = '';
        $valorta = '';

        if (is_array($arrayNotifications) and count($arrayNotifications) > 0)
        {
            foreach ($arrayNotifications as $notification)
            {
                $time = $this->getTiempoFromNotificacion($notification->getNumeroCaso(), $fin, $fout, $resol);
                
                $valortn1 = $time['tn1'];
                $valorta = $time['ta'];
                
                if((strcmp($valortn1,'00:00:00') == 0 and strcmp($valorta,'00:00:00') == 0) or ($valorta == '' and $valortn1 == ''))
                {
                    continue;
                }
                if (strcmp($valortn1,'00:00:00') > 0)
                {
                        $contadorN1 += 1;
                        $tn1 += strtotime($valortn1);
                }
                if (strcmp($valorta,'00:00:00') > 0)
                {
                        $contadorA += 1;
                        $ta += strtotime($valorta);
                }
            }
            $media['tn1'] = date('H:i:s', ($tn1 / $contadorN1));
            $media['ta'] = date('H:i:s', ($ta / $contadorA));
            return $media;
        }
    }
    
    protected function getSecondsFromTimestring($time)
    {
        $seconds = 0;
        if (is_string($time) and strcmp($time, '00:00:00') > 0)
        {
            $partes = explode(':', $time);
            $seconds += ($partes[0] * 60 * 60) + ($partes[1] * 60) + $partes[2];
            return $seconds;
        }
        else
        {
            return 0;
        }
    }
    
    protected function getDatesStringFromRange($startdate, $enddate){
        $result = array();
        
        
    }

}
