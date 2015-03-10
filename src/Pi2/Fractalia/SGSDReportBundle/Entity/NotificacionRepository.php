<?php

namespace Pi2\Fractalia\SGSDReportBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\DriverManager;

/**
 * NotificacionRepository
 *
 */
class NotificacionRepository extends EntityRepository
{

    /**
     * Prepara la parte de la Query que se repite en todas las consultas
     * 
     * @param string $tipoaccion
     * @param string $estado
     * @param string $buzonGenerico
     * @return string
     */
    protected function getHeaderQuery($tipoaccion = "resolucion", $estado = "resolved")
    {

        return "SELECT "
//            . "n.numerocaso AS numerocaso "
            . "n "
            . "FROM Pi2\Fractalia\SGSDReportBundle\Entity\Notificacion n "
            . "WHERE "
            . "(n.tipoaccion LIKE '{$tipoaccion}' AND n.estado LIKE '{$estado}') ";
    }

    /**
     * Prepara la parte de la Query generica
     * 
     * @return string
     */
    protected function getNewHeaderQuery()
    {

        return "SELECT "
            . "n "
            . "FROM Pi2\Fractalia\SGSDReportBundle\Entity\Notificacion n "
            . "WHERE n.tipoaccion NOT LIKE 'cierre' ";
    }

    /**
     * añade la condicion del buzon SOC SEGURIDAD
     * 
     * @param string $buzonGenerico
     * @return string
     */
    protected function getGenericBuzonCondition($buzonGenerico = "soc seguridad", $cond = true, $not = true)
    {
        if ($cond)
        {
            if ($not)
            {
                return "AND (n.grupoorigen LIKE '{$buzonGenerico}' OR n.grupodestino LIKE '{$buzonGenerico}') ";
            }
            else
            {
                return "AND (n.grupoorigen LIKE '{$buzonGenerico}' AND n.grupodestino LIKE '{$buzonGenerico}') ";
            }
        }
        else
        {
            return "AND (n.grupoorigen LIKE '{$buzonGenerico}' AND n.grupodestino NOT LIKE '{$buzonGenerico}') ";
        }
    }

    /**
     * añade la condicion del buzon SOC SEGURIDAD y los distintos buzones
     * 
     * @param string|Array $buzon
     * @param string $buzonGenerico
     * @return string
     */
    protected function getBuzonesCondition($buzon, $buzonGenerico = "soc seguridad")
    {
        if (!is_null($buzon) and is_string($buzon))
        {
            return "AND ((n.grupoorigen LIKE '{$buzonGenerico}' or n.grupodestino LIKE '{$buzonGenerico}') AND (n.grupodestino LIKE '{$buzon}')) ";
        }
        elseif (is_array($buzon) and count($buzon) > 0)
        {
            return "AND ((n.grupoorigen LIKE '{$buzonGenerico}' or n.grupodestino LIKE '{$buzonGenerico}') AND (n.grupodestino NOT IN (:buzon)))";
        }
        else
        {
            return "AND (n.grupoorigen LIKE '{$buzonGenerico}' OR n.grupodestino LIKE '{$buzonGenerico}') ";
        }
    }

    /**
     * Prepara la condicion de la fecha de apertura o fecha de resolucion
     * En funcion del flag resolucion
     * 
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @return string
     */
    protected function getTimeCondition($startdate, $enddate, $resolucion = 0)
    {
        if ($resolucion == 0)
        {
            if (!is_null($startdate) and ! is_null($enddate))
            {
                return "AND (DATE(n.fechaapertura) >= '{$startdate}' AND DATE(n.fechaapertura) <= '{$enddate}') ";
            }
            else
            {
                return -1;
            }
        }
        elseif ($resolucion == 1)
        {
            if (!is_null($startdate) and ! is_null($enddate))
            {
                return "AND (DATE(n.fecharesolucion) >= '{$startdate}' AND DATE(n.fecharesolucion) <= '{$enddate}') ";
            }
            else
            {
                return -1;
            }
        }
        else
        {
            return -1;
        }
    }

