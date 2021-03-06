<?php

namespace Pururin\Crawlers;

use Curl\Curl;
use Pururin\Crawler;
use Pururin\Exceptions\PururinException;

class Content extends Crawler
{	
	/**
	 * @var array
	 */
	private $result = [];

	/**
	 * @var int
	 */
	private $pointer = 1;

	/**
	 * @var string
	 */
	private $binary;

	/**
	 * Action
	 */
	public function action()
	{
		if (! isset($this->ins->result['info']['Pages']) || $this->pointer <= $this->ins->result['info']['Pages']) {
			$ch = new Curl("http://pururin.us/assets/images/data/".$this->ins->id."/".$this->pointer.".jpg");
			$ch->setOpt(
				[
					CURLOPT_REFERER => $this->ins->url,
					CURLOPT_CONNECTTIMEOUT => 1000,
					CURLOPT_TIMEOUT => 1000
				]
			);
			$this->binary = $ch->exec();
			if ($ch->errno()) {
				$ex = new PururinException($ch->error(), 1);
				$ex->pointer($this->pointer);
				throw $ex;
			}
			$info = $ch->info();
			if ($info['http_code'] !== 200) {
				return false;
			}
			return true;
		}
		return false;
	}

	public function setOffsetPoint($offset)
	{
		$this->pointer = $offset;
	}

	public function build()
	{
	}

	public function get()
	{
		return [
			"number" => $this->pointer++,
			"binary" => $this->binary
		];
	}
}
