<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Util;

use Pi2\Fractalia\Entity\SGSD\Incidencia;

/**
 * Description of PrepareArrayIncidencia
 * Cada Incidencia de un EVENTO que se captura en el listener
 * Se copia en este array para su gestion en el SMS
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class IncidenciaArrayEvento
{
    private $array = array();
    private $platillaName;

    /*
     * Valores Obtenidos de la Incidencia y el Listener
     */
    private $id;
    private $fecha;
    private $tecnico;
    /*
     * Coleccion de filas de la Incidencia
     */
    private $detalle='';

    /*
     * Arrays de configuracion
     */
    private $confCliente = array();
    private $traducciones = array();

    /*
     * Valores para almacenar las configuraciones
     */
    private $tipo;
    private $cliente;
    private $tsol = "";

    /*
     * De momento Fake a falta de conversaciones con telefonica
     */
    private $modo;

    /*
     * Constructor Necesita el nombre de la plantilla y saber si el modo es fake fix
     */

    public function __construct($name, $tsol, $clientes, $traducciones = null, $modo = null)
    {
        $this->platillaName = $name;
        if (is_null($modo))
        {
            $this->setFooModo();
        }
        $this->confCliente = $clientes;
        $this->traducciones = $traducciones;
        $this->tsol = strtolower(reset($tsol));
    }

    public function setArrayIncidencia(Incidencia $incidencia)
    {
        $this->setFooModo();
        $this->setNumcaso($incidencia);
        $this->setFechaApertura($incidencia);
        $this->setCliente($incidencia);
        $this->setTipo($incidencia);
        $this->setTecnico($incidencia);
        $this->setTsol();
        $this->setResoluciones($incidencia);
        return $this->array;
    }

    protected function setFooModo()
    {
        $this->modo = 'Correo LoremIpsum';
        $this->array['modo'] = $this->modo;
    }

    protected function setNumcaso(Incidencia $incidencia)
    {
        if (method_exists($incidencia, 'getNumeroCaso') and null != $incidencia->getNumeroCaso())
        {
            $this->id = $incidencia->getNumeroCaso();
        }
        else
        {
            $this->id = 'missing';
        }
        $this->array['id'] = $this->id;
    }

    protected function setFechaApertura(Incidencia $incidencia)
    {
//        $format = 'd/m/y H:m:s';
        if (method_exists($incidencia, 'getFechaApertura') and null != $incidencia->getFechaApertura())
        {
            $this->fecha = $incidencia->getFechaApertura();
        }
        else
        {
            $this->fecha = 'missing';
        }
        $this->array['fecha'] = $this->fecha;
    }

    protected function setCliente(Incidencia $incidencia)
    {
        $pattern = "^\[(.*?)\]^";
        $matches = array();
        if (method_exists($incidencia, 'getTitulo') and null != $incidencia->getTitulo())
        {
            if (preg_match_all($pattern, $incidencia->getTitulo(), $matches, PREG_SET_ORDER) >= 3)
            {
                $this->cliente = strtolower($matches[2][1]);
            }
            else
            {
                foreach ($this->confCliente as $cliente)
                {
                    if (strpos(strtolower($incidencia->getTitulo()), strtolower($cliente)) > 0)
                    {
                        $this->cliente = strtolower($cliente);
                    }
                }
            }
            $this->cliente = 'missing';
        }
        else
        {
            $this->cliente = 'missing';
        }
        $this->array['cliente'] = $this->cliente;
    }

    protected function setTipo(Incidencia $incidencia)
    {
        if (method_exists($incidencia, 'getPrioridad') and null != $incidencia->getPrioridad())
        {
            $this->tipo = strtolower($this->traducciones[$incidencia->getTipoCaso()]) . "/" . strtolower($incidencia->getPrioridad());
        }
        else
        {
            $this->tipo = 'missing';
        }
        $this->array['tipo'] = $this->tipo;
    }

    protected function setTecnico(Incidencia $incidencia)
    {
        if (method_exists($incidencia, 'getTecnicoAsignadoFinal') and null != $incidencia->getTecnicoAsignadoFinal())
        {
            $this->tecnico = strtolower($incidencia->getTecnicoAsignadoFinal());
        }
        else
        {
            $this->tecnico = 'missing';
        }
        $this->array['tecnico'] = $this->tecnico;
    }

    protected function setTsol()
    {
        if ($this->tsol == "")
        {
            $this->tsol = "missing";
        }
        $this->array['tsol'] = $this->tsol;
    }
    
    protected function setResoluciones(Incidencia $incidencia)
    {
        if (method_exists($incidencia, 'getResoluciones') and null != $incidencia->getResoluciones())
        {
            foreach ($incidencia->getResoluciones() as $resolucion)
            {
                $this->detalle .= $resolucion->getTexto();
            }
        }
        if ($this->detalle == '')
        {
            $this->detalle = 'missing';
        }
        $this->array['detalle'] = $this->detalle;
    }
    

}
