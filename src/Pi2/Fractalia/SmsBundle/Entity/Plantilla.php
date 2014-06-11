<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plantilla
 */
class Plantilla
{
    /**
     * @var string
     */
    private $nombreEvento;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set nombreEvento
     *
     * @param string $nombreEvento
     * @return Plantilla
     */
    public function setNombreEvento($nombreEvento)
    {
        $this->nombreEvento = $nombreEvento;

        return $this;
    }

    /**
     * Get nombreEvento
     *
     * @return string 
     */
    public function getNombreEvento()
    {
        return $this->nombreEvento;
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
