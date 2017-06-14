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

		Tester\Assert::true($presenter->dropzoneUploader->getTranslator() instanceof Nette\Localization\ITranslator);
		Tester\Assert::same(__DIR__ . "/../files/template.latte", $presenter->dropzoneUploader->getDropzoneTemplate());
		Tester\Assert::true(array_key_exists("AlesWita\\Components\\DropzoneUploader\\UploadDriver\\IUploadDriver", class_implements($presenter->dropzoneUploader->getUploadDriver())));
		Tester\Assert::same(1048576, $presenter->dropzoneUploader->getSettings()["maxFilesize"]);
		Tester\Assert::same(["foo"], $presenter->dropzoneUploader->getSettings()["acceptedFiles"]);
		Tester\Assert::same("foo", $presenter->dropzoneUploader->getMessages()["dictDefaultMessage"]);
		Tester\Assert::false(isset($presenter->dropzoneUploader->getMessages()["dictRemoveFileConfirmation"]));


		$dropzoneUploader = $presenter->dropzoneUploader->getDropzoneUploader();

		Tester\Assert::true($dropzoneUploader instanceof AlesWita\Components\DropzoneUploader\DropzoneUploader);
		Tester\Assert::true($dropzoneUploader->getTranslator() instanceof Nette\Localization\ITranslator);
		Tester\Assert::same(__DIR__ . "/../files/template.latte", $dropzoneUploader->getDropzoneTemplate());
		Tester\Assert::same(1, $dropzoneUploader->getSettings()["maxFilesize"]);
		Tester\Assert::same("foo", $dropzoneUploader->getSettings()["acceptedFiles"]);
		Tester\Assert::same("foo", $dropzoneUploader->getMessages()["dictDefaultMessage"]);
		Tester\Assert::false(isset($dropzoneUploader->getMessages()["dictRemoveFileConfirmation"]));
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

		Tester\Assert::same(AlesWita\Components\DropzoneUploader\Factory::BOOTSTRAP_V4_TEMPLATE, $presenter->dropzoneUploader->getDropzoneTemplate());
		Tester\Assert::same("foo", $presenter->dropzoneUploader->getMessages()["dictRemoveFileConfirmation"]);

		// check Ftp driver
		$uploadDriver = $presenter->dropzoneUploader->getUploadDriver();

		Tester\Assert::true(array_key_exists("AlesWita\\Components\\DropzoneUploader\\UploadDriver\\IUploadDriver", class_implements($uploadDriver)));
		Tester\Assert::same(["url" => "foo"], $uploadDriver->getSettings());
		Tester\Assert::same(NULL, $uploadDriver->getFolder());

		$uploadDriver->setSettings(["url" => "test"])
			->setFolder("foo");

		Tester\Assert::same(["url" => "test"], $uploadDriver->getSettings());
		Tester\Assert::same("foo", $uploadDriver->getFolder());
	}
}


$test = new GettersTest;
$test->run();
