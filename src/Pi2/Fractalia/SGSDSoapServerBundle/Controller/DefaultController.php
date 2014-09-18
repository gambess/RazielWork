<?php

namespace Pi2\Fractalia\SGSDSoapServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Pi2\Fractalia\SGSDSoapServerBundle\Soap\SGSDAPI;

class DefaultController extends Controller {

    public function wsdlAction(Request $request) {
        
        $xsd = $this->container->get('router')->generate('xsd', array(), true);
        $location = $this->container->get('router')->generate('soap_service', array(), true);

        $response = $this->render('Pi2FracSGSDSoapServerBundle:Default:wsdl.xml.twig', array(
            'xsd' => $xsd,
            'location' => $location
        ));

        $headers = array('Content-Type' => 'text/XML');

        $response->headers->add($headers);


        return $response;
    }

    public function xsdAction() {


        $response = $this->render('Pi2FracSGSDSoapServerBundle:Default:xsd.xml.twig');

        $headers = array('Content-Type' => 'text/XML');

        $response->headers->add($headers);

        return $response;
    }

    public function soapServiceAction(Request $request) {
        
        $logger = $this->get('logger');
                
        $logger->notice('SGSD-WS: Recibida petición al web service', array('from' => $request->getClientIp()));
                
        ini_set("soap.wsdl_cache_enabled", $this->container->getParameter('sgsd_soap_cache'));

        $wsdl = $this->container->get('router')->generate('wsdl', array(), true);

        $soapServer = new \SoapServer($wsdl, array('soap_version' => SOAP_1_1));
        $api = $this->get('soap_api');
        $soapServer->setObject($api);

        try {
            //Capturo el buffer de salida y lo modifico en caso de error para
            // adaptar la salida a las especificaciones del proyecto
            ob_start(array($this, 'cambiarRespuesta'));
            $soapServer->handle();
            ob_get_flush();
            $logger->notice('SGSD-WS: La petición al web service ha sido resuelta satisfactoriamente', array('from' => $request->getClientIp()));
            if($api->existObjectsInArray()){
                $entity = $api->getObjectFromArray();
                $logger->notice('Capturada y rescatada la entidad', array('Numero de Caso' => $entity->getNumeroCaso()));
            }
            exit;
        } catch (\Exception $e) {
            $messageException = $e->getMessage();
            $soapServer->fault("1", $messageException);
            $logger->error('SGSD-WS: Una Excepción a sido capturada', array('mensaje' => $messageException));
            exit;
        }
    }

    public function cambiarRespuesta($buffer) {
                
        if (strpos($buffer, '<SOAP-ENV:Fault>') === FALSE) {
            return $buffer;
        }

        $errorCode = $this->getXmlValueByTag($buffer, 'faultcode');
        $errorMessage = $this->getXmlValueByTag($buffer, 'faultstring');

        $buffer = <<< EOT
<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope" xmlns:ns1="http://service.gestionincidencias.telefonica.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <env:Body>
      <ns1:notificarEventoResponse>
         <ns1:returnCode>$errorCode</ns1:returnCode>
         <ns1:message>$errorMessage</ns1:message>
         <ns1:IDCasoExterno xsi:nil="true"/>
      </ns1:notificarEventoResponse>
   </env:Body>
</env:Envelope>
EOT;
        $logger = $this->get('logger');
        $logger->error('SGSD-WS: La petición al web service ha devuelto un error', array('message' => $errorMessage));        

        return $buffer;
    }    

    protected function getXmlValueByTag($inXmlset,$needle){
        $resource    =    xml_parser_create();//Create an XML parser
        xml_parse_into_struct($resource, $inXmlset, $outArray);// Parse XML data into an array structure
        xml_parser_free($resource);//Free an XML parser
       
        for($i=0;$i<count($outArray);$i++){
            if($outArray[$i]['tag']==strtoupper($needle)){
                $tagValue    =    $outArray[$i]['value'];
            }
        }
        return $tagValue;
    } 

}
