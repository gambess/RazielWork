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
            $arrayTipoAccion = array_slice($evento, 0, 1, true);
            $arrayPrioridad = array_slice($evento, 1, 1, true);
            $arrayEstado = array_slice($evento, 2, 1, true);
            $arrayGrupoOrigenIn = array_slice($evento, 3, 1, true);
            $arrayGrupoOrigenNot = array_slice($evento, 4, 1, true);
            $arrayGrupoDestinoIn = array_slice($evento, 5, 1, true);
            $arrayGrupoDestinoNot = array_slice($evento, 6, 1, true);
            $arrayFiltroTitulo = array_slice($evento, 7, 1, true);
            $arrayFiltroTituloNot = array_slice($evento, 8, 1, true);
            $arrayTecnicoInicial = array_slice($evento, 9, 1, true);
            $arrayTecnicoFinal = array_slice($evento, 10, 1, true);

            if (isset($arrayTipoAccion['tipo_accion']))
            {
                $this->filtros[$plantillaNombre][key($arrayTipoAccion)] = $arrayTipoAccion;
            }
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

    public function pasarFiltro(Incidencia $incidencia, $estadoPrevio = null, $previas = null)
    {
        $filtroEstado = '';
        $filtrosArray = $this->copiArrayTemporal();
        $filtro = array();
        $pasoTodosFiltros = array();
        $resultados = array();
        $tipoAccionPrevia = '';
        $grupoOrigenPrevio = '';
        
        //Historia sgsd-371
        if(is_array($previas) and (count($previas) > 0 and count($previas) < 3)){
            $tipoAccionPrevia = $previas['tipoAccion'];
            $grupoOrigenPrevio = $previas['grupoOrigen'];
        }
        

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
                    if ($clave == 'tipo_accion')
                    {
                        $cantidadFiltros = count($filtro['tipo_accion']);
                        if ( $cantidadFiltros == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                        }
                        elseif ($cantidadFiltros > 0)
                        {
                            foreach ($filtro['tipo_accion'] as $key => $tiposAcciones)
                            {
                                
                                if (is_null($tiposAcciones['previa']) and ! is_null($tiposAcciones['actual']))
                                {
                                    if (!is_null($tiposAcciones['actual']))
                                    {
                                        if (strtoupper($incidencia->getTipoAccion()) == $tiposAcciones['actual'])
                                        {
                                            $filtroEstado = 'REQUIRED_AND_PASS';
                                            break;
                                        }
                                        else
                                        {
                                            $filtroEstado = 'REQUIRED_BUT_FAIL';
                                            continue;
                                        }
                                    }
                                }
                                elseif ($tiposAcciones['previa'] == '*' and ! is_null($tiposAcciones['actual']))
                                {
                                    if ($tiposAcciones['actual'] != strtoupper($tipoAccionPrevia) and $tiposAcciones['actual'] == strtoupper($incidencia->getTipoAccion()))
                                    {
                                        $filtroEstado = 'REQUIRED_AND_PASS';
                                        break;
                                    }
                                    else
                                    {
                                        $filtroEstado = 'REQUIRED_BUT_FAIL';
                                        continue;
                                    }
                                }
                                elseif (is_string($tiposAcciones['previa']) and ! is_null($tiposAcciones['actual']))
                                {
                                    $condition = false;
                                    $arrayPrevias = preg_split('/\s*,\s*/', $tiposAcciones['previa']);
                                    if (is_array($arrayPrevias) and count($arrayPrevias) > 0)
                                    {
                                        $condition = $this->estaEn(strtoupper($tipoAccionPrevia), $arrayPrevias);
                                    }
                                    elseif (!is_array($arrayPrevias))
                                    {
                                        $condition = boolval(strtoupper($tipoAccionPrevia) == $arrayPrevias);
                                    }
                                    if ($condition and ( $tiposAcciones['actual'] == strtoupper($incidencia->getTipoAccion())))
                                    {
                                        $filtroEstado = 'REQUIRED_AND_PASS';
                                        break;
                                    }
                                    else
                                    {
                                        $filtroEstado = 'REQUIRED_BUT_FAIL';
                                        continue;
                                    }
                                }
                                else
                                {
                                    $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                                    break;
                                }
                                if ($cantidadFiltros > 1)
                                {
                                    $resultadosFiltro[$key] = $filtroEstado;
                                }
                            }
                            if (is_array($resultadosFiltro) and count($resultadosFiltro) > 1)
                            {
                                if ($this->estaEn('REQUIRED_AND_PASS', $resultadosFiltro))
                                {
                                    $filtroEstado = 'REQUIRED_AND_PASS';
                                }
                            }
                        }

                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }

                    if ($clave == 'prioridad')
                    {
                        $resultadosFiltro = array();
                        $cantidadFiltros = count($filtro['prioridad']);
                        if ($cantidadFiltros == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                        }
                        elseif ($cantidadFiltros > 0)
                        {
                            foreach ($filtro['prioridad'] as $key => $prioridades)
                            {
                                if (is_null($prioridades['previas']) and ! is_null($prioridades['actual']))
                                {
                                    if (!is_null($prioridades['actual']))
                                    {
                                        if (strtoupper($incidencia->getPrioridad()) == $prioridades['actual'])
                                        {
                                            $filtroEstado = 'REQUIRED_AND_PASS';
                                            break;
                                        }
                                        else
                                        {
                                            $filtroEstado = 'REQUIRED_BUT_FAIL';
                                            continue;
                                        }
                                    }
                                }
                                elseif ($prioridades['previas'] == '*' and ! is_null($prioridades['actual']))
                                {
                                    if ($prioridades['actual'] != strtoupper($estadoPrevio) and $prioridades['actual'] == strtoupper($incidencia->getPrioridad()))
                                    {
                                        $filtroEstado = 'REQUIRED_AND_PASS';
                                        break;
                                    }
                                    else
                                    {
                                        $filtroEstado = 'REQUIRED_BUT_FAIL';
                                        continue;
                                    }
                                }
                                elseif (is_string($prioridades['previas']) and ! is_null($prioridades['actual']))
                                {
                                    $condition = false;
                                    $arrayPrevias = preg_split('/\s*,\s*/', $prioridades['previas']);
                                    if (is_array($arrayPrevias) and count($arrayPrevias) > 0)
                                    {
                                        $condition = $this->estaEn(strtoupper($estadoPrevio), $arrayPrevias);
                                    }
                                    elseif (!is_array($arrayPrevias))
                                    {
                                        $condition = boolval(strtoupper($estadoPrevio) == $arrayPrevias);
                                    }
                                    if ($condition and ( $prioridades['actual'] == strtoupper($incidencia->getPrioridad())))
                                    {
                                        $filtroEstado = 'REQUIRED_AND_PASS';
                                        break;
                                    }
                                    else
                                    {
                                        $filtroEstado = 'REQUIRED_BUT_FAIL';
                                        continue;
                                    }
                                }
                                else
                                {
                                    $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                                    break;
                                }
                                if ($cantidadFiltros > 1)
                                {
                                    $resultadosFiltro[$key] = $filtroEstado;
                                }
                            }
                            if (is_array($resultadosFiltro) and count($resultadosFiltro) > 1)
                            {
                                if ($this->estaEn('REQUIRED_AND_PASS', $resultadosFiltro))
                                {
                                    $filtroEstado = 'REQUIRED_AND_PASS';
                                }
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
                        $cantidadFiltros = count($filtro['grupo_origen_IN']);
                        if ( $cantidadFiltros == 0)
                        {
                            $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                        }
                        elseif ($cantidadFiltros > 0)
                        {
                            foreach ($filtro['grupo_origen_IN'] as $key => $gruposOrigen)
                            {
                                
                                if (is_null($gruposOrigen['previos']) and ! is_null($gruposOrigen['actual']))
                                {
                                    if (!is_null($gruposOrigen['actual']))
                                    {
                                        if (strtoupper($incidencia->getGrupoOrigen()) == $gruposOrigen['actual'])
                                        {
                                            $filtroEstado = 'REQUIRED_AND_PASS';
                                            break;
                                        }
                                        else
                                        {
                                            $filtroEstado = 'REQUIRED_BUT_FAIL';
                                            continue;
                                        }
                                    }
                                }
                                elseif ($gruposOrigen['previos'] == '*' and ! is_null($gruposOrigen['actual']))
                                {
                                    if ($gruposOrigen['actual'] != strtoupper($grupoOrigenPrevio) and $gruposOrigen['actual'] == strtoupper($incidencia->getGrupoOrigen()))
                                    {
                                        $filtroEstado = 'REQUIRED_AND_PASS';
                                        break;
                                    }
                                    else
                                    {
                                        $filtroEstado = 'REQUIRED_BUT_FAIL';
                                        continue;
                                    }
                                }
                                elseif (is_string($gruposOrigen['previos']) and ! is_null($gruposOrigen['actual']))
                                {
                                    $condition = false;
                                    $arrayPrevias = preg_split('/\s*,\s*/', $gruposOrigen['previos']);
                                    if (is_array($arrayPrevias) and count($arrayPrevias) > 0)
                                    {
                                        $condition = $this->estaEn(strtoupper($grupoOrigenPrevio), $arrayPrevias);
                                    }
                                    elseif (!is_array($arrayPrevias))
                                    {
                                        $condition = boolval(strtoupper($grupoOrigenPrevio) == $arrayPrevias);
                                    }
                                    if ($condition and ( $gruposOrigen['actual'] == strtoupper($incidencia->getGrupoOrigen())))
                                    {
                                        $filtroEstado = 'REQUIRED_AND_PASS';
                                        break;
                                    }
                                    else
                                    {
                                        $filtroEstado = 'REQUIRED_BUT_FAIL';
                                        continue;
                                    }
                                }
                                elseif(is_null($gruposOrigen['actual']) and is_string($gruposOrigen['previos']))
                                {
                                    $condition = false;
                                    $arrayPrevias = preg_split('/\s*,\s*/', $gruposOrigen['previos']);
                                    if (is_array($arrayPrevias) and count($arrayPrevias) > 0)
                                    {
                                        $condition = $this->estaEn(strtoupper($grupoOrigenPrevio), $arrayPrevias);
                                    }
                                    elseif (!is_array($arrayPrevias))
                                    {
                                        $condition = boolval(strtoupper($grupoOrigenPrevio) == $arrayPrevias);
                                    }
                                    if ($condition)
                                    {
                                        $filtroEstado = 'REQUIRED_AND_PASS';
                                        break;
                                    }
                                    else
                                    {
                                        $filtroEstado = 'REQUIRED_BUT_FAIL';
                                        continue;
                                    }
                                }
                                else
                                {
                                    $filtroEstado = 'NOT_NEEDED_AND_EMPTY';
                                    break;
                                }
                                if ($cantidadFiltros > 1)
                                {
                                    $resultadosFiltro[$key] = $filtroEstado;
                                }
                            }
                            if (is_array($resultadosFiltro) and count($resultadosFiltro) > 1)
                            {
                                if ($this->estaEn('REQUIRED_AND_PASS', $resultadosFiltro))
                                {
                                    $filtroEstado = 'REQUIRED_AND_PASS';
                                }
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
                                if (!preg_match($this->processClientesConfig($filter), strtoupper($incidencia->getTitulo()), $matches))
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
                                if (!preg_match($this->processClientesConfig($filter), strtoupper($incidencia->getTitulo()), $matches))
                                {
                                    $filtroEstado = 'REQUIRED_AND_PASS';
                                    continue;
                                }
                                else
                                {
                                    $filtroEstado = 'REQUIRED_BUT_FAIL';
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
                            if ($this->estaEn(strtoupper($incidencia->getTecnicoAsignadoInicial()), $filtro['tecnico_inicial']))
                            {
                                $filtroEstado = 'REQUIRED_AND_PASS';
                            }
                            else
                            {
                                $filtroEstado = 'REQUIRED_BUT_FAIL';
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
                            if ($this->estaEn(strtoupper($incidencia->getTecnicoAsignadoFinal()), $filtro['tecnico_final']))
                            {
                                $filtroEstado = 'REQUIRED_AND_PASS';
                            }
                            else
                            {
                                $filtroEstado = 'REQUIRED_BUT_FAIL';
                            }
                        }
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }
                }
            }
        }
//        print_r($pasoTodosFiltros);die;
        
        foreach ($pasoTodosFiltros as $plantilla => $filtros)
        {
            if (!$this->estaEn('REQUIRED_BUT_FAIL', $filtros) == true)
            {
                $resultados[$plantilla] = $filtros;
            }
        }
        
//        print_r($resultados);die;
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
            if (in_array(strtoupper($texto), $array))
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
            if (!in_array(strtoupper($texto), $array))
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
