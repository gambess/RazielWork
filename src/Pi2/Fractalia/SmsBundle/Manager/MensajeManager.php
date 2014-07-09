<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MensajeManager
 * Clase que se encarga de gestionar la creacion de mensajes a partir de una incidencia
 * Implementada como servicio
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

class MensajeManager {
    /*
     *  Inyectamos las configuracioness
     */

    private $configuraciones;

    public function __construct($confs) {
        $this->configuraciones = $confs;
    }

    /*
     * Crear el Mensaje A partir de los datos enviados 
     * @param Incidencia|Array $data Incidencia Capturada o Array con resumenes
     * @return id id del Mensaje
     */

    public function createMensaje($data, $plantilla = null, $em = null) {
        $now = (new \DateTime('NOW'));
        if ($em == null) {
            $em = $this->getDoctrineManager();
        }
        if ($data instanceof Incidencia and $plantilla != null) {
            $mensaje = new Mensaje();
            $columna = new Columnaevento();
            $evento = $data->getEstado(); //Al estar en la incidencia obtenemos el estado en hot
            //Cambiar esto
            $array = new IncidenciaArrayEvento($plantilla, $this->configuraciones->getTsolGuardia(), $this->configuraciones->getNombresCortos(), $this->configuraciones->getTraduccionesTipos());
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
            $mensaje->setNombrePlantilla($plantilla);
            $eventoNombre = key($this->configuraciones->getEventos()[$plantilla]['estado']);
            $mensaje->setTipoMensaje($eventoNombre);

            $mensaje->setEstado($estado);
            $em->persist($mensaje);

            $em->flush();
            $mensaje->setTexto($this->getText($columna, $plantilla));
            $mensaje->setColumnaEvento($columna);
            $em->persist($mensaje);
            $em->flush();

            return $mensaje->getId();
        }
        if ((is_array($data) and count($data) > 0)and $plantilla == "RESUMEN" ) {
            $mensaje = new Mensaje();
            $mensaje->setColumnaEvento(null);
            $mensaje->setFechaCreacion($now);
            $mensaje->setFechaActualizacion($now);
            //CAMBIAR nombre tipo plantilla y tipo mensaje
            $mensaje->setNombrePlantilla($plantilla);
            $mensaje->setTipoMensaje($plantilla);
            $em->persist($mensaje);
            $em->flush();

            foreach ($data as $index => $col) {
                $now_in = (new \DateTime('NOW'));
                $resumen = new Columnaresumen();
                $repoIncidencias = $em->getRepository('Pi2\Fractalia\Entity\SGSD\Incidencia');
                $incidencia = $repoIncidencias->findOneByNumeroCaso($col['numeroCaso']);
                $estados[$index] = $this->setEstado($col);
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
        if (is_string($data) and $plantilla == "NO_PENDIENTES") {
            
            $mensaje = new Mensaje();
            $mensaje->setTexto($data);
            $mensaje->setColumnaEvento(null);
            $mensaje->setNombrePlantilla($plantilla);
            $mensaje->setTipoMensaje('RESUMEN');
            $estado = 'CORRECT';
            $mensaje->setEstado($estado);
            $mensaje->setFechaCreacion($now);
            $mensaje->setFechaActualizacion($now);
            $em->persist($mensaje);
            $em->flush();

            return $mensaje->getId();
            
            
        }
    }

    /*
     * Actualizar el Mensaje A partir de los datos enviados 
     * @param $id Id del Mensaje
     * @return true|null
     */

    public function updateMensaje($id) {
        
    }

    /*
     * Leer el Mensaje A partir de los datos enviados 
     * @param $id Id del Mensaje
     * @return Mensaje|null
     */

    public function readMensaje($id) {
        
    }

    /*
     * Borrado Logico del Mensaje A partir de los datos enviados 
     * @param $id Id del Mensaje
     * @return true|null
     */

    public function deleteMensaje($id) {
        
    }

    /*
     * Listado de Mensajes
     * @param $id Id del Mensaje
     * @return true|null
     */

    function listMensajes() {
        
    }

    /*
     * Setea los estados correcto y fallido cuando existe la palabra missing
     * En el Array in_array('missing')
     *
     */

    protected function setEstado(&$array) {
        $keys = array();
        //'FAIL' or 'CORRECT'
        $response = "";
        if (in_array('missing', $array)) {
            $response = 'FAIL';
        } else {
            $response = 'CORRECT';
        }
        $this->fixMissing($array);
        return $response;
    }

    /*
     * Limpiamos los arrays generados para copiar Entidades
     * La Incidencia o Incidencias con datos missing
     */

    protected function fixMissing(&$array) {

        $keys = array_keys($array, 'missing');
        if (count($keys) > 0) {
            foreach ($keys as $key) {
                $array[$key] = null;
            }
        }
    }

    /*
     * Se renderiza la plantilla con el texto en txt
     * que se copia como cuerpo del mensaje
     */

    protected function getText($entity, $plantilla = null) {
        //Modificar templating
        $engine = $GLOBALS['kernel']->getContainer()->get('templating');

        //Modificar estados
        if ($entity instanceof Columnaevento) {
            $content = $engine->render('FractaliaSmsBundle:Columnaevento:text.txt.twig', array(
                'label' => $this->getLabelsFromConfigByEvento($plantilla),
                'entity' => $entity
            ));
        }
        if (($entity instanceof PersistentCollection) and (count($entity) > 0)) {
            $content = $engine->render('FractaliaSmsBundle:Columnaresumen:text.txt.twig', array(
                'label' => $this->getLabelFromConfigByResumen('titulo'),
                'entities' => $entity
            ));
        }
        return $content;
    }

    protected function getDoctrineManager() {

        $doctrine = $GLOBALS['kernel']->getContainer()->get('doctrine');
        return $doctrine->getManager();
    }

//Etiquetas de las plantillas

    /*
     * Setear las etiquetas de los textos con datos de la configuracion
     */
    protected function getLabelsFromConfigByEvento($name) {
        return $this->configuraciones->getPlantillas()[$name];
    }

    protected function getLabelFromConfigByResumen($name) {
        return $this->configuraciones->getResumenes()[$name];
    }

}
