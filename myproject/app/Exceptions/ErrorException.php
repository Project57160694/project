<?php

namespace App\Exceptions;

use Exception;

class ErrorException extends Exception
{
	private $extra = null;

	public function setExtraParameter($extra){
		$this->extra = $extra;
	}

	public function getExtraParameter(){
		return $this->extra;
	}

	public function hasExtraParameter(){
		if (empty($this->extra)) {
			return false;
		}
		return true;
	}

}
