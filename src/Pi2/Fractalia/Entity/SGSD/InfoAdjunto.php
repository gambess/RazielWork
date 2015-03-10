<?php

namespace Pi2\Fractalia\Entity\SGSD;

use Doctrine\ORM\Mapping as ORM;

/**
 * InfoAdjunto
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Pi2\Fractalia\Entity\SGSD\InfoAdjuntoRepository")
 */
class InfoAdjunto
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
     * @ORM\Column(name="idattach", type="string", length=255, nullable=true)
     */
    private $idattach;
    
    /**
     *
     * @var integer
     * @ORM\Column(name="lenattach", type="integer", nullable=true)
     */
    private $lenattach;
    
    /**
     *
     * @var string
     * @ORM\Column(name="nameattach", type="string", length=255, nullable=true)
     */
    private $nameattach;
    
    /**
     *
     * @var string
     * @ORM\Column(name="tipoattach", type="string", length=255, nullable=true)
     */
    private $tipoattach;
    
    /**
     *
     * @var string
     * @ORM\Column(name="operadorattach", type="string", length=255, nullable=true)
     */
    private $operadorattach;
    
    /**
     *
     * @var type \DateTime
     * @ORM\Column(name="fechaattach", type="datetime", nullable=true)
     */
    private $fechaattach;
    
    /**
     *
     * @var type integer
     * @ORM\Column(name="numberOfSegments", type="integer", nullable=true)
     */
    private $numberOfSegments;
    
    /**
     *
     * @var type boolean
     * @ORM\Column(name="compressed", type="boolean", nullable=true)
     */
    private $compressed;
    
    /**
     *
     * @var type integer
     * @ORM\Column(name="lenCompressed", type="integer", nullable=true)
     */
    private $lenCompressed;

    /**
     * @ORM\ManyToOne(targetEntity="Incidencia", inversedBy="infoAdjuntos")
     **/
    private $incidencia;
    
    public function getId() {
        return $this->id;
    }

    public function getIdattach() {
        return $this->idattach;
    }

    public function getLenattach() {
        return $this->lenattach;
    }

    public function getNameattach() {
        return $this->nameattach;
    }

    public function getTipoattach() {
        return $this->tipoattach;
    }

    public function getOperadorattach() {
        return $this->operadorattach;
    }

    public function getFechaattach() {
        return $this->fechaattach;
    }

    public function getNumberOfSegments() {
        return $this->numberOfSegments;
    }

    public function getCompressed() {
        return $this->compressed;
    }

    public function getLenCompressed() {
        return $this->lenCompressed;
    }

    public function getIncidencia() {
        return $this->incidencia;
    }

    public function setIdattach($idattach) {
        $this->idattach = $idattach;
    }

    public function setLenattach($lenattach) {
        $this->lenattach = $lenattach;
    }

    public function setNameattach($nameattach) {
        $this->nameattach = $nameattach;
    }

    public function setTipoattach($tipoattach) {
        $this->tipoattach = $tipoattach;
    }

    public function setOperadorattach($operadorattach) {
        $this->operadorattach = $operadorattach;
    }

    public function setFechaattach(\DateTime $fechaattach) {
        $this->fechaattach = $fechaattach;
    }

    public function setNumberOfSegments($numberOfSegments) {
        $this->numberOfSegments = $numberOfSegments;
    }

    public function setCompressed($compressed) {
        $this->compressed = $compressed;
    }

    public function setLenCompressed($lenCompressed) {
        $this->lenCompressed = $lenCompressed;
    }

    public function setIncidencia(Incidencia $incidencia) {
        $this->incidencia = $incidencia;
    }


}
