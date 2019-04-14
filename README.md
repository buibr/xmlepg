# xmlepg
Parse XMLTV Epg from file or url.

```php
<?php
require 'vendor/autoload.php';

$Parser = new \buibr\xmlepg\Parser();
$Parser->setFile($argv[1]);
$Parser->setTargetTimeZone('Europe/Berlin');
//$Parser->setChannelfilter('prosiebenmaxx.de'); //optional
$Parser->setIgnoreDescr('Keine Details verfügbar.'); //optional

try {
	$Parser->parseFile();
} catch (Exception $e) {
	throw new \RuntimeException($e);
}
/** @noinspection ForgottenDebugOutputInspection */
print_r($Parser->getEpgdata());
```

OR

```php
<?php
require 'vendor/autoload.php';

$epg = new \buibr\xmlepg\Parser();
$epg->setUrl($url);
$epg->setTargetTimeZone('Europe/Berlin');

try {
	$epg->parseUrl();
} catch (Exception $e) {
	throw new \RuntimeException($e);
}
/** @noinspection ForgottenDebugOutputInspection */
print_r($epg->getEpgdata());
```

Example call: `parse.php xml/sample.xml`
