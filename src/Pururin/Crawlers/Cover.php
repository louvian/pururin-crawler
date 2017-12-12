<?php

namespace Pururin\Crawlers;

use Curl\Curl;
use Pururin\Crawler;
use Pururin\Exceptions\PururinException;

/**
 * @author Louvian Lyndal <louvianlyndal@gmail.com>
 * @license MIT
 * @package Pururin\Crawlers
 */
class Cover extends Crawler
{

	/**
	 * @var string
	 */
	private $raw;

	/**
	 * @var array
	 */
	private $info = [];

	/**
	 * Action.
	 * @throws \Pururin\Exceptions\PururinException
	 * @return bool
	 */
	public function action()
	{
		$ch = new Curl($this->ins->url);
		$ch->setOpt(
			[
				CURLOPT_FOLLOWLOCATION => true
			]
		);
		$out = $ch->exec();
		if ($ch->errno()) {
			throw new PururinException($ch->error(), 1);
		}
		$this->raw = $out;
		return true;
	}

	/**
	 * Build cover information.
	 * @throws \Pururin\Exceptions\PururinException
	 * @return bool
	 */
	public function build()
	{
		$a = explode("<span class=\"info\">", $this->raw, 2);
		if (isset($a[1])) {
			/**
			 * Get title.
			 */
			$b = explode("<div class=\"title\">", $a[1], 2);
			$b = explode("</div>", $b[1], 2);
			$this->info['title'] = static::e($b[0]);

			/**
			 * Table adaptation.
			 */
			$a = explode("<tbody>", $a[1], 2);
			$a = explode("</tbody>", $a[1], 2);

			/**
			 * Build info.
			 */
			$b = explode("<tr", $a[0]);
			unset($b[0]);
			if (count($b) > 1) {
				foreach ($b as $val) {
					$c = explode("<td>", $val, 2);
					if (isset($c[1])) {
						$c = explode("</td>", $c[1], 2);
						$d = explode("<ul class=\"list-inline gallery-info-list\">", $c[1], 2);
						if (! isset($d[1])) {
							$d = explode("<td>", $c[1], 2);
							$d = explode("</td>", $d[1], 2);
							$c[0] = static::e($c[0]);
							$this->info[$c[0]] = $c[0] === "Pages" ? (int) $d[0] : static::e(strip_tags($d[0]));
						} else {
							$d = explode("</ul>", $d[1], 2);
							$d = explode("<li", $d[0]);
							unset($d[0]);
							foreach ($d as $vax) {
								$vax = explode("<a href=\"", $vax);
								$vax = explode(">", $vax[1], 2);
								$vax = explode("<", $vax[1], 2);
								$this->info[static::e($c[0])][] = static::e($vax[0]);
							}
						}
					}
				}
			} else {
				throw new PururinException("Error Building Cover Info", 1);
			}
		}
		return true;
	}

	/**
	 * Get cover information.
	 *
	 * @return array
	 */
	public function get()
	{
		return $this->info;
	}
}
