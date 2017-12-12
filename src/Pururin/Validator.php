<?php

namespace Pururin;

class Validator
{
	public function __construct($url, $totalPage, $path)
	{
		$this->url = $url;
		$this->totalPage = $totalPage;
		$i = 0;
		while (file_exists($path."/".$i.".jpg")) {
			$this->offset = $i++;
		}
		if ($totalPage > $i) {
			$this->status = "pending";
		} else {
			$this->status = "success";
		}
	}

	public function run()
	{
		if ($this->status === "pending") {
			
		}
	}
}