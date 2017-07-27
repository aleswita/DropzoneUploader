<?php

/**
 * This file is part of the AlesWita\DropzoneUploader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\DropzoneUploader;

use AlesWita;
use Nette;


/**
 * @author Ales Wita
 * @license MIT
 */
class DropzoneUploader extends Nette\Application\UI\Control
{
	/** @var callable[] */
	public $onBeginning;

	/** @var Nette\Localization\ITranslator */
	private $translator;

	/** @var array */
	private $dropzoneTemplate;

	/** @var AlesWita\DropzoneUploader\UploadDriver\IUploadRiver */
	private $uploadDriver;

	/** @var array */
	private $settings;

	/** @var array */
	private $messages;

	/** @var string */
	private $folder;


	/**
	 * @param Nette\Localization\ITranslator
	 * @return self
	 */
	public function setTranslator(Nette\Localization\ITranslator $translator): self
	{
		$this->translator = $translator;
		return $this;
	}


	/**
	 * @param array
	 * @return self
	 */
	public function setDropzoneTemplate(array $template): self
	{
		$this->dropzoneTemplate = $template;
		return $this;
	}


	/**
	 * @param AlesWita\DropzoneUploader\UploadDriver\IUploadDriver
	 * @return self
	 */
	public function setUploadDriver(UploadDriver\IUploadDriver $driver): self
	{
		$this->uploadDriver = $driver;
		return $this;
	}


	/**
	 * @param array
	 * @return self
	 */
	public function setSettings(array $settings): self
	{
		if (isset($settings['maxFilesize'])) {
			$settings['maxFilesize'] = $settings['maxFilesize'] / 1024 / 1024;
		}
		if (isset($settings['maxFilesize'])) {
			$settings['acceptedFiles'] = implode(', ', $settings['acceptedFiles']);
		}

		$this->settings = $settings;
		return $this;
	}


	/**
	 * @param array
	 * @return self
	 */
	public function setMessages(array $messages): self
	{
		$this->messages = $messages;
		return $this;
	}


	/**
	 * @param string|null
	 * @return self
	 */
	public function setFolder(?string $folder): self
	{
		$this->folder = $folder;
		return $this;
	}


	/**
	 * @return Nette\Localization\ITranslator
	 */
	public function getTranslator(): Nette\Localization\ITranslator
	{
		return $this->translator;
	}


	/**
	 * @return array
	 */
	public function getDropzoneTemplate(): array
	{
		return $this->dropzoneTemplate;
	}


	/**
	 * @return AlesWita\DropzoneUploader\UploadDriver\IUploadDriver
	 */
	public function getUploadDriver(): AlesWita\DropzoneUploader\UploadDriver\IUploadDriver
	{
		return $this->uploadDriver;
	}


	/**
	 * @return array
	 */
	public function getSettings(): array
	{
		return $this->settings;
	}


	/**
	 * @return array
	 */
	public function getMessages(): array
	{
		return $this->messages;
	}


	/**
	 * @return string|null
	 */
	public function getFolder(): ?string
	{
		return $this->folder;
	}


	/**
	 * @return void
	 */
	private function prepareTemplate(): void
	{
		if ($this->onBeginning !== null) {
			if (!is_array($this->onBeginning) && !($this->onBeginning instanceof \Traversable)) {
				throw new AlesWita\DropzoneUploader\DropzoneUploaderException('Property DropzoneUploader::$onBeginning must be array or Traversable, ' . gettype($this->onBeginning) . ' given.');
			}
			foreach ($this->onBeginning as $callback) {
				Nette\Utils\Callback::invoke($callback, $this);
			}
		}

		$this->template->fileParam = $this->getParameterId('file');
		$this->template->folderParam = $this->getParameterId('folder');
		$this->template->settings = $this->settings;
		$this->template->messages = $this->messages;

		$this->template->setTranslator($this->translator);
	}


	/**
	 * @return void
	 */
	public function render(): void
	{
		$this->prepareTemplate();

		$this->template->setFile($this->dropzoneTemplate['main']);
		$this->template->render();
	}


	/**
	 * @return void
	 */
	public function renderForm(): void
	{
		$this->prepareTemplate();

		$this->template->setFile($this->dropzoneTemplate['form']);
		$this->template->render();
	}


	/**
	 * @return void
	 */
	public function renderFiles(): void
	{
		//$this->prepareTemplate();
		$this->template->setFile($this->dropzoneTemplate['files']);
		$this->template->render();
	}


	/**
	 * @return void
	 */
	public function renderJs(): void
	{
		//$this->prepareTemplate();
		$this->template->setFile($this->dropzoneTemplate['js']);
		$this->template->render();
	}


	/**
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentForm(): Nette\Application\UI\Form
	{
		$form = new Nette\Application\UI\Form;

		$form->getElementPrototype()->addClass('dropzone')
			->addId('dropzoneForm');

		$form->addHidden('folder', $this->folder);

		$form->onSuccess[] = function (Nette\Application\UI\Form $form, array $values): void {
			$httpData = $form->getHttpData();

			$this->uploadDriver->setFolder($values['folder'])
				->upload($httpData['file']);
		};

		return $form;
	}


	/**
	 * @param string
	 * @param string
	 * @return void
	 */
	public function handleDownload(string $folder = null, string $file = null): void
	{
		$this->uploadDriver->setFolder($folder);

		if ($file !== null) {
			$this->presenter->sendResponse(new Nette\Application\Responses\CallbackResponse($this->uploadDriver->download($file)));
		}
	}


	/**
	 * @param string
	 * @param string
	 * @return void
	 */
	public function handleRemove(string $folder = null, string $file = null): void
	{
		if ($this->settings['addRemoveLinks']) {
			$this->uploadDriver->setFolder($folder);

			if ($file !== null) {
				$this->uploadDriver->remove($file);
			}
		}
	}


	/**
	 * @param string
	 * @return void
	 */
	public function handleUploadedFiles(string $folder = null): void
	{
		$this->uploadDriver->setFolder($folder);

		$this->presenter->payload->uploadedFiles = $this->uploadDriver->getUploadedFiles();
		$this->presenter->sendPayload();
	}
}
