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
		if (! isset($data['save_directory'], $data['manga_url'])) {
			throw new PururinException("Invalid construct data", 1);
		}
		is_dir($data['save_directory']) or mkdir($data['save_directory']);
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
