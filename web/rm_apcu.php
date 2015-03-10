<?php
require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';
$cacheDriver = new \Doctrine\Common\Cache\ApcCache();
$cacheDriver->deleteAll();
