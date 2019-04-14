# xmlepg
Parse XMLTV Epg from file or url.

```php
<?php
require 'vendor/autoload.php';

$Parser = new \buibr\xmlepg\EpgParser();
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

$epg = new \buibr\xmlepg\EpgParser();
$epg->setUrl($url);
$epg->setTargetTimeZone('Europe/Berlin');
$epg->setProgrammGroup('@id'); // group programms by index.

try {
	$epg->parseUrl();
} catch (Exception $e) {
	throw new \RuntimeException($e);
}
/** @noinspection ForgottenDebugOutputInspection */
print_r($epg->getEpgdata());
```

Example file:
```
php -f testFile.php xml/sample.xml
```

Example url:
```
php -f testUrl.php http://tvprofil.net/xmltv/data/top-channel.al/2019-04-15_top-channel.al_tvprofil.net.xml
```
