<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Sms;

use Pi2\Fractalia\Entity\SGSD\Incidencia;

/**
 * Description of Message
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class Message
{
    private $incidenciaClon;
    private $logger;
    private $caso;
    private $tsol;
    private $clientes;
    private $text;
    private $buildState;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function copyIncidencia(Incidencia $incidencia)
    {
        $this->incidenciaClon = $incidencia;
    }

    public function getTextMessage()
    {
        $format = 'd/m/y H:m:s';

        return "RESUELTO ID: {$this->incidenciaClon->getNumeroCaso()} "
            . "CLIENTE: {$this->getClienteMensaje()} "
            . "TIPO: {$this->getTipoCaso()} "
            . "TECNICO: " . strtolower($this->incidenciaClon->getTecnicoAsignadoFinal()) . " "
            . "TSOL: {$this->getTsol()} "
            . "FECHA: " . $this->incidenciaClon->getFechaApertura()->format($format) . " "
            . "MODO RECEPCION: correo "
            . "RESOLUCION: {$this->getResoluciones()}";
    }

    public function setTipoCaso($caso)
    {
        $this->caso = $caso;
    }

    public function getTipoCaso()
    {
        return strtolower($this->caso[$this->incidenciaClon->getTipoCaso()]) . "/" . strtolower($this->incidenciaClon->getPrioridad());
    }

    public function setTsol($tsol)
    {
        $this->tsol = $tsol;
    }

    public function getTsol()
    {
        if ($this->tsol['nombre'] == "" or ( sizeof($this->tsol['nombre']) == 0))
        {
            $this->buildState = "FAIL";
        }
        return $this->tsol['nombre'];
    }

    protected function getResoluciones()
    {
        $texto = 'No hay datos';
        $concat = '';
        if (null != $this->incidenciaClon->getResoluciones())
        {
            foreach ($this->incidenciaClon->getResoluciones() as $resolucion)
            {
                $concat .= $resolucion->getTexto();
            }
        }
        if ('' != $concat)
        {
            $texto = $concat;
        }
        return $texto;
    }

    public function setClienteMensaje($arrayClientes)
    {
        $this->clientes = $arrayClientes;
    }

    protected function getClienteMensaje()
    {
        $pattern = "^\[(.*?)\]^";
        $matches = array();
        if (preg_match_all($pattern, $this->incidenciaClon->getTitulo(), $matches, PREG_SET_ORDER) >= 3)
        {
            return strtolower($matches[2][1]);
        }
        else
        {
            foreach ($this->clientes as $cliente)
            {
                if (strpos(strtolower($this->incidenciaClon->getTitulo()), strtolower($cliente)) > 0)
                {
                    return strtolower($cliente);
                }
            }
        }
    }

    public function ValidData()
    {
        if (is_null($this->incidenciaClon->getNumeroCaso()))
        {
            $this->logger->error('No se encuentró el Numero de Caso');
            $this->buildState = "FAIL";
            throw new Exception('NUMERO_CASO_NO_EXISTE');
        }
        if (is_null($this->incidenciaClon->getTecnicoAsignadoFinal()))
        {
            $this->logger->error('No se encuentró el Tecnico Final');
            $this->buildState = "FAIL";
            throw new Exception('TECNICO_FINAL_NO_EXISTE');
        }
        if (is_null($this->incidenciaClon->getFechaApertura()))
        {
            $this->logger->error('No se encuentró la Fecha de Apertura');
            $this->buildState = "FAIL";
            throw new Exception('FECHA_APERTURA_NO_EXISTE');
        }
        if (is_null($this->incidenciaClon->getResoluciones()))
        {
            $this->logger->error('No se encuentraron Resoluciones');
            $this->buildState = "FAIL";
            throw new Exception('RESOLUCIONES_NO_EXISTE');
        }
        if (is_null($this->incidenciaClon->getTitulo()))
        {
            $this->logger->error('No se encuentró el Título');
            $this->buildState = "FAIL";
            throw new Exception('TITULO_NO_EXISTE');
        }
        if ($this->buildState == "" or is_null($this->buildState))
        {
            $this->buildState = "CORRECT";
        }
    }

}
