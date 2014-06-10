<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mensaje
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */

namespace Pi2\Fractalia\SmsBundle\Manager;

use Pi2\Fractalia\SmsBundle\Entity\Mensaje;
use Pi2\Fractalia\Entity\SGSD\Incidencia;

class MensajeManager
{
    private $incidenciaClon;
    private $elementos = array();
    private $plantillas = array();

    public function __construct($logger)
    {
        $this->logger = $logger;
    }
    
    public function copyIncidencia(Incidencia $incidencia)
    {
        $this->incidenciaClon = $incidencia;
    }
    
    public function setElementosTemplate()
    {
        $this->elementos = $elementos;
    }
    
    public function createMensaje($param)
    {
        
    }
    
    public function readMensaje($id)
    {
        
    }
    
    public function updateMensaje($param)
    {
        
    }
    public function deleteMensaje($param)
    {
        
    }

    public function setIncidenciaToPlantilla()
    {
        return $this->buildArraytoMatchPlantilla();
    }

    public function countElementosPlantilla()
    {
        return count($this->elementos);
    }

    protected function buildArraytoMatchPlantilla()
    {
        $temp = array();
        $temp = array_flip($this->elementos);
        return array_fill_keys($temp, 0);
    }
    
    public function fillArrayWithIncidencia(){
        $incidenciaArray = $this->setIncidenciaToPlantilla();
        $temp = array();
        
        foreach ($incidenciaArray as $index => $value)
        {
            switch ($index){
                case "id":
                    $temp[$index] = method_exists($this->incidenciaClon, 'getNumeroCaso') ? $this->incidenciaClon->getNumeroCaso(): $value;
                    break;
                case "cliente":
                    $temp[$index] = (method_exists(get_class($this->incidenciaClon), 'getTitulo')) ? $this->incidenciaClon->getTitulo(): $value;
                    break;
                case "tipo":
                    $temp[$index] = (method_exists(get_class($this->incidenciaClon), 'getPrioridad') )? $this->incidenciaClon->getPrioridad(): $value;
                    break;
                case "tecnico":
                    $temp[$index] = (method_exists(get_class($this->incidenciaClon), 'getTecnicoAsignadoFinal')) ? $this->incidenciaClon->getTecnicoAsignadoFinal(): $value;
                    break;
                case "tsol":
                    $temp[$index] = (method_exists(get_class($this->incidenciaClon), 'getTecnicoAsignadoInicial')) ? $this->incidenciaClon->getTecnicoAsignadoInicial(): $value;
                    break;
                case "fecha":
                    $temp[$index] = (method_exists(get_class($this->incidenciaClon), 'getFechaApertura')) ? $this->incidenciaClon->getFechaApertura(): $value;
                    break;
                case "modo":
                    $temp[$index] = (method_exists(get_class($this->incidenciaClon), 'getTitulo')) ? $this->incidenciaClon->getTitulo(): $value;
                    break;
                case "detalle":
                    $temp[$index] = (method_exists(get_class($this->incidenciaClon), 'getResoluciones')) ? : $value;
                    break;
            }
        }
                print_r($temp);die;

        return $temp;
        
    }
    
    public function setPlantillaFromPlantillas($nombrePlantilla)
    {
        $temp = array();
        if(array_key_exists($nombrePlantilla, $this->plantillas) or (  in_array($nombrePlantilla, $this->plantillas))){
            
        }
    }
    
    public function setPlantillas($plantillas){
        $this->elementos = $plantillas;
    }
    
    public function getPlantilla($templateName){
        return $this->plantillas[$templateNames];
        
    }
    
    public function buildIncidenciaArrayforPlantilla($templateName){
        
        return $tmp;
    }

    public function bindIncidenciaToPlantillaArray()
    {
        foreach ($this->elementos as $key => $val)
        {
            switch ($key){
                case 'id':
                    
                case 'cliente':
                case 'tipo':
                case 'tecnico':
                case 'tsol':
                case 'fecha':
                case 'modo':
                case 'detalle':
            }
        }
    }

}
