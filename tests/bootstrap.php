<?php

/**
 * This file is part of the AlesWita\Components\DropzoneUploader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

if (@!include __DIR__ . "/../vendor/autoload.php") {
	echo "Install Nette Tester using `composer install`";
	exit(1);
}


Tester\Environment::setup();

define("TEMP_DIR", __DIR__ . "/tmp/" . lcg_value());
@mkdir(dirname(TEMP_DIR));
@mkdir(TEMP_DIR);
