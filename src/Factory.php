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
class Factory
{
	// default template
	public const DEFAULT_TEMPLATE = [
		'main' => __DIR__ . '/templates/default/main.latte',
		'form' => __DIR__ . '/templates/default/form.latte',
		'files' => __DIR__ . '/templates/default/files.latte',
		'js' => __DIR__ . '/templates/default/js.latte',
	];

	// bootsrapt v4 template
	public const BOOTSTRAP_V4_TEMPLATE = [
		'main' => __DIR__ . '/templates/bootstrap_v4/main.latte',
		'form' => __DIR__ . '/templates/bootstrap_v4/form.latte',
		'files' => __DIR__ . '/templates/bootstrap_v4/files.latte',
		'js' => __DIR__ . '/templates/bootstrap_v4/js.latte',
	];

	/** @var Nette\Localization\ITranslator */
	private $translator;

	/** @var array */
	private $dropzoneTemplate = self::DEFAULT_TEMPLATE;

	/** @var AlesWita\DropzoneUploader\UploadDriver\IUploadRiver */
	private $uploadDriver;

	/** @var array */
	private $settings = [];

	/** @var array */
	private $messages = [];


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
	 * @return AlesWita\DropzoneUploader\DropzoneUploader
	 */
	public function getDropzoneUploader(): AlesWita\DropzoneUploader\DropzoneUploader
	{
		$dropzoneUploader = new DropzoneUploader;

		$dropzoneUploader->setTranslator($this->translator)
			->setDropzoneTemplate($this->dropzoneTemplate)
			->setUploadDriver($this->uploadDriver)
			->setSettings($this->settings)
			->setMessages($this->messages);

		return $dropzoneUploader;
	}
}
