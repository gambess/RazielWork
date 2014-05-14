<?php

namespace Pi2\Fractalia\Entity\SGSD;

use Doctrine\ORM\Mapping as ORM;

/**
 * Resolucion
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Resolucion {

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
     * @ORM\Column(name="texto", type="string", length=255)
     */
    private $texto;

    /**
     * @ORM\ManyToOne(targetEntity="Incidencia", inversedBy="resoluciones")
     * */
    private $incidencia;

    public function getId() {
        return $this->id;
    }

    public function getTexto() {
        return $this->texto;
    }

    public function getIncidencia() {
        return $this->incidencia;
    }
    

    public function setTexto($texto) {
        $this->texto = $texto;
    }

    public function setIncidencia(Incidencia $incidencia = null) {
        $this->incidencia = $incidencia;
    }


}
