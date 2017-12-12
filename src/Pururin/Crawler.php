<?php

namespace Pururin;

use Pururin\PururinCrawler;

abstract class Crawler
{

	/**
	 * @var \Pururin\PururinCrawler
	 */
	protected $ins;

	/**
	 * @param \Pururin\PururinCrawler
	 */
	final public function __construct(PururinCrawler $mainInstance)
	{
		$this->ins = $mainInstance;
	}

	/**
	 * @param string $str
	 * @return string
	 */
	protected static function e($str)
	{
		return trim(html_entity_decode($str, ENT_QUOTES, 'UTF-8'));
	}

	/**
	 * @return bool
	 */
	abstract public function action();

	/**
	 * @return bool
	 */
	abstract public function build();

	/**
	 * @return mixed
	 */
	abstract public function get();
}
