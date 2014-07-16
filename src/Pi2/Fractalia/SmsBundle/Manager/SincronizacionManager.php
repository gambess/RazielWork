<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Manager;

use Pi2\Fractalia\SmsBundle\Entity\Nombretsol;

/**
 * Description of SynManager
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class SincronizacionManager
{
    private $tsolSync = false;
    private $nombreCortoSync = false;
    private $em;
    private $tsolArray = array();
    private $NombreCortoArray = array();

    public function __construct($em = null)
    {
        if (is_null($em))
        {
            $doctrine = $this->getService('doctrine');
            $this->em = $doctrine->getManager();
        }
        else
        {
            $this->em = $em;
        }
    }

    //******************
    /*
     * tsol sync
     */
    public function estaTsolSincronizado()
    {
        return $this->tsolSync;
    }

    public function sincronizarTsol()
    {
        $now = (new \DateTime('NOW'));
        $configuracion = $this->getService('fractalia_sms.configuracion_manager');

        if (!$this->estaTsolSincronizado())
        {
                        echo "entro3";

            $entities = $this->em->getRepository('FractaliaSmsBundle:Nombretsol')->findAll();
            if (is_null($entities) or count($entities) == 0)
            {
                            echo "entro a guardar";

                $tsolObj = new Nombretsol();
                $tsolObj->setNombre($configuracion->getTsolGuardia()['nombre']);
                $tsolObj->setFechaModificacion($now);
                print_r($tsolObj);
                $this->em->persist($tsolObj);
                $this->em->flush();
                echo "despues de guardar debe tener id";
                print_r($tsolObj);

                $this->tsolSync = true;
            }
            elseif (count($entities) == 1 and $this->tsolSync == false)
            {
                            echo "aca no debo entrar";

                $this->tsolSync = true;
            }
            elseif (count($entities) > 1)
            {
                //ERROR;
            }
        }
    }

    public function setTsolDb()
    {
        if (!$this->estaTsolSincronizado())
        {
                        echo "entro2";

            $this->sincronizarTsol();
        }
        $tsolObj = $this->em->getRepository('FractaliaSmsBundle:Nombretsol')->getTsol();
        if ($tsolObj instanceof Nombretsol and isset($tsolObj))
        {
                        echo "entro";

            $this->tsolArray['nombre'] = $tsolObj->getNombre();
        }
        else
        {
            //ERROR
        }
    }

    public function getTsolDb()
    {
        if (!$this->estaTsolSincronizado())
        {
            echo "entro1";
            $this->setTsolDb(); 
        }
        return $this->tsolArray;
    }

    //******************
    /*
     * Nombre corto sync
     */

    private function getService($name)
    {
        return $GLOBALS['kernel']->getContainer()->get($name);
    }

}
