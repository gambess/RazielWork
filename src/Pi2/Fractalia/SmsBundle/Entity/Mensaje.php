<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mensaje
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pi2\Fractalia\SmsBundle\Entity\MensajeRepository")
 */
class Mensaje
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var integer
     *
     * @ORM\Column(name="incidenciaId", type="integer")
     */
    private $incidenciaId;

    /**
     * @var string
     *
     * @ORM\Column(name="templateName", type="string", length=100)
     */
    private $templateName;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text")
     */
    private $texto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaConstruccion", type="datetime")
     */
    private $fechaConstruccion;

    /**
     * @var string
     *
     * @ORM\Column(name="estadoConstruccion", type="string", length=50)
     */
    private $estadoConstruccion;

    /**
     * @var string
     *
     * @ORM\Column(name="logFalloConstruccion", type="text")
     */
    private $logFalloConstruccion;


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
     * Set incidenciaId
     *
     * @param integer $incidenciaId
     * @return Mensaje
     */
    public function setIncidenciaId($incidenciaId)
    {
        $this->incidenciaId = $incidenciaId;

        return $this;
    }

    /**
     * Get incidenciaId
     *
     * @return integer 
     */
    public function getIncidenciaId()
    {
        return $this->incidenciaId;
    }

    /**
     * Set templateName
     *
     * @param string $templateName
     * @return Mensaje
     */
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;

        return $this;
    }

    /**
     * Get templateName
     *
     * @return string 
     */
    public function getTemplateName()
    {
        return $this->templateName;
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
     * Set fechaConstruccion
     *
     * @param \DateTime $fechaConstruccion
     * @return Mensaje
     */
    public function setFechaConstruccion($fechaConstruccion)
    {
        $this->fechaConstruccion = $fechaConstruccion;

        return $this;
    }

    /**
     * Get fechaConstruccion
     *
     * @return \DateTime 
     */
    public function getFechaConstruccion()
    {
        return $this->fechaConstruccion;
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
     * Set logFalloConstruccion
     *
     * @param string $logFalloConstruccion
     * @return Mensaje
     */
    public function setLogFalloConstruccion($logFalloConstruccion)
    {
        $this->logFalloConstruccion = $logFalloConstruccion;

        return $this;
    }

    /**
     * Get logFalloConstruccion
     *
     * @return string 
     */
    public function getLogFalloConstruccion()
    {
        return $this->logFalloConstruccion;
    }
}
