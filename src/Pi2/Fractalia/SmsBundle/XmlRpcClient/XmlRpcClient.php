<?php

/**
 * CLiente Simple XML-RPC
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */

namespace Pi2\Fractalia\SmsBundle\XmlRpcClient;

class XmlRpcClient
{
    private $_methods;
    private $_context;
    private $_url;

    /**
     * Instancia un nuevo CLiente con la URL dada Preparada solo para movistar
     * 
     * @param string $url
     */
    function __construct($url)
    {
        $this->_url = $url;
        $this->registerMethod("MensajeriaNegocios_enviarSMS");
        $this->registerMethod("MensajeriaNegocios_enviarAGrupoContacto");
        $this->registerMethod("MensajeriaNegocios_agregarGrupoContactos");
        $this->registerMethod("MensajeriaNegocios_agregarContactoaGrupoContactos");
        $this->registerMethod("MensajeriaNegocios_obtenerNumeroContactosdeGrupoContactos");
        $this->registerMethod("MensajeriaNegocios_obtenerContactosdeGrupoContactos");
    }

    /**
     * 
     * @param string $methodName
     * @param array $params
     * @return string
     */
    function __call($methodName, $params)
    {
        if (array_key_exists($methodName, $this->_methods))
        {
            $m = str_replace('_', '.', $methodName);
            $r = xmlrpc_encode_request($m, $params, array('encoding' => 'ISO-8859-15'));
            $this->_context = stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'header' => "Content-Type: text/xml\r\n" .
                    "Content-length: " . strlen($r),
                    //10 segundo maximo de timeout
                    'timeout' => 10,
            )));
            $c = $this->_context;
            stream_context_set_option($c, 'http', 'content', $r);
            $f = file_get_contents($this->_url, false, $c);
            if ($f == false)
            {
                return $f;
            }
            $resp = xmlrpc_decode($f);
            return $resp;
        }
        else
        {
            call_user_method_array($methodName, $this, $params);
        }
    }
    /**
     * Registra los metodos de la API Externa
     * 
     * @param string $method
     */
    private function registerMethod($method)
    {
        $this->_methods[$method] = true;
    }

}
