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
	 * @var string
	 */
	public $url;

	/**
	 * @var string
	 */
	private $saveDir;

	/**
	 * @var array
	 */
	private $result = [];

	/**
	 * @throws \Pururin\Exceptions\PururinException
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
		$this->saveDir = $data['save_directory']."/".$this->result['id'];
		is_dir($this->saveDir) or mkdir($this->saveDir);
		if (! is_dir($this->saveDir)) {
			throw new PururinException("Cannot create directory", 1);
		}
		if (! is_writable($this->saveDir)) {
			throw new PururinException("Save directory is not writeable", 1);	
		}
		$this->url = $data['manga_url'];
	}

	/**
	 * Run app.
	 */
	public function run()
	{
		$this->getCover();
		$this->getContent();
	}

	/**
	 *
	 * Build result context.
	 *
	 * @param $data    any
	 * @param $content string
	 * @throws \Pururin\Exceptions\PururinException
	 */
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
