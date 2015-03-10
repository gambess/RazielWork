<?php

namespace Pi2\Fractalia\SGSDSoapServerBundle\Soap;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Pi2\Fractalia\Entity\SGSD\Incidencia;
use Pi2\Fractalia\SGSDReportBundle\Entity\Notificacion;
use Pi2\Fractalia\SGSDReportBundle\Entity\Rechazada;

class SGSDAPI
{
    private $em;
    private $_rem;
    private $hydrator;
    private $incidenciaManager;
    private $arrayObjectsInserted = array();
    private $_logger;
    private $_previas = array();

    public function __construct(EntityManager $em, $hydrator, $incidenciaManager, $logger, $rem)
    {
        $this->em = $em;
        $this->hydrator = $hydrator;
        $this->incidenciaManager = $incidenciaManager;
        $this->_logger = $logger;
        $this->_rem = $rem;
    }

    public function notificarEvento($soapRequest)
    {
        $incidencia = new \Pi2\Fractalia\Entity\SGSD\Incidencia();
        $notificacion = new Notificacion();
        $rechazada = new Rechazada();
        $soapRequestArray = $this->prepareSoapRequest($soapRequest);

//        print_r($soapRequestArray);
//        die;

        $this->hydrator->hydrate($soapRequestArray, $incidencia);
        $incidenciaArray = $this->hydrator->extract($incidencia);
        $this->hydrator->hydrate($incidenciaArray, $notificacion);
        $this->hydrator->hydrate($incidenciaArray, $rechazada);
        $IncidenciaDb = $this->getSavedIncidencia($incidencia->getNumeroCaso());

//        var_dump($IncidenciaDb);die;

        try
        {
            //Nueva Incidencia
            if (is_array($IncidenciaDb) and count($IncidenciaDb) == 0)
            {
                $incidencia->setFechaInsercion(new \DateTime('NOW'));
                $this->em->persist($incidencia);
                //Almacenar en el buffer para procesado posterior
                $this->pushObjectArray($incidencia, "NUEVA");
                $this->em->flush();

                $this->_logger->info("Nueva Incidencia Almacenada: ", array(
                    "Numero caso" => $incidencia->getNumeroCaso(),
                    "Estado" => $incidencia->getEstado(),
                    "Prioridad" => $incidencia->getPrioridad(),
                    "Fecha de insercion" => $incidencia->getFechaInsercion()->format("d/m/Y H:i:s")
                    )
                );
                $notificacion->setFechaInsercion(new \DateTime('NOW'));
                //Se copia en la base de datos reports
                $this->_rem->persist($notificacion);
                $this->_rem->flush();
                return array(
                    'returnCode' => "0",
                    'message' => null,
                    'IDCasoExterno' => null);
            }
            //Actualizacion Incidencia
            elseif (is_array($IncidenciaDb) and count($IncidenciaDb) == 1 and $IncidenciaDb[0] instanceof Incidencia)
            {
                //Obtenemos el unico elemento del array
                $savedIncidencia = array_shift($IncidenciaDb);

                // seters Para filtros extra para ingresar al post procesado
                $prioridadPrevia = $savedIncidencia->getPrioridad();
                $this->setTipoAccion($savedIncidencia->getTipoAccion());
                $this->setGrupoOrigen($savedIncidencia->getGrupoOrigen());

                //Rechazar
                //1.- Fecha Actualizacion de la nueva incidencia es menor que la incidencia guardada
                //2.- Si esta en los estados de resolucion
                //3.- Si esta en un estado no permitido
                if (($incidencia->getFechaActualizacion() < $savedIncidencia->getFechaActualizacion()) or ( $this->isStateResolved($savedIncidencia->getEstado()) and ! $this->inPermitedState($incidencia->getEstado())))
                {
                    $this->_logger->warning("Actualización No Permitida: ", array(
                        "Numero caso" => $incidencia->getNumeroCaso(),
                        "Accion" => $incidencia->getTipoAccion(),
                        "GrupoOrigen" => $incidencia->getGrupoOrigen(),
                        "GrupoDestino" => $incidencia->getGrupoDestino(),
                        "TecnicoAsignado" => $incidencia->getTecnicoAsignadoInicial(),
                        "Fecha Actualizacion Previa" => $savedIncidencia->getFechaActualizacion()->format("d/m/Y H:i:s"),
                        "Fecha Actualizacion Nueva" => $incidencia->getFechaActualizacion()->format("d/m/Y H:i:s"),
                        "Estado Previo" => $savedIncidencia->getEstado(),
                        "Estado Nuevo" => $incidencia->getEstado(),
                        )
                    );
                    //Copia en rechazadas
                    $rechazada->setFechaInsercion(new \DateTime('NOW'));
                    $this->_rem->persist($rechazada);
                    $this->_rem->flush();

                    return array(
                        'returnCode' => "0",
                        'message' => null,
                        'IDCasoExterno' => null);
                }
                //Permitido actualizar
                else
                {
                    $this->incidenciaManager->updateOldFromNew($savedIncidencia, $incidencia);
                    //Se habilita para que se muestre en el monitor
                    $savedIncidencia->setHideInMonitor(false);
                    $savedIncidencia->setFechaInsercion(new \DateTime('NOW'));
                    $this->em->persist($savedIncidencia);

                    $this->_logger->info("Incidencia a Actualizada:", array(
                        "Numero caso" => $savedIncidencia->getNumeroCaso(),
                        "Estado" => $savedIncidencia->getEstado(),
                        "Prioridad" => $savedIncidencia->getPrioridad(),
                        "Fecha Inserción" => $savedIncidencia->getFechaInsercion()->format("d/m/Y H:i:s"),
                        )
                    );
                    //Se copia en notificaciones y se guarda en el buffer de post procesado
                    $notificacion->setFechaInsercion(new \DateTime('NOW'));
                    $this->_rem->persist($notificacion);
                    $this->_rem->flush();
                    $this->pushObjectArray($savedIncidencia, $prioridadPrevia);

                    return array(
                        'returnCode' => "0",
                        'message' => null,
                        'IDCasoExterno' => null);
                }
            }
            //Caso de Actualizacion y Reparacion de Notificaciones
            elseif (is_array($IncidenciaDb) and count($IncidenciaDb) > 1)
            {
                $this->_logger->info("Encontradas " . count($IncidenciaDb) . " Incidencias Almacenadas: ", array("Numero caso" => $IncidenciaDb[0]->getNumeroCaso())
                );
                $i = 0;
                $max = count($IncidenciaDb);
                foreach ($IncidenciaDb as $saveIncidencia)
                {
                    if ($saveIncidencia instanceof Incidencia)
                    {
                        if ($i == 0)
                        {
                            //Misma operativa para ver si se puede actualizar la notificacion pero solo por fechas de actualizacion
                            $prioridadPrevia = $saveIncidencia->getPrioridad();
                            $this->setTipoAccion($saveIncidencia->getTipoAccion());
                            $this->setGrupoOrigen($saveIncidencia->getGrupoOrigen());

                            if ($incidencia->getFechaActualizacion() < $saveIncidencia->getFechaActualizacion())
                            {
                                $this->_logger->warning("Actualización No Permitida: ", array(
                                    "Numero caso" => $incidencia->getNumeroCaso(),
                                    "Accion" => $incidencia->getTipoAccion(),
                                    "GrupoOrigen" => $incidencia->getGrupoOrigen(),
                                    "GrupoDestino" => $incidencia->getGrupoDestino(),
                                    "TecnicoAsignado" => $incidencia->getTecnicoAsignadoInicial(),
                                    "Fecha Actualizacion Previa" => $saveIncidencia->getFechaActualizacion()->format("d/m/Y H:i:s"),
                                    "Fecha Actualizacion Nueva" => $incidencia->getFechaActualizacion()->format("d/m/Y H:i:s"),
                                    )
                                );
                                //Proceso de rechazado
                                $rechazada->setHideInMonitor(true);
                                $rechazada->setFechaInsercion(new \DateTime('NOW'));
                                $this->_rem->persist($rechazada);
                                $this->_rem->flush();
                                $i++;
                            }
                            else
                            {
                                //Actualizado
                                $this->incidenciaManager->updateOldFromNew($saveIncidencia, $incidencia);
                                $saveIncidencia->setHideInMonitor(false);
                                $saveIncidencia->setFechaInsercion(new \DateTime('NOW'));
                                $this->em->persist($saveIncidencia);
                                $this->em->flush();

                                $this->_logger->info("Incidencia Actualizada:", array(
                                    "Numero caso" => $saveIncidencia->getNumeroCaso(),
                                    "Estado" => $saveIncidencia->getEstado(),
                                    "Prioridad" => $saveIncidencia->getPrioridad(),
                                    "Fecha Inserción" => $saveIncidencia->getFechaInsercion()->format("d/m/Y H:i:s"),
                                    )
                                );

                                $notificacion->setFechaInsercion(new \DateTime('NOW'));
                                //Se copia en la base de datos reports
                                $this->_rem->persist($notificacion);
                                $this->_rem->flush();
                                $this->pushObjectArray($saveIncidencia, $prioridadPrevia);
                                $i++;
                            }
                        }
                        elseif ($i >= 1 and $i < $max)
                        {
                            //Rechazar las duplicadas y borrar
                            $reject = new Rechazada();
                            $hydratorReject = new DoctrineObject($this->_rem);
                            $saveIncidencia->setHideInMonitor(true);
                            $arraySaveIncidencia = $this->hydrator->extract($saveIncidencia);
                            $arraySaveIncidencia['id'] = null;
                            $hydratorReject->hydrate($arraySaveIncidencia, $reject);
                            $reject->setFechaInsercion(new \DateTime('NOW'));
                            $this->_rem->persist($reject);
                            $this->_rem->flush();
                            $this->em->remove($saveIncidencia);
                            $this->em->flush();
                            $i++;
                        }
                    }
                    else
                    {
                        $i++;
                        continue;
                    }
                }
                return array(
                    'returnCode' => "0",
                    'message' => null,
                    'IDCasoExterno' => null);
            }
        }
        catch (\Exception $e)
        {
            $this->_logger->error("Exception capturada en la inserción remota", array("mensaje" => $e->getMessage(), "traza", $e->getTraceAsString()));
            throw $e;
        }
    }

