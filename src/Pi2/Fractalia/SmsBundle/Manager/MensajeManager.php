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
use Doctrine\Common\Collections\ArrayCollection;

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
     * @param Incidencia|ArrayCollection $data
     * @return id id del Mensaje
     */

    public function createMensaje($data, $em)
    {
        if ($data instanceof Incidencia)
        {
            $mensaje = new Mensaje();
            $columna = new Columnaevento();


//            $em = $this->getDoctrine()->getManager();
//            $data = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia')->find(66);
            $evento = $data->getEstado(); //Es necesario definir la construccion de los estados
            $array = new IncidenciaArrayEvento($evento, $this->tsolArrayConf, $this->nombresCortosConf, $this->traducciones);
            /*
             * Array con los datos copiados de la incidencia Evento
             */
            $arrayIncidencia = $array->setArrayIncidencia($data);
            $estado = in_array('missing', $arrayIncidencia) ? 'FAIL' : 'CORRECT';

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

            $now = (new \DateTime('NOW'));
            $mensaje->setFechaCreacion($now);
            $mensaje->setFechaActualizacion($now);
            $mensaje->setNombrePlantilla($evento);
            $mensaje->setTipoMensaje('EVENTO');
            $mensaje->setColumnaresumen(null);
            $mensaje->setEstado($estado);
            $em->persist($mensaje);
            $em->flush();



            $mensaje->setTexto($this->getText($columna));
            $mensaje->setColumnaEvento($columna);
            $em->persist($mensaje);
            $em->flush();
            
            return $mensaje->getId();
        }
        if ($data instanceof ArrayCollection)
        {
            
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

    protected function getText($entity)
    {
        if($entity instanceof Columnaevento){
        $engine = $GLOBALS['kernel']->getContainer()->get('templating');
        $content = $engine->render('FractaliaSmsBundle:Columnaevento:text.txt.twig', array(
            'label' => $this->getLabelsFromConfigByEvento('RESUELTO'),
            'entity' => $entity
        ));
        }
        return $content;
    }
    
    protected function getLabelsFromConfigByEvento($name)
    {
        return $GLOBALS['kernel']->getContainer()->getParameter('pi2_frac_sgsd_soap_server.plantillas')[$name];
    }

}
