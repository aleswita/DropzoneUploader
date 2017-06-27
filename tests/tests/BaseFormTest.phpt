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
final class BaseFormTest extends Tester\TestCase
{
	/**
	 * @return void
	 */
	public function testOne(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/baseFormTestOne.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");

		$presenter = $presenterFactory->createPresenter("Base");
		$presenter->getTemplate()->setTranslator(new AlesWita\DropzoneUploader\Tests\App\Service\FakeTranslator);
		$presenter->autoCanonicalize = FALSE;
		$request = new Nette\Application\Request("Base", "GET", ["action" => "one"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($response->getSource() instanceof Nette\Application\UI\ITemplate);

		$source = (string) $response->getSource();
		$dom = Tester\DomQuery::fromHtml($source);


		// form tag
		$data = $dom->find("form");

		Tester\Assert::count(1, $data);
		Tester\Assert::same("/base/one", (string) $data[0]["action"]);
		Tester\Assert::same("post", (string) $data[0]["method"]);
		Tester\Assert::same("dropzone", (string) $data[0]["class"]);
		Tester\Assert::same("dropzoneForm", (string) $data[0]["id"]);


		// input tag's
		$data = $dom->find("input");

		Tester\Assert::count(2, $data);

		Tester\Assert::same("hidden", (string) $data[0]["type"]);
		Tester\Assert::same("folder", (string) $data[0]["name"]);
		Tester\Assert::same("foo", (string) $data[0]["value"]);

		Tester\Assert::same("hidden", (string) $data[1]["type"]);
		Tester\Assert::same("_do", (string) $data[1]["name"]);
		Tester\Assert::same("dropzoneUploader-form-submit", (string) $data[1]["value"]);


		// script tag
		$data = $dom->find("script");

		Tester\Assert::count(1, $data);
		Tester\Assert::same("text/javascript", (string) $data[0]["type"]);
	}

	/**
	 * @return void
	 */
	public function testTwo(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/baseFormTestOne.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");

		$presenter = $presenterFactory->createPresenter("Base");
		$presenter->getTemplate()->setTranslator(new AlesWita\DropzoneUploader\Tests\App\Service\FakeTranslator);
		$presenter->autoCanonicalize = FALSE;
		$request = new Nette\Application\Request("Base", "GET", ["action" => "two"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($response->getSource() instanceof Nette\Application\UI\ITemplate);

		$source = (string) $response->getSource();
		$dom = Tester\DomQuery::fromHtml($source);


		// form tag
		$data = $dom->find("form");

		Tester\Assert::count(1, $data);
		Tester\Assert::same("/base/two", (string) $data[0]["action"]);
		Tester\Assert::same("post", (string) $data[0]["method"]);
		Tester\Assert::same("dropzone", (string) $data[0]["class"]);
		Tester\Assert::same("dropzoneForm", (string) $data[0]["id"]);


		// input tag's
		$data = $dom->find("input");

		Tester\Assert::count(2, $data);

		Tester\Assert::same("hidden", (string) $data[0]["type"]);
		Tester\Assert::same("folder", (string) $data[0]["name"]);
		Tester\Assert::same("", (string) $data[0]["value"]);

		Tester\Assert::same("hidden", (string) $data[1]["type"]);
		Tester\Assert::same("_do", (string) $data[1]["name"]);
		Tester\Assert::same("dropzoneUploader-form-submit", (string) $data[1]["value"]);


		// script tag
		$data = $dom->find("script");

		Tester\Assert::count(1, $data);
		Tester\Assert::same("text/javascript", (string) $data[0]["type"]);
	}

	/**
	 * @return void
	 */
	public function testThree(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/baseFormTestTwo.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");

		$presenter = $presenterFactory->createPresenter("Base");
		$presenter->getTemplate()->setTranslator(new AlesWita\DropzoneUploader\Tests\App\Service\FakeTranslator);
		$presenter->autoCanonicalize = FALSE;
		$request = new Nette\Application\Request("Base", "GET", ["action" => "one"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($response->getSource() instanceof Nette\Application\UI\ITemplate);

		$source = (string) $response->getSource();
		$dom = Tester\DomQuery::fromHtml($source);


		// form tag
		$data = $dom->find("form");

		Tester\Assert::count(1, $data);
		Tester\Assert::same("/base/one", (string) $data[0]["action"]);
		Tester\Assert::same("post", (string) $data[0]["method"]);
		Tester\Assert::same("dropzone", (string) $data[0]["class"]);
		Tester\Assert::same("dropzoneForm", (string) $data[0]["id"]);


		// input tag's
		$data = $dom->find("input");

		Tester\Assert::count(2, $data);

		Tester\Assert::same("hidden", (string) $data[0]["type"]);
		Tester\Assert::same("folder", (string) $data[0]["name"]);
		Tester\Assert::same("foo", (string) $data[0]["value"]);

		Tester\Assert::same("hidden", (string) $data[1]["type"]);
		Tester\Assert::same("_do", (string) $data[1]["name"]);
		Tester\Assert::same("dropzoneUploader-form-submit", (string) $data[1]["value"]);


		// script tag
		$data = $dom->find("script");

		Tester\Assert::count(1, $data);
		Tester\Assert::same("text/javascript", (string) $data[0]["type"]);
	}

	/**
	 * @return void
	 */
	public function testFour(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/baseFormTestTwo.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");

		$presenter = $presenterFactory->createPresenter("Base");
		$presenter->getTemplate()->setTranslator(new AlesWita\DropzoneUploader\Tests\App\Service\FakeTranslator);
		$presenter->autoCanonicalize = FALSE;
		$request = new Nette\Application\Request("Base", "GET", ["action" => "two"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($response->getSource() instanceof Nette\Application\UI\ITemplate);

		$source = (string) $response->getSource();
		$dom = Tester\DomQuery::fromHtml($source);


		// form tag
		$data = $dom->find("form");

		Tester\Assert::count(1, $data);
		Tester\Assert::same("/base/two", (string) $data[0]["action"]);
		Tester\Assert::same("post", (string) $data[0]["method"]);
		Tester\Assert::same("dropzone", (string) $data[0]["class"]);
		Tester\Assert::same("dropzoneForm", (string) $data[0]["id"]);


		// input tag's
		$data = $dom->find("input");

		Tester\Assert::count(2, $data);

		Tester\Assert::same("hidden", (string) $data[0]["type"]);
		Tester\Assert::same("folder", (string) $data[0]["name"]);
		Tester\Assert::same("", (string) $data[0]["value"]);

		Tester\Assert::same("hidden", (string) $data[1]["type"]);
		Tester\Assert::same("_do", (string) $data[1]["name"]);
		Tester\Assert::same("dropzoneUploader-form-submit", (string) $data[1]["value"]);


		// script tag
		$data = $dom->find("script");

		Tester\Assert::count(1, $data);
		Tester\Assert::same("text/javascript", (string) $data[0]["type"]);
	}
}


$test = new BaseFormTest;
$test->run();
