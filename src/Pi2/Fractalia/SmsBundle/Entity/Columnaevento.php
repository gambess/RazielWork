<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Columnaevento
 */
class Columnaevento
{
    /**
     * @var string
     */
    private $numeroCaso;

    /**
     * @var string
     */
    private $cliente;

    /**
     * @var string
     */
    private $tipo;

    /**
     * @var string
     */
    private $tecnico;

    /**
     * @var string
     */
    private $tsol;

    /**
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var string
     */
    private $modo;

    /**
     * @var string
     */
    private $detalle;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Pi2\Fractalia\SmsBundle\Entity\Mensaje
     */
    private $mensaje;


    /**
     * Set numeroCaso
     *
     * @param string $numeroCaso
     * @return Columnaevento
     */
    public function setNumeroCaso($numeroCaso)
    {
        $this->numeroCaso = $numeroCaso;

        return $this;
    }

    /**
     * Get numeroCaso
     *
     * @return string 
     */
    public function getNumeroCaso()
    {
        return $this->numeroCaso;
    }

    /**
     * Set cliente
     *
     * @param string $cliente
     * @return Columnaevento
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return string 
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Columnaevento
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set tecnico
     *
     * @param string $tecnico
     * @return Columnaevento
     */
    public function setTecnico($tecnico)
    {
        $this->tecnico = $tecnico;

        return $this;
    }

    /**
     * Get tecnico
     *
     * @return string 
     */
    public function getTecnico()
    {
        return $this->tecnico;
    }

    /**
     * Set tsol
     *
     * @param string $tsol
     * @return Columnaevento
     */
    public function setTsol($tsol)
    {
        $this->tsol = $tsol;

        return $this;
    }

    /**
     * Get tsol
     *
     * @return string 
     */
    public function getTsol()
    {
        return $this->tsol;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Columnaevento
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set modo
     *
     * @param string $modo
     * @return Columnaevento
     */
    public function setModo($modo)
    {
        $this->modo = $modo;

        return $this;
    }

    /**
     * Get modo
     *
     * @return string 
     */
    public function getModo()
    {
        return $this->modo;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     * @return Columnaevento
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string 
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set mensaje
     *
     * @param \Pi2\Fractalia\SmsBundle\Entity\Mensaje $mensaje
     * @return Columnaevento
     */
    public function setMensaje(\Pi2\Fractalia\SmsBundle\Entity\Mensaje $mensaje = null)
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    /**
     * Get mensaje
     *
     * @return \Pi2\Fractalia\SmsBundle\Entity\Mensaje 
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }
}
