<?php

namespace Pururin;

abstract class Crawler
{

	/**
	 * @var \Pururin\PururinCrawler
	 */
	private $ins;

	/**
	 * @param \Pururin\PururinCrawler
	 */
	public function __construct(PururinCrawler $mainInstance)
	{
		$this->ins = $mainInstance;
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
