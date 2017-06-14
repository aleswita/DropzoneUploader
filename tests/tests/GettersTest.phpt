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
final class GettersTest extends Tester\TestCase
{
	/**
	 * @return void
	 */
	public function testOne(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/gettersTestOne.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");

		$presenter = $presenterFactory->createPresenter("Getters");
		$presenter->autoCanonicalize = FALSE;
		$request = new Nette\Application\Request("Getters", "GET", ["action" => "one"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($response->getSource() instanceof Nette\Application\UI\ITemplate);

		// check factory
		$factory = $presenter->dropzoneUploader;

		Tester\Assert::true($factory->getTranslator() instanceof Nette\Localization\ITranslator);
		Tester\Assert::same(__DIR__ . "/../files/template.latte", $factory->getDropzoneTemplate());
		Tester\Assert::true(array_key_exists("AlesWita\\Components\\DropzoneUploader\\UploadDriver\\IUploadDriver", class_implements($factory->getUploadDriver())));
		Tester\Assert::same(1048576, $factory->getSettings()["maxFilesize"]);
		Tester\Assert::same(["foo"], $factory->getSettings()["acceptedFiles"]);
		Tester\Assert::same("foo", $factory->getMessages()["dictDefaultMessage"]);
		Tester\Assert::false(isset($factory->getMessages()["dictRemoveFileConfirmation"]));


		// check component
		$component = $factory->getDropzoneUploader();

		Tester\Assert::true($component instanceof AlesWita\Components\DropzoneUploader\DropzoneUploader);
		Tester\Assert::true($component->getTranslator() instanceof Nette\Localization\ITranslator);
		Tester\Assert::same(__DIR__ . "/../files/template.latte", $component->getDropzoneTemplate());
		Tester\Assert::true(array_key_exists("AlesWita\\Components\\DropzoneUploader\\UploadDriver\\IUploadDriver", class_implements($component->getUploadDriver())));
		Tester\Assert::same(1, $component->getSettings()["maxFilesize"]);// different between component and factory
		Tester\Assert::same("foo", $component->getSettings()["acceptedFiles"]);// different between component and factory
		Tester\Assert::same("foo", $component->getMessages()["dictDefaultMessage"]);
		Tester\Assert::false(isset($component->getMessages()["dictRemoveFileConfirmation"]));
	}

	/**
	 * @return void
	 */
	public function testTwo(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/gettersTestTwo.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");

		$presenter = $presenterFactory->createPresenter("Getters");
		$presenter->autoCanonicalize = FALSE;
		$request = new Nette\Application\Request("Getters", "GET", ["action" => "one"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($response->getSource() instanceof Nette\Application\UI\ITemplate);


		// check factory
		$factory = $presenter->dropzoneUploader;

		Tester\Assert::same(AlesWita\Components\DropzoneUploader\Factory::BOOTSTRAP_V4_TEMPLATE, $factory->getDropzoneTemplate());
		Tester\Assert::same("foo", $factory->getMessages()["dictRemoveFileConfirmation"]);


		// check driver
		$driver = $factory->getUploadDriver();

		Tester\Assert::true(array_key_exists("AlesWita\\Components\\DropzoneUploader\\UploadDriver\\IUploadDriver", class_implements($driver)));
		Tester\Assert::same(["url" => "foo"], $driver->getSettings());
		Tester\Assert::same(NULL, $driver->getFolder());

		$driver->setSettings(["url" => "test"])
			->setFolder("foo");

		Tester\Assert::same(["url" => "test"], $driver->getSettings());
		Tester\Assert::same("foo", $driver->getFolder());
	}
}


$test = new GettersTest;
$test->run();
