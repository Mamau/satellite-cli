#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';

use Mamau\Wkit\Commands\BowerCommand;
use Mamau\Wkit\Commands\ComposerCommand;
use Mamau\Wkit\Commands\GulpCommand;
use Mamau\Wkit\Commands\YarnCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new GulpCommand());
$application->add(new ComposerCommand());
$application->add(new BowerCommand());
$application->add(new YarnCommand());

$application->run();
