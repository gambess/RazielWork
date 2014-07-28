<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Manager;

use Pi2\Fractalia\Entity\SGSD\Incidencia;

/**
 * Description of FiltrosManager
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class FiltrosManager
{
    private $filtros = array();

    public function __construct(array $eventos)
    {
        $this->cargarFiltros($eventos);
    }

    protected function cargarFiltros(array $eventos)
    {
        $arrayFiltro = array();
        foreach ($eventos as $plantillaNombre => $evento)
        {
            if ($plantillaNombre == null or is_array($evento) == null or count($evento) == 0)
            {
                break;
            }
            //load filtros
            $arrayPrioridad = array_slice($evento, 0, 1, true);
            $arrayEstado = array_slice($evento, 1, 1, true);
            $arrayGrupoOrigenIn = array_slice($evento, 2, 1, true);
            $arrayGrupoOrigenNot = array_slice($evento, 3, 1, true);
            $arrayGrupoDestinoIn = array_slice($evento, 4, 1, true);
            $arrayGrupoDestinoNot = array_slice($evento, 5, 1, true);
            $arrayFiltroTitulo = array_slice($evento, 6, 1, true);

            if (isset($arrayPrioridad['prioridad']))
            {
                $this->filtros[$plantillaNombre][key($arrayPrioridad)] = $arrayPrioridad;
            }
            if (isset($arrayEstado['estado']))
            {
                $this->filtros[$plantillaNombre][key($arrayEstado)] = $arrayEstado;
            }

            if (isset($arrayGrupoOrigenIn['grupo_origen_IN']))
            {
                $this->filtros[$plantillaNombre][key($arrayGrupoOrigenIn)] = $arrayGrupoOrigenIn;
            }

            if (isset($arrayGrupoOrigenNot['grupo_origen_NOT']))
            {
                $this->filtros[$plantillaNombre][key($arrayGrupoOrigenNot)] = $arrayGrupoOrigenNot;
            }

            if (isset($arrayGrupoDestinoIn['grupo_destino_IN']))
            {
                $this->filtros[$plantillaNombre][key($arrayGrupoDestinoIn)] = $arrayGrupoDestinoIn;
            }

            if (isset($arrayGrupoDestinoNot['grupo_destino_NOT']))
            {
                $this->filtros[$plantillaNombre][key($arrayGrupoDestinoNot)] = $arrayGrupoDestinoNot;
            }

            if (isset($arrayFiltroTitulo['filtro_titulo']))
            {
                $this->filtros[$plantillaNombre][key($arrayFiltroTitulo)] = $arrayFiltroTitulo;
            }
        }
    }

    public function pasarFiltro(Incidencia $incidencia)
    {
        $filtroEstado = '';
        $filtrosArray = $this->copiArrayTemporal();
        $filtro = array();
        $pasoTodosFiltros = array();

        foreach ($filtrosArray as $plantillaNombre => $filtrosCargados)
        {
            if ($plantillaNombre == null or is_array($filtrosCargados) == null or count($filtrosCargados) == 0)
            {
                break;
            }
            foreach ($filtrosCargados as $clave => $filtro)
            {
                if (is_array($filtro))
                {
                    if ($clave == 'prioridad')
                    {
                        if (count($filtro['prioridad']) == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED';
                        }
                        if ($this->estaEn($incidencia->getPrioridad(), $filtro['prioridad']))
                        {
                            $filtroEstado = 'PASS';
                        }
                        else
                        {
                            $filtroEstado = 'FAIL';
                        }
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }
                    if ($clave == 'estado')
                    {
                        if (count($filtro) > 0)
                        {
                            $arrayEstado = array_shift($filtro);
                            if (count($arrayEstado) == 0)
                            {
                                $filtroEstado = 'NOT_NEEDED';
                            }
                        }
                        if (count($arrayEstado) > 0)
                        {
                            $arrayTraducciones = array_shift($arrayEstado);
                        }
                        if (($this->estaEn($incidencia->getEstado(), $arrayTraducciones)) == true)
                        {
                            $filtroEstado = 'PASS';
                        }
                        else
                            $filtroEstado = 'FAIL';
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }
                    if ($clave == 'grupo_origen_IN')
                    {
                        if (count($filtro['grupo_origen_IN']) == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED';
                        }
                        else
                        {
                            if ($this->filtrarByGrupo($incidencia->getGrupoOrigen(), $filtro[$clave], true))
                            {
                                $filtroEstado = 'PASS';
                            }
                            else
                            {
                                $filtroEstado = 'FAIL';
                            }
                        }
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }
                    if ($clave == 'grupo_origen_NOT')
                    {
                        if (count($filtro['grupo_origen_NOT']) == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED';
                        }
                        else
                        {
                            if ($this->filtrarByGrupo($incidencia->getGrupoOrigen(), $filtro[$clave]))
                            {
                                $filtroEstado = 'PASS';
                            }
                            else
                            {
                                $filtroEstado = 'FAIL';
                            }
                        }
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }
                    if ($clave == 'grupo_destino_IN')
                    {
                        if (count($filtro['grupo_destino_IN']) == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED';
                        }
                        else
                        {
                            if ($this->filtrarByGrupo($incidencia->getGrupoDestino(), $filtro[$clave], true))
                            {
                                $filtroEstado = 'PASS';
                            }
                            else
                            {
                                $filtroEstado = 'FAIL';
                            }
                        }
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }
                    if ($clave == 'grupo_destino_NOT')
                    {
                        if (count($filtro['grupo_destino_NOT']) == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED';
                        }
                        else
                        {
                            if ($this->filtrarByGrupo($incidencia->getGrupoDestino(), $filtro[$clave]))
                            {
                                $filtroEstado = 'PASS';
                            }
                            else
                            {
                                $filtroEstado = 'FAIL';
                            }
                        }
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }

                    if ($clave == 'filtro_titulo')
                    {
                        if (count($filtro[$clave]) == 0)
                        {
                            //TODO: FIX - HOOK FOR DIFERENCE BEtWEEN ASIGNACION Y ALARMA
//                            if ($plantillaNombre == "ASIGNACION")
//                            {
//                                $filtroEstado = 'FAIL';
//                            }
//                            else
//                            {
                                $filtroEstado = 'NOT_NEEDED';
//                            }
                        }
                        else
                        {
                            foreach ($filtro[$clave] as $filter)
                            {
                                if (!preg_match($this->processClientesConfig($filter), $incidencia->getTitulo(), $matches))
                                {
                                    $filtroEstado = 'FAIL';
                                }
                                else
                                {
                                    $filtroEstado = 'PASS';
                                    break;
                                }
                            }
                        }

                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }
                }
            }
        }
        foreach ($pasoTodosFiltros as $plantilla => $filtros)
        {
            if (!$this->estaEn('FAIL', $filtros) == true)
            {
                return $plantilla;
            }
        }
        
    }

    protected function copiArrayTemporal()
    {
        $temp = array();
        $temp = $this->filtros;
        return $temp;
    }

    protected function existeClaveEnArray($indice, $array)
    {
        return array_key_exists($indice, $array);
    }

    protected function estaEn($txt, $array, $strict = false)
    {
        return in_array($txt, $array, $strict);
    }

    protected function filtrarByGrupo($texto, $array, $not = false)
    {

        if ($not)
        {
            if (in_array($texto, $array))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            if (!in_array($texto, $array))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    /*
     * Transformar las palabras del fichero de configuracion
     * en Patron regEXP, para comparar
     */

    protected function processClientesConfig($string)
    {
        if (!is_null($string))
        {
            return '^\[' . $string . '\]^';
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

}
