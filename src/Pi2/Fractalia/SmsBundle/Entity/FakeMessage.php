<?php

namespace Pi2\Fractalia\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FakeMessage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pi2\Fractalia\SmsBundle\Entity\FakeMessageRepository")
 */
class FakeMessage
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
     * @var string
     *
     * @ORM\Column(name="ticket", type="string", length=20 , nullable=true)
     */
    private $ticketId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="destinatario", type="string", length=20 , nullable=true)
     */
    private $destinatario;

    /**
     * @var string
     *
     * @ORM\Column(name="cliente", type="string", length=20 , nullable=true)
     */
    private $cliente;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=30 , nullable=true)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="tecnico", type="string", length=100 , nullable=true)
     */
    private $tecnico;

    /**
     * @var string
     *
     * @ORM\Column(name="tsol", type="string", length=20 , nullable=true)
     */
    private $tsol;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime" , nullable=true)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="modo", type="string", length=50 , nullable=true)
     */
    private $modo;

    /**
     * @var string
     *
     * @ORM\Column(name="resolucion", type="string", length=255 , nullable=true)
     */
    private $resolucion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaEnvio", type="datetime")
     */
    private $fechaEnvio;


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
     * Set destinatario
     *
     * @param string $destinatario
     * @return FakeMessage
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
     * Set ticketId
     *
     * @param string $ticket
     * @return FakeMessage
     */
    public function setTicketId($ticket)
    {
        $this->ticketId = $ticket;

        return $this;
    }

    /**
     * Get ticketId
     *
     * @return string 
     */
    public function getTicketId()
    {
        return $this->ticketId;
    }
    /**
     * Set cliente
     *
     * @param string $cliente
     * @return FakeMessage
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return string 
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return FakeMessage
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set tecnico
     *
     * @param string $tecnico
     * @return FakeMessage
     */
    public function setTecnico($tecnico)
    {
        $this->tecnico = $tecnico;

        return $this;
    }

    /**
     * Get tecnico
     *
     * @return string 
     */
    public function getTecnico()
    {
        return $this->tecnico;
    }

    /**
     * Set tsol
     *
     * @param string $tsol
     * @return FakeMessage
     */
    public function setTsol($tsol)
    {
        $this->tsol = $tsol;

        return $this;
    }

    /**
     * Get tsol
     *
     * @return string 
     */
    public function getTsol()
    {
        return $this->tsol;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return FakeMessage
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set modo
     *
     * @param string $modo
     * @return FakeMessage
     */
    public function setModo($modo)
    {
        $this->modo = $modo;

        return $this;
    }

    /**
     * Get modo
     *
     * @return string 
     */
    public function getModo()
    {
        return $this->modo;
    }

    /**
     * Set resolucion
     *
     * @param string $resolucion
     * @return FakeMessage
     */
    public function setResolucion($resolucion)
    {
        $this->resolucion = $resolucion;

        return $this;
    }

    /**
     * Get resolucion
     *
     * @return string 
     */
    public function getResolucion()
    {
        return $this->resolucion;
    }

    /**
     * Set fechaEnvio
     *
     * @param \DateTime $fechaEnvio
     * @return FakeMessage
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
}
