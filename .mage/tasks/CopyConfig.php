<?php
namespace Task;

use Mage\Task\AbstractTask;

class CopyConfig extends AbstractTask
{
    public function getName()
    {
        return 'Copying configuration';
    }

    public function run()
    {
        $command = 'cp /root/sgsd_config/parameters.yml /var/www/sgsd/current/app/config/parameters.yml';
        $result = $this->runCommandRemote($command);

        return $result;
    }
}