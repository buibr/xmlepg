<?php

namespace buibr\xmlepg;


use DateTimeZone;
use SimpleXMLElement;
use XMLReader;

class Parser {

	private $file;
	private $url;
	private $content;
	private $channels;
	private $epgdata;
	private $channelfilter = [];
	private $ignoreDescr = [];
	private $targetTimeZone;

	public function __construct() {
		$this->targetTimeZone = \date_default_timezone_get();
	}

	/**
	 * @throws \RuntimeException
	 * @throws \Exception
	 */
	public function parseFile(): void {

		if (!$this->file) {
			throw new \RuntimeException('missing file: please use setFile before parse');
		}

		if (!file_exists($this->file)) {
			throw new \RuntimeException('file does not exists: ' . $this->file);
		}

		$xml = new XMLReader();
		//compress.zlib://'
		$xml->open($this->file);

		/** @noinspection PhpStatementHasEmptyBodyInspection */
		/** @noinspection LoopWhichDoesNotLoopInspection */
		/** @noinspection MissingOrEmptyGroupStatementInspection */
		while ($xml->read() && $xml->name !== 'channel') {
		}

		while ($xml->name === 'channel') {
			$element = new SimpleXMLElement($xml->readOuterXML());

			/** @noinspection PhpUndefinedFieldInspection */
			$this->channels[(string)$element->attributes()->id] = (string)$element->{'display-name'};

			$xml->next('channel');
			unset($element);
		}

		$xml->close();
		$xml->open($this->file);

		/** @noinspection PhpStatementHasEmptyBodyInspection */
		/** @noinspection LoopWhichDoesNotLoopInspection */
		/** @noinspection MissingOrEmptyGroupStatementInspection */
		while ($xml->read() && $xml->name !== 'programme') 
		{
		}

		while ($xml->name === 'programme') 
		{
			$element = new SimpleXMLElement($xml->readOuterXML());

			/** @noinspection PhpUndefinedFieldInspection */
			if ( !\count($this->channelfilter) || (\count($this->channelfilter) && $this->channelMatchFilter((string)$element->attributes()->channel))) 
			{

				/** @noinspection 	PhpUndefinedFieldInspection */
				$startString		= $this->parseDate( (string)$element->attributes()->start );
				
				/** @noinspection	PhpUndefinedFieldInspection */
				$stopString			= $this->parseDate( (string)$element->attributes()->stop );

				/** @noinspection PhpUndefinedFieldInspection */
				$this->epgdata[(string)$element->attributes()->channel . ' ' . $startString] = [
					'start'       => $startString,
					'start_raw'   => (string)$element->attributes()->start,
					'channel'     => (string)$element->attributes()->channel,
					'stop'        => $stopString,
					'title'       => (string)$element->title,
					'sub-title'   => (string)$element->{'sub-title'},
					'desc'        => $this->filterDescr((string)$element->desc),
					'date'        => (int)(string)$element->date,
					'country'     => (string)$element->country,
					'episode-num' => (string)$element->{'episode-num'},
				];

			}

			$xml->next('programme');
			unset($element);
		}

		$xml->close();

	}

