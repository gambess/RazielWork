<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Columnaresumen
 */
class Columnaresumen
{
    /**
     * @var string
     */
    private $numeroCaso;

    /**
     * @var string
     */
    private $estado;

    /**
     * @var string
     */
    private $servicio;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Pi2\Fractalia\SmsBundle\Entity\Resumen
     */
    private $resumen;


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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set resumen
     *
     * @param \Pi2\Fractalia\SmsBundle\Entity\Resumen $resumen
     * @return Columnaresumen
     */
    public function setResumen(\Pi2\Fractalia\SmsBundle\Entity\Resumen $resumen = null)
    {
        $this->resumen = $resumen;

        return $this;
    }

    /**
     * Get resumen
     *
     * @return \Pi2\Fractalia\SmsBundle\Entity\Resumen 
     */
    public function getResumen()
    {
        return $this->resumen;
    }
}
