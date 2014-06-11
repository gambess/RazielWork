<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mensaje
 *
 * @ORM\Table(name="Mensaje", indexes={@ORM\Index(name="IDX_54DE249D521E1017", columns={"Incidencia_id"})})
 * @ORM\Entity
 */
class Mensaje
{
    /**
     * @var string
     *
     * @ORM\Column(name="templateName", type="string", length=20, nullable=false)
     */
    private $templatename;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text", nullable=false)
     */
    private $texto;

    /**
     * @var string
     *
     * @ORM\Column(name="estado_construccion", type="string", length=20, nullable=false)
     */
    private $estadoConstruccion;

    /**
     * @var string
     *
     * @ORM\Column(name="bitacora", type="text", nullable=false)
     */
    private $bitacora;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=false)
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_actualizacion", type="datetime", nullable=false)
     */
    private $fechaActualizacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_insercion_sms", type="datetime", nullable=true)
     */
    private $fechaInsercionSms;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Pi2\Fractalia\Entity\SGSD\Incidencia
     *
     * @ORM\ManyToOne(targetEntity="Pi2\Fractalia\Entity\SGSD\Incidencia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Incidencia_id", referencedColumnName="id")
     * })
     */
    private $incidencia;



    /**
     * Set templatename
     *
     * @param string $templatename
     * @return Mensaje
     */
    public function setTemplatename($templatename)
    {
        $this->templatename = $templatename;

        return $this;
    }

    /**
     * Get templatename
     *
     * @return string 
     */
    public function getTemplatename()
    {
        return $this->templatename;
    }

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
     * Set estadoConstruccion
     *
     * @param string $estadoConstruccion
     * @return Mensaje
     */
    public function setEstadoConstruccion($estadoConstruccion)
    {
        $this->estadoConstruccion = $estadoConstruccion;

        return $this;
    }

    /**
     * Get estadoConstruccion
     *
     * @return string 
     */
    public function getEstadoConstruccion()
    {
        return $this->estadoConstruccion;
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
}
