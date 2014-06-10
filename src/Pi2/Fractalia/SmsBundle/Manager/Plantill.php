<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Manager;

/**
 * Description of Plantilla
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */

class Plantill
{
    public function getPlantillaResuelto()
    {
        return array(
            'id' => 'RESUELTO ID: ',
            'cliente' => 'CLIENTE: ',
            'tipo' => 'TIPO: ',
            'tecnico' => 'TECNICO: ',
            'tsol' => 'TSOL: ',
            'fecha' => 'FECHA: ',
            'modo' => 'MODO RECEPCION: ',
            'detalle' => 'RESOLUCIONES: ',
        );
    }
}
