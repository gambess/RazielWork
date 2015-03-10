<?php

/**
 * CLiente Simple XML-RPC
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */

namespace Pi2\Fractalia\SmsBundle\TcpClient;

class TcpClient
{
    private $socket;

    /**
     * Instancia un nuevo CLiente Sobre sockets
     * 
     */
    function __construct()
    {
        // se crea el socket
        // SOL_TCP el tipo de protocolo que se utilizara para la transmision (SOL_TCP - SOL_UDP)
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        // La comparacion para verificar si el socket se creo correctamente
        // debe ser con triple =
        if ($this->socket === FALSE)
        {
            //Creacion del socket fallida
            return false;
        }
        else
        {
            return true;
        }
    }
    /**
     * Conecta via socket con el servidor
     * 
     * @param string $ip ip del servidor
     * @param string $port puerto del servidor tcp
     * @return boolean
     */
    public function connect($ip, $port)
    {
        if ($this->socket)
        {
            $resultado = socket_connect($this->socket, $ip, $port);
            if ($resultado === FALSE)
            {
                return false;
            }
            else
            {
                return true;
            }
        }

    }
    /**
     * Escribe el mensaje en el socket
     * 
     * @param string $message
     * @return string
     */
    public function write($message)
    {
        // ahora escribimos en el socket para que el servidor
        // lea lo que nosotros le enviamos
        socket_write($this->socket, $message, strlen($message));
        // leemos el resultado que el socket nos envio
        $respuesta = $this->read(1024);
        // imprimimos el resultado que leimos
        return $respuesta;
    }

    /**
     * Leer desde el socket
     * 
     * @param int $size tamaño del buffer
     * @return string
     */
    public function read($size)
    {
        $respuesta = socket_read($this->socket, $size);
        return $respuesta;
    }
    
    /**
     * Cerrar la conexion establecida
     */
    public function close()
    {
        socket_close($this->socket);
    }

}
