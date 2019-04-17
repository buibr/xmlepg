# xmlepg
Parse XMLTV Epg from file or url.

```php
<?php
require 'vendor/autoload.php';

$Parser = new \buibr\xmlepg\EpgParser();
$Parser->setFile($argv[1]);
$Parser->setTargetTimeZone('Europe/Skopje');
// $Parser->setChannelfilter('prosiebenmaxx.de'); //optional
// $Parser->setIgnoreDescr('Keine Details verfügbar.'); //optional

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
$epg->setTargetTimeZone('Europe/Skopje');
$epg->setProgrammGroup('@id'); // group programms by index.

try {
	$epg->parseUrl();
} catch (Exception $e) {
	throw new \RuntimeException($e);
}
/** @noinspection ForgottenDebugOutputInspection */
print_r($epg->getEpgdata());
```

OR

```php
<?php
require 'vendor/autoload.php';

$cmd = "curl '{$url}' \
	-H 'Connection: keep-alive' \
	-H 'Pragma: no-cache' \
	-H 'Cache-Control: no-cache' \
	-H 'Upgrade-Insecure-Requests: 1' \
	-H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36' \
	-H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8' \
	-H 'Accept-Encoding: gzip, deflate' \
	-H 'Accept-Language: en-US,en;q=0.9' \
	-H 'Cookie: _ga=GA1.2.1889888653.1552387822; _gid=GA1.2.1207299097.1552387822' --compressed ";
$output = shell_exec($cmd);

try 
{
	$Parser = new \buibr\xmlepg\EpgParser();
	$Parser->setContent( $output );
	$Parser->parseContent();
} 
catch (Exception $e) 
{
	throw new \RuntimeException($e);
}

print_r($Parser->getEpgdata());
```

Example file:
```
php -f testFile.php xml/sample.xml
```

Example url:
```
php -f testUrl.php http://tvprofil.net/xmltv/data/top-channel.al/2019-04-15_top-channel.al_tvprofil.net.xml
```

Example content:
```
php -f testContent.php http://tvprofil.net/xmltv/data/top-channel.al/2019-04-15_top-channel.al_tvprofil.net.xml
```
