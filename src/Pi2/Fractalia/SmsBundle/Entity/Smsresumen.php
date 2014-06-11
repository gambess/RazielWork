<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Smsresumen
 */
class Smsresumen
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Pi2\Fractalia\SmsBundle\Entity\Resumen
     */
    private $resumen;


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
     * @return Smsresumen
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
