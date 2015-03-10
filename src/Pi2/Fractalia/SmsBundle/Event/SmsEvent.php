<?php

namespace Pi2\Fractalia\SmsBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Encapsulates sms operation result for events
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class SmsEvent extends Event
{
    private $result;
    private $id;

    public function GetResult()
    {
        return $this->result;
    }

    public function GetId()
    {
        return $this->id;
    }

    function __construct($result, $id)
    {
        $this->result = $result;
        $this->id = $id;
    }

}
