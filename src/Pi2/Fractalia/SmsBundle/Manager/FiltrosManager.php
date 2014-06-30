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

            if (count($arrayPrioridad['prioridad']) > 0)
            {
                $this->filtros[$plantillaNombre][key($arrayPrioridad)] = $arrayPrioridad;
            }
            if (count($arrayEstado['estado']) > 0)
            {
                $this->filtros[$plantillaNombre][key($arrayEstado)] = $arrayEstado;
            }

            if (count($arrayGrupoOrigenIn['grupo_origen_IN']) > 0)
            {
                $this->filtros[$plantillaNombre][key($arrayGrupoOrigenIn)] = $arrayGrupoOrigenIn;
            }

            if (count($arrayGrupoOrigenNot['grupo_origen_NOT']) > 0)
            {
                $this->filtros[$plantillaNombre][key($arrayGrupoOrigenNot)] = $arrayGrupoOrigenNot;
            }

            if (count($arrayGrupoDestinoIn['grupo_destino_IN']) > 0)
            {
                $this->filtros[$plantillaNombre][key($arrayGrupoDestinoIn)] = $arrayGrupoDestinoIn;
            }

            if (count($arrayGrupoDestinoNot['grupo_destino_NOT']) > 0)
            {
                $this->filtros[$plantillaNombre][key($arrayGrupoDestinoNot)] = $arrayGrupoDestinoNot;
            }

            if (count($arrayFiltroTitulo['filtro_titulo']) > 0)
            {
                $this->filtros[$plantillaNombre][key($arrayFiltroTitulo)] = $arrayFiltroTitulo;
            }
        }

//        return true;
    }

    public function pasarFiltro(Incidencia $incidencia)
    {
        $filtroEstado = false;
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
                if (is_array($filtro) == true and count($filtro) > 0 and $this->existeClaveEnArray($clave, $filtro) >= 1)
                {
                    if ($clave == 'prioridad' and $this->estaEn($incidencia->getPrioridad(), $filtro['prioridad'], true))
                        $filtroEstado = true;
                    $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;

                    if ($clave == 'estado')
                    {
                        $arrayEstado = array_shift($filtro);
                        $arrayTraducciones = array_shift($arrayEstado);

                        if (($this->estaEn($incidencia->getEstado(), $arrayTraducciones, true)) == true)
                        {
                            $filtroEstado = true;
                        }
                        else
                            $filtroEstado = false;
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }


                    if ($clave == 'grupo_origen_IN' and $this->filtrarByGrupo($incidencia->getGrupoOrigen(), $filtro[$clave], true))
                    {
                        $filtroEstado = true;
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }


                    if ($clave == 'grupo_origen_NOT' and $this->filtrarByGrupo($incidencia->getGrupoOrigen(), $filtro[$clave]))
                    {
                        $filtroEstado = true;
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }

                    if ($clave == 'grupo_destino_IN' and $this->filtrarByGrupo($incidencia->getGrupoDestino(), $filtro[$clave], true))
                    {
                        $filtroEstado = true;
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }

                    if ($clave == 'grupo_destino_NOT' and $this->filtrarByGrupo($incidencia->getGrupoDestino(), $filtro[$clave]))
                    {
                        $filtroEstado = true;
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }

                    if ($clave == 'filtro_titulo' and strpos($filtro[$clave][0],$incidencia->getTitulo()) > 0)
                    {
                        $filtroEstado = true;
                        $pasoTodosFiltros[$plantillaNombre][$clave] = $filtroEstado;
                    }
                }
            }
        }

        foreach($pasoTodosFiltros as $plantilla => $filtros){
            if(!$this->estaEn(false, $filtros) == true){
                return $plantilla;
            }
        }
        return false;
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

}
