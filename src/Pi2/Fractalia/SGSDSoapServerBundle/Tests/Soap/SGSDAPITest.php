<?php

namespace Pi2\Fractalia\SGSDSoapServerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class SGSDAPITest extends WebTestCase {

    public function testNotificarEvento() {
                
        $container = $this->createClient()->getContainer();
        $soapRequest = new \stdClass();
               
        $soapRequest->NumeroCaso = "32";
        $soapRequest->IncidenciaAjena = "Si";
        //$soapRequest->FechaActualizacion = "02/08/2012";
        
        $sgsdApi = $container->get('soap_api');

        $response = $sgsdApi->notificarEvento($soapRequest);
        
        $this->assertEquals($response['returnCode'], "200");
        $this->assertEquals($response['message'], "tuttobene");
        $this->assertEquals($response['IDCasoExterno'], "32");
        
    }
    
    }
