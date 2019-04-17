<?php
require 'vendor/autoload.php';


$cmd = "curl '{$argv[1]}' \
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

$Parser = new \buibr\xmlepg\EpgParser();
$Parser->setContent( $output );

try 
{
	$Parser->parseContent();
} 
catch (Exception $e) 
{
	throw new \RuntimeException($e);
}

/** @noinspection ForgottenDebugOutputInspection */
print_r($Parser->getEpgdata());