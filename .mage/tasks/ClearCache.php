<?php

namespace Task;

use Mage\Task\AbstractTask;

class ClearCache extends AbstractTask
{
    public function getName()
    {
        return 'Clear cache';
    }

    public function run()
    {
        $command = "php app/console cache:clear";
        $result = $this->runCommandRemote($command);

        return $result;
    }
}