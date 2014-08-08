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
            $arrayFiltroTituloNot = array_slice($evento, 7, 1, true);
            $arrayTecnicoInicial = array_slice($evento, 8, 1, true);
            $arrayTecnicoFinal = array_slice($evento, 9, 1, true);

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

            if (isset($arrayFiltroTituloNot['filtro_titulo_NOT']))
            {
                $this->filtros[$plantillaNombre][key($arrayFiltroTituloNot)] = $arrayFiltroTituloNot;
            }

            if (isset($arrayTecnicoInicial['tecnico_inicial']))
            {
                $this->filtros[$plantillaNombre][key($arrayTecnicoInicial)] = $arrayTecnicoInicial;
            }

            if (isset($arrayTecnicoFinal['tecnico_final']))
            {
                $this->filtros[$plantillaNombre][key($arrayTecnicoFinal)] = $arrayTecnicoFinal;
            }
        }
    }

    public function pasarFiltro(Incidencia $incidencia)
    {
        $filtroEstado = '';
        $filtrosArray = $this->copiArrayTemporal();
        $filtro = array();
        $pasoTodosFiltros = array();
        $resultados = array();

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
                            $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                        }

                        if (count($filtro['prioridad']) > 0)
                        {
                            $filtroEstado = 'REQUIRED_';
                            if ($this->estaEn($incidencia->getPrioridad(), $filtro['prioridad']))
                            {
                                $filtroEstado .= 'AND_PASS';
                            }
                            else
                            {
                                $filtroEstado .= 'BUT_FAIL';
                            }
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
                                $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                            }
                        }
                        if (count($arrayEstado) > 0)
                        {
                            $arrayTraducciones = array_shift($arrayEstado);
                            $filtroEstado = 'REQUIRED_';
                            if (($this->estaEn(strtoupper($incidencia->getEstado()), $arrayTraducciones)) == true)
                            {
                                $filtroEstado .= 'AND_PASS';
                            }
                            else
                            {
                                $filtroEstado .= 'BUT_FAIL';
                            }
                        }


                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }
                    if ($clave == 'grupo_origen_IN')
                    {
                        if (count($filtro['grupo_origen_IN']) == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                        }

                        if (count($filtro['grupo_origen_IN']) > 0)
                        {
                            $filtroEstado = 'REQUIRED_';
                            if ($this->filtrarByGrupo($incidencia->getGrupoOrigen(), $filtro[$clave], true))
                            {
                                $filtroEstado .= 'AND_PASS';
                            }
                            else
                            {
                                $filtroEstado .= 'BUT_FAIL';
                            }
                        }

                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }
                    if ($clave == 'grupo_origen_NOT')
                    {
                        if (count($filtro['grupo_origen_NOT']) == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                        }

                        if (count($filtro['grupo_origen_NOT']) > 0)
                        {
                            $filtroEstado = 'REQUIRED_';
                            if ($this->filtrarByGrupo($incidencia->getGrupoOrigen(), $filtro[$clave]))
                            {
                                $filtroEstado .= 'AND_PASS';
                            }
                            else
                            {
                                $filtroEstado .= 'BUT_FAIL';
                            }
                        }
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }
                    if ($clave == 'grupo_destino_IN')
                    {
                        if (count($filtro['grupo_destino_IN']) == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                        }

                        if (count($filtro['grupo_destino_IN']) > 0)
                        {
                            $filtroEstado = 'REQUIRED_';
                            if ($this->filtrarByGrupo($incidencia->getGrupoDestino(), $filtro[$clave], true))
                            {
                                $filtroEstado .= 'AND_PASS';
                            }
                            else
                            {
                                $filtroEstado .= 'BUT_FAIL';
                            }
                        }
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }
                    if ($clave == 'grupo_destino_NOT')
                    {
                        if (count($filtro['grupo_destino_NOT']) == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                        }

                        if (count($filtro['grupo_destino_NOT']) > 0)
                        {
                            $filtroEstado = 'REQUIRED_';
                            if ($this->filtrarByGrupo($incidencia->getGrupoDestino(), $filtro[$clave]))
                            {
                                $filtroEstado .= 'AND_PASS';
                            }
                            else
                            {
                                $filtroEstado .= 'BUT_FAIL';
                            }
                        }
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }

                    if ($clave == 'filtro_titulo')
                    {
                        if (count($filtro[$clave]) == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                        }

                        if (count($filtro[$clave]) > 0)
                        {
                            foreach ($filtro[$clave] as $filter)
                            {
                                if (!preg_match($this->processClientesConfig($filter), $incidencia->getTitulo(), $matches))
                                {
                                    $filtroEstado = 'REQUIRED_BUT_FAIL';
                                    continue;
                                }
                                else
                                {
                                    $filtroEstado = 'REQUIRED_AND_PASS';
                                    break;
                                }
                            }
                        }
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }

                    if ($clave == 'filtro_titulo_NOT')
                    {
                        if (count($filtro[$clave]) == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                        }

                        if (count($filtro[$clave]) > 0)
                        {
                            foreach ($filtro[$clave] as $filter)
                            {
                                if (!preg_match($this->processClientesConfig($filter), $incidencia->getTitulo(), $matches))
                                {
                                    $filtroEstado = 'REQUIRED_AND_PASS';
                                    continue;
                                }
                                else
                                {
                                    $filtroEstado = 'REQUIRED_AND_FAIL';
                                    break;
                                }
                            }
                        }
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }

                    if ($clave == 'tecnico_inicial')
                    {
                        if (count($filtro['tecnico_inicial']) == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                        }

                        if (count($filtro['tecnico_inicial']) > 0)
                        {
                            $filtroEstado = 'REQUIRED_';
                            if ($this->estaEn($incidencia->getTecnicoAsignadoInicial(), $filtro['tecnico_inicial']))
                            {
                                $filtroEstado .= 'AND_PASS';
                            }
                            else
                            {
                                $filtroEstado .= 'BUT_FAIL';
                            }
                        }
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }

                    if ($clave == 'tecnico_final')
                    {
                        if (count($filtro['tecnico_final']) == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                        }

                        if (count($filtro['tecnico_final']) > 0)
                        {
                            $filtroEstado = 'REQUIRED_';
                            if ($this->estaEn($incidencia->getTecnicoAsignadoFinal(), $filtro['tecnico_final']))
                            {
                                $filtroEstado .= 'AND_PASS';
                            }
                            else
                            {
                                $filtroEstado .= 'AND_PASS';
                            }
                        }
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }
                }
            }
        }
        
        foreach ($pasoTodosFiltros as $plantilla => $filtros)
        {
            if (!$this->estaEn('REQUIRED_BUT_FAIL', $filtros) == true)
            {
                $resultados[$plantilla] = $filtros;
            }
        }
        $nombrePlantilla = "";
        if (count($resultados) == 1)
        {
            $nombrePlantilla = key($resultados);
            return $nombrePlantilla;
        }
        
        $temp1 = array();
        $temp2 = array();
        if (count($resultados) > 1)
        {
            foreach ($resultados as $plantName => $arrayfiltros)
            {
                $temp1[$plantName] = array_count_values($arrayfiltros);
                $temp2[] = $plantName;
            }
            $temp3 = array_column($temp1, 'REQUIRED_AND_PASS');
            $final = array_combine($temp2, $temp3);
            $nombrePlantilla = array_search(max($final), $final);
        }
        return $nombrePlantilla;
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
