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
final class GettersTest extends Tester\TestCase
{
	/**
	 * @return void
	 */
	public function testOne(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/gettersTestOne.neon');

		$container = $configurator->createContainer();


		// check service
		$service = $container->getService('dropzoneuploader.dropzoneuploader');

		Tester\Assert::true($service->getTranslator() instanceof Nette\Localization\ITranslator);
		Tester\Assert::same(['main' => __DIR__ . '/../files/template.latte', 'form' => __DIR__ . '/../files/template.latte', 'files' => __DIR__ . '/../files/template.latte', 'js' => __DIR__ . '/../files/template.latte'], $service->getDropzoneTemplate());
		Tester\Assert::true(array_key_exists('AlesWita\\DropzoneUploader\\UploadDriver\\IUploadDriver', class_implements($service->getUploadDriver())));
		Tester\Assert::same(1048576, $service->getSettings()['maxFilesize']);
		Tester\Assert::same(['foo'], $service->getSettings()['acceptedFiles']);
		Tester\Assert::same('foo', $service->getMessages()['dictDefaultMessage']);
		Tester\Assert::false(isset($service->getMessages()['dictRemoveFileConfirmation']));


		// check component
		$component = $service->getDropzoneUploader();

		Tester\Assert::true($component instanceof AlesWita\DropzoneUploader\DropzoneUploader);
		Tester\Assert::true($component->getTranslator() instanceof Nette\Localization\ITranslator);
		Tester\Assert::same(['main' => __DIR__ . '/../files/template.latte', 'form' => __DIR__ . '/../files/template.latte', 'files' => __DIR__ . '/../files/template.latte', 'js' => __DIR__ . '/../files/template.latte'], $component->getDropzoneTemplate());
		Tester\Assert::true(array_key_exists('AlesWita\\DropzoneUploader\\UploadDriver\\IUploadDriver', class_implements($component->getUploadDriver())));
		Tester\Assert::same(1, $component->getSettings()['maxFilesize']);// different between component and factory
		Tester\Assert::same('foo', $component->getSettings()['acceptedFiles']);// different between component and factory
		Tester\Assert::same('foo', $component->getMessages()['dictDefaultMessage']);
		Tester\Assert::false(isset($component->getMessages()['dictRemoveFileConfirmation']));
	}


	/**
	 * @return void
	 */
	public function testTwo(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/gettersTestTwo.neon');

		$container = $configurator->createContainer();


		// check factory
		$service = $container->getService('dropzoneuploader.dropzoneuploader');

		Tester\Assert::same(AlesWita\DropzoneUploader\Factory::BOOTSTRAP_V4_TEMPLATE, $service->getDropzoneTemplate());
		Tester\Assert::same('foo', $service->getMessages()['dictRemoveFileConfirmation']);


		// check driver
		$driver = $service->getUploadDriver();

		Tester\Assert::true(array_key_exists('AlesWita\\DropzoneUploader\\UploadDriver\\IUploadDriver', class_implements($driver)));
		Tester\Assert::same(['url' => 'foo'], $driver->getSettings());
		Tester\Assert::same(null, $driver->getFolder());

		$driver->setSettings(['url' => 'ftp://user:password@127.0.0.1'])
			->setFolder('foo');

		Tester\Assert::same(['url' => 'ftp://user:password@127.0.0.1'], $driver->getSettings());
		Tester\Assert::same('foo', $driver->getFolder());
	}
}


$test = new GettersTest;
$test->run();
