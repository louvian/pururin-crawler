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
	private $pointer = 0;

	/**
	 * @var string
	 */
	private $binary;

	/**
	 * Action
	 */
	public function action()
	{
		if (! isset($this->ins->result['info']['Pages']) || $this->ins->result['info']['Pages'] <= $this->pointer) {
			$ch = new Curl("http://pururin.us/assets/images/data/".$this->ins->id."/".$this->pointer.".jpg");
			$ch->setOpt(
				[
					CURLOPT_REFERER => $this->ins->url
				]
			);
			$this->binary = $ch->exec();
			if ($ch->errno()) {
				throw new PururinException($ch->error(), 1);
			}
			return true;
		}
		return false;
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
// http://pururin.us/assets/images/data/