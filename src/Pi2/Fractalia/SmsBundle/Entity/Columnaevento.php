<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Columnaevento
 *
 * @ORM\Table(name="ColumnaEvento", indexes={@ORM\Index(name="fk_ColumnaEvento_Incidencia1_idx", columns={"incidencia_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Pi2\Fractalia\SmsBundle\Entity\ColumnaeventoRepository")
 */
class Columnaevento
{
    /**
     * @var string
     *
     * @ORM\Column(name="numero_caso", type="string", length=20, nullable=true)
     */
    private $numeroCaso;

    /**
     * @var string
     *
     * @ORM\Column(name="cliente", type="string", length=50, nullable=true)
     */
    private $cliente;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=50, nullable=true)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="tecnico", type="string", length=100, nullable=true)
     */
    private $tecnico;

    /**
     * @var string
     *
     * @ORM\Column(name="tsol", type="string", length=10, nullable=true)
     */
    private $tsol;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="modo", type="string", length=10, nullable=true)
     */
    private $modo;

    /**
     * @var string
     *
     * @ORM\Column(name="detalle", type="text", nullable=true)
     */
    private $detalle;

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
     *   @ORM\JoinColumn(name="incidencia_id", referencedColumnName="id")
     * })
     */
    private $incidencia;



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
     * Set incidencia
     *
     * @param \Pi2\Fractalia\Entity\SGSD\Incidencia $incidencia
     * @return Columnaevento
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
