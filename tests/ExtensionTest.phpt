<?php

/**
 * This file is part of the AlesWita\Components\DropzoneUploader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 *
 * @phpVersion 7.1.0
 */

declare(strict_types=1);

namespace AlesWita\Components\DropzoneUploader\Tests\Tests;

use AlesWita;
use Nette;
use Tester;

require_once __DIR__ . "/../bootstrap.php";


/**
 * @author Ales Wita
 * @license MIT
 */
final class ExtensionTest extends Tester\TestCase
{
	/**
	 * @return void
	 */
	public function testOne(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/extensionTestOne.neon");

		$container = $configurator->createContainer();
		$service = $container->getService("dropzoneuploader.dropzoneuploader");

		Tester\Assert::same(100, $service->getSettings()["maxFilesize"]);
	}

	/**
	 * @return void
	 */
	public function testTwo(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/extensionTestTwo.neon");

		$container = $configurator->createContainer();

		$container = $configurator->createContainer();
		$service = $container->getService("dropzoneuploader.dropzoneuploader");

		Tester\Assert::same(102400, $service->getSettings()["maxFilesize"]);
	}

	/**
	 * @return void
	 */
	public function testThree(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/extensionTestThree.neon");

		$container = $configurator->createContainer();

		$container = $configurator->createContainer();
		$service = $container->getService("dropzoneuploader.dropzoneuploader");

		Tester\Assert::same(104857600, $service->getSettings()["maxFilesize"]);
	}

	/**
	 * @return void
	 */
	public function testFour(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/extensionTestFour.neon");

		$container = $configurator->createContainer();

		$container = $configurator->createContainer();
		$service = $container->getService("dropzoneuploader.dropzoneuploader");

		Tester\Assert::same(1073741824, $service->getSettings()["maxFilesize"]);
	}

	/**
	 * @return void
	 */
	public function testFive(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/extensionTestFive.neon");

		$container = $configurator->createContainer();

		$container = $configurator->createContainer();
		$service = $container->getService("dropzoneuploader.dropzoneuploader");

		Tester\Assert::same(109951162777.6, $service->getSettings()["maxFilesize"]);
	}
}


$test = new ExtensionTest;
$test->run();
