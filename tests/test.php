<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

class TestClass
{
	use AuthorizationRequired\RequireAuthorization;

	public function __construct()
	{
		echo "Hello world!" . PHP_EOL;
	}

}

new TestClass;
