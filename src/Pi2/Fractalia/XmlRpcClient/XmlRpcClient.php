<?php

/**
 * CLiente Simple XML-RPC
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */

namespace Pi2\Fractalia\XmlRpcClient;

class XmlRpcClient
{
    private $_methods;
    private $_context;
    private $_url;


    function __construct($url)
    {
        $this->_url = $url;
        $this->registerMethod("MensajeriaNegocios_enviarSMS");
        $this->registerMethod("MensajeriaNegocios_enviarAGrupoContacto");
        $this->registerMethod("MensajeriaNegocios_agregarGrupoContactos");
        $this->registerMethod("MensajeriaNegocios_agregarContactoaGrupoContactos");
        $this->registerMethod("MensajeriaNegocios_obtenerNumeroContactosdeGrupoContactos");
    }

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
                                "Content-length: ".strlen($r),
                )));
            $c = $this->_context;
            stream_context_set_option($c, 'http', 'content', $r);
            $f = file_get_contents($this->_url, false, $c);
            $resp = xmlrpc_decode($f);
            return $resp;
        }
        else
        {
            call_user_method_array($methodName, $this, $params);
        }
    }

    private function registerMethod($method)
    {
        $this->_methods[$method] = true;
    }

}
