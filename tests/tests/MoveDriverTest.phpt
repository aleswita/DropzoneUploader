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
final class MoveDriverTest extends Tester\TestCase
{
	/**
	 * @return void
	 */
	public function testOne(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/moveDriverTest.neon');

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType('Nette\\Application\\IPresenterFactory');
		$file = new Nette\Http\FileUpload(['name' => 'template.latte', 'type' => '', 'size' => 10, 'tmp_name' => __DIR__ . '/../files/template.latte', 'error' => 0]);

		$presenter = $presenterFactory->createPresenter('Base');
		$presenter->getTemplate()->setTranslator(new AlesWita\DropzoneUploader\Tests\App\Service\FakeTranslator);
		$presenter->autoCanonicalize = false;
		$request = new Nette\Application\Request('Base', 'POST', ['action' => 'one'], ['_do' => 'dropzoneUploader-form-submit'], ['file' => $file]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($response->getSource() instanceof Nette\Application\UI\Template);


		// check service
		$service = $presenter['dropzoneUploader'];

		Tester\Assert::same('upload', $service->getUploadDriver()->getSettings()['dir']);


		// check form
		$form = $presenter['dropzoneUploader']['form'];

		Tester\Assert::count(0, $form->getErrors());
		Tester\Assert::true($form->isSuccess());
		Tester\Assert::same($file, $form->getHttpData()['file']);


		// check file
		Tester\Assert::true(is_file($service->getUploadDriver()->getSettings()['dir'] . '/template.latte'));
	}


	/**
	 * @return void
	 */
	public function testTwo(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/moveDriverTest.neon');

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType('Nette\\Application\\IPresenterFactory');

		$presenter = $presenterFactory->createPresenter('Base');
		$presenter->getTemplate()->setTranslator(new AlesWita\DropzoneUploader\Tests\App\Service\FakeTranslator);
		$presenter->autoCanonicalize = false;
		$request = new Nette\Application\Request('Base', 'GET', ['action' => 'one', 'do' => 'dropzoneUploader-uploadedFiles']);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\JsonResponse);


		// check service
		$service = $presenter['dropzoneUploader'];


		// check file
		Tester\Assert::same([['name' => 'template.latte', 'size' => 10, 'accepted' => true]], $response->getPayload()->uploadedFiles);
	}


	/**
	 * @return void
	 */
	public function testThree(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/moveDriverTest.neon');

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType('Nette\\Application\\IPresenterFactory');

		$presenter = $presenterFactory->createPresenter('Base');
		$presenter->getTemplate()->setTranslator(new AlesWita\DropzoneUploader\Tests\App\Service\FakeTranslator);
		$presenter->autoCanonicalize = false;
		$request = new Nette\Application\Request('Base', 'GET', ['action' => 'one', 'do' => 'dropzoneUploader-download', 'dropzoneUploader-file' => 'template.latte']);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\CallbackResponse);


		// check service
		$service = $presenter['dropzoneUploader'];
	}


	/**
	 * @return void
	 */
	public function testFour(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/moveDriverTest.neon');

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType('Nette\\Application\\IPresenterFactory');

		$presenter = $presenterFactory->createPresenter('Base');
		$presenter->getTemplate()->setTranslator(new AlesWita\DropzoneUploader\Tests\App\Service\FakeTranslator);
		$presenter->autoCanonicalize = false;
		$request = new Nette\Application\Request('Base', 'GET', ['action' => 'one', 'do' => 'dropzoneUploader-remove', 'dropzoneUploader-file' => 'template.latte']);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($response->getSource() instanceof Nette\Application\UI\Template);


		// check service
		$service = $presenter['dropzoneUploader'];


		// check file
		Tester\Assert::false(is_file($service->getUploadDriver()->getSettings()['dir'] . '/template.latte'));
	}
}


$test = new MoveDriverTest;
$test->run();
