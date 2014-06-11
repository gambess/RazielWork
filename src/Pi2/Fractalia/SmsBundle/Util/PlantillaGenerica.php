<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyArrayObject
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */

namespace Pi2\Fractalia\SmsBundle\Util;

class PlantillaGenerica
{
    private $id;
    private $cliente;
    private $tipo;
    private $tecnico;
    private $tsol;
    private $fecha;
    private $modo;
    private $detalle;

    public function __construct($arrayBase = null)
    {
        if (is_array($arrayBase) and ( count($arrayBase) > 0 and ( count($arrayBase) < 9)))
        {
            foreach ($arrayBase as $name => $value)
            {
                $this->__set($name, $value);
            }
        }else
    return $this;
        ;
    }

    private function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCliente()
    {
        return $this->cliente;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getTecnico()
    {
        return $this->tecnico;
    }

    public function getTsol()
    {
        return $this->tsol;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getModo()
    {
        return $this->modo;
    }

    public function getDetalle()
    {
        return $this->detalle;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setCliente($cliente)
    {
        $this->cliente = $cliente;
        return $this;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function setTecnico($tecnico)
    {
        $this->tecnico = $tecnico;
        return $this;
    }

    public function setTsol($tsol)
    {
        $this->tsol = $tsol;
        return $this;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
        return $this;
    }

    public function setModo($modo)
    {
        $this->modo = $modo;
        return $this;
    }

    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;
        return $this;
    }

}
