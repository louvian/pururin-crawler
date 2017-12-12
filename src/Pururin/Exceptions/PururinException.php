<?php

namespace Pururin\Exceptions;

class PururinException extends \Exception
{
	private $pointer;

	/**
	 * @param int $point
	 */
	public function pointer($point)
	{
		$this->pointer = $point;
	}

	public function getPoint()
	{
		return $this->pointer;
	}
}
