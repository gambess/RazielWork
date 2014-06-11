<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Smsevento
 */
class Smsevento
{
    /**
     * @var string
     */
    private $mensajeTexto;

    /**
     * @var string
     */
    private $destinatario;

    /**
     * @var string
     */
    private $remitente;

    /**
     * @var string
     */
    private $estado;

    /**
     * @var integer
     */
    private $respuestaEnvio;

    /**
     * @var string
     */
    private $log;

    /**
     * @var \DateTime
     */
    private $fechaCreacion;

    /**
     * @var string
     */
    private $fechaActualizacion;

    /**
     * @var \DateTime
     */
    private $fechaEnvio;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Pi2\Fractalia\SmsBundle\Entity\Mensaje
     */
    private $mensaje;


    /**
     * Set mensajeTexto
     *
     * @param string $mensajeTexto
     * @return Smsevento
     */
    public function setMensajeTexto($mensajeTexto)
    {
        $this->mensajeTexto = $mensajeTexto;

        return $this;
    }

    /**
     * Get mensajeTexto
     *
     * @return string 
     */
    public function getMensajeTexto()
    {
        return $this->mensajeTexto;
    }

    /**
     * Set destinatario
     *
     * @param string $destinatario
     * @return Smsevento
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
     * @return Smsevento
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
     * Set estado
     *
     * @param string $estado
     * @return Smsevento
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
     * Set respuestaEnvio
     *
     * @param integer $respuestaEnvio
     * @return Smsevento
     */
    public function setRespuestaEnvio($respuestaEnvio)
    {
        $this->respuestaEnvio = $respuestaEnvio;

        return $this;
    }

    /**
     * Get respuestaEnvio
     *
     * @return integer 
     */
    public function getRespuestaEnvio()
    {
        return $this->respuestaEnvio;
    }

    /**
     * Set log
     *
     * @param string $log
     * @return Smsevento
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Smsevento
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
     * @param string $fechaActualizacion
     * @return Smsevento
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return string 
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * Set fechaEnvio
     *
     * @param \DateTime $fechaEnvio
     * @return Smsevento
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
     * @return Smsevento
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
