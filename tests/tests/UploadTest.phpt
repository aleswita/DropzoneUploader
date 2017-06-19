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
final class UploadTest extends Tester\TestCase
{
	/**
	 * @return void
	 */
	public function testOne(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/uploadTest.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");
		$file = new Nette\Http\FileUpload(["name" => "template.latte", "type" => "", "size" => 10, "tmp_name" => __DIR__ . "/../files/template.latte", "error" => 0]);

		$presenter = $presenterFactory->createPresenter("Base");
		$presenter->getTemplate()->setTranslator(new AlesWita\DropzoneUploader\Tests\App\Service\FakeTranslator);
		$presenter->autoCanonicalize = FALSE;
		$request = new Nette\Application\Request("Base", "POST", ["action" => "two"], ["_do" => "dropzoneUploader-form-submit"], ["file" => $file]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($response->getSource() instanceof Nette\Application\UI\ITemplate);


		// check service
		$service = $presenter["dropzoneUploader"];

		Tester\Assert::same("upload", $service->getUploadDriver()->getSettings()["dir"]);
		Tester\Assert::same("foo", $service->getUploadDriver()->getFolder());


		// check form
		$form = $presenter["dropzoneUploader"]["form"];

		Tester\Assert::count(0, $form->getErrors());
		Tester\Assert::true($form->isSuccess());
		Tester\Assert::same($file, $form->getHttpData()["file"]);


		// check file
		Tester\Assert::true(is_file("{$service->getUploadDriver()->getSettings()["dir"]}/{$service->getUploadDriver()->getFolder()}/template.latte"));
	}

	/**
	 * @return void
	 */
	public function testTwo(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/uploadTest.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");

		$presenter = $presenterFactory->createPresenter("Base");
		$presenter->getTemplate()->setTranslator(new AlesWita\DropzoneUploader\Tests\App\Service\FakeTranslator);
		$presenter->autoCanonicalize = FALSE;
		$request = new Nette\Application\Request("Base", "GET", ["action" => "three", "do" => "dropzoneUploader-remove", "dropzoneUploader-file" => "template.latte"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($response->getSource() instanceof Nette\Application\UI\ITemplate);


		// check service
		$service = $presenter["dropzoneUploader"];

		Tester\Assert::same("foo", $service->getUploadDriver()->getFolder());


		// check file
		Tester\Assert::true(!is_file("{$service->getUploadDriver()->getSettings()["dir"]}/{$service->getUploadDriver()->getFolder()}/template.latte"));
	}
}


$test = new UploadTest;
$test->run();