	/**
	 * @throws \RuntimeException
	 * @throws \Exception
	 */
	public function parseUrl(): void {

		if (!$this->url) {
			throw new \RuntimeException('Url missing: please use setUrl before parseUrl');
		}

		if (!filter_var($this->url, FILTER_VALIDATE_URL)) {
			throw new \RuntimeException('Url invalid: ' . $this->url);
		}

		$xml = new XMLReader();

		//compress.zlib://'
		$xml->open($this->url);

		/** @noinspection PhpStatementHasEmptyBodyInspection */
		/** @noinspection LoopWhichDoesNotLoopInspection */
		/** @noinspection MissingOrEmptyGroupStatementInspection */
		while ($xml->read() && $xml->name !== 'channel') {
		}

		while ($xml->name === 'channel') {
			$element = new SimpleXMLElement($xml->readOuterXML());

			/** @noinspection PhpUndefinedFieldInspection */
			$this->channels[(string)$element->attributes()->id] = (string)$element->{'display-name'};

			$xml->next('channel');
			unset($element);
		}

		$xml->close();
		$xml->open($this->url);

		/** @noinspection PhpStatementHasEmptyBodyInspection */
		/** @noinspection LoopWhichDoesNotLoopInspection */
		/** @noinspection MissingOrEmptyGroupStatementInspection */
		while ($xml->read() && $xml->name !== 'programme') {
		}

		while ($xml->name === 'programme') {
			$element = new SimpleXMLElement($xml->readOuterXML());

			/** @noinspection PhpUndefinedFieldInspection */
			if ( !\count($this->channelfilter) || (\count($this->channelfilter) && $this->channelMatchFilter((string)$element->attributes()->channel))) 
			{
				/** @noinspection 	PhpUndefinedFieldInspection */
				$startString		= $this->parseDate( (string)$element->attributes()->start );
				
				/** @noinspection	PhpUndefinedFieldInspection */
				$stopString			= $this->parseDate( (string)$element->attributes()->stop );


				/** @noinspection PhpUndefinedFieldInspection */
				$this->epgdata[(string)$element->attributes()->channel . ' ' . $startString] = [
					'start'       => $startString,
					'start_raw'   => (string)$element->attributes()->start,
					'channel'     => (string)$element->attributes()->channel,
					'stop'        => $stopString,
					'title'       => (string)$element->title,
					'sub-title'   => (string)$element->{'sub-title'},
					'desc'        => $this->filterDescr((string)$element->desc),
					'date'        => (int)(string)$element->date,
					'country'     => (string)$element->country,
					'episode-num' => (string)$element->{'episode-num'},
				];

			}

			$xml->next('programme');
			unset($element);
		}

		$xml->close();

	}

	/**
	 * @param mixed $file`
	 */
	public function setFile($file): void {
		$this->file = $file;
	}

	/**
	 * @param mixed $url - url 
	 */
	public function setUrl($url): void {
		$this->url = $url;
	}

	/**
	 * 
	 */
	public function parseDate( string $date ){

		try
		{
			$dt		= \DateTime::createFromFormat('YmdHis P', $date,new DateTimeZone('UTC'));
			$dt->setTimezone( new DateTimeZone($this->targetTimeZone) );
			return	$dt->format('Y-m-d H:i:s');
		}
		catch( \Exception $e ){}
		catch( \Error $e ){}

		try
		{
			$ex = explode(' ', $date);
			$sd = $ex[0];
			$ed = $ex[1];
			
			if(strlen($sd) == 13) {
				$sd = "{$sd}0";
			}
			
			$date = $sd." ".$ed;

			$dt		= \DateTime::createFromFormat('YmdHis P', $date,new DateTimeZone('UTC'));
			$dt->setTimezone( new DateTimeZone($this->targetTimeZone) );
			return	$dt->format('Y-m-d H:i:s');
		}
		catch( \Exception $e ){}
		catch( \Error $e ){}

		
		return null;
	}

	/**
	 * @param $descr
	 *
	 * @return string
	 */
	private function filterDescr($descr): string {
		if (array_key_exists($descr,$this->ignoreDescr)) {
			return '';
		}
		return $descr;
	}

	/**
	 * @return mixed
	 */
	public function getChannels() {
		return $this->channels;
	}

	/**
	 * @return array
	 */
	public function getEpgdata() {
		return $this->epgdata;
	}

	/**
	 * @param mixed $channelfilter
	 */
	public function setChannelfilter($channelfilter): void {
		$this->channelfilter[$channelfilter] = 1;
	}

	/**
	 * 
	 */
	public function resetChannelfilter(): void {
		$this->channelfilter = [];
	}

	/**
	 * 
	 */
	private function channelMatchFilter(string $channel): bool {
		return array_key_exists($channel, $this->channelfilter);
	}

	/**
	 * @param string $descr
	 */
	public function setIgnoreDescr(string $descr): void {
		$this->ignoreDescr[$descr]=1;
	}

	/**
	 * @param mixed $targetTimeZone
	 */
	public function setTargetTimeZone($targetTimeZone): void {
		$this->targetTimeZone = $targetTimeZone;
	}


}