<?php
require 'vendor/autoload.php';

$Parser = new \macropage\xmltv\parser\parser();
$Parser->setFile($argv[1]);
$Parser->setTargetTimeZone('Europe/Berlin');
//$Parser->setChannelfilter('prosiebenmaxx.de'); //optional
$Parser->setIgnoreDescr('Keine Details verfügbar.'); //optional
try {
	$Parser->parse();
} catch (Exception $e) {
	throw new \RuntimeException($e);
}
/** @noinspection ForgottenDebugOutputInspection */
print_r($Parser->getEpgdata());