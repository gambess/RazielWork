<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Sms;

/**
 * Description of SMSManager
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class SmsManager
{
    private $msg;
    private $send;

    public function __construct($msg, $sender)
    {
        $this->msg = $msg;
        $this->send = $sender;
    }

    public function write()
    {
        return $this->send->escribe();
    }

}
