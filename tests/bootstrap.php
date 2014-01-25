<?php

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
/** @var $loader \Composer\Autoload\ClassLoader */
$loader->add('Koriym\FusionCache', __DIR__);
$loader->add('Doctrine\Tests', dirname(__DIR__) . '/vendor/doctrine/cache/tests');
