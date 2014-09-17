<?php

namespace Pi2\Fractalia\SGSDSoapServerBundle\Soap;

use Doctrine\ORM\EntityManager;
use Pi2\Fractalia\Entity\SGSD\Incidencia;

class SGSDAPI
{
    private $em;
    private $hydrator;
    private $incidenciaManager;
    private $logger;

    public function __construct(EntityManager $em, $hydrator, $incidenciaManager, $logger)
    {
        $this->em = $em;
        $this->hydrator = $hydrator;
        $this->incidenciaManager = $incidenciaManager;
        $this->logger = $logger;
    }

    public function notificarEvento($soapRequest)
    {

        $now = (new \DateTime("NOW"));
        $originalRequest = $soapRequest;
        $this->logger->info("Procesando nuevo request", array("Hora ingreso" => $now->format("d/m/Y H:i:s")));
//        var_dump($soapRequest);exit;

        $incidencia = new \Pi2\Fractalia\Entity\SGSD\Incidencia();
        $incidencia->setFechaInsercion(new \DateTime());
        $this->logger->info("Seteando fecha inserción: " . $incidencia->getFechaInsercion()->format("d/m/Y H:i:s"));

        $soapRequestArray = $this->prepareSoapRequest($soapRequest);
        $this->logger->info("Preparando el Request");

        $this->hydrator->hydrate($soapRequestArray, $incidencia);
        $this->logger->info("Hidratando");

        $savedIncidencia = $this->getSavedIncidencia($incidencia->getNumeroCaso());
        if ($savedIncidencia instanceof Incidencia)
        {
            $this->logger->info("Se modificara la incidencia: ", array(
                "Numero de Caso" => $incidencia->getNumeroCaso(),
                "estado" => $incidencia->getEstado(),
                "fecha de actualizacion" => $incidencia->getFechaActualizacion()->format("d/m/Y H:i:s")));
        }
        if (is_null($savedIncidencia))
        {
            $this->logger->info("Inserción nueva incidencia");
        }
        try
        {

            if ($savedIncidencia instanceof \Pi2\Fractalia\Entity\SGSD\Incidencia)
            {
                $this->incidenciaManager->updateOldFromNew($savedIncidencia, $incidencia);

                $this->logger->info("Modificando Incidencia: ", array(
                    "Numero de Caso" => $incidencia->getNumeroCaso(),
                    "estado" => $incidencia->getEstado(),
                    "fecha actualizacion" => $incidencia->getFechaActualizacion()->format("d/m/Y H:i:s"),
                    "fecha insercion" => $incidencia->getFechaInsercion()->format("d/m/Y H:i:s")));

                $this->em->persist($savedIncidencia);
                $this->logger->info("Persistida la Incidencia", array("Numero de Caso" => $incidencia->getNumeroCaso()));
            }
            else
            {
                $this->logger->info("Añadiendo la Incidencia: ", array(
                    "Numero de Caso" => $incidencia->getNumeroCaso(),
                    "Nuevo estado" => $incidencia->getEstado(),
                    "fecha apertura" => $incidencia->getFechaApertura()->format("d/m/Y H:i:s"),
                    "fecha insercion" => $incidencia->getFechaInsercion()->format("d/m/Y H:i:s")));

                $this->em->persist($incidencia);
                $this->logger->info("Persistida la Incidencia", array("Numero de Caso" => $incidencia->getNumeroCaso()));
            }

            $this->em->flush();
            $this->logger->info("flush incidencia", array("Numero de Caso" => $incidencia->getNumeroCaso()));
            $this->logger->info("Se Retornará un caso exitoso");
            return array(
                'returnCode' => "0",
                'message' => null,
                'IDCasoExterno' => null);
        }
        catch (\Exception $e)
        {
            $this->logger->info("Capturada Exception: ", array(
                "code" => $e->getCode(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
                "message" => $e->getMessage(),
                "traceString" => $e->getTraceAsString(),
                "original_request" => $originalRequest
                )
            );
            throw $e;
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
        // Este es el formato de fecha que viene en la petición 26/03/2014 12:25:34

        $datetime = \DateTime::createFromFormat('d/m/Y H:i:s', $fechaString);

        return $datetime;
    }

    protected function getSavedIncidencia($numCaso)
    {
        $repoIncidencias = $this->em->getRepository('Pi2\Fractalia\Entity\SGSD\Incidencia');

        $incidencia = $repoIncidencias->findOneByNumeroCaso($numCaso);

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

}
