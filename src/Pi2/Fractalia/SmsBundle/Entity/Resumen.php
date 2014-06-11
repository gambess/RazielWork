<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Resumen
 */
class Resumen
{
    /**
     * @var string
     */
    private $texto;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Pi2\Fractalia\Entity\SGSD\Incidencia
     */
    private $incidencia;


    /**
     * Set texto
     *
     * @param string $texto
     * @return Resumen
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
     * @return Resumen
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
