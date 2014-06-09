<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plantilla
 * @ORM\Table(name="Plantilla")
 * @ORM\Entity(repositoryClass="Pi2\Fractalia\SmsBundle\Entity\PlantillaRepository")
 */
class Plantilla
{
    /**
     * @var string
     */
    private $nombre;

    /**
     * @var integer
     */
    private $numeroElementos;

    /**
     * @var string
     */
    private $texto;

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
     * @var integer
     */
    private $id;


    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Plantilla
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set numeroElementos
     *
     * @param integer $numeroElementos
     * @return Plantilla
     */
    public function setNumeroElementos($numeroElementos)
    {
        $this->numeroElementos = $numeroElementos;

        return $this;
    }

    /**
     * Get numeroElementos
     *
     * @return integer 
     */
    public function getNumeroElementos()
    {
        return $this->numeroElementos;
    }

    /**
     * Set texto
     *
     * @param string $texto
     * @return Plantilla
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
     * Set bitacora
     *
     * @param string $bitacora
     * @return Plantilla
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
     * @return Plantilla
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
     * @return Plantilla
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
