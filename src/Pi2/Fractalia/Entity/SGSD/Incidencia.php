<?php

namespace Pi2\Fractalia\Entity\SGSD;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * Incidencia
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\EntityListeners({"Pi2\Fractalia\Listener\IncidenciaListener"})
 * @ORM\Entity(repositoryClass="Pi2\Fractalia\Entity\SGSD\IncidenciaRepository")
 */
class Incidencia {

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
     * @ORM\Column(name="NumeroCaso", type="string", length=255, nullable=true)
     */
    private $numeroCaso;

    /**
     * @var string
     *
     * @ORM\Column(name="IncidenciaAjena", type="string", length=255, nullable=true)
     */
    private $incidenciaAjena;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaActualizacion", type="datetime", nullable=true)
     */
    private $fechaActualizacion;

    /**
     * @var string
     *
     * @ORM\Column(name="TipoAccion", type="string", length=255, nullable=true)
     */
    private $tipoAccion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="VisibleUsuario", type="boolean", nullable=true)
     */
    private $visibleUsuario;

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
    private $tipoCaso;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaApertura", type="datetime", nullable=true)
     */
    private $fechaApertura;

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
    private $grupoOrigen;

    /**
     * @var string
     *
     * @ORM\Column(name="GrupoDestino", type="string", length=255, nullable=true)
     */
    private $grupoDestino;

    /**
     * @var string
     *
     * @ORM\Column(name="TecnicoAsignadoInicial", type="string", length=255, nullable=true)
     */
    private $tecnicoAsignadoInicial;

    /**
     * @var string
     *
     * @ORM\Column(name="TecnicoAsignadoFinal", type="string", length=255, nullable=true)
     */
    private $tecnicoAsignadoFinal;

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
    private $cI;

    /**
     * @var string
     *
     * @ORM\Column(name="CIEtiqueta", type="string", length=255, nullable=true)
     */
    private $ciEtiqueta;

    /**
     * @var string
     *
     * @ORM\Column(name="CITipo", type="string", length=255, nullable=true)
     */
    private $cITipo;

    /**
     * @var string
     *
     * @ORM\Column(name="CINumeroSerie", type="string", length=255, nullable=true)
     */
    private $cINumeroSerie;

    /**
     * @var string
     *
     * @ORM\Column(name="CIFabricante", type="string", length=255, nullable=true)
     */
    private $cIFabricante;

    /**
     * @var string
     *
     * @ORM\Column(name="CIModelo", type="string", length=255, nullable=true)
     */
    private $cIModelo;

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
    private $nombreUbicacion;

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
    private $codigoPostal;

    /**
     * @var string
     *
     * @ORM\Column(name="UsuarioAfectado", type="string", length=255, nullable=true)
     */
    private $usuarioAfectado;

    /**
     * @var string
     *
     * @ORM\Column(name="TelefonoUsuarioAfectado", type="string", length=255, nullable=true)
     */
    private $telefonoUsuarioAfectado;

    /**
     * @var string
     *
     * @ORM\Column(name="EmailUsuarioAfectado", type="string", length=255, nullable=true)
     */
    private $emailUsuarioAfectado;

    /**
     * @var string
     *
     * @ORM\Column(name="InformadoPor", type="string", length=255, nullable=true)
     */
    private $informadoPor;

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
    private $servicioAfectado;

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
    private $organizacionInterna;

    /**
     * @var string
     *
     * @ORM\Column(name="CodigoResolucion", type="string", length=255, nullable=true)
     */
    private $codigoResolucion;

    /**
     * @var string
     *
     * @ORM\Column(name="FechaResolucion", type="datetime", nullable=true)
     */
    private $fechaResolucion;

    /**
     * @var string
     *
     * @ORM\Column(name="CasoRelacionado", type="string", length=255, nullable=true)
     */
    private $casoRelacionado;

    /**
     * @var string
     *
     * @ORM\Column(name="NumAdjuntos", type="string", length=255, nullable=true)
     */
    private $numAdjuntos;

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
    private $sistemaOrigen;

    /**
     * @var string
     *
     * @ORM\Column(name="IDCasoSistemaOrigen", type="string", length=255, nullable=true)
     */
    private $iDCasoSistemaOrigen;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Pi2\Fractalia\Entity\SGSD\Accion", mappedBy="incidencia", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $acciones;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Pi2\Fractalia\Entity\SGSD\Descripcion", mappedBy="incidencia", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $descripciones;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Pi2\Fractalia\Entity\SGSD\InfoAdjunto", mappedBy="incidencia", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $infoAdjuntos;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Pi2\Fractalia\Entity\SGSD\Resolucion", mappedBy="incidencia", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $resoluciones;

    /**
     * @var string
     *
     * @ORM\Column(name="FechaInsercion", type="datetime", nullable=true)
     */
    private $fechaInsercion;

    public function __construct() {
        $this->acciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->descripciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->infoAdjuntos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->resoluciones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getNumeroCaso() {
        return $this->numeroCaso;
    }

    public function getIncidenciaAjena() {
        return $this->incidenciaAjena;
    }

    public function getFechaActualizacion() {
        return $this->fechaActualizacion;
    }

    public function getTipoAccion() {
        return $this->tipoAccion;
    }

    public function getVisibleUsuario() {
        return $this->visibleUsuario;
    }

    public function getMotivo() {
        return $this->motivo;
    }

    public function getTipoCaso() {
        return $this->tipoCaso;
    }

    public function getFechaApertura() {
        return $this->fechaApertura;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getTipificacion1() {
        return $this->tipificacion1;
    }

    public function getTipificacion2() {
        return $this->tipificacion2;
    }

    public function getTipificacion3() {
        return $this->tipificacion3;
    }

    public function getTipificacion4() {
        return $this->tipificacion4;
    }

    public function getGrupoOrigen() {
        return $this->grupoOrigen;
    }

    public function getGrupoDestino() {
        return $this->grupoDestino;
    }

    public function getTecnicoAsignadoInicial() {
        return $this->tecnicoAsignadoInicial;
    }

    public function getTecnicoAsignadoFinal() {
        return $this->tecnicoAsignadoFinal;
    }

    public function getImpacto() {
        return $this->impacto;
    }

    public function getUrgencia() {
        return $this->urgencia;
    }

    public function getPrioridad() {
        return $this->prioridad;
    }

    public function getCI() {
        return $this->cI;
    }

    public function getCiEtiqueta() {
        return $this->ciEtiqueta;
    }

    public function getCITipo() {
        return $this->cITipo;
    }

    public function getCINumeroSerie() {
        return $this->cINumeroSerie;
    }

    public function getCIFabricante() {
        return $this->cIFabricante;
    }

    public function getCIModelo() {
        return $this->cIModelo;
    }

    public function getUbicacion() {
        return $this->ubicacion;
    }

    public function getNombreUbicacion() {
        return $this->nombreUbicacion;
    }

    public function getPais() {
        return $this->pais;
    }

    public function getProvincia() {
        return $this->provincia;
    }

    public function getLocalidad() {
        return $this->localidad;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function getCodigoPostal() {
        return $this->codigoPostal;
    }

    public function getUsuarioAfectado() {
        return $this->usuarioAfectado;
    }

    public function getTelefonoUsuarioAfectado() {
        return $this->telefonoUsuarioAfectado;
    }

    public function getEmailUsuarioAfectado() {
        return $this->emailUsuarioAfectado;
    }

    public function getInformadoPor() {
        return $this->informadoPor;
    }

    public function getContrato() {
        return $this->contrato;
    }

    public function getSubactividad() {
        return $this->subactividad;
    }

    public function getServicioAfectado() {
        return $this->servicioAfectado;
    }

    public function getCliente() {
        return $this->cliente;
    }

    public function getOrganizacionInterna() {
        return $this->organizacionInterna;
    }

    public function getCodigoResolucion() {
        return $this->codigoResolucion;
    }

    public function getFechaResolucion() {
        return $this->fechaResolucion;
    }

    public function getCasoRelacionado() {
        return $this->casoRelacionado;
    }

    public function getNumAdjuntos() {
        return $this->numAdjuntos;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getSistemaOrigen() {
        return $this->sistemaOrigen;
    }

    public function getIDCasoSistemaOrigen() {
        return $this->iDCasoSistemaOrigen;
    }

    public function getAcciones() {
        return $this->acciones;
    }

    public function getDescripciones() {
        return $this->descripciones;
    }

    public function getInfoAdjuntos() {
        return $this->infoAdjuntos;
    }

    public function getResoluciones() {
        return $this->resoluciones;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNumeroCaso($numeroCaso) {
        $this->numeroCaso = $numeroCaso;
    }

    public function setIncidenciaAjena($incidenciaAjena) {
        $this->incidenciaAjena = $incidenciaAjena;
    }

    public function setFechaActualizacion(\DateTime $fechaActualizacion) {
        $this->fechaActualizacion = $fechaActualizacion;
    }

    public function setTipoAccion($tipoAccion) {
        $this->tipoAccion = $tipoAccion;
    }

    public function setVisibleUsuario($visibleUsuario) {
        $this->visibleUsuario = $visibleUsuario;
    }

    public function setMotivo($motivo) {
        $this->motivo = $motivo;
    }

    public function setTipoCaso($tipoCaso) {
        $this->tipoCaso = $tipoCaso;
    }

    public function setFechaApertura(\DateTime $fechaApertura) {
        $this->fechaApertura = $fechaApertura;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setTipificacion1($tipificacion1) {
        $this->tipificacion1 = $tipificacion1;
    }

    public function setTipificacion2($tipificacion2) {
        $this->tipificacion2 = $tipificacion2;
    }

    public function setTipificacion3($tipificacion3) {
        $this->tipificacion3 = $tipificacion3;
    }

    public function setTipificacion4($tipificacion4) {
        $this->tipificacion4 = $tipificacion4;
    }

    public function setGrupoOrigen($grupoOrigen) {
        $this->grupoOrigen = $grupoOrigen;
    }

    public function setGrupoDestino($grupoDestino) {
        $this->grupoDestino = $grupoDestino;
    }

    public function setTecnicoAsignadoInicial($tecnicoAsignadoInicial) {
        $this->tecnicoAsignadoInicial = $tecnicoAsignadoInicial;
    }

    public function setTecnicoAsignadoFinal($tecnicoAsignadoFinal) {
        $this->tecnicoAsignadoFinal = $tecnicoAsignadoFinal;
    }

    public function setImpacto($impacto) {
        $this->impacto = $impacto;
    }

    public function setUrgencia($urgencia) {
        $this->urgencia = $urgencia;
    }

    public function setPrioridad($prioridad) {
        $this->prioridad = $prioridad;
    }

    public function setCI($cI) {
        $this->cI = $cI;
    }

    public function setCiEtiqueta($ciEtiqueta) {
        $this->ciEtiqueta = $ciEtiqueta;
    }

    public function setCITipo($cITipo) {
        $this->cITipo = $cITipo;
    }

    public function setCINumeroSerie($cINumeroSerie) {
        $this->cINumeroSerie = $cINumeroSerie;
    }

    public function setCIFabricante($cIFabricante) {
        $this->cIFabricante = $cIFabricante;
    }

    public function setCIModelo($cIModelo) {
        $this->cIModelo = $cIModelo;
    }

    public function setUbicacion($ubicacion) {
        $this->ubicacion = $ubicacion;
    }

    public function setNombreUbicacion($nombreUbicacion) {
        $this->nombreUbicacion = $nombreUbicacion;
    }

    public function setPais($pais) {
        $this->pais = $pais;
    }

    public function setProvincia($provincia) {
        $this->provincia = $provincia;
    }

    public function setLocalidad($localidad) {
        $this->localidad = $localidad;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function setCodigoPostal($codigoPostal) {
        $this->codigoPostal = $codigoPostal;
    }

    public function setUsuarioAfectado($usuarioAfectado) {
        $this->usuarioAfectado = $usuarioAfectado;
    }

    public function setTelefonoUsuarioAfectado($telefonoUsuarioAfectado) {
        $this->telefonoUsuarioAfectado = $telefonoUsuarioAfectado;
    }

    public function setEmailUsuarioAfectado($emailUsuarioAfectado) {
        $this->emailUsuarioAfectado = $emailUsuarioAfectado;
    }

    public function setInformadoPor($informadoPor) {
        $this->informadoPor = $informadoPor;
    }

    public function setContrato($contrato) {
        $this->contrato = $contrato;
    }

    public function setSubactividad($subactividad) {
        $this->subactividad = $subactividad;
    }

    public function setServicioAfectado($servicioAfectado) {
        $this->servicioAfectado = $servicioAfectado;
    }

    public function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    public function setOrganizacionInterna($organizacionInterna) {
        $this->organizacionInterna = $organizacionInterna;
    }

    public function setCodigoResolucion($codigoResolucion) {
        $this->codigoResolucion = $codigoResolucion;
    }

    public function setFechaResolucion($fechaResolucion) {
        $this->fechaResolucion = $fechaResolucion;
    }

    public function setCasoRelacionado($casoRelacionado) {
        $this->casoRelacionado = $casoRelacionado;
    }

    public function setNumAdjuntos($numAdjuntos) {
        $this->numAdjuntos = $numAdjuntos;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setSistemaOrigen($sistemaOrigen) {
        $this->sistemaOrigen = $sistemaOrigen;
    }

    public function setIDCasoSistemaOrigen($iDCasoSistemaOrigen) {
        $this->iDCasoSistemaOrigen = $iDCasoSistemaOrigen;
    }

    public function addAcciones(Collection $acciones) {

        foreach ($acciones as $accion) {
            $accion->setIncidencia($this);
            $this->acciones->add($accion);
        }
    }

    public function removeAcciones(Collection $acciones) {
        foreach ($acciones as $accion) {
            $accion->setIncidencia(null);
            $this->acciones->removeElement($accion);
        }
    }

    public function addDescripciones(Collection $descripciones) {

        foreach ($descripciones as $descripcion) {
            $descripcion->setIncidencia($this);
            $this->descripciones->add($descripcion);
        }
    }

    public function removeDescripciones(Collection $descripciones) {
        foreach ($descripciones as $descripcion) {
            $descripcion->setIncidencia(null);
            $this->descripciones->removeElement($descripcion);
        }
    }

    public function addInfoAdjuntos(Collection $infoAdjuntos) {
        foreach ($infoAdjuntos as $infoadjunto) {
            $infoadjunto->setIncidencia($this);
            $this->infoAdjuntos->add($infoadjunto);
        }
    }

    public function removeInfoAdjuntos(Collection $infoAdjuntos) {
        foreach ($infoAdjuntos as $infoadjunto) {
            $infoadjunto->setIncidencia(null);
            $this->infoAdjuntos->removeElement($infoadjunto);
        }
    }

    public function addResoluciones(Collection $resoluciones) {
        foreach ($resoluciones as $resolucion) {
            $resolucion->setIncidencia($this);
            $this->resoluciones->add($resolucion);
        }
    }

    public function removeResoluciones(Collection $resoluciones) {
        foreach ($resoluciones as $resolucion) {
            $resolucion->setIncidencia(null);
            $this->resoluciones->removeElement($resolucion);
        }
    }

    public function getFechaInsercion() {
        return $this->fechaInsercion;
    }

    public function setFechaInsercion($fechaInsercion) {
        $this->fechaInsercion = $fechaInsercion;
    }
}
