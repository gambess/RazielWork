<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Util;

use Pi2\Fractalia\Entity\SGSD\Incidencia;

/**
 * Description of PrepareArrayIncidencia
 * Cada Incidencia de un EVENTO que se captura en el listener
 * Se copia en un array para su gestion en el SMS
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class IncidenciaArrayEvento
{
    private $array = array();

    /*
     * Nombre de la plantilla
     */
    private $platillaName;

    /*
     * Valores Obtenidos de la Incidencia y el Listener
     */
    private $id;
    private $fecha;
    private $tecnico;
    /*
     * Coleccion de filas de la Incidencia
     */
    private $detalle = '';

    /*
     * Arrays de configuracion
     */
    private $confCliente = array();
    private $traducciones = array();

    /*
     * Valores para almacenar las configuraciones
     */
    private $tipo;
    private $cliente = '';
    private $tsol = '';

    /*
     * De momento Fake a falta de conversaciones con telefonica
     */
    private $modo;

    /*
     * Constructor Necesita el nombre de la plantilla y saber si el modo es fake fix
     */

    public function __construct($name, $tsol, $clientes, $traducciones = null, $modo = null)
    {
        $this->platillaName = $name;
        if (is_null($modo))
        {
            $this->setFooModo();
        }
        if (!is_null($modo))
        {
            $this->modo = $modo;
        }
        $this->confCliente = $clientes;
        $this->traducciones = $traducciones;
        $this->tsol = strtolower(reset($tsol));
    }

    /*
     * 
     * Setter del Array
     * @Param $incidencia Incidencia
     * @Return array array
     *      
     */

    public function setArrayIncidencia(Incidencia $incidencia)
    {
        $this->setFooModo();
        $this->setNumcaso($incidencia);
        $this->setFechaApertura($incidencia);
        $this->setCliente($incidencia);
        $this->setTipo($incidencia);
        $this->setTecnico($incidencia);
        $this->setTsol();
        $this->setDetalles($incidencia);
        return $this->array;
    }

    /*
     * Setear el modo fake en modo de envio
     */

    protected function setFooModo()
    {
        $this->modo = 'correo-foo-lorem';
        $this->array['modo'] = $this->modo;
    }

    /*
     * Settea el numero de caso con el numero de caso de la incidencia
     * @Param $incidencia Incidencia
     */

    protected function setNumcaso(Incidencia $incidencia)
    {
        if (method_exists($incidencia, 'getNumeroCaso') and null != $incidencia->getNumeroCaso())
        {
            $this->id = $incidencia->getNumeroCaso();
        }
        else
        {
            $this->id = 'missing';
        }
        $this->array['id'] = $this->id;
    }

    /*
     * Settea la fecha con la fecha de apertura de la incidencia
     * @Param $incidencia Incidencia
     */

    protected function setFechaApertura(Incidencia $incidencia)
    {
        if (method_exists($incidencia, 'getFechaApertura') and null != $incidencia->getFechaApertura())
        {
            $this->fecha = $incidencia->getFechaApertura();
        }
        else
        {
            $this->fecha = 'missing';
        }
        $this->array['fecha'] = $this->fecha;
    }

    /*
     * Settea el cliente con un filtro sobre el campo titulo de la incidencia
     * Si no se encuentra el patron en la incidencia se busca un patron el en titulo
     * Tomado por configuracion
     * @Param $incidencia Incidencia
     */

    protected function setCliente(Incidencia $incidencia)
    {
        $pattern = "/^(\[(\w+)*\]){3}/";
        $matches = array();
        $matches2 = array();
        if (method_exists($incidencia, 'getTitulo') and null != $incidencia->getTitulo())
        {

            $result = preg_match($pattern, $incidencia->getTitulo(), $matches);
            if ($result == 1 and count($matches) == 3)
            {
                $this->cliente = strtolower($matches[2]);
            }
            if ($result == 0)
            {
                foreach ($this->confCliente as $cliente)
                {
                    $pattern2 = $this->processClientesConfig($cliente);
                    $result2 = preg_match($pattern2, strtolower($incidencia->getTitulo()), $matches2);
                    if ($result2 > 0)
                    {
                        $this->cliente = strtolower($cliente);
                        break;
                    }
                    if ($result2 == 0)
                    {
                        $result3 = preg_match("/" . strtolower($cliente) . "/", strtolower($incidencia->getTitulo()), $matches3);
                        if ($result3 > 0)
                        {
                            $this->cliente = strtolower($cliente);
                            break;
                        }
                    }
                }
            }
            if ($this->cliente == '')
            {
                $this->cliente = 'missing';
            }
        }
        else
        {
            $this->cliente = 'missing';
        }
        $this->array['cliente'] = $this->cliente;
    }
    

    /*
     * Transformar las palabras del fichero de configuracion
     * en Patron regEXP, para comparar
     */

    protected function processClientesConfig($string)
    {
        if (!is_null($string))
        {
            return '^\[' . strtolower($string) . '\]^';
        }
        else
        {
            return null;
        }
    }

    /*
     * Retira los square brackets del nombre corto del cliente encontrado
     * en Patron regEXP, para comparar
     */

    protected function cleanCliente($string)
    {

        if (!is_null($string))
        {
            return trim($string, "[]");
        }
        else
        {
            return null;
        }
    }

    /*
     * Settea el tipo de caso aplicando un filtro sobre el campo prioridad de la incidencia
     * Y traduciendo el campo numerico tipo caso de la incidencia segun fichero de configuracion
     * @Param $incidencia Incidencia
     */

    protected function setTipo(Incidencia $incidencia)
    {
        if (method_exists($incidencia, 'getPrioridad') and null != $incidencia->getPrioridad())
        {
            $this->tipo = strtolower($this->traducciones[$incidencia->getTipoCaso()]) . "/" . strtolower($incidencia->getPrioridad());
        }
        else
        {
            $this->tipo = 'missing';
        }
        $this->array['tipo'] = $this->tipo;
    }

    /*
     * Settea el tecnico obteniendo del dato del campo tecnico asignado final de la incidencia
     * @Param $incidencia Incidencia
     */

    protected function setTecnico(Incidencia $incidencia)
    {
        if (method_exists($incidencia, 'getTecnicoAsignadoFinal') and null != $incidencia->getTecnicoAsignadoFinal())
        {
            $this->tecnico = strtolower($incidencia->getTecnicoAsignadoFinal());
        }
        elseif (method_exists($incidencia, 'getTecnicoAsignadoInicial') and null != $incidencia->getTecnicoAsignadoInicial())
        {
            $this->tecnico = strtolower($incidencia->getTecnicoAsignadoInicial());
        }
        else
        {
            $this->tecnico = 'ficticio';
        }
        $this->array['tecnico'] = $this->tecnico;
    }

    /*
     * Settea el tsol obteniendo del dato del fichero de configuracion
     */

    protected function setTsol()
    {
        if ($this->tsol == "")
        {
            $this->tsol = "missing";
        }
        $this->array['tsol'] = $this->tsol;
    }

    /*
     * Settear el detalle del Mensaje
     * Si el evento es resolucion se obtinen las resoluciones
     * En caso contrario se copia el campo titulo de la incidencia
     * @Param $incidencia Incidencia
     */

    protected function setDetalles(Incidencia $incidencia)
    {
        switch ($this->platillaName)
        {
            case 'RESOLUCION':
                if (method_exists($incidencia, 'getResoluciones') and null != $incidencia->getResoluciones())
                {
                    foreach ($incidencia->getResoluciones() as $resolucion)
                    {
                        $this->detalle .= strtolower($resolucion->getTexto()).' ';
                    }
                }
                break;
            default:
                $this->detalle = strtolower($incidencia->getTitulo()).' ';
                break;
        }


        if ($this->detalle == '')
        {
            $this->detalle = 'missing';
        }
        $this->array['detalle'] = rtrim($this->detalle);
    }

}
