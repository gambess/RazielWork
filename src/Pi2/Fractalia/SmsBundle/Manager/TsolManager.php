<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Manager;

use Pi2\Fractalia\SmsBundle\Entity\Nombretsol;

/**
 * Description of TsolManager
 *
 * @author gambess
 */
class TsolManager {

    private $em;
    private $tsolConf;
    private $tsolArray = array();
    private $tsolLoaded = false;

    public function __construct($em = null) {
        if (!is_null($em)) {
            $this->em = $em;
        } else {
            $doctrine = $this->getService('doctrine');
            $this->em = $doctrine->getManager();
        }
        $this->setTsolFromConf($this->getParameter('fractalia_sms.envio_sms.tsol_guardia'));

        $tsol = $this->em->getRepository('FractaliaSmsBundle:Nombretsol')->getTsol();
        if ($tsol instanceof Nombretsol) {
            $this->setLoadedFlag(true);
        }
        if (is_null($tsol)) {
            $this->copiarTsol();
        }
    }

    protected function setLoadedFlag($flag) {
        $this->tsolLoaded = $flag;
    }

    public function copiarTsol() {
        $now = (new \DateTime('NOW'));
        if (!$this->estaTsolInicializado()) {
            $tsolObj = new Nombretsol();
            $tsolObj->setNombre($this->tsolArray['nombre']);
            $tsolObj->setFechaModificacion($now);
            $this->em->persist($tsolObj);
            $this->em->flush();
            $this->setLoadedFlag(true);
        }
    }

    public function setTsolFromConf($tsolConf) {
        $this->tsolConf = $tsolConf;
    }

    public function getTsolConf() {
        $this->tsolConf;
    }

    public function estaTsolInicializado() {
        return $this->tsolLoaded;
    }

    public function setTsolDb() {
        if ($this->estaTsolInicializado()) {
            $tsolObj = $this->em->getRepository('FractaliaSmsBundle:Nombretsol')->getTsol();
            $this->tsolArray['nombre'] = $tsolObj->getNombre();
        }
    }

    public function getTsolDb() {
        if ($this->estaTsolInicializado()) {
            return $this->tsolArray;
        }
    }

//******************
    /*
     * Globales
     */

    private function getService($name) {
        return $GLOBALS['kernel']->getContainer()->get($name);
    }

    private function getParameter($name) {
        return $GLOBALS['kernel']->getContainer()->getParameter($name);
    }

}
?>
