<?php

/**
 * This file is part of the AlesWita\Components\DropzoneUploader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\Components\DropzoneUploader;

use AlesWita;
use Nette;
use Nette\Application;


/**
 * @author Ales Wita
 * @license MIT
 */
class DropzoneUploader extends Application\UI\Control
{
	/** @var string */
	private $dropzoneTemplate;

	/** @var AlesWita\Components\DropzoneUploader\UploadDriver\IUploadRiver */
	private $uploadDriver;

	/** @var array */
	private $settings;

	/** @var array */
	private $messages;

	/**
	 * @param string
	 * @return self
	 */
	public function setDropzoneTemplate(string $template): self {
		$this->dropzoneTemplate = $template;
		return $this;
	}

	/**
	 * @param AlesWita\Components\DropzoneUploader\UploadDriver\IUploadDriver
	 * @return self
	 */
	public function setUploadDriver(UploadDriver\IUploadDriver $driver): self {
		$this->uploadDriver = $driver;
		return $this;
	}

	/**
	 * @param array
	 * @return self
	 */
	public function setSettings(array $settings): self {
		if (isset($settings["maxFilesize"])) {
			$settings["maxFilesize"] = $settings["maxFilesize"] / 1024 / 1024;
		}
		if (isset($settings["maxFilesize"])) {
			$settings["acceptedFiles"] = implode(", ", $settings["acceptedFiles"]);
		}

		$this->settings = $settings;
		return $this;
	}

	/**
	 * @param array
	 * @return self
	 */
	public function setMessages(array $messages): self {
		$this->messages = $messages;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDropzoneTemplate(): string {
		return $this->dropzoneTemplate;
	}

	/**
	 * @return AlesWita\Components\DropzoneUploader\UploadDriver\IUploadDriver
	 */
	public function getUploadDriver(): AlesWita\Components\DropzoneUploader\UploadDriver\IUploadDriver {
		return $this->uploadDriver;
	}

	/**
	 * @return array
	 */
	public function getMessages(): array {
		return $this->messages;
	}

	/**
	 * @return void
	 */
	public function render(): void {
		$this->template->fileParam = $this->getParameterId("file");
		$this->template->settings = $this->settings;
		$this->template->messages = $this->messages;

		$this->template->setFile($this->dropzoneTemplate);
		$this->template->render();
	}

	/** ******************** */

	/**
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentForm(): Nette\Application\UI\Form {
		$form = new Application\UI\Form;

		$form->getElementPrototype()
			->setClass("dropzone")
			->setId("dropzoneForm");

		$form->onSuccess[] = function (Application\UI\Form $form, array $values): void {
			$httpData = $form->getHttpData();
			$this->uploadDriver->upload($httpData["file"]);
		};

		return $form;
	}

	/**
	 * @param string
	 * @return void
	 */
	public function handleRemove(string $file = NULL): void {
		if ($file !== NULL) {
			$this->uploadDriver->remove($file);
		}
	}
}
