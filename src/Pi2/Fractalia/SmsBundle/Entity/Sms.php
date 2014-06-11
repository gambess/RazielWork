<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Pi2\Fractalia\SmsBundle\Entity\Mensaje;

/**
 * Sms
 *
 * @ORM\Table(name="Sms", indexes={@ORM\Index(name="IDX_88E47C974C54F362", columns={"mensaje_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Pi2\Fractalia\SmsBundle\Entity\SmsRepository")
 */
class Sms
{
    /**
     * @var string
     *
     * @ORM\Column(name="destinatario", type="string", length=20, nullable=true)
     */
    private $destinatario;

    /**
     * @var string
     *
     * @ORM\Column(name="remitente", type="string", length=15, nullable=true)
     */
    private $remitente;

    /**
     * @var string
     *
     * @ORM\Column(name="respuesta", type="string", length=5, nullable=false)
     */
    private $respuesta;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=20, nullable=false)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="bitacora", type="text", nullable=false)
     */
    private $bitacora;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_envio", type="datetime", nullable=true)
     */
    private $fechaEnvio;

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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Pi2\Fractalia\SmsBundle\Entity\Mensaje
     *
     * @ORM\ManyToOne(targetEntity="Pi2\Fractalia\SmsBundle\Entity\Mensaje", inversedBy="mensaje", cascade={"persist", "remove" })
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mensaje_id", referencedColumnName="id")
     * })
     */
    private $mensaje;



    /**
     * Set destinatario
     *
     * @param string $destinatario
     * @return Sms
     */
    public function setDestinatario($destinatario)
    {
        $this->destinatario = $destinatario;

        return $this;
    }

    /**
     * Get destinatario
     *
     * @return string 
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }

    /**
     * Set remitente
     *
     * @param string $remitente
     * @return Sms
     */
    public function setRemitente($remitente)
    {
        $this->remitente = $remitente;

        return $this;
    }

    /**
     * Get remitente
     *
     * @return string 
     */
    public function getRemitente()
    {
        return $this->remitente;
    }

    /**
     * Set respuesta
     *
     * @param string $respuesta
     * @return Sms
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    /**
     * Get respuesta
     *
     * @return string 
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Sms
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
     * Set bitacora
     *
     * @param string $bitacora
     * @return Sms
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
     * Set fechaEnvio
     *
     * @param \DateTime $fechaEnvio
     * @return Sms
     */
    public function setFechaEnvio($fechaEnvio)
    {
        $this->fechaEnvio = $fechaEnvio;

        return $this;
    }

    /**
     * Get fechaEnvio
     *
     * @return \DateTime 
     */
    public function getFechaEnvio()
    {
        return $this->fechaEnvio;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Sms
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
     * @return Sms
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

    /**
     * Set mensaje
     *
     * @param \Pi2\Fractalia\SmsBundle\Entity\Mensaje $mensaje
     * @return Sms
     */
    public function setMensaje(\Pi2\Fractalia\SmsBundle\Entity\Mensaje $mensaje = null)
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    /**
     * Get mensaje
     *
     * @return \Pi2\Fractalia\SmsBundle\Entity\Mensaje 
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }
}