    /**
     * Prepara la condicion de la fecha de actualizacion o fecha de resolucion
     * En funcion del flag resolucion
     * 
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @return string
     */
    protected function getNewTimeCondition($startdate, $enddate, $resolucion = 0)
    {
        if ($resolucion == 0)
        {
            if (!is_null($startdate) and ! is_null($enddate))
            {
                return "AND (DATE(n.fechaactualizacion) >= '{$startdate}' AND DATE(n.fechaactualizacion) <= '{$enddate}') ";
            }
            else
            {
                return -1;
            }
        }
        elseif ($resolucion == 1)
        {
            if (!is_null($startdate) and ! is_null($enddate))
            {
                return "AND (DATE(n.fecharesolucion) >= '{$startdate}' AND DATE(n.fecharesolucion) <= '{$enddate}') ";
            }
            else
            {
                return -1;
            }
        }
        else
        {
            return -1;
        }
    }

    /**
     * Prepara la condicion para añadir en casa de que hayan usuarios por buzon
     * 
     * @param type $usuarios
     * @return int|string
     */
    protected function getUserCondition($usuarios)
    {
        if (is_array($usuarios) and count($usuarios) > 0)
        {
            return "AND (n.tecnicoasignadoinicial IN (:usuarios)) ";
        }
        else
        {
            return 0;
        }
    }

    /**
     * Añade la condicion tipo caso
     * 
     * @param int $tipoCaso
     * @return string
     */
    protected function getTipoCasoCondition($tipoCaso = 1)
    {
        return "AND (n.tipocaso = {$tipoCaso}) ";
    }

    /**
     * Añade la condicion Queja
     * Tipo Caso 3|4
     * 
     * @param int $queja
     * @param int $consulta
     * @return string
     */
    protected function getQuejaCondition($queja = 3, $consulta = 4)
    {
        return "AND (n.tipocaso = {$queja} or n.tipocaso = {$consulta}) ";
    }

    /**
     * Añade la condicion tipificacion
     * 
     * @param bool $not
     * @param string $word1
     * @param string $word2
     * @return string
     */
    protected function getTipificationCondition($not = true, $word1 = "provision", $word2 = "proyecto")
    {
        if ($not)
        {
            return "AND (n.tipificacion1 NOT LIKE '{$word1}' AND n.tipificacion1 NOT LIKE '{$word2}') ";
        }
        else
        {
            return "AND (n.tipificacion1 LIKE '{$word1}' OR n.tipificacion1 LIKE '{$word2}') ";
        }
    }

    /**
     * Añade la parte group by a la query
     * 
     * @return string
     */
    protected function getGroupBy()
    {
        return "GROUP BY n.numerocaso, n.fecharesolucion HAVING COUNT(n.fecharesolucion) >= 1 AND n.fecharesolucion = MAX(n.fecharesolucion) ";
    }

    /**
     * Añade la parte group by a la query
     * 
     * @return string
     */
    protected function getOtherGroupBy()
    {
        return "GROUP BY n.numerocaso ";
    }

