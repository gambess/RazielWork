<?php

namespace Pi2\Fractalia\SGSDSoapServerBundle\Soap;

use Doctrine\ORM\EntityManager;
use Pi2\Fractalia\Entity\SGSD\Incidencia;

class SGSDAPI {

    private $em;
    private $hydrator;
    private $incidenciaManager;

    public function __construct(EntityManager $em, $hydrator, $incidenciaManager) {
        $this->em = $em;
        $this->hydrator = $hydrator;
        $this->incidenciaManager = $incidenciaManager;
    }

    public function notificarEvento($soapRequest) {

        //var_dump($soapRequest);exit;

        $incidencia = new \Pi2\Fractalia\Entity\SGSD\Incidencia();
        $incidencia->setFechaInsercion(new \DateTime());

        $soapRequestArray = $this->prepareSoapRequest($soapRequest);

        $this->hydrator->hydrate($soapRequestArray, $incidencia);

        $savedIncidencia = $this->getSavedIncidencia($incidencia->getNumeroCaso());

        try {
            
            if ($savedIncidencia instanceof \Pi2\Fractalia\Entity\SGSD\Incidencia) {
                $this->incidenciaManager->updateOldFromNew($savedIncidencia,$incidencia);
                $this->em->persist($savedIncidencia);
            }else{                
                $this->em->persist($incidencia);
            }
                        
            $this->em->flush();

            return array(
                'returnCode' => "0",
                'message' => null,
                'IDCasoExterno' => null);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function prepareSoapRequest($soapRequest) {

        $arrSoapRequest = (array) $soapRequest;


        if (isset($soapRequest->DescripcionAcciones) && isset($soapRequest->DescripcionAcciones->DescripcionAccion)) {
            $acciones = (array) $soapRequest->DescripcionAcciones->DescripcionAccion;
            $arrSoapRequest['DescripcionAcciones'] = null;
            $arrSoapRequest['acciones'] = $this->prepareArray($acciones, '\Pi2\Fractalia\Entity\SGSD\Accion');
        }

        if (isset($soapRequest->Descripciones) && isset($soapRequest->Descripciones->Descripcion)) {

            $descripciones = $soapRequest->Descripciones->Descripcion;
            $arrSoapRequest['Descripciones'] = null;
            $arrSoapRequest['descripciones'] = $this->prepareArray($descripciones, '\Pi2\Fractalia\Entity\SGSD\Descripcion');
        }

        if (isset($soapRequest->Resoluciones) && isset($soapRequest->Resoluciones->Resolucion)) {

            $resoluciones = $soapRequest->Resoluciones->Resolucion;
            $arrSoapRequest['Resoluciones'] = null;
            $arrSoapRequest['resoluciones'] = $this->prepareArray($resoluciones, '\Pi2\Fractalia\Entity\SGSD\Resolucion');
        }

        if (isset($soapRequest->InfoAdjuntos) && isset($soapRequest->InfoAdjuntos->Infoadjunto)) {

            $infoadjuntos = $soapRequest->InfoAdjuntos->Infoadjunto;
            $arrSoapRequest['InfoAdjuntos'] = null;
            $arrSoapRequest['infoAdjuntos'] = $this->prepareArray($infoadjuntos, '\Pi2\Fractalia\Entity\SGSD\InfoAdjunto');
        }

        if (isset($soapRequest->FechaActualizacion)) {
            $arrSoapRequest['FechaActualizacion'] = $this->prepareDatetime($soapRequest->FechaActualizacion);
        }

        if (isset($soapRequest->FechaApertura)) {
            $arrSoapRequest['FechaApertura'] = $this->prepareDatetime($soapRequest->FechaApertura);
        }

        if (isset($soapRequest->FechaResolucion)) {
            $arrSoapRequest['FechaResolucion'] = $this->prepareDatetime($soapRequest->FechaResolucion);
        }

        return $arrSoapRequest;
    }

    protected function prepareArray($items, $class) {
        $itemsPrepared = array();

        // items puede llegar como un valor escalar o como un 
        // array. Necesitamos un array para la hydration

        if (!is_array($items)) {
            $items = array($items);
        }

        foreach ($items as $item) {
            $entity = new $class();
            ($item instanceof \stdClass) ?
                            $this->hydrator->hydrate((array) $item, $entity) : $entity->setTexto($item);
            $itemsPrepared[] = $entity;
        }

        return $itemsPrepared;
    }

    protected function prepareDatetime($fechaString) {
        // Este es el formato de fecha que viene en la petición 26/03/2014 12:25:34

        $datetime = \DateTime::createFromFormat('d/m/Y H:i:s', $fechaString);

        return $datetime;
    }

    protected function getSavedIncidencia($numCaso) {
        $repoIncidencias = $this->em->getRepository('Pi2\Fractalia\Entity\SGSD\Incidencia');
    
        $incidencia = $repoIncidencias->findOneByNumeroCaso($numCaso);
        
        return $incidencia;
    }
    
    public function updateIncidenciaFrom(Incidencia $incidenciaOld, Incidencia $incidenciaNew) {
        $clase = new \ReflectionClass('Pi2\Fractalia\Entity\SGSD\Incidencia');
        $propiedades = $clase->getProperties(\ReflectionProperty::IS_PRIVATE);

        foreach ($propiedades as $propiedad) {
            if ($propiedad->name == 'id' ||
                    $propiedad->name == 'acciones' ||
                    $propiedad->name == 'descripciones' ||
                    $propiedad->name == 'infoAdjuntos' ||
                    $propiedad->name == 'resoluciones') {
                continue;
            }

            $valorProp = $incidenciaNew->{"get" . ucfirst($propiedad->getName())}();
            if (!is_null($valorProp)) {
                $incidenciaOld->{"set" . ucfirst($propiedad->getName())}($valorProp);
            }            
        }
        
        $incidenciaOld->addAcciones($incidenciaNew->getAcciones());
        $incidenciaOld->addDescripciones($incidenciaNew->getDescripciones());
        $incidenciaOld->addResoluciones($incidenciaNew->getResoluciones());
        $incidenciaOld->addInfoAdjuntos($incidenciaNew->getInfoAdjuntos());
        
    }

}
