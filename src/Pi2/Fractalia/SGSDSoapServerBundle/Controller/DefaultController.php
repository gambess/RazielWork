<?php

namespace Pi2\Fractalia\SGSDSoapServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Pi2\Fractalia\SGSDSoapServerBundle\Soap\SGSDAPI;
use Pi2\Fractalia\SmsBundle\Manager\IncidenciaFilterManager;

class DefaultController extends Controller
{

    public function wsdlAction(Request $request)
    {

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

    public function xsdAction()
    {


        $response = $this->render('Pi2FracSGSDSoapServerBundle:Default:xsd.xml.twig');

        $headers = array('Content-Type' => 'text/XML');

        $response->headers->add($headers);

        return $response;
    }

    public function soapServiceAction(Request $request)
    {
        $arrayObject = array();

        $logger = $this->get('logger');

        $logger->notice('SGSD-WS: Recibida petición al web service', array('from' => $request->getClientIp()));

        ini_set("soap.wsdl_cache_enabled", $this->container->getParameter('sgsd_soap_cache'));

        $wsdl = $this->container->get('router')->generate('wsdl', array(), true);

        $soapServer = new \SoapServer($wsdl, array('soap_version' => SOAP_1_1));
        $api = $this->get('soap_api');
        
        $soapServer->setObject($api);

        $requestMessage = null;
        $contenidoPost = $request->getContent();
        
        try
        {
            //Capturo el buffer de salida y lo modifico en caso de error para
            // adaptar la salida a las especificaciones del proyecto
            ob_start(array($this, 'cambiarRespuesta'));
            try
            {
                if ($contenidoPost != "")
                {
                    $now = (new \DateTime('NOW'));
                    $requestMessage = "El fichero xml se almacenó en: ";
                    $requestMessage .= $this->saveXmlInTmp("request__" . $now->format("H-i-s_d-m-Y"), $contenidoPost);
                }
                if ($requestMessage == false and $contenidoPost != "")
                {
                    $requestMessage = "No se ha podido crear el fichero xml con los siguientes datos: " . $contenidoPost;
                }
                if ($requestMessage == null or $contenidoPost == "")
                {
                    $requestMessage = "Se envio un post vacio";
                }
                $logger->info($requestMessage);
                $parser = xml_parser_create("UTF-8");
                if (!xml_parse($parser, $contenidoPost, true))
                {
                    if ($contenidoPost == "")
                    {
                        $logger->error("Empty Post");
                        $soapServer->fault("400", "EMPTY POST");
                    }
                    else
                    {
                        $mess = xml_error_string(xml_get_error_code($parser)) . " at line: " . xml_get_current_line_number($parser) . ", in column: " . xml_get_current_column_number($parser);
                        $logger->error("ERROR: ", array(
                            'Message' => $mess,
                            'Xml' => $contenidoPost)
                        );
                        $soapServer->fault("400", $mess);
                    }
                }
                $soapServer->handle();
                ob_get_flush();

                $logger->notice('SGSD-WS: La petición al web service ha sido resuelta satisfactoriamente', array('from' => $request->getClientIp()));
        
                //Nuevo trigger de incidencias
                //Si existe el Objeto almacenado en el Array Se extrae y se envia al filter
                if ($api->existObjectsInArray())
                {
                    $filter = $this->get('incidencia.filter');
                    
                    $arrayObject = $api->getObjectFromArray();
                    
                    $estadoPrevio = key($arrayObject);
                    
                    $entity = $arrayObject[$estadoPrevio];
                    
                    if($estadoPrevio == "NUEVA"){
                        $estadoPrevio = null;
                    }
                    $filter->incidenciaFilter($entity, $estadoPrevio, $api->getPrevias());
                }
                exit;
            }
            catch (\SoapFault $fault)
            {
                $logger->error("Fault: ", array("code" => $fault->getCode(), "mensaje" => $fault->getMessage(), "trace" => $fault->getTraceAsString(), "line" => $fault->getLine()));
            }
            catch (\Exception $e)
            {
                $logger->error("Exeption: ", array("code" => $e->getCode(), "mensaje" => $e->getMessage(), "trace" => $e->getTraceAsString(), "line" => $e->getLine()));
            }
        }
        catch (\Exception $e)
        {
            $messageException = $e->getMessage();
            $soapServer->fault("1", $messageException);
            exit;
        }
    }

    public function cambiarRespuesta($buffer)
    {

        $originalBuffer = $buffer;
        if (strpos($buffer, '<SOAP-ENV:Fault>') === FALSE)
        {
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
        $logger->error('SGSD-WS: La petición al web service ha devuelto un error', array('message' => $errorMessage, 'code' => $errorCode, 'response' => $originalBuffer));

        return $buffer;
    }

    protected function getXmlValueByTag($inXmlset, $needle)
    {
        $resource = xml_parser_create(); //Create an XML parser
        xml_parse_into_struct($resource, $inXmlset, $outArray); // Parse XML data into an array structure
        xml_parser_free($resource); //Free an XML parser

        for ($i = 0; $i < count($outArray); $i++)
        {
            if ($outArray[$i]['tag'] == strtoupper($needle))
            {
                $tagValue = $outArray[$i]['value'];
            }
        }
        return $tagValue;
    }

    protected function saveXmlInTmp($fileName, $xmlString)
    {
        $xml = new \SimpleXMLElement($xmlString);

        $tmp = "/tmp/{$fileName}.xml";
        if ($xml->asXML($tmp))
        {
            return $tmp;
        }
        else
        {
            return false;
        }
    }

}
