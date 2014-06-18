<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mensaje
 *
 * @ORM\Table(name="Mensaje", indexes={@ORM\Index(name="fk_Mensaje_ColumnaEvento1_idx", columns={"columna_evento_id"})})
 * @ORM\Entity
 */
class Mensaje
{
    /**
     * @var string
     *
     * @ORM\Column(name="nombre_plantilla", type="string", length=20, nullable=true)
     */
    private $nombrePlantilla;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_mensaje", type="string", length=20, nullable=true)
     */
    private $tipoMensaje;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text", nullable=true)
     */
    private $texto;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=20, nullable=true)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="log", type="text", nullable=true)
     */
    private $log;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=true)
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_actualizacion", type="datetime", nullable=true)
     */
    private $fechaActualizacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_adjuntado_sms", type="datetime", nullable=true)
     */
    private $fechaAdjuntadoSms;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Pi2\Fractalia\SmsBundle\Entity\Columnaresumen", mappedBy="mensaje")
     */
    private $columnaResumen;

    /**
     * @var \Pi2\Fractalia\SmsBundle\Entity\Columnaevento
     *
     * @ORM\ManyToOne(targetEntity="Pi2\Fractalia\SmsBundle\Entity\Columnaevento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="columna_evento_id", referencedColumnName="id")
     * })
     */
    private $columnaEvento;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->columnaResumen = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set nombrePlantilla
     *
     * @param string $nombrePlantilla
     * @return Mensaje
     */
    public function setNombrePlantilla($nombrePlantilla)
    {
        $this->nombrePlantilla = $nombrePlantilla;

        return $this;
    }

    /**
     * Get nombrePlantilla
     *
     * @return string 
     */
    public function getNombrePlantilla()
    {
        return $this->nombrePlantilla;
    }

    /**
     * Set tipoMensaje
     *
     * @param string $tipoMensaje
     * @return Mensaje
     */
    public function setTipoMensaje($tipoMensaje)
    {
        $this->tipoMensaje = $tipoMensaje;

        return $this;
    }

    /**
     * Get tipoMensaje
     *
     * @return string 
     */
    public function getTipoMensaje()
    {
        return $this->tipoMensaje;
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
     * Add columnaResumen
     *
     * @param \Pi2\Fractalia\SmsBundle\Entity\Columnaresumen $columnaResumen
     * @return Mensaje
     */
    public function addColumnaResuman(\Pi2\Fractalia\SmsBundle\Entity\Columnaresumen $columnaResumen)
    {
        $this->columnaResumen[] = $columnaResumen;

        return $this;
    }

    /**
     * Remove columnaResumen
     *
     * @param \Pi2\Fractalia\SmsBundle\Entity\Columnaresumen $columnaResumen
     */
    public function removeColumnaResuman(\Pi2\Fractalia\SmsBundle\Entity\Columnaresumen $columnaResumen)
    {
        $this->columnaResumen->removeElement($columnaResumen);
    }

    /**
     * Get columnaResumen
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getColumnaResumen()
    {
        return $this->columnaResumen;
    }

    /**
     * Set columnaEvento
     *
     * @param \Pi2\Fractalia\SmsBundle\Entity\Columnaevento $columnaEvento
     * @return Mensaje
     */
    public function setColumnaEvento(\Pi2\Fractalia\SmsBundle\Entity\Columnaevento $columnaEvento = null)
    {
        $this->columnaEvento = $columnaEvento;

        return $this;
    }

    /**
     * Get columnaEvento
     *
     * @return \Pi2\Fractalia\SmsBundle\Entity\Columnaevento 
     */
    public function getColumnaEvento()
    {
        return $this->columnaEvento;
    }
}
