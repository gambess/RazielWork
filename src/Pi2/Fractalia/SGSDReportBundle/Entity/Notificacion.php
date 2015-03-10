<?php

namespace Pi2\Fractalia\SGSDReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notificacion
 *
 * @ORM\Table(name="Notificacion", indexes={@ORM\Index(name="NumeroCaso", columns={"NumeroCaso"}), @ORM\Index(name="TipoAccion", columns={"TipoAccion"}), @ORM\Index(name="TipoCaso", columns={"TipoCaso"}), @ORM\Index(name="Tipificacion1", columns={"Tipificacion1"}), @ORM\Index(name="GrupoOrigen", columns={"GrupoOrigen"}), @ORM\Index(name="GrupoDestino", columns={"GrupoDestino"}), @ORM\Index(name="TecnicoAsignadoInicial", columns={"TecnicoAsignadoInicial"}), @ORM\Index(name="FechaResolucion", columns={"FechaResolucion"}), @ORM\Index(name="Estado", columns={"Estado"}), @ORM\Index(name="FechaApertura", columns={"FechaApertura"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Pi2\Fractalia\SGSDReportBundle\Entity\NotificacionRepository")
 */
class Notificacion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="NumeroCaso", type="string", length=255, nullable=true)
     */
    private $numerocaso;

    /**
     * @var string
     *
     * @ORM\Column(name="IncidenciaAjena", type="string", length=255, nullable=true)
     */
    private $incidenciaajena;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaActualizacion", type="datetime", nullable=true)
     */
    private $fechaactualizacion;

    /**
     * @var string
     *
     * @ORM\Column(name="TipoAccion", type="string", length=255, nullable=true)
     */
    private $tipoaccion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="VisibleUsuario", type="boolean", nullable=true)
     */
    private $visibleusuario;

    /**
     * @var string
     *
     * @ORM\Column(name="Motivo", type="string", length=255, nullable=true)
     */
    private $motivo;

    /**
     * @var string
     *
     * @ORM\Column(name="TipoCaso", type="string", length=255, nullable=true)
     */
    private $tipocaso;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaApertura", type="datetime", nullable=true)
     */
    private $fechaapertura;

    /**
     * @var string
     *
     * @ORM\Column(name="Titulo", type="string", length=255, nullable=true)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="Tipificacion1", type="string", length=255, nullable=true)
     */
    private $tipificacion1;

    /**
     * @var string
     *
     * @ORM\Column(name="Tipificacion2", type="string", length=255, nullable=true)
     */
    private $tipificacion2;

    /**
     * @var string
     *
     * @ORM\Column(name="Tipificacion3", type="string", length=255, nullable=true)
     */
    private $tipificacion3;

    /**
     * @var string
     *
     * @ORM\Column(name="Tipificacion4", type="string", length=255, nullable=true)
     */
    private $tipificacion4;

    /**
     * @var string
     *
     * @ORM\Column(name="GrupoOrigen", type="string", length=255, nullable=true)
     */
    private $grupoorigen;

    /**
     * @var string
     *
     * @ORM\Column(name="GrupoDestino", type="string", length=255, nullable=true)
     */
    private $grupodestino;

    /**
     * @var string
     *
     * @ORM\Column(name="TecnicoAsignadoInicial", type="string", length=255, nullable=true)
     */
    private $tecnicoasignadoinicial;

    /**
     * @var string
     *
     * @ORM\Column(name="TecnicoAsignadoFinal", type="string", length=255, nullable=true)
     */
    private $tecnicoasignadofinal;

    /**
     * @var string
     *
     * @ORM\Column(name="Impacto", type="string", length=255, nullable=true)
     */
    private $impacto;

    /**
     * @var string
     *
     * @ORM\Column(name="Urgencia", type="string", length=255, nullable=true)
     */
    private $urgencia;

    /**
     * @var string
     *
     * @ORM\Column(name="Prioridad", type="string", length=255, nullable=true)
     */
    private $prioridad;

    /**
     * @var string
     *
     * @ORM\Column(name="CI", type="string", length=255, nullable=true)
     */
    private $ci;

    /**
     * @var string
     *
     * @ORM\Column(name="CIEtiqueta", type="string", length=255, nullable=true)
     */
    private $cietiqueta;

    /**
     * @var string
     *
     * @ORM\Column(name="CITipo", type="string", length=255, nullable=true)
     */
    private $citipo;

    /**
     * @var string
     *
     * @ORM\Column(name="CINumeroSerie", type="string", length=255, nullable=true)
     */
    private $cinumeroserie;

    /**
     * @var string
     *
     * @ORM\Column(name="CIFabricante", type="string", length=255, nullable=true)
     */
    private $cifabricante;

    /**
     * @var string
     *
     * @ORM\Column(name="CIModelo", type="string", length=255, nullable=true)
     */
    private $cimodelo;

    /**
     * @var string
     *
     * @ORM\Column(name="Ubicacion", type="string", length=255, nullable=true)
     */
    private $ubicacion;

    /**
     * @var string
     *
     * @ORM\Column(name="NombreUbicacion", type="string", length=255, nullable=true)
     */
    private $nombreubicacion;

    /**
     * @var string
     *
     * @ORM\Column(name="Pais", type="string", length=255, nullable=true)
     */
    private $pais;

    /**
     * @var string
     *
     * @ORM\Column(name="Provincia", type="string", length=255, nullable=true)
     */
    private $provincia;

    /**
     * @var string
     *
     * @ORM\Column(name="Localidad", type="string", length=255, nullable=true)
     */
    private $localidad;

    /**
     * @var string
     *
     * @ORM\Column(name="Direccion", type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="CodigoPostal", type="string", length=255, nullable=true)
     */
    private $codigopostal;

    /**
     * @var string
     *
     * @ORM\Column(name="UsuarioAfectado", type="string", length=255, nullable=true)
     */
    private $usuarioafectado;

    /**
     * @var string
     *
     * @ORM\Column(name="TelefonoUsuarioAfectado", type="string", length=255, nullable=true)
     */
    private $telefonousuarioafectado;

    /**
     * @var string
     *
     * @ORM\Column(name="EmailUsuarioAfectado", type="string", length=255, nullable=true)
     */
    private $emailusuarioafectado;

    /**
     * @var string
     *
     * @ORM\Column(name="InformadoPor", type="string", length=255, nullable=true)
     */
    private $informadopor;

    /**
     * @var string
     *
     * @ORM\Column(name="Contrato", type="string", length=255, nullable=true)
     */
    private $contrato;

    /**
     * @var string
     *
     * @ORM\Column(name="Subactividad", type="string", length=255, nullable=true)
     */
    private $subactividad;

    /**
     * @var string
     *
     * @ORM\Column(name="ServicioAfectado", type="string", length=255, nullable=true)
     */
    private $servicioafectado;

    /**
     * @var string
     *
     * @ORM\Column(name="Cliente", type="string", length=255, nullable=true)
     */
    private $cliente;

    /**
     * @var string
     *
     * @ORM\Column(name="OrganizacionInterna", type="string", length=255, nullable=true)
     */
    private $organizacioninterna;

    /**
     * @var string
     *
     * @ORM\Column(name="CodigoResolucion", type="string", length=255, nullable=true)
     */
    private $codigoresolucion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaResolucion", type="datetime", nullable=true)
     */
    private $fecharesolucion;

    /**
     * @var string
     *
     * @ORM\Column(name="CasoRelacionado", type="string", length=255, nullable=true)
     */
    private $casorelacionado;

    /**
     * @var string
     *
     * @ORM\Column(name="NumAdjuntos", type="string", length=255, nullable=true)
     */
    private $numadjuntos;

    /**
     * @var string
     *
     * @ORM\Column(name="Estado", type="string", length=255, nullable=true)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="SistemaOrigen", type="string", length=255, nullable=true)
     */
    private $sistemaorigen;

    /**
     * @var string
     *
     * @ORM\Column(name="IDCasoSistemaOrigen", type="string", length=255, nullable=true)
     */
    private $idcasosistemaorigen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaInsercion", type="datetime", nullable=true)
     */
    private $fechainsercion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notifica_vista", type="boolean", nullable=true)
     */
    private $notificaVista;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hideInMonitor", type="boolean", nullable=true)
     */
    private $hideinmonitor;



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
     * Set numerocaso
     *
     * @param string $numerocaso
     * @return Notificacion
     */
    public function setNumerocaso($numerocaso)
    {
        $this->numerocaso = $numerocaso;

        return $this;
    }

    /**
     * Get numerocaso
     *
     * @return string 
     */
    public function getNumerocaso()
    {
        return $this->numerocaso;
    }

    /**
     * Set incidenciaajena
     *
     * @param string $incidenciaajena
     * @return Notificacion
     */
    public function setIncidenciaajena($incidenciaajena)
    {
        $this->incidenciaajena = $incidenciaajena;

        return $this;
    }

    /**
     * Get incidenciaajena
     *
     * @return string 
     */
    public function getIncidenciaajena()
    {
        return $this->incidenciaajena;
    }

    /**
     * Set fechaactualizacion
     *
     * @param \DateTime $fechaactualizacion
     * @return Notificacion
     */
    public function setFechaactualizacion($fechaactualizacion)
    {
        $this->fechaactualizacion = $fechaactualizacion;

        return $this;
    }

    /**
     * Get fechaactualizacion
     *
     * @return \DateTime 
     */
    public function getFechaactualizacion()
    {
        return $this->fechaactualizacion;
    }

    /**
     * Set tipoaccion
     *
     * @param string $tipoaccion
     * @return Notificacion
     */
    public function setTipoaccion($tipoaccion)
    {
        $this->tipoaccion = $tipoaccion;

        return $this;
    }

    /**
     * Get tipoaccion
     *
     * @return string 
     */
    public function getTipoaccion()
    {
        return $this->tipoaccion;
    }

    /**
     * Set visibleusuario
     *
     * @param boolean $visibleusuario
     * @return Notificacion
     */
    public function setVisibleusuario($visibleusuario)
    {
        $this->visibleusuario = $visibleusuario;

        return $this;
    }

    /**
     * Get visibleusuario
     *
     * @return boolean 
     */
    public function getVisibleusuario()
    {
        return $this->visibleusuario;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     * @return Notificacion
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * Get motivo
     *
     * @return string 
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set tipocaso
     *
     * @param string $tipocaso
     * @return Notificacion
     */
    public function setTipocaso($tipocaso)
    {
        $this->tipocaso = $tipocaso;

        return $this;
    }

    /**
     * Get tipocaso
     *
     * @return string 
     */
    public function getTipocaso()
    {
        return $this->tipocaso;
    }

    /**
     * Set fechaapertura
     *
     * @param \DateTime $fechaapertura
     * @return Notificacion
     */
    public function setFechaapertura($fechaapertura)
    {
        $this->fechaapertura = $fechaapertura;

        return $this;
    }

    /**
     * Get fechaapertura
     *
     * @return \DateTime 
     */
    public function getFechaapertura()
    {
        return $this->fechaapertura;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     * @return Notificacion
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set tipificacion1
     *
     * @param string $tipificacion1
     * @return Notificacion
     */
    public function setTipificacion1($tipificacion1)
    {
        $this->tipificacion1 = $tipificacion1;

        return $this;
    }

    /**
     * Get tipificacion1
     *
     * @return string 
     */
    public function getTipificacion1()
    {
        return $this->tipificacion1;
    }

    /**
     * Set tipificacion2
     *
     * @param string $tipificacion2
     * @return Notificacion
     */
    public function setTipificacion2($tipificacion2)
    {
        $this->tipificacion2 = $tipificacion2;

        return $this;
    }

    /**
     * Get tipificacion2
     *
     * @return string 
     */
    public function getTipificacion2()
    {
        return $this->tipificacion2;
    }

    /**
     * Set tipificacion3
     *
     * @param string $tipificacion3
     * @return Notificacion
     */
    public function setTipificacion3($tipificacion3)
    {
        $this->tipificacion3 = $tipificacion3;

        return $this;
    }

    /**
     * Get tipificacion3
     *
     * @return string 
     */
    public function getTipificacion3()
    {
        return $this->tipificacion3;
    }

    /**
     * Set tipificacion4
     *
     * @param string $tipificacion4
     * @return Notificacion
     */
    public function setTipificacion4($tipificacion4)
    {
        $this->tipificacion4 = $tipificacion4;

        return $this;
    }

    /**
     * Get tipificacion4
     *
     * @return string 
     */
    public function getTipificacion4()
    {
        return $this->tipificacion4;
    }

    /**
     * Set grupoorigen
     *
     * @param string $grupoorigen
     * @return Notificacion
     */
    public function setGrupoorigen($grupoorigen)
    {
        $this->grupoorigen = $grupoorigen;

        return $this;
    }

    /**
     * Get grupoorigen
     *
     * @return string 
     */
    public function getGrupoorigen()
    {
        return $this->grupoorigen;
    }

    /**
     * Set grupodestino
     *
     * @param string $grupodestino
     * @return Notificacion
     */
    public function setGrupodestino($grupodestino)
    {
        $this->grupodestino = $grupodestino;

        return $this;
    }

    /**
     * Get grupodestino
     *
     * @return string 
     */
    public function getGrupodestino()
    {
        return $this->grupodestino;
    }

    /**
     * Set tecnicoasignadoinicial
     *
     * @param string $tecnicoasignadoinicial
     * @return Notificacion
     */
    public function setTecnicoasignadoinicial($tecnicoasignadoinicial)
    {
        $this->tecnicoasignadoinicial = $tecnicoasignadoinicial;

        return $this;
    }

    /**
     * Get tecnicoasignadoinicial
     *
     * @return string 
     */
    public function getTecnicoasignadoinicial()
    {
        return $this->tecnicoasignadoinicial;
    }

    /**
     * Set tecnicoasignadofinal
     *
     * @param string $tecnicoasignadofinal
     * @return Notificacion
     */
    public function setTecnicoasignadofinal($tecnicoasignadofinal)
    {
        $this->tecnicoasignadofinal = $tecnicoasignadofinal;

        return $this;
    }

    /**
     * Get tecnicoasignadofinal
     *
     * @return string 
     */
    public function getTecnicoasignadofinal()
    {
        return $this->tecnicoasignadofinal;
    }

    /**
     * Set impacto
     *
     * @param string $impacto
     * @return Notificacion
     */
    public function setImpacto($impacto)
    {
        $this->impacto = $impacto;

        return $this;
    }

    /**
     * Get impacto
     *
     * @return string 
     */
    public function getImpacto()
    {
        return $this->impacto;
    }

    /**
     * Set urgencia
     *
     * @param string $urgencia
     * @return Notificacion
     */
    public function setUrgencia($urgencia)
    {
        $this->urgencia = $urgencia;

        return $this;
    }

    /**
     * Get urgencia
     *
     * @return string 
     */
    public function getUrgencia()
    {
        return $this->urgencia;
    }

    /**
     * Set prioridad
     *
     * @param string $prioridad
     * @return Notificacion
     */
    public function setPrioridad($prioridad)
    {
        $this->prioridad = $prioridad;

        return $this;
    }

    /**
     * Get prioridad
     *
     * @return string 
     */
    public function getPrioridad()
    {
        return $this->prioridad;
    }

    /**
     * Set ci
     *
     * @param string $ci
     * @return Notificacion
     */
    public function setCi($ci)
    {
        $this->ci = $ci;

        return $this;
    }

    /**
     * Get ci
     *
     * @return string 
     */
    public function getCi()
    {
        return $this->ci;
    }

    /**
     * Set cietiqueta
     *
     * @param string $cietiqueta
     * @return Notificacion
     */
    public function setCietiqueta($cietiqueta)
    {
        $this->cietiqueta = $cietiqueta;

        return $this;
    }

    /**
     * Get cietiqueta
     *
     * @return string 
     */
    public function getCietiqueta()
    {
        return $this->cietiqueta;
    }

    /**
     * Set citipo
     *
     * @param string $citipo
     * @return Notificacion
     */
    public function setCitipo($citipo)
    {
        $this->citipo = $citipo;

        return $this;
    }

    /**
     * Get citipo
     *
     * @return string 
     */
    public function getCitipo()
    {
        return $this->citipo;
    }

    /**
     * Set cinumeroserie
     *
     * @param string $cinumeroserie
     * @return Notificacion
     */
    public function setCinumeroserie($cinumeroserie)
    {
        $this->cinumeroserie = $cinumeroserie;

        return $this;
    }

    /**
     * Get cinumeroserie
     *
     * @return string 
     */
    public function getCinumeroserie()
    {
        return $this->cinumeroserie;
    }

    /**
     * Set cifabricante
     *
     * @param string $cifabricante
     * @return Notificacion
     */
    public function setCifabricante($cifabricante)
    {
        $this->cifabricante = $cifabricante;

        return $this;
    }

    /**
     * Get cifabricante
     *
     * @return string 
     */
    public function getCifabricante()
    {
        return $this->cifabricante;
    }

    /**
     * Set cimodelo
     *
     * @param string $cimodelo
     * @return Notificacion
     */
    public function setCimodelo($cimodelo)
    {
        $this->cimodelo = $cimodelo;

        return $this;
    }

    /**
     * Get cimodelo
     *
     * @return string 
     */
    public function getCimodelo()
    {
        return $this->cimodelo;
    }

    /**
     * Set ubicacion
     *
     * @param string $ubicacion
     * @return Notificacion
     */
    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = $ubicacion;

        return $this;
    }

    /**
     * Get ubicacion
     *
     * @return string 
     */
    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    /**
     * Set nombreubicacion
     *
     * @param string $nombreubicacion
     * @return Notificacion
     */
    public function setNombreubicacion($nombreubicacion)
    {
        $this->nombreubicacion = $nombreubicacion;

        return $this;
    }

    /**
     * Get nombreubicacion
     *
     * @return string 
     */
    public function getNombreubicacion()
    {
        return $this->nombreubicacion;
    }

    /**
     * Set pais
     *
     * @param string $pais
     * @return Notificacion
     */
    public function setPais($pais)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Get pais
     *
     * @return string 
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Set provincia
     *
     * @param string $provincia
     * @return Notificacion
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return string 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set localidad
     *
     * @param string $localidad
     * @return Notificacion
     */
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad
     *
     * @return string 
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Notificacion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set codigopostal
     *
     * @param string $codigopostal
     * @return Notificacion
     */
    public function setCodigopostal($codigopostal)
    {
        $this->codigopostal = $codigopostal;

        return $this;
    }

    /**
     * Get codigopostal
     *
     * @return string 
     */
    public function getCodigopostal()
    {
        return $this->codigopostal;
    }

    /**
     * Set usuarioafectado
     *
     * @param string $usuarioafectado
     * @return Notificacion
     */
    public function setUsuarioafectado($usuarioafectado)
    {
        $this->usuarioafectado = $usuarioafectado;

        return $this;
    }

    /**
     * Get usuarioafectado
     *
     * @return string 
     */
    public function getUsuarioafectado()
    {
        return $this->usuarioafectado;
    }

    /**
     * Set telefonousuarioafectado
     *
     * @param string $telefonousuarioafectado
     * @return Notificacion
     */
    public function setTelefonousuarioafectado($telefonousuarioafectado)
    {
        $this->telefonousuarioafectado = $telefonousuarioafectado;

        return $this;
    }

    /**
     * Get telefonousuarioafectado
     *
     * @return string 
     */
    public function getTelefonousuarioafectado()
    {
        return $this->telefonousuarioafectado;
    }

    /**
     * Set emailusuarioafectado
     *
     * @param string $emailusuarioafectado
     * @return Notificacion
     */
    public function setEmailusuarioafectado($emailusuarioafectado)
    {
        $this->emailusuarioafectado = $emailusuarioafectado;

        return $this;
    }

    /**
     * Get emailusuarioafectado
     *
     * @return string 
     */
    public function getEmailusuarioafectado()
    {
        return $this->emailusuarioafectado;
    }

    /**
     * Set informadopor
     *
     * @param string $informadopor
     * @return Notificacion
     */
    public function setInformadopor($informadopor)
    {
        $this->informadopor = $informadopor;

        return $this;
    }

    /**
     * Get informadopor
     *
     * @return string 
     */
    public function getInformadopor()
    {
        return $this->informadopor;
    }

    /**
     * Set contrato
     *
     * @param string $contrato
     * @return Notificacion
     */
    public function setContrato($contrato)
    {
        $this->contrato = $contrato;

        return $this;
    }

    /**
     * Get contrato
     *
     * @return string 
     */
    public function getContrato()
    {
        return $this->contrato;
    }

    /**
     * Set subactividad
     *
     * @param string $subactividad
     * @return Notificacion
     */
    public function setSubactividad($subactividad)
    {
        $this->subactividad = $subactividad;

        return $this;
    }

    /**
     * Get subactividad
     *
     * @return string 
     */
    public function getSubactividad()
    {
        return $this->subactividad;
    }

    /**
     * Set servicioafectado
     *
     * @param string $servicioafectado
     * @return Notificacion
     */
    public function setServicioafectado($servicioafectado)
    {
        $this->servicioafectado = $servicioafectado;

        return $this;
    }

    /**
     * Get servicioafectado
     *
     * @return string 
     */
    public function getServicioafectado()
    {
        return $this->servicioafectado;
    }

    /**
     * Set cliente
     *
     * @param string $cliente
     * @return Notificacion
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
     * Set organizacioninterna
     *
     * @param string $organizacioninterna
     * @return Notificacion
     */
    public function setOrganizacioninterna($organizacioninterna)
    {
        $this->organizacioninterna = $organizacioninterna;

        return $this;
    }

    /**
     * Get organizacioninterna
     *
     * @return string 
     */
    public function getOrganizacioninterna()
    {
        return $this->organizacioninterna;
    }

    /**
     * Set codigoresolucion
     *
     * @param string $codigoresolucion
     * @return Notificacion
     */
    public function setCodigoresolucion($codigoresolucion)
    {
        $this->codigoresolucion = $codigoresolucion;

        return $this;
    }

    /**
     * Get codigoresolucion
     *
     * @return string 
     */
    public function getCodigoresolucion()
    {
        return $this->codigoresolucion;
    }

    /**
     * Set fecharesolucion
     *
     * @param \DateTime $fecharesolucion
     * @return Notificacion
     */
    public function setFecharesolucion($fecharesolucion)
    {
        $this->fecharesolucion = $fecharesolucion;

        return $this;
    }

    /**
     * Get fecharesolucion
     *
     * @return \DateTime 
     */
    public function getFecharesolucion()
    {
        return $this->fecharesolucion;
    }

    /**
     * Set casorelacionado
     *
     * @param string $casorelacionado
     * @return Notificacion
     */
    public function setCasorelacionado($casorelacionado)
    {
        $this->casorelacionado = $casorelacionado;

        return $this;
    }

    /**
     * Get casorelacionado
     *
     * @return string 
     */
    public function getCasorelacionado()
    {
        return $this->casorelacionado;
    }

    /**
     * Set numadjuntos
     *
     * @param string $numadjuntos
     * @return Notificacion
     */
    public function setNumadjuntos($numadjuntos)
    {
        $this->numadjuntos = $numadjuntos;

        return $this;
    }

    /**
     * Get numadjuntos
     *
     * @return string 
     */
    public function getNumadjuntos()
    {
        return $this->numadjuntos;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Notificacion
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
     * Set sistemaorigen
     *
     * @param string $sistemaorigen
     * @return Notificacion
     */
    public function setSistemaorigen($sistemaorigen)
    {
        $this->sistemaorigen = $sistemaorigen;

        return $this;
    }

    /**
     * Get sistemaorigen
     *
     * @return string 
     */
    public function getSistemaorigen()
    {
        return $this->sistemaorigen;
    }

    /**
     * Set idcasosistemaorigen
     *
     * @param string $idcasosistemaorigen
     * @return Notificacion
     */
    public function setIdcasosistemaorigen($idcasosistemaorigen)
    {
        $this->idcasosistemaorigen = $idcasosistemaorigen;

        return $this;
    }

    /**
     * Get idcasosistemaorigen
     *
     * @return string 
     */
    public function getIdcasosistemaorigen()
    {
        return $this->idcasosistemaorigen;
    }

    /**
     * Set fechainsercion
     *
     * @param \DateTime $fechainsercion
     * @return Notificacion
     */
    public function setFechainsercion($fechainsercion)
    {
        $this->fechainsercion = $fechainsercion;

        return $this;
    }

    /**
     * Get fechainsercion
     *
     * @return \DateTime 
     */
    public function getFechainsercion()
    {
        return $this->fechainsercion;
    }

    /**
     * Set notificaVista
     *
     * @param boolean $notificaVista
     * @return Notificacion
     */
    public function setNotificaVista($notificaVista)
    {
        $this->notificaVista = $notificaVista;

        return $this;
    }

    /**
     * Get notificaVista
     *
     * @return boolean 
     */
    public function getNotificaVista()
    {
        return $this->notificaVista;
    }

    /**
     * Set hideinmonitor
     *
     * @param boolean $hideinmonitor
     * @return Notificacion
     */
    public function setHideinmonitor($hideinmonitor)
    {
        $this->hideinmonitor = $hideinmonitor;

        return $this;
    }

    /**
     * Get hideinmonitor
     *
     * @return boolean 
     */
    public function getHideinmonitor()
    {
        return $this->hideinmonitor;
    }
}
