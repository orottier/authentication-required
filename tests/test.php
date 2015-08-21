<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

class TestClass
{
	use AuthorizationRequired\AuthorizationRequired;

	public function __construct()
	{
		echo "Hello world!" . PHP_EOL;
	}

}

$a = new TestClass;
