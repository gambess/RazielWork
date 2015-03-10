<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sms
 *
 * @ORM\Table(name="Sms", indexes={@ORM\Index(name="fk_Sms_Mensaje1_idx", columns={"mensaje_id"}), @ORM\Index(name="destinatario", columns={"destinatario"}), @ORM\Index(name="estado_envio", columns={"estado_envio"}), @ORM\Index(name="fecha_creacion", columns={"fecha_creacion"})})
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
     * @ORM\Column(name="respuesta_api", type="string", length=5, nullable=true)
     */
    private $respuestaApi;

    /**
     * @var string
     *
     * @ORM\Column(name="estado_envio", type="string", length=20, nullable=true)
     */
    private $estadoEnvio;

    /**
     * @var string
     *
     * @ORM\Column(name="log", type="text", nullable=true)
     */
    private $log;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="notifica_fallo", type="boolean", nullable=true)
     */
    private $notificaFallo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_envio", type="datetime", nullable=true)
     */
    private $fechaEnvio;

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
     * @ORM\ManyToOne(targetEntity="Pi2\Fractalia\SmsBundle\Entity\Mensaje")
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
     * Set respuestaApi
     *
     * @param string $respuestaApi
     * @return Sms
     */
    public function setRespuestaApi($respuestaApi)
    {
        $this->respuestaApi = $respuestaApi;

        return $this;
    }

    /**
     * Get respuestaApi
     *
     * @return string 
     */
    public function getRespuestaApi()
    {
        return $this->respuestaApi;
    }

    /**
     * Set estadoEnvio
     *
     * @param string $estadoEnvio
     * @return Sms
     */
    public function setEstadoEnvio($estadoEnvio)
    {
        $this->estadoEnvio = $estadoEnvio;

        return $this;
    }

    /**
     * Get estadoEnvio
     *
     * @return string 
     */
    public function getEstadoEnvio()
    {
        return $this->estadoEnvio;
    }

    /**
     * Set log
     *
     * @param string $log
     * @return Sms
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
    
    /**
     * Set notificaFallo
     *
     * @param boolean $notificaFallo
     * @return Sms
     */
    public function setNotificaFallo($notificaFallo)
    {
        $this->notificaFallo = $notificaFallo;

        return $this;
    }

    /**
     * Get notificaFallo
     *
     * @return boolean 
     */
    public function getNotificaFallo()
    {
        return $this->notificaFallo;
    }
}