    /**
     * a)Se obtienen todos los tickets totales tratadas en N1
     * 
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @param string $servicio
     * @return \Doctrine\Orm\ORMException|Array|int
     */
    public function getTotalesTratadasN1($startdate, $enddate, $resolucion = 0, $usuarios = null, $servicio = 'SOC')
    {
        $permitedServices = array('SOC', 'OIT');
        if (!in_array($servicio, $permitedServices))
        {
            return -1;
        }
        //Añadimos la funcion mysql date
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');
        $q = $this->getHeaderQuery();
        if ($servicio == 'SOC')
        {
            $q .= $this->getGenericBuzonCondition();
        }
        elseif ($servicio == 'OIT')
        {
            $q .= $this->getGenericOitBuzonCondition();
        }
        $timeCond = $this->getTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        if ($servicio == 'OIT')
        {
            $q .= $this->getOitTituloCondition();
        }
        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $q .= $this->getGroupBy();
//        print_r($q); //Imprimir query que obtiene resultados
        $query = $this->getEntityManager()->createQuery($q);

        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }
        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * a)Se obtienen todos los tickets totales tratadas en N1
     * 
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @param string $servicio
     * @return \Doctrine\Orm\ORMException|Array|int
     */
    public function getNewTotalesTratadasN1($startdate, $enddate, $resolucion = 0, $usuarios = null, $servicio = 'SOC')
    {
        $permitedServices = array('SOC', 'OIT');
        if (!in_array($servicio, $permitedServices))
        {
            return -1;
        }
        //Añadimos la funcion mysql date
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');
        $q = $this->getNewHeaderQuery();
        if ($servicio == 'SOC')
        {
            $q .= $this->getGenericBuzonCondition();
        }
        elseif ($servicio == 'OIT')
        {
            $q .= $this->getGenericOitBuzonCondition();
        }
        $timeCond = $this->getNewTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        if ($servicio == 'OIT')
        {
            $q .= $this->getOitTituloCondition();
        }
        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $q .= $this->getOtherGroupBy();
//        print_r($q); //Imprimir query que obtiene resultados
        $query = $this->getEntityManager()->createQuery($q);

        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }
        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * b)Se obtienen todos los tickets cerrados en N1
     * 
     * @param type $startdate
     * @param type $enddate
     * @param type $resolucion
     * @param type $usuarios
     * @return type
     */
    public function getCerradasN1($startdate, $enddate, $resolucion = 0, $usuarios = null, $servicio = 'SOC')
    {
        $permitedServices = array('SOC', 'OIT');
        if (!in_array($servicio, $permitedServices))
        {
            return -1;
        }
        return $this->getSubConjuntoCerradas('soc seguridad', $startdate, $enddate, $resolucion, $usuarios, $servicio);
    }

    /**
     * b)Se obtienen todos los tickets cerrados en N1
     * 
     * @param type $startdate
     * @param type $enddate
     * @param type $resolucion
     * @param type $usuarios
     * @return type
     */
    public function getNewCerradasN1($startdate, $enddate, $resolucion = 0, $usuarios = null, $servicio = 'SOC')
    {
        $permitedServices = array('SOC', 'OIT');
        if (!in_array($servicio, $permitedServices))
        {
            return -1;
        }
        //Añadimos la funcion mysql date
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');
        $q = $this->getHeaderQuery();
        if ($servicio == 'SOC')
        {
            $q .= $this->getGenericBuzonCondition('soc seguridad', true, false);
        }
        elseif ($servicio == 'OIT')
        {
            $q .= $this->getGenericOitBuzonCondition();
        }
        $timeCond = $this->getNewTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        if ($servicio == 'OIT')
        {
            $q .= $this->getOitTituloCondition();
        }
        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $q .= $this->getOtherGroupBy();
//        print_r($q); //Imprimir query que obtiene resultados
        $query = $this->getEntityManager()->createQuery($q);

        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }
        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * a)Se obtienen todos los tickets totales tratadas en N1
     * 
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @param string $servicio
     * @return \Doctrine\Orm\ORMException|Array|int
     */
    public function getTrasferidasN2($startdate, $enddate, $resolucion = 0, $usuarios = null, $servicio = 'SOC')
    {
        $permitedServices = array('SOC', 'OIT');
        if (!in_array($servicio, $permitedServices))
        {
            return -1;
        }
        //Añadimos la funcion mysql date
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');
        $q = $this->getNewHeaderQuery();
        if ($servicio == 'SOC')
        {
            $q .= $this->getGenericBuzonCondition('soc seguridad', false);
        }
        elseif ($servicio == 'OIT')
        {
            $q .= $this->getGenericOitBuzonCondition();
        }
        $timeCond = $this->getNewTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        if ($servicio == 'OIT')
        {
            $q .= $this->getOitTituloCondition();
        }
        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $q .= $this->getOtherGroupBy();
//        print_r($q); //Imprimir query que obtiene resultados
        $query = $this->getEntityManager()->createQuery($q);

        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }
        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * Query generica para recuperar subconjuntos de cerradas en buzon
     * 
     * @param type $buzon
     * @param type $startdate
     * @param type $enddate
     * @param type $resolucion
     * @param type $usuarios
     * @return \Doctrine\Orm\ORMException
     */
    public function getSubConjuntoCerradas($buzon, $startdate, $enddate, $resolucion = 0, $usuarios = null, $servicio = 'SOC')
    {
        $permitedServices = array('SOC', 'OIT');
        if (!in_array($servicio, $permitedServices))
        {
            return -1;
        }

        //Añadimos la funcion mysql date
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');
        $q = $this->getNewHeaderQuery();
        if ($servicio == 'SOC')
        {
            $q .= $this->getBuzonesCondition($buzon);
        }
        elseif ($servicio == 'OIT')
        {
            $q .= $this->getGenericOitCerradasCondition();
        }
        $timeCond = $this->getNewTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $q .= $this->getOtherGroupBy();
//        print_r($q); //Imprimir query que obtiene resultados
        $query = $this->getEntityManager()->createQuery($q);

        if (is_array($buzon) and count($buzon) > 0)
        {
            $query->setParameter('buzon', $buzon);
        }
        if (!is_null($usuarios) and is_array($usuarios) and count($usuarios) > 0)
        {
            $query->setParameter('usuarios', $usuarios);
        }

        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * d)Se obtienen todos los tickets con tipoCaso = 1
     * Utiliza la query de tratadas en N1 y añade la condicion tipoCaso = 1
     * 
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @return \Doctrine\Orm\ORMException|Array
     */
    public function getIncidencias($startdate, $enddate, $resolucion = 0, $usuarios = null, $servicio = 'SOC')
    {
        $permitedServices = array('SOC', 'OIT');
        if (!in_array($servicio, $permitedServices))
        {
            return -1;
        }

        //Añadimos la funcion mysql date
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');
        $q = $this->getNewHeaderQuery();
        if ($servicio == "SOC")
        {
            $q .= $this->getGenericBuzonCondition();
        }
        elseif ($servicio == "OIT")
        {
            $q .= $this->getGenericOitBuzonCondition();
        }

        $timeCond = $this->getNewTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        $q .= $this->getTipoCasoCondition();
        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $q .= $this->getOtherGroupBy();
//        print_r($q); //Imprimir query que obtiene resultados
        $query = $this->getEntityManager()->createQuery($q);

        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }

        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * e)Se obtienen todos los tickets tipocaso = 2 tipificacion != provision|proyecto 
     * 
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @return \Doctrine\Orm\ORMException|Array
     */
    public function getPeticion($startdate, $enddate, $resolucion = 0, $usuarios = null, $servicio = 'SOC')
    {
        $permitedServices = array('SOC', 'OIT');
        if (!in_array($servicio, $permitedServices))
        {
            return -1;
        }
        //Añadimos la funcion mysql date
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');
        $q = $this->getNewHeaderQuery();
        if ($servicio == "SOC")
        {
            $q .= $this->getGenericBuzonCondition();
        }
        elseif ($servicio == "OIT")
        {
            $q .= $this->getGenericOitBuzonCondition();
        }
        $timeCond = $this->getNewTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        if ($servicio == "SOC")
        {
            $q .= $this->getTipoCasoCondition(2);
            $q .= $this->getTipificationCondition();
        }
        elseif ($servicio == "OIT")
        {
            $q .= $this->getTipoCasoCondition(2);
        }
        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $q .= $this->getOtherGroupBy();
//        print_r($q); //Imprimir query que obtiene resultados
        $query = $this->getEntityManager()->createQuery($q);

        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }

        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * f)Se obtienen todos los tickets tipocaso = 2 tipificacion != provision|proyecto
     * 
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @return \Doctrine\Orm\ORMException|Array
     */
    public function getProvicion($startdate, $enddate, $resolucion = 0, $usuarios = null, $servicio = 'SOC')
    {
        //Añadimos la funcion mysql date
        if ($servicio == "OIT")
        {
            //hook for remove in oit view
            return $servicio;
        }
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');
        $q = $this->getNewHeaderQuery();
        $q .= $this->getGenericBuzonCondition();
        $timeCond = $this->getNewTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        $q .= $this->getTipoCasoCondition(2);
        $q .= $this->getTipificationCondition(false);

        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $q .= $this->getOtherGroupBy();
//        print_r($q); //Imprimir query que obtiene resultados
        $query = $this->getEntityManager()->createQuery($q);

        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }

        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * g)Se obtienen todos los tickets tipocaso = 3 | 4
     * 
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @return \Doctrine\Orm\ORMException|Array
     */
    public function getQuejaConsulta($startdate, $enddate, $resolucion = 0, $usuarios = null, $servicio = 'SOC')
    {
        $permitedServices = array('SOC', 'OIT');
        if (!in_array($servicio, $permitedServices))
        {
            return -1;
        }
        //Añadimos la funcion mysql date
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');
        $q = $this->getNewHeaderQuery();
        if ($servicio == "SOC")
        {
            $q .= $this->getGenericBuzonCondition();
        }
        elseif ($servicio == "OIT")
        {
            $q .= $this->getGenericOitBuzonCondition();
        }

        $timeCond = $this->getNewTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        $q .= $this->getQuejaCondition();
        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $q .= $this->getOtherGroupBy();
//        print_r($q); //Imprimir query que obtiene resultados
        $query = $this->getEntityManager()->createQuery($q);

        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }
        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * m)Media Tiempo N1
     * 
     * @param Array $array
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @return \Doctrine\Orm\ORMException|Array
     */
    public function getMediaTiempoN1($array, $startdate, $enddate, $resolucion = 0, $usuarios = null)
    {
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('TIMEDIFF', 'Pi2\Fractalia\DBAL\Functions\TimeDiff');
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');

        $q = "SELECT TIMEDIFF(n.fechaactualizacion, n.fechaapertura) AS diferencia "
            . "FROM Pi2\Fractalia\SGSDReportBundle\Entity\Notificacion n "
            . "WHERE "
            . "(n.tipoaccion LIKE 'cambiogrupo') "
            . "AND (n.grupoorigen LIKE 'soc seguridad' AND n.grupodestino NOT LIKE 'soc seguridad')  "
            . "AND (n.numerocaso IN (:numeros)) ";

        $timeCond = $this->getNewTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $having = "GROUP BY n.numerocaso ";
        $q .= $having;
        $query = $this->getEntityManager()->createQuery($q);
        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }
        $query->setParameter(':numeros', $array);
        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    public function getTimeNotifications($sd, $startdate, $enddate, $resoluciondate, $service = 'SOC SEGURIDAD')
    {

        $sql = "SELECT TipoAccion, Estado, GrupoOrigen, GrupoDestino, FechaActualizacion FROM Notificacion "
            . "WHERE NumeroCaso LIKE '{$sd}' "
            . "and (GrupoOrigen LIKE '{$service}' OR GrupoDestino LIKE '{$service}' ) ";
        if ($startdate == $enddate)
        {
            if (!$resoluciondate)
            {
                $sql .= "and (DATE(FechaActualizacion) = '$startdate' ) ";
            }
            else
            {
                $sql .= "and (DATE(FechaResolucion) = '$startdate' ) ";
            }
        }
        else
        {
            if (!$resoluciondate)
            {
                $sql .= "and ((DATE(FechaActualizacion) >= '$startdate' ) AND (DATE(FechaActualizacion) <= '$enddate' )) ";
            }
            else
            {
                $sql .= "and ((DATE(FechaResolucion) >= '$startdate' ) AND (DATE(FechaResolucion) <= '$enddate' )) ";
            }
            
        }
        $sql .= "UNION "
            . "SELECT TipoAccion, Estado, GrupoOrigen, GrupoDestino, FechaActualizacion FROM Rechazada "
            . "WHERE NumeroCaso LIKE '{$sd}' "
            . "and ( GrupoOrigen LIKE '{$service}' OR GrupoDestino LIKE '{$service}' ) ";

        if ($startdate == $enddate)
        {
            if (!$resoluciondate)
            {
                $sql .= "and (DATE(FechaActualizacion) = '$startdate' ) ";
            }
            else
            {
                $sql .= "and (DATE(FechaResolucion) = '$startdate' ) ";
            }
        }
        else
        {
            if (!$resoluciondate)
            {
                $sql .= "and ((DATE(FechaActualizacion) >= '$startdate' ) AND (DATE(FechaActualizacion) <= '$enddate' )) ";
            }
            else
            {
                $sql .= "and ((DATE(FechaResolucion) >= '$startdate' ) AND (DATE(FechaResolucion) <= '$enddate' )) ";
            }
            
        }
        $sql .= "ORDER BY FechaActualizacion ASC";




        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();

        try
        {
            return $stmt->fetchAll();
//            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * m)Media Tiempo N1 Oit
     * 
     * @param Array $array
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @return \Doctrine\Orm\ORMException|Array
     */
    public function getMediaTiempoN1Oit($array, $startdate, $enddate, $resolucion = 0, $usuarios = null)
    {
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('TIMEDIFF', 'Pi2\Fractalia\DBAL\Functions\TimeDiff');
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');

        $q = "SELECT TIMEDIFF(n.fechaactualizacion, n.fechaapertura) AS diferencia "
            . "FROM Pi2\Fractalia\SGSDReportBundle\Entity\Notificacion n "
            . "WHERE "
            . "(n.tipoaccion LIKE 'resolucion') "
            . "AND (n.grupoorigen LIKE 'sdnivel1' or n.grupoorigen LIKE 'sdnivel1 sistevoz' )  "
            . "AND (n.id IN (:numeros)) ";

        $timeCond = $this->getTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $having = "GROUP BY n.numerocaso, n.fechaactualizacion HAVING n.fechaactualizacion = MIN(n.fechaactualizacion) ";
        $q .= $having;
        $query = $this->getEntityManager()->createQuery($q);
        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }
        $query->setParameter(':numeros', $array);
        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * n)Media Tiempo Asistencia N1
     * 
     * @param Array $array
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @return \Doctrine\Orm\ORMException|Array
     */
    public function getTiempoAsistencia($array, $startdate, $enddate, $resolucion = 0, $usuarios = null)
    {
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('TIMEDIFF', 'Pi2\Fractalia\DBAL\Functions\TimeDiff');
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');

        $q = "SELECT TIMEDIFF(n.fecharesolucion,n.fechaapertura) AS diferencia "
            . "FROM Pi2\Fractalia\SGSDReportBundle\Entity\Notificacion n "
            . "WHERE "
            . "(n.tipoaccion LIKE 'resolucion' AND (n.estado LIKE 'resolved' or n.estado LIKE 'closed')) "
            . "AND (n.grupoorigen LIKE 'soc seguridad' OR n.grupodestino LIKE 'soc seguridad')  "
            . "AND (n.numerocaso IN (:numeros)) ";

        $timeCond = $this->getNewTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $having = "GROUP BY n.numerocaso, n.fecharesolucion HAVING n.fecharesolucion = MAX(n.fecharesolucion) ";
        $q .= $having;
        $query = $this->getEntityManager()->createQuery($q);
        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }
        $query->setParameter(':numeros', $array);
        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * n)Media Tiempo Asistencia N1
     * 
     * @param Array $array
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @return \Doctrine\Orm\ORMException|Array
     */
    public function getTiempoAsistenciaOit($array, $startdate, $enddate, $resolucion = 0, $usuarios = null)
    {
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('TIMEDIFF', 'Pi2\Fractalia\DBAL\Functions\TimeDiff');
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');

        $q = "SELECT TIMEDIFF(n.fecharesolucion,n.fechaapertura) AS diferencia "
            . "FROM Pi2\Fractalia\SGSDReportBundle\Entity\Notificacion n "
            . "WHERE "
            . "(n.tipoaccion LIKE 'resolucion' AND (n.estado LIKE 'resolved' or n.estado LIKE 'closed')) "
            . "AND (n.grupodestino LIKE 'sdnivel1' or n.grupodestino LIKE 'sdnivel1 sistevoz' )  "
            . "AND (n.id IN (:numeros)) ";

        $timeCond = $this->getTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $having = "GROUP BY n.numerocaso, n.fecharesolucion HAVING n.fecharesolucion = MAX(n.fecharesolucion) ";
        $q .= $having;
        $query = $this->getEntityManager()->createQuery($q);
        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }
        $query->setParameter(':numeros', $array);
        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * ñ)Veces tratada
     *  
     * @param Array $array
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @return \Doctrine\Orm\ORMException
     */
    public function getVecesTratada($startdate, $enddate, $resolucion = 0, $usuarios = null)
    {

        $q = "SELECT DISTINCT(COUNT(n.numerocaso)) AS counter "
            . "FROM Pi2\Fractalia\SGSDReportBundle\Entity\Notificacion n "
            . "WHERE "
            . "(n.tipoaccion LIKE 'asignacion') "
            . "AND (n.grupodestino LIKE 'SOC SEGURIDAD') ";

        $timeCond = $this->getNewTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $query = $this->getEntityManager()->createQuery($q);
        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }

        try
        {
            return $query->getSingleScalarResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * ñ)Veces tratada
     *  
     * @param Array $array
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @return \Doctrine\Orm\ORMException
     */
    public function getVecesTratadaOit($startdate, $enddate, $resolucion = 0, $usuarios = null)
    {

        $q = "SELECT COUNT(n.numerocaso) AS counter "
            . "FROM Pi2\Fractalia\SGSDReportBundle\Entity\Notificacion n "
            . "WHERE "
            . "(n.tipoaccion LIKE 'resolucion') "
            . "AND (n.grupodestino LIKE 'sdnivel1' or n.grupodestino LIKE 'sdnivel1 sistevoz' )  ";

        $timeCond = $this->getTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $query = $this->getEntityManager()->createQuery($q);
        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }

        try
        {
            return $query->getSingleScalarResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /*
     * Condiciones para reportes OIT
     */

    /**
     * añade la condicion del buzon SOC SEGURIDAD
     * 
     * @param string $buzonGenerico
     * @return string
     */
    protected function getGenericOitBuzonCondition($buzon1 = "sdnivel1", $buzon2 = "sdnivel1 sistvoz")
    {
        return "AND ((n.grupoorigen LIKE '{$buzon1}' OR n.grupodestino LIKE '{$buzon1}') OR (n.grupoorigen LIKE '{$buzon2}' OR n.grupodestino LIKE '{$buzon2}')) ";
    }

    /**
     * añade la condicion del buzon SOC SEGURIDAD
     * 
     * @param string $buzonGenerico
     * @return string
     */
    protected function getOitTituloCondition($alarma = "%#alarma#%", $in = false)
    {
        if (!$in)
        {
            return "AND (n.titulo NOT LIKE '{$alarma}') ";
        }
        else
        {
            return "AND (n.titulo LIKE '{$alarma}') ";
        }
    }

    /**
     * añade la condicion de ICM en el servicio OIT
     * 
     * @param string $buzonGenerico
     * @return string
     */
    protected function getICMTituloCondition($palabra1 = "%ICM%", $palabra2 = "%ICN%", $cond = "OR")
    {
        $permitedCond = array("OR", "AND");
        if (in_array($cond, $permitedCond))
        {
            return "AND((n.titulo LIKE '{$palabra1}') {$cond} (n.titulo LIKE '{$palabra2}') ) ";
        }
        return -1;
    }

    /**
     * añade la condicion del buzon SOC SEGURIDAD
     * 
     * @param string $buzonGenerico
     * @return string
     */
    protected function getGenericOitCerradasCondition($buzon1 = "sdnivel1", $buzon2 = "sdnivel1 sistvoz")
    {
        return "AND (((n.grupoorigen LIKE '{$buzon1}' OR n.grupodestino LIKE '{$buzon1}') OR (n.grupoorigen LIKE '{$buzon2}' OR n.grupodestino LIKE '{$buzon2}')) AND (n.grupodestino LIKE '{$buzon1}' OR n.grupodestino LIKE '{$buzon2}')) ";
    }

    /**
     * g)Se obtienen las Alarmas de OIT
     * 
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @return \Doctrine\Orm\ORMException|Array|int
     */
    public function getAlarma($startdate, $enddate, $resolucion = 0, $usuarios = null)
    {
        //Añadimos la funcion mysql date
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');
        $q = $this->getHeaderQuery();
        $q .= $this->getGenericOitBuzonCondition();
        $timeCond = $this->getTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        $q .= $this->getOitTituloCondition("%alarma%", true);

        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $q .= $this->getGroupBy();
//        print_r($q); //Imprimir query que obtiene resultados
        $query = $this->getEntityManager()->createQuery($q);

        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }
        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * h)Se obtienen las notificaciones del buzon Iberia de OIT
     * 
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @return \Doctrine\Orm\ORMException|Array|int
     */
    public function getIberia($startdate, $enddate, $resolucion = 0, $usuarios = null)
    {
        //Añadimos la funcion mysql date
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');
        $q = $this->getHeaderQuery();
        $q .= $this->getGenericOitBuzonCondition();
        $timeCond = $this->getTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        $q .= $this->getOitTituloCondition("%iberia%", true);

        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $q .= $this->getGroupBy();
//        print_r($q); //Imprimir query que obtiene resultados
        $query = $this->getEntityManager()->createQuery($q);

        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }
        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    /**
     * h)Se obtienen las notificaciones del buzon Iberia de OIT
     * 
     * @param string $startdate
     * @param string $enddate
     * @param int $resolucion
     * @param Array $usuarios
     * @return \Doctrine\Orm\ORMException|Array|int
     */
    public function getIcm($startdate, $enddate, $resolucion = 0, $usuarios = null)
    {
        //Añadimos la funcion mysql date
        $this->getEntityManager()->getConfiguration()->addCustomStringFunction('DATE', 'Pi2\Fractalia\DBAL\Functions\Date');
        $q = $this->getHeaderQuery();
        $q .= $this->getGenericOitBuzonCondition();
        $timeCond = $this->getTimeCondition($startdate, $enddate, $resolucion);
        if ($timeCond != -1 and is_string($timeCond))
        {
            $q .= $timeCond;
        }
        else
        {
            return -1;
        }
        $q .= $this->getICMTituloCondition();

        $usersCond = null;
        if (!is_null($usuarios) or count($usuarios) > 0)
        {
            $usersCond = $this->getUserCondition($usuarios);
            $q .= $usersCond;
        }
        $q .= $this->getGroupBy();
//        print_r($q); //Imprimir query que obtiene resultados
        $query = $this->getEntityManager()->createQuery($q);

        if ($usersCond != 0 and ! is_null($usersCond) and is_string($usersCond))
        {
            $query->setParameter(':usuarios', $usuarios);
        }
        try
        {
            return $query->getResult();
        }
        catch (\Doctrine\Orm\NoResultException $e)
        {
            return null;
        }
        catch (\Doctrine\Orm\ORMException $e)
        {
            return $e;
        }
    }

    protected function getDateTime($string)
    {
        if (is_null($string))
        {
            return null;
        }

        $format = "d/m/Y H:i:s";
        $dateTimeOb = new \DateTime();
        return $dateTimeOb->createFromFormat($format, $string);
    }

}