    /**
     * 
     * @param Incidencia $incidenciaClone
     * @param string $key
     */
    private function pushObjectArray(Incidencia $incidenciaClone, $key = null)
    {
        if (!is_null($key))
        {
            $this->arrayObjectsInserted[$key] = $incidenciaClone;
        }
        else
        {
            $this->arrayObjectsInserted[] = $incidenciaClone;
        }
    }

    public function existObjectsInArray()
    {
        if (count($this->arrayObjectsInserted) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getObjectFromArray()
    {
        if ($this->existObjectsInArray())
        {
            return $this->arrayObjectsInserted;
        }
        else
        {
            return false;
        }
    }

    protected function prepareSoapRequest($soapRequest)
    {

        $arrSoapRequest = (array) $soapRequest;


        if (isset($soapRequest->DescripcionAcciones) && isset($soapRequest->DescripcionAcciones->DescripcionAccion))
        {
            $acciones = (array) $soapRequest->DescripcionAcciones->DescripcionAccion;
            $arrSoapRequest['DescripcionAcciones'] = null;
            $arrSoapRequest['acciones'] = $this->prepareArray($acciones, '\Pi2\Fractalia\Entity\SGSD\Accion');
        }

        if (isset($soapRequest->Descripciones) && isset($soapRequest->Descripciones->Descripcion))
        {

            $descripciones = $soapRequest->Descripciones->Descripcion;
            $arrSoapRequest['Descripciones'] = null;
            $arrSoapRequest['descripciones'] = $this->prepareArray($descripciones, '\Pi2\Fractalia\Entity\SGSD\Descripcion');
        }

        if (isset($soapRequest->Resoluciones) && isset($soapRequest->Resoluciones->Resolucion))
        {

            $resoluciones = $soapRequest->Resoluciones->Resolucion;
            $arrSoapRequest['Resoluciones'] = null;
            $arrSoapRequest['resoluciones'] = $this->prepareArray($resoluciones, '\Pi2\Fractalia\Entity\SGSD\Resolucion');
        }

        if (isset($soapRequest->InfoAdjuntos) && isset($soapRequest->InfoAdjuntos->Infoadjunto))
        {

            $infoadjuntos = $soapRequest->InfoAdjuntos->Infoadjunto;
            $arrSoapRequest['InfoAdjuntos'] = null;
            $arrSoapRequest['infoAdjuntos'] = $this->prepareArray($infoadjuntos, '\Pi2\Fractalia\Entity\SGSD\InfoAdjunto');
        }

        if (isset($soapRequest->FechaActualizacion))
        {
            $arrSoapRequest['FechaActualizacion'] = $this->prepareDatetime($soapRequest->FechaActualizacion);
        }

        if (isset($soapRequest->FechaApertura))
        {
            $arrSoapRequest['FechaApertura'] = $this->prepareDatetime($soapRequest->FechaApertura);
        }

        if (isset($soapRequest->FechaResolucion))
        {
            $arrSoapRequest['FechaResolucion'] = $this->prepareDatetime($soapRequest->FechaResolucion);
        }

        return $arrSoapRequest;
    }

    protected function prepareArray($items, $class)
    {
        $itemsPrepared = array();

        // items puede llegar como un valor escalar o como un 
        // array. Necesitamos un array para la hydration

        if (!is_array($items))
        {
            $items = array($items);
        }

        foreach ($items as $item)
        {
            $entity = new $class();
            ($item instanceof \stdClass) ?
                    $this->hydrator->hydrate((array) $item, $entity) : $entity->setTexto($item);
            $itemsPrepared[] = $entity;
        }

        return $itemsPrepared;
    }

    protected function prepareDatetime($fechaString)
    {
        //PARA UTILIZAR GMT+0 Es necesario establecer la zona horaria UTC
        $utcTimeZone = new \DateTimeZone('UTC');
        // Este es el formato de fecha que supuestamente viene en todas las fechas de la petición 26/03/2014 12:25:34
        //Pero este no así, y la siguiente linea de codigo captura otra fecha
        $fechaCorta = "/^[\d]{2}\/[\d]{2}\/[\d]{2}\s{1}[\d]{2}\:[\d]{2}\:[\d]{2}$/";
        if (preg_match($fechaCorta, $fechaString))
        {
            $fecha = \DateTime::createFromFormat('d/m/y H:i:s O', $fechaString." +0000");
            $fecha->setTimezone($utcTimeZone);
            return $fecha;
        }
        $fechaLarga = "/^[\d]{2}\/[\d]{2}\/[\d]{4}\s{1}[\d]{2}\:[\d]{2}\:[\d]{2}$/";
        if (preg_match($fechaLarga, $fechaString))
        {
            $fecha = \DateTime::createFromFormat('d/m/Y H:i:s O', $fechaString." +0000");
            $fecha->setTimezone($utcTimeZone);
            return $fecha;
        }
        $fechaGmtCorta = "/^[\d]{2}\/[\d]{2}\/[\d]{2}\s{1}[\d]{2}\:[\d]{2}\:[\d]{2}\s{1}[\+-]{1}0{2,3}[\d]{1,2}$/";
        if (preg_match($fechaGmtCorta, $fechaString))
        {
            $partesFecha = explode(" ", $fechaString);
            $nuevaFecha = $partesFecha[0] . " " . $partesFecha[1];
            $res = preg_match("/^([\+-]){1}0{2,3}([\d]{1,2})$/", $partesFecha[2], $match);
            if ($res == 1 and count($match) == 3)
            {
                $signo = $match[1];
                $numero = $match[2];
                if ($match[2] > 0 and $match[2] < 9)
                {
                    $gmt = $signo . "0" . $match[2] . "00";
                }
                elseif ($match[2] > 9 and $match[2] < 25)
                {
                    $gmt = $signo . $match[2] . "00";
                }
                
                $fecha = \DateTime::createFromFormat('d/m/y H:i:s O', $nuevaFecha . " " . $gmt);
                $fecha->setTimezone($utcTimeZone);
                return $fecha;
            }
        }
        $fechaGmtLarga = "/^[\d]{2}\/[\d]{2}\/[\d]{4}\s{1}[\d]{2}\:[\d]{2}\:[\d]{2}\s{1}[\+-]{1}0{2,3}[\d]{1,2}$/";
        if (preg_match($fechaGmtLarga, $fechaString))
        {
            
            $partesFecha = explode(" ", $fechaString);
            
            $nuevaFecha = $partesFecha[0] . " " . $partesFecha[1];
            $res = preg_match("/^([\+-]){1}0{2,3}([\d]{1,2})$/", $partesFecha[2], $match);
            if ($res == 1 and count($match) == 3)
            {
                $signo = $match[1];
                $numero = $match[2];
                if ($match[2] > 0 and $match[2] < 9)
                {
                    $gmt = $signo . "0" . $match[2] . "00";
                }
                elseif ($match[2] > 9 and $match[2] < 25)
                {
                    $gmt = $signo . $match[2] . "00";
                }
                
                $fecha = \DateTime::createFromFormat('d/m/Y H:i:s O', $nuevaFecha . " " . $gmt);
                $fecha->setTimezone($utcTimeZone);
                return $fecha;

            }
        }
    }

    protected function getSavedIncidencia($numCaso)
    {
        $repoIncidencias = $this->em->getRepository('Pi2\Fractalia\Entity\SGSD\Incidencia');

//        $incidencia = $repoIncidencias->findOneByNumeroCaso($numCaso);
        $incidencia = $repoIncidencias->findBy(array('numeroCaso' => $numCaso), array('fechaActualizacion' => 'DESC'));

        return $incidencia;
    }

    public function updateIncidenciaFrom(Incidencia $incidenciaOld, Incidencia $incidenciaNew)
    {
        $clase = new \ReflectionClass('Pi2\Fractalia\Entity\SGSD\Incidencia');
        $propiedades = $clase->getProperties(\ReflectionProperty::IS_PRIVATE);

        foreach ($propiedades as $propiedad)
        {
            if ($propiedad->name == 'id' ||
                $propiedad->name == 'acciones' ||
                $propiedad->name == 'descripciones' ||
                $propiedad->name == 'infoAdjuntos' ||
                $propiedad->name == 'resoluciones')
            {
                continue;
            }

            $valorProp = $incidenciaNew->{"get" . ucfirst($propiedad->getName())}();
            if (!is_null($valorProp))
            {
                $incidenciaOld->{"set" . ucfirst($propiedad->getName())}($valorProp);
            }
        }

        $incidenciaOld->addAcciones($incidenciaNew->getAcciones());
        $incidenciaOld->addDescripciones($incidenciaNew->getDescripciones());
        $incidenciaOld->addResoluciones($incidenciaNew->getResoluciones());
        $incidenciaOld->addInfoAdjuntos($incidenciaNew->getInfoAdjuntos());
    }

    protected function isStateResolved($estado)
    {

        $resultados = array("RESOLVED", "RESUELTO");
        return in_array(strtoupper($estado), $resultados);
    }

    protected function isStateOpen($estado)
    {

        $abiertos = array("OPEN", "ASIGNADO");
        return in_array(strtoupper($estado), $abiertos);
    }

    protected function inPermitedState($estado)
    {
        //En realidad una notificación en estado Resolved puede voler a resolved
        //Pueden re abrir se o Pueden cerrarse
        $estados = array("OPEN", "ASIGNADO", "REOPENED", "RESOLVED", "RESUELTO", "CLOSED", "CERRADO");
        //Estados no permitidos
        //"Work in progress", "En proceso", "Suspended" , "Parado" 
        return in_array(strtoupper($estado), $estados);
    }

    /**
     *
     *  
     * @param type $tipoAccion
     */
    protected function setTipoAccion($tipoAccion)
    {
        $this->_previas['tipoAccion'] = $tipoAccion;
    }

    /**
     * 
     * 
     * @param type $grupoOrigen
     */
    protected function setGrupoOrigen($grupoOrigen)
    {
        $this->_previas['grupoOrigen'] = $grupoOrigen;
    }

    public function getPrevias()
    {
        return $this->_previas;
    }

}
