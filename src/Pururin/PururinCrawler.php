<?php

namespace Pururin;

use Pururin\Crawlers\Cover;

/**
 * @author Louvian Lyndal <louvianlyndal@gmail.com>
 * @license MIT
 */
class PururinCrawler
{

	/**
	 * @var array
	 */
	private $data = [];

	/**
	 * @var array
	 */
	private $result = [];

	/**
	 *
	 * @param $data array
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * Run app.
	 */
	public function run()
	{
		$this->getCover();
	}

	private function buildContext($data, $context)
	{
		switch ($context) {
			case 'cover':
				
				break;
			
			default:
				# code...
				break;
		}
	}

	/**
	 * Get gallery cover.
	 */
	private function getCover()
	{
		$get = new Cover($this);
		$get->action();
		$get->build();
		$this->buildContext($get->get(), "cover");
	}
}
