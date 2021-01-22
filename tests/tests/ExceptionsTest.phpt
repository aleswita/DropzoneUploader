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

require_once __DIR__ . '/../bootstrap.php';


/**
 * @author Ales Wita
 * @license MIT
 */
final class ExceptionsTest extends Tester\TestCase
{
	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Parameter "dropzoneTemplate" must be array with keys "main", "form", "files" and "js"!
	 * @return void
	 */
	public function testOne(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestOne.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Can not find template "main.latte"!
	 * @return void
	 */
	public function testTwo(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestTwo.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Can not find template "form.latte"!
	 * @return void
	 */
	public function testThree(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestThree.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Can not find template "files.latte"!
	 * @return void
	 */
	public function testFour(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestFour.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Can not find template "js.latte"!
	 * @return void
	 */
	public function testFive(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestFive.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws Nette\DI\InvalidConfigurationException The mandatory item 'dropzoneuploader › uploadDriver › driver' is missing.
	 * @return void
	 */
	public function testSix(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestSix.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Upload driver "Foo\Foo" no exists!
	 * @return void
	 */
	public function testSeven(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestSeven.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Upload driver must implements AlesWita\DropzoneUploader\UploadDriver\IUploadDriver interface!
	 * @return void
	 */
	public function testEight(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestEight.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws Nette\DI\InvalidConfigurationException The item 'dropzoneuploader › uploadDriver › settings' expects to be array, 'foo' given.
	 * @return void
	 */
	public function testNine(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestNine.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Unknow upload driver setting "foo"!
	 * @return void
	 */
	public function testTen(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestTen.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws Nette\DI\InvalidConfigurationException The item 'dropzoneuploader › settings › maxFilesize' expects to be string|int|float|null, array given.
	 * @return void
	 */
	public function testEleven(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestEleven.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\DropzoneUploader\DropzoneUploaderException Maximum file size setting is unknown!
	 * @return void
	 */
	public function testTwelve(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestTwelve.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws Nette\DI\InvalidConfigurationException The item 'dropzoneuploader › settings › acceptedFiles' expects to be array, 1 given.
	 * @return void
	 */
	public function testThirteen(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestThirteen.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws Nette\DI\InvalidConfigurationException The item 'dropzoneuploader › settings › method' expects to be 'POST'|'post'|'GET'|'get', 'foo' given.
	 * @return void
	 */
	public function testFourteen(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestFourteen.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws Nette\DI\InvalidConfigurationException The item 'dropzoneuploader › settings › parallelUploads' expects to be int, 'foo' given.
	 * @return void
	 */
	public function testFifteen(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestFifteen.neon');

		$configurator->createContainer();
	}


	/**

	 * @throws Nette\DI\InvalidConfigurationException The item 'dropzoneuploader › settings › uploadMultiple' expects to be bool, 'foo' given.
	 * @return void
	 */
	public function testSixteen(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestSixteen.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws Nette\DI\InvalidConfigurationException The item 'dropzoneuploader › settings › paramName' expects to be string, array given.
	 * @return void
	 */
	public function testSeventeen(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestSeventeen.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws Nette\DI\InvalidConfigurationException The item 'dropzoneuploader › settings › addRemoveLinks' expects to be bool, 'foo' given.
	 * @return void
	 */
	public function testEighteen(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestEighteen.neon');

		$configurator->createContainer();
	}
}


$test = new ExceptionsTest;
$test->run();
