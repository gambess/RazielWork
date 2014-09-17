<?php

namespace Pi2\Fractalia\SGSDSoapServerBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IncidenciaTest extends WebTestCase {

    public function testUpdateFrom() {

        $container = $this->createClient()->getContainer();
        $incidenciaManager = $container->get('incidencia_manager');

        $incidenciaOld = $this->getIncidenciaOld();
        $incidenciaNew = $this->getIncidenciaNew();

        $incidenciaManager->updateOldFromNew($incidenciaOld, $incidenciaNew);

        $this->assertEquals($incidenciaOld->getCIFabricante(), $incidenciaNew->getCIFabricante());
        $this->assertEquals($incidenciaOld->getCliente(), "Cliente 1");
        $this->assertEquals($incidenciaOld->getEstado(), $incidenciaNew->getEstado());
        $this->assertEquals($incidenciaOld->getInformadoPor(), "Informador");
        $this->assertEquals($incidenciaOld->getCodigoPostal(), $incidenciaNew->getCodigoPostal());

        $this->assertEquals($incidenciaOld->getAcciones()->count(), 3);

        $this->assertEquals($incidenciaOld->getDescripciones()->count(), 3);

        $this->assertEquals($incidenciaOld->getResoluciones()->count(), 3);

        $this->assertEquals($incidenciaOld->getInfoAdjuntos()->count(), 3);
    }

    private function getIncidenciaOld() {
        $incidencia = new \Pi2\Fractalia\Entity\SGSD\Incidencia();
        $accion1 = new \Pi2\Fractalia\Entity\SGSD\Accion();
        $accion2 = new \Pi2\Fractalia\Entity\SGSD\Accion();
        $descripcion1 = new \Pi2\Fractalia\Entity\SGSD\Descripcion();
        $descripcion2 = new \Pi2\Fractalia\Entity\SGSD\Descripcion();
        $resolucion1 = new \Pi2\Fractalia\Entity\SGSD\Resolucion();
        $resolucion2 = new \Pi2\Fractalia\Entity\SGSD\Resolucion();
        $infoAdjunto1 = new \Pi2\Fractalia\Entity\SGSD\InfoAdjunto();
        $infoAdjunto2 = new \Pi2\Fractalia\Entity\SGSD\InfoAdjunto();

        $incidencia->setId(1);
        $incidencia->setCIFabricante("Fabricante 1");
        $incidencia->setCliente("Cliente 1");
        $incidencia->setEstado("Abierto");
        $incidencia->setInformadoPor("Informador");

        $accion1->setTexto('Texto acción 11');
        $accion1->setIncidencia($incidencia);

        $accion2->setTexto('Texto acción 12');
        $accion2->setIncidencia($incidencia);

        $acciones = new \Doctrine\Common\Collections\ArrayCollection();
        $acciones->add($accion1);
        $acciones->add($accion2);
        $incidencia->addAcciones($acciones);

        $descripcion1->setTexto('Texto descripcion 11');
        $descripcion1->setIncidencia($incidencia);

        $descripcion2->setTexto('Texto descripcion 12');
        $descripcion2->setIncidencia($incidencia);

        $descripciones = new \Doctrine\Common\Collections\ArrayCollection();
        $descripciones->add($descripcion1);
        $descripciones->add($descripcion2);
        $incidencia->addDescripciones($descripciones);

        $resolucion1->setTexto('Texto resolución 11');
        $resolucion1->setIncidencia($incidencia);

        $resolucion2->setTexto('Texto resolución 12');
        $resolucion2->setIncidencia($incidencia);

        $resoluciones = new \Doctrine\Common\Collections\ArrayCollection();
        $resoluciones->add($resolucion1);
        $resoluciones->add($resolucion2);
        $incidencia->addResoluciones($resoluciones);

        $infoAdjunto1->setOperadorattach('Operador 11');
        $infoAdjunto1->setCompressed('si');
        $infoAdjunto1->setIncidencia($incidencia);

        $infoAdjunto2->setOperadorattach('Operador 12');
        $infoAdjunto2->setCompressed('si');
        $infoAdjunto2->setIncidencia($incidencia);

        $infoAdjuntos = new \Doctrine\Common\Collections\ArrayCollection();
        $infoAdjuntos->add($infoAdjunto1);
        $infoAdjuntos->add($infoAdjunto2);
        $incidencia->addInfoAdjuntos($infoAdjuntos);

        return $incidencia;
    }

    private function getIncidenciaNew() {
        $incidencia = new \Pi2\Fractalia\Entity\SGSD\Incidencia();
        $accion1 = new \Pi2\Fractalia\Entity\SGSD\Accion();
        $accion2 = new \Pi2\Fractalia\Entity\SGSD\Accion();
        $descripcion1 = new \Pi2\Fractalia\Entity\SGSD\Descripcion();
        $descripcion2 = new \Pi2\Fractalia\Entity\SGSD\Descripcion();
        $resolucion1 = new \Pi2\Fractalia\Entity\SGSD\Resolucion();
        $resolucion2 = new \Pi2\Fractalia\Entity\SGSD\Resolucion();
        $infoAdjunto1 = new \Pi2\Fractalia\Entity\SGSD\InfoAdjunto();
        $infoAdjunto2 = new \Pi2\Fractalia\Entity\SGSD\InfoAdjunto();

        $incidencia->setId(1);
        $incidencia->setCIFabricante("Fabricante 1 nuevo");
        $incidencia->setEstado("En proceso");
        $incidencia->setCodigoPostal('28005');

        $accion1->setTexto('Texto acción 11 new');
        $accion1->setIncidencia($incidencia);

        $acciones = new \Doctrine\Common\Collections\ArrayCollection();
        $acciones->add($accion1);
        $incidencia->addAcciones($acciones);

        $descripcion1->setTexto('Texto descripcion 11 new');
        $descripcion1->setIncidencia($incidencia);

        $descripciones = new \Doctrine\Common\Collections\ArrayCollection();
        $descripciones->add($descripcion1);
        $incidencia->addDescripciones($descripciones);


        $resolucion1->setTexto('Texto resolución 11 new');
        $resolucion1->setIncidencia($incidencia);

        $resoluciones = new \Doctrine\Common\Collections\ArrayCollection();
        $resoluciones->add($resolucion1);
        $incidencia->addResoluciones($resoluciones);

        $infoAdjunto1->setOperadorattach('Operador 11 new');
        $infoAdjunto1->setCompressed('no');
        $infoAdjunto1->setIdattach('10');
        $infoAdjunto1->setIncidencia($incidencia);

        $infoAdjuntos = new \Doctrine\Common\Collections\ArrayCollection();
        $infoAdjuntos->add($infoAdjunto1);
        $incidencia->addInfoAdjuntos($infoAdjuntos);

        return $incidencia;
    }

}
