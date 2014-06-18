<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mensaje
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */

namespace Pi2\Fractalia\SmsBundle\Manager;

use Pi2\Fractalia\SmsBundle\Entity\Mensaje;
use Pi2\Fractalia\SmsBundle\Entity\Columnaevento;
use Pi2\Fractalia\SmsBundle\Entity\Columnaresumen;
use Pi2\Fractalia\Entity\SGSD\Incidencia;
use Pi2\Fractalia\SmsBundle\Util\IncidenciaArrayEvento;
use Doctrine\ORM\PersistentCollection;

class MensajeManager
{
    /*
     *  container->getParameter('pi2_frac_sgsd_soap_server.envio_sms.tsol_guardia')
     */
    private $tsolArrayConf;
    /*
     *  container->getParameter('pi2_frac_sgsd_soap_server.envio_sms.nombres_cortos')
     */
    private $nombresCortosConf;
    /*
     *  container->getParameter('pi2_frac_sgsd_soap_server.envio_sms.traduccion_tipo_caso')
     */
    private $traducciones;

    /*
     * Crear el Mensaje A partir de los datos enviados
     * @param Incidencia|Array $data
     * @return id id del Mensaje
     */

    public function createMensaje($data, $em=null)
    {
        $now = (new \DateTime('NOW'));
        if ($data instanceof Incidencia)
        {


            $mensaje = new Mensaje();

            $columna = new Columnaevento();
            $evento = $data->getEstado(); //Es necesario definir la construccion de los estados
            $array = new IncidenciaArrayEvento($evento, $this->tsolArrayConf, $this->nombresCortosConf, $this->traducciones);
            //Array con los datos copiados de la incidencia Evento
            $arrayIncidencia = $array->setArrayIncidencia($data);
            $estado = $this->setEstado($arrayIncidencia);
            $columna->setIncidencia($data);
            $columna->setNumeroCaso($arrayIncidencia['id']);
            $columna->setCliente($arrayIncidencia['cliente']);
            $columna->setTipo($arrayIncidencia['tipo']);
            $columna->setTecnico($arrayIncidencia['tecnico']);
            $columna->setTsol($arrayIncidencia['tsol']);
            $columna->setFecha($arrayIncidencia['fecha']);
            $columna->setModo($arrayIncidencia['modo']);
            $columna->setDetalle($arrayIncidencia['detalle']);
            $em->persist($columna);
            $em->flush();

            $mensaje->setFechaCreacion($now);
            $mensaje->setFechaActualizacion($now);
            $mensaje->setNombrePlantilla($evento);
            $mensaje->setTipoMensaje('EVENTO');
            $mensaje->setEstado($estado);  
            $em->persist($mensaje);
              
            $em->flush();
            $mensaje->setTexto($this->getText($columna));
                                                           
            $mensaje->setColumnaEvento($columna);
            $em->persist($mensaje);
            $em->flush();

            return $mensaje->getId();
        }
        if (is_array($data) and count($data) > 0)
        {
            $mensaje = new Mensaje();
            $mensaje->setColumnaEvento(null);
            $mensaje->setFechaCreacion($now);
            $mensaje->setFechaActualizacion($now);
            $mensaje->setNombrePlantilla('RESUMEN');
            $mensaje->setTipoMensaje('RESUMEN');
            $em->persist($mensaje);
            $em->flush();

            foreach ($data as $index => $col)
            {
                $now_in = (new \DateTime('NOW'));
                $resumen = new Columnaresumen();
                $repoIncidencias = $em->getRepository('Pi2\Fractalia\Entity\SGSD\Incidencia');
                $incidencia = $repoIncidencias->findOneByNumeroCaso($col['numeroCaso']);
                $resumen->setIncidencia($incidencia);
                $resumen->setNumeroCaso($col['numeroCaso']);
                $resumen->setEstado($col['estado']);
                $resumen->setServicio($col['destino']);
                $resumen->setMensaje($mensaje);
                $em->persist($resumen);
                $em->flush();

                $mensaje->addColumnaResuman($resumen);
                $mensaje->setFechaActualizacion($now_in);

                $em->persist($mensaje);
                $em->flush();
                $estados[$index] = $this->setEstado($col);
            }
            $estado = in_array('FAIL', $estados) ? 'FAIL' : 'CORRECT';
            $mensaje->setEstado($estado);
            $em->persist($mensaje);
            $em->flush();
            $resumenes = $mensaje->getColumnaResumen();
            $mensaje->setTexto($this->getText($resumenes));
            $em->persist($mensaje);
            $em->flush();
            return $mensaje->getId();
        }
    }

    public function setTsolArrayConf($tsolArrayConf)
    {
        $this->tsolArrayConf = $tsolArrayConf;
    }

    public function setNombresCortosConf($nombresCortosConf)
    {
        $this->nombresCortosConf = $nombresCortosConf;
    }

    public function setTraducciones($traducciones)
    {
        $this->traducciones = $traducciones;
    }

    public function setDestinatarios($destinatarios)
    {
        $this->destinatarios = $destinatarios;
    }

    protected function setEstado($array)
    {
        return in_array('missing', $array) ? 'FAIL' : 'CORRECT';
    }

    protected function getText($entity)
    {
        $engine = $GLOBALS['kernel']->getContainer()->get('templating');

        if ($entity instanceof Columnaevento)
        {
            $content = $engine->render('FractaliaSmsBundle:Columnaevento:text.txt.twig', array(
                'label' => $this->getLabelsFromConfigByEvento('RESUELTO'),
                'entity' => $entity
            ));
        }
        if (($entity instanceof PersistentCollection) and (count($entity) > 0)){
            $content = $engine->render('FractaliaSmsBundle:Columnaresumen:text.txt.twig', array(
                'label' => $this->getLabelFromConfigByResumen('titulo'),
                'entities' => $entity
            ));
        }
        return $content;
    }

    protected function getLabelsFromConfigByEvento($name)
    {
        return $GLOBALS['kernel']->getContainer()->getParameter('pi2_frac_sgsd_soap_server.plantillas')[$name];
    }
    
    protected function getLabelFromConfigByResumen($name)
    {
        return $GLOBALS['kernel']->getContainer()->getParameter('pi2_frac_sgsd_soap_server.resumenes.resumen')[$name];
    }

}
