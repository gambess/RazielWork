<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mensaje
 * @ORM\Table(name="Mensaje")
 * @ORM\Entity(repositoryClass="Pi2\Fractalia\SmsBundle\Entity\MensajeRepository")
 */
class Mensaje
{
    /**
     * @var string
     */
    private $texto;

    /**
     * @var string
     */
    private $estado;

    /**
     * @var string
     */
    private $bitacora;

    /**
     * @var \DateTime
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     */
    private $fechaActualizacion;

    /**
     * @var \DateTime
     */
    private $fechaInsercionSms;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Pi2\Fractalia\Entity\SGSD\Incidencia
     */
    private $incidencia;

    /**
     * @var \Pi2\Fractalia\SmsBundle\Entity\Plantilla
     */
    private $plantilla;


    /**
     * Set texto
     *
     * @param string $texto
     * @return Mensaje
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string 
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Mensaje
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set bitacora
     *
     * @param string $bitacora
     * @return Mensaje
     */
    public function setBitacora($bitacora)
    {
        $this->bitacora = $bitacora;

        return $this;
    }

    /**
     * Get bitacora
     *
     * @return string 
     */
    public function getBitacora()
    {
        return $this->bitacora;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Mensaje
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     * @return Mensaje
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime 
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * Set fechaInsercionSms
     *
     * @param \DateTime $fechaInsercionSms
     * @return Mensaje
     */
    public function setFechaInsercionSms($fechaInsercionSms)
    {
        $this->fechaInsercionSms = $fechaInsercionSms;

        return $this;
    }

    /**
     * Get fechaInsercionSms
     *
     * @return \DateTime 
     */
    public function getFechaInsercionSms()
    {
        return $this->fechaInsercionSms;
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
     * Set incidencia
     *
     * @param \Pi2\Fractalia\Entity\SGSD\Incidencia $incidencia
     * @return Mensaje
     */
    public function setIncidencia(\Pi2\Fractalia\Entity\SGSD\Incidencia $incidencia = null)
    {
        $this->incidencia = $incidencia;

        return $this;
    }

    /**
     * Get incidencia
     *
     * @return \Pi2\Fractalia\Entity\SGSD\Incidencia 
     */
    public function getIncidencia()
    {
        return $this->incidencia;
    }

    /**
     * Set plantilla
     *
     * @param \Pi2\Fractalia\SmsBundle\Entity\Plantilla $plantilla
     * @return Mensaje
     */
    public function setPlantilla(\Pi2\Fractalia\SmsBundle\Entity\Plantilla $plantilla = null)
    {
        $this->plantilla = $plantilla;

        return $this;
    }

    /**
     * Get plantilla
     *
     * @return \Pi2\Fractalia\SmsBundle\Entity\Plantilla 
     */
    public function getPlantilla()
    {
        return $this->plantilla;
    }
}
