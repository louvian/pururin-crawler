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
	public $data = [];

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
		if (substr($data['manga_url'], 0, 26) !== "http://pururin.us/gallery/") {
			throw new PururinException("Invalid gallery url, the gallery url must be start with \"http://pururin.us/gallery/\"", 1);
		}

		$a = explode("http://pururin.us/gallery/", $data['manga_url'], 2);
		if (! isset($a[1])) {
			throw new PururinException("Invalid gallery url", 1);
		}
		$a = explode("/", $a[1]);
		$this->result['id'] = (int) $a[0];
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
					$this->result['info'] = $data;
				break;
			
			case 'content':

				break;
			default:
				throw new PururinException("Unknown context", 1);
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
