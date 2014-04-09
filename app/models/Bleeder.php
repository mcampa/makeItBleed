<?php

use bleed\bleed;
use bleed\Payload;

use Illuminate\Support\Contracts\JsonableInterface;

class Bleeder extends bleed implements JsonableInterface
{
	public $data = '';
	public $message = '';

	public function check()
	{
		ob_start();

		$this->run();

		$this->data = ob_get_clean();
	}


	public function toArray()
	{		
		return array(
			'results' => utf8_encode($this->data),
			'message' => $this->message,
		);
	}

	public function toJson($option = 0)
	{		
		return json_encode($this->toArray());
	}

}