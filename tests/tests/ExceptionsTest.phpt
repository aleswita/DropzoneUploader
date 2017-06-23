<?php

/**
 * This file is part of the AlesWita\DropzoneUploader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 *
 * @phpVersion 7.1.0
 */

declare(strict_types=1);

namespace AlesWita\DropzoneUploader\Tests\Tests;

use AlesWita;
use Nette;
use Tester;

require_once __DIR__ . "/../bootstrap.php";


/**
 * @author Ales Wita
 * @license MIT
 */
final class ExceptionsTest extends Tester\TestCase
{
	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Parameter 'dropzoneTemplate' must be array with keys 'main', 'form', 'files' and 'js'!
	 * @return void
	 */
	public function testOne(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestOne.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Can not find template 'main.latte'!
	 * @return void
	 */
	public function testTwo(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestTwo.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Can not find template 'form.latte'!
	 * @return void
	 */
	public function testThree(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestThree.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Can not find template 'files.latte'!
	 * @return void
	 */
	public function testFour(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestFour.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Can not find template 'js.latte'!
	 * @return void
	 */
	public function testFive(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestFive.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Upload driver must be set!
	 * @return void
	 */
	public function testSix(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestSix.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Upload driver 'Foo\Foo' no exists!
	 * @return void
	 */
	public function testSeven(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestSeven.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Upload driver must implements AlesWita\DropzoneUploader\UploadDriver\IUploadDriver interface!
	 * @return void
	 */
	public function testEight(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestEight.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Upload driver settings must be array!
	 * @return void
	 */
	public function testNine(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestNine.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Unknow upload driver settings 'foo'!
	 * @return void
	 */
	public function testTen(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestTen.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Maximum file size settings must be integer, float or string!
	 * @return void
	 */
	public function testEleven(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestEleven.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Maximum file size settings is unknown!
	 * @return void
	 */
	public function testTwelve(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestTwelve.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Accepted files settings must be string or array type!
	 * @return void
	 */
	public function testThirteen(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestThirteen.neon");

		$configurator->createContainer();
	}
}


$test = new ExceptionsTest;
$test->run();
