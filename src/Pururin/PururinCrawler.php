<?php

namespace Pururin;

use Pururin\Crawlers\Cover;
use Pururin\Crawlers\Content;
use Pururin\Exceptions\PururinException;

/**
 * @author Louvian Lyndal <louvianlyndal@gmail.com>
 * @license MIT
 */
class PururinCrawler
{

	/**
	 * @var int
	 */
	public $id;

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
	 * @var array
	 */
	private $tmpContainer = [];

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
		$this->id = (int) $a[0];
		is_dir($data['save_directory']) or mkdir($data['save_directory']);
		$this->saveDir = $data['save_directory']."/".$this->id;
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
		while ($content = $this->getContent()) {
		}
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
				$handle = fopen($this->saveDir."/info.txt", "w");
				flock($handle, LOCK_EX);
				fwrite($handle, json_encode($data, JSON_UNESCAPED_SLASHES));
				fclose($handle);
				break;
			
			case 'content':
				if (! isset($data['number'], $data['binary'])) {
					throw new PururinException("Invalid content data", 1);
				}
				$handle = fopen($this->saveDir."/".$data['number'].".jpg", "w");
				flock($handle, LOCK_EX);
				fwrite($handle, $data['binary']);
				fclose($handle);
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
		$this->tmpContainer['content_crawler'] = new Content($this);
	}

	/**
	 * Get content
	 */
	private function getContent()
	{
		$action = $this->tmpContainer['content_crawler']->action();
		$this->tmpContainer['content_crawler']->build();
		$this->buildContext(
			$this->tmpContainer['content_crawler']->get(), 
			"content"
		);
		return $action;
	}
}
