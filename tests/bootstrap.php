<?php

include __DIR__.'/../vendor/autoload.php';

$tempDir = __DIR__.'/temp/'.getmypid();
define('TEMP_DIR', $tempDir);
Nette\Utils\FileSystem::delete($tempDir);
mkdir($tempDir, 0777, true);
