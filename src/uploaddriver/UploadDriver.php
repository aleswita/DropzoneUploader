<?php

/**
 * This file is part of the AlesWita\DropzoneUploader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\DropzoneUploader\UploadDriver;

use AlesWita;
use Nette;


/**
 * @author Ales Wita
 * @license MIT
 */
abstract class UploadDriver implements IUploadDriver
{
	/** @var array */
	protected $settings = [];

	/** @var string */
	protected $folder;


	/**
	 * @param array
	 * @return AlesWita\DropzoneUploader\UploadDriver\IUploadDriver
	 */
	public function setSettings(array $settings): AlesWita\DropzoneUploader\UploadDriver\IUploadDriver
	{
		$this->settings = array_replace($this->settings, $settings);
		return $this;
	}


	/**
	 * @param string|null
	 * @return AlesWita\DropzoneUploader\UploadDriver\IUploadDriver
	 */
	public function setFolder(?string $folder): AlesWita\DropzoneUploader\UploadDriver\IUploadDriver
	{
		if ($folder !== null) {
			$this->folder = Nette\Utils\Strings::trim($folder, '\\/');
		}

		return $this;
	}


	/**
	 * @return array
	 */
	public function getSettings(): array
	{
		return $this->settings;
	}


	/**
	 * @return string|null
	 */
	public function getFolder(): ?string
	{
		return $this->folder;
	}


	/**
	 * @return array
	 */
	abstract public function getUploadedFiles(): array;


	/**
	 * @param Nette\Http\FileUpload
	 * @return bool
	 */
	public function upload(Nette\Http\FileUpload $file): bool
	{
		if (!$file->isOk()) {
			return false;
		}

		return true;
	}


	/**
	 * @param string
	 * @return callable
	 */
	abstract public function download(string $file): callable;


	/**
	 * @param string
	 * @return bool
	 */
	abstract public function remove(string $file): bool;
}
