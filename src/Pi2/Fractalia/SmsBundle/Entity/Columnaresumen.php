<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Columnaresumen
 *
 * @ORM\Table(name="ColumnaResumen", indexes={@ORM\Index(name="fk_ColumnaResumen_Incidencia1_idx", columns={"Incidencia_id"})})
 * @ORM\Entity
 */
class Columnaresumen
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_caso", type="string", length=45, nullable=true)
     */
    private $numeroCaso;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=45, nullable=true)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="servicio", type="string", length=45, nullable=true)
     */
    private $servicio;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set numeroCaso
     *
     * @param string $numeroCaso
     * @return Columnaresumen
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
     * Set estado
     *
     * @param string $estado
     * @return Columnaresumen
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
     * Set servicio
     *
     * @param string $servicio
     * @return Columnaresumen
     */
    public function setServicio($servicio)
    {
        $this->servicio = $servicio;

        return $this;
    }

    /**
     * Get servicio
     *
     * @return string 
     */
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * Set incidencia
     *
     * @param \Pi2\Fractalia\Entity\SGSD\Incidencia $incidencia
     * @return Columnaresumen
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
