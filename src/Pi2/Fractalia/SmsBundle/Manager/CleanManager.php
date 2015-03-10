<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Manager;

/**
 * Encargado de realizar las limpiezas periodicas
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class CleanManager
{
    private $_counters = array();

    /**
     * Inicializacion de los contadores para el resumen de la limpieza
     * 
     * @return boolean
     */
    private function initCounters()
    {
        $this->_counters['incidenciast'] = 0;
        $this->_counters['incidencias'] = 0;
        $this->_counters['incidenciasf'] = 0;
        $this->_counters['eventos'] = 0;
        $this->_counters['resumenes'] = 0;
        $this->_counters['mensajesr'] = 0;
        $this->_counters['mensajese'] = 0;
        $this->_counters['smsr'] = 0;
        $this->_counters['smse'] = 0;
        $this->_counters['resoluciones'] = 0;
        $this->_counters['acciones'] = 0;
        $this->_counters['infoadjuntas'] = 0;
        $this->_counters['descripciones'] = 0;
        return true;
    }

    public function cleanDb($antiguedad = 7, $antiguedadFerr = 30, $em = null, $logger, &$result)
    {
        $this->initCounters();
        //Buscar las incidencias por antiguedad y obtener sus ids
        $arrayIncidenciasId = $this->findIncidencias($antiguedad, $em);
        if (is_array($arrayIncidenciasId) and count($arrayIncidenciasId) > 0)
        {
            $this->_counters['incidencias'] = count($arrayIncidenciasId);
            $logger->info("Encontradas " . count($arrayIncidenciasId) . " Incidencias para remover");
            $this->removeAll($arrayIncidenciasId, $em, $logger);
            $this->_counters['incidenciast'] += $this->_counters['incidencias'];
        }
        elseif ($arrayIncidenciasId == 0)
        {
            $this->_counters['incidencias'] = 0;
            $logger->info("No se encontraron Incidencias para remover");
        }
        else
        {
            $logger->error("Ocurrio un error no controlado");
            return false;
        }
        //Buscar las incidencias de Ferrovial y obtener sus ids
        $arrayIncidenciasFerrId = $this->findIncidencias($antiguedadFerr, $em, "FERROVIAL");
        if (is_array($arrayIncidenciasFerrId) and count($arrayIncidenciasFerrId) > 0)
        {
            $this->_counters['incidenciasf'] = count($arrayIncidenciasFerrId);
            $logger->info("Encontradas " . count($arrayIncidenciasFerrId) . " Incidencias de Ferrovial");
            $this->removeAll($arrayIncidenciasFerrId, $em, $logger);
            $this->_counters['incidenciast'] += $this->_counters['incidenciasf'];
        }
        elseif ($arrayIncidenciasFerrId == 0)
        {
            $this->_counters['incidenciasf'] = 0;
            $logger->info("No se encontraron Incidencias de Ferrovial para remover");
        }
        else
        {
            $logger->error("Ocurrio un error no controlado");
            return false;
        }
        if ($arrayIncidenciasId == 0 and $arrayIncidenciasFerrId == 0)
        {
            $this->_counters['incidenciast'] = 0;
            $logger->info("No se encontraron Incidencias para remover");
        }
        $result = $this->_counters;
        return true;
    }

    /**
     * 
     * @param type $arrayIncidenciasId
     * @param type $em
     */
    protected function removeAll($arrayIncidenciasId, $em, $logger)
    {
        //Borramos los resumenes
        $this->removeMensajesResumen($arrayIncidenciasId, $em, $logger);
        //Borramos los eventos
        $this->removeMensajesEvento($arrayIncidenciasId, $em, $logger);
        //Borramos el resto
        $this->removeIncidencias($arrayIncidenciasId, $em, $logger);
    }

    /**
     * 
     * @param type $antiguedad
     * @param type $em
     * @param type $grupo
     * @return type
     */
    public function findIncidencias($antiguedad, $em, $grupo = null)
    {
        $iMan = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia');
        if (is_null($grupo))
        {
            $resultado = $iMan->findByAntiguedad($antiguedad);
            if (is_array($resultado) and count($resultado) > 0)
            {
                return array_column($resultado, 'id');
            }
            if ($resultado == 0)
            {
                return $resultado;
            }
        }
        if ($grupo == 'FERROVIAL')
        {
            $resultado = $iMan->findByAntiguedadFerrovial($antiguedad);
            if (is_array($resultado) and count($resultado) > 0)
            {
                return array_column($resultado, 'id');
            }
            if ($resultado == 0)
            {
                return $resultado;
            }
        }
    }

    /**
     * 
     * @param type $incidencia
     * @param type $em
     */
    protected function removeRelatedRows($incidencia, $em, $logger)
    {
        $acciones = $incidencia->getAcciones();
        if ($acciones->count() > 0)
        {

            $logger->info("Encontrados " . $acciones->count() . " acciones para remover");
            $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Accion')->removeAccionByIncidenciaId($incidencia->getId());
            $logger->info("Borradas " . $acciones->count() . " acciones");
            $this->_counters['acciones'] += $acciones->count();
        }
        elseif ($acciones->count() == 0)
        {
            $this->_counters['acciones'] += 0;
            $logger->info("No se encontraron acciones");
        }

        $descripciones = $incidencia->getDescripciones();
        if ($descripciones->count() > 0)
        {
            $logger->info("Encontradas " . $descripciones->count() . " descripciones para remover");
            $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Descripcion')->removeDescripcionByIncidenciaId($incidencia->getId());
            $logger->info("Borradas " . $descripciones->count() . " descripciones");
            $this->_counters['descripciones'] += $descripciones->count();
        }
        elseif ($descripciones->count() == 0)
        {
            $this->_counters['descripciones'] += 0;
            $logger->info("No se encontraron descripciones");
        }

        $adjuntos = $incidencia->getInfoAdjuntos();
        if ($adjuntos->count() > 0)
        {
            $logger->info("Encontradas " . $adjuntos->count() . " info adjuntas para remover");
            $em->getRepository('\Pi2\Fractalia\Entity\SGSD\InfoAdjunto')->removeInfoAdjuntoByIncidenciaId($incidencia->getId());
            $logger->info("Borradas " . $adjuntos->count() . " info adjunta");
            $this->_counters['infoadjuntas'] += $adjuntos->count();
        }
        elseif ($adjuntos->count() == 0)
        {
            $this->_counters['infoadjuntas'] += 0;
            $logger->info("No se encontraron adjuntos");
        }

        $resoluciones = $incidencia->getResoluciones();
        if ($resoluciones->count() > 0)
        {
            $logger->info("Encontradas " . $resoluciones->count() . " resoluciones para remover");
            $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Resolucion')->removeResolucionByIncidenciaId($incidencia->getId());
            $logger->info("Borradas " . $resoluciones->count() . " resoluciones");
            $this->_counters['resoluciones'] += $resoluciones->count();
        }
        elseif ($resoluciones->count() == 0)
        {
            $this->_counters['resoluciones'] += 0;
            $logger->info("No se encontraron resoluciones");
        }
        $em->flush();
    }

    /**
     * 
     * @param type $arrayIncidenciasId
     * @param type $em
     * @param type $logger
     * @return boolean
     */
    protected function removeMensajesResumen($arrayIncidenciasId, $em, $logger)
    {
        $resMan = $em->getRepository('\Pi2\Fractalia\SmsBundle\Entity\Columnaresumen');

        //Obtener los distintos mensajes_id de las incidencias_id que generaron resumenes  
        $arrayMensajesId = $resMan->getMensajesByIncidencias($arrayIncidenciasId);

        if ($arrayMensajesId == 0)
        {
            $this->_counters['mensajesr'] += 0;
            $logger->info("No se encontraron Mensajes de Resumenes para remover");
            return true;
        }
        elseif (is_array($arrayMensajesId) and count($arrayMensajesId) > 0)
        {
            //recortamos el array
            $arrayIdsM = array_column($arrayMensajesId, 'mensaje_id');


            $resumenes = $resMan->getResumenesByMensajesId($arrayIdsM);

            if ($resumenes == 0)
            {
                $this->_counters['resumenes'] += 0;
                $logger->info("No se encontraron resumenes para remover");
                return true;
            }
            elseif (is_array($resumenes) and count($resumenes) > 0)
            {
                $this->_counters['resumenes'] += count($resumenes);
                $logger->info("Encontrados " . count($resumenes) . " resumenes para remover");
                $this->deleteEntity($resumenes, $em);
                $logger->info("Borrados " . count($resumenes) . " resumenes");

                $smss = $em->getRepository('FractaliaSmsBundle:Sms')->findByMensaje($arrayIdsM);

                //borrar sms
                if ($smss == 0)
                {
                    $this->_counters['smsr'] += 0;
                    $logger->info("No se encontraron sms de resumenes para remover");
                }
                elseif (count($smss) > 0)
                {
                    $this->_counters['smsr'] += count($smss);
                    $logger->info("Encontrados " . count($smss) . " sms de resumenes para remover");
                    $this->deleteEntity($resumenes, $em);
                    $logger->info("Borrados " . count($smss) . " sms de resumenes");
                }
                $mensajes = $em->getRepository('FractaliaSmsBundle:Mensaje')->findById($arrayIdsM);

                //borrar mensajes
                if ($mensajes == 0)
                {
                    $this->_counters['mensajesr'] += 0;
                    $logger->info("No se encontraron mensajes de resumenes para remover");
                    return true;
                }
                elseif (count($mensajes) > 0)
                {
                    $this->_counters['mensajesr'] += count($mensajes);
                    $logger->info("Encontrados " . count($mensajes) . " mensajes de resumenes para remover");
                    $this->deleteEntity($mensajes, $em);
                    $logger->info("Borrados " . count($mensajes) . " mensajes de resumenes");
                    return true;
                }
            }
        }
    }

    /**
     * 
     * @param type $arrayIncidenciasId
     * @param type $em
     * @param type $logger
     * @return boolean
     */
    protected function removeMensajesEvento($arrayIncidenciasId, $em, $logger)
    {
        $menMan = $em->getRepository('FractaliaSmsBundle:Mensaje');
        $eveMan = $em->getRepository('FractaliaSmsBundle:Columnaevento');

        $arrayEventosId = $eveMan->getIdByIncidencias($arrayIncidenciasId);
        if ($arrayEventosId == 0)
        {
            $this->_counters['eventos'] += $arrayEventosId;
            $logger->info("No se encontraron Eventos para remover");
            return true;
        }
        elseif (is_array($arrayEventosId) and count($arrayEventosId) > 0)
        {
            $arrayIdsE = array_column($arrayEventosId, 'id');

            //Buscamos los mensajes con eventos
            $arrayMensajesId = $menMan->getIdByEventos($arrayIdsE);
            if ($arrayMensajesId == 0)
            {
                $this->_counters['mensajese'] += $arrayEventosId;
                $logger->info("No se encontraron Mensaje de eventos para remover");
                return true;
            }
            elseif (is_array($arrayMensajesId) and count($arrayMensajesId) > 0)
            {
                $arrayIdsM = array_column($arrayMensajesId, 'id');
                //borrar sms
                $smss = $em->getRepository('FractaliaSmsBundle:Sms')->findByMensaje($arrayIdsM);
                if ($smss == 0)
                {
                    $this->_counters['smse'] += $smss;
                    $logger->info("No se encontraron sms de eventos para remover");
                }
                elseif (count($smss) > 0)
                {
                    $this->_counters['smse'] += count($smss);
                    $logger->info("Encontrados " . count($smss) . " sms de eventos para remover");
                    $this->deleteEntity($smss, $em);
                    $logger->info("Borrados " . count($smss) . " sms de eventos");
                }
                //borrar mensajes
                $mensajes = $menMan->findByColumnaEvento($arrayIdsE);
                if ($mensajes == 0)
                {
                    $this->_counters['mensajese'] += $mensajes;
                    $logger->info("No se encontraron mensajes de eventos para remover");
                }
                elseif (count($mensajes) > 0)
                {
                    $this->_counters['mensajese'] += count($mensajes);
                    $logger->info("Encontrados " . count($mensajes) . " mensajes de eventos para remover");
                    $this->deleteEntity($mensajes, $em);
                    $logger->info("Borrados " . count($mensajes) . " mensajes de eventos");
                }
                $eventos = $eveMan->findById($arrayIdsE);
                if ($eventos == 0)
                {
                    $this->_counters['eventos'] += $eventos;
                    $logger->info("No se encontraron mensajes de eventos para remover");
                    return true;
                }
                elseif (count($eventos) > 0)
                {
                    $this->_counters['eventos'] += count($eventos);
                    $logger->info("Encontrados " . count($eventos) . " mensajes de resumenes para remover");
                    $this->deleteEntity($eventos, $em);
                    $logger->info("Borrados " . count($eventos) . " mensajes de resumenes");
                    return true;
                }
            }
        }
    }

    /**
     * Borrado fisico de las entidades enviadas en un array de entidades
     * 
     * @param array $entities
     * @param EntityManager $em
     */
    private function deleteEntity($entities, $em)
    {

        foreach ($entities as $entity)
        {
            $em->remove($entity);
            $em->flush();
        }
    }

    protected function removeIncidencias($incidenciasId, $em, $logger)
    {

        $inMan = $em->getRepository('\Pi2\Fractalia\Entity\SGSD\Incidencia');
        if (!is_array($incidenciasId) OR count($incidenciasId) <= 0)
        {
            $this->_counters['incidencias'] = 0;
            $logger->info("No hay incidencias para remover");
            return true;
        }
        if (is_array($incidenciasId) AND count($incidenciasId) > 0)
        {
            foreach ($incidenciasId as $id)
            {
                $incidencia = $inMan->find($id);
                if ($incidencia instanceof \Pi2\Fractalia\Entity\SGSD\Incidencia and ! is_null($incidencia))
                {
                    $this->removeRelatedRows($incidencia, $em, $logger);
                    $em->remove($incidencia);
                }
            }
            $logger->info("Borradas " . count($incidenciasId) . " Incidencias\n");
        }
        $em->flush();
        $em->clear();
        return true;
    }

}
