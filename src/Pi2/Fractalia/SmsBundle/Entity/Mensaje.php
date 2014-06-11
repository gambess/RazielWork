<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mensaje
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
    private $log;

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
    private $fechaAdjuntadoSms;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Pi2\Fractalia\SmsBundle\Entity\Plantilla
     */
    private $plantilla;

    /**
     * @var \Pi2\Fractalia\Entity\SGSD\Incidencia
     */
    private $incidencia;


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
     * Set log
     *
     * @param string $log
     * @return Mensaje
     */
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return string 
     */
    public function getLog()
    {
        return $this->log;
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
     * Set fechaAdjuntadoSms
     *
     * @param \DateTime $fechaAdjuntadoSms
     * @return Mensaje
     */
    public function setFechaAdjuntadoSms($fechaAdjuntadoSms)
    {
        $this->fechaAdjuntadoSms = $fechaAdjuntadoSms;

        return $this;
    }

    /**
     * Get fechaAdjuntadoSms
     *
     * @return \DateTime 
     */
    public function getFechaAdjuntadoSms()
    {
        return $this->fechaAdjuntadoSms;
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
}
