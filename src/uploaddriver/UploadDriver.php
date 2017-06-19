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
	/** @var callable[] */
	public $onUploadBeginning;

	/** @var callable[] */
	public $onRemoveBeginning;

	/** @var array */
	protected $settings = [];

	/** @var string */
	protected $folder;

	/**
	 * @param array
	 * @return AlesWita\DropzoneUploader\UploadDriver\IUploadDriver
	 */
	public function setSettings(array $settings): AlesWita\DropzoneUploader\UploadDriver\IUploadDriver {
		$this->settings = array_replace($this->settings, $settings);
		return $this;
	}

	/**
	 * @param string
	 * @return AlesWita\DropzoneUploader\UploadDriver\IUploadDriver
	 */
	public function setFolder(string $folder): AlesWita\DropzoneUploader\UploadDriver\IUploadDriver {
		$this->folder = Nette\Utils\Strings::trim($folder, "\\/");
		return $this;
	}

	/**
	 * @return array
	 */
	public function getSettings(): array {
		return $this->settings;
	}

	/**
	 * @return string|NULL
	 */
	public function getFolder(): ?string {
		return $this->folder;
	}

	/**
	 * @param Nette\Http\FileUpload
	 * @return bool
	 */
	public function upload(Nette\Http\FileUpload $file): bool {
		// callback onUploadBeginning
		if ($this->onUploadBeginning !== NULL) {
			if (!is_array($this->onUploadBeginning) && !($this->onUploadBeginning instanceof \Traversable)) {
				throw new AlesWita\DropzoneUploader\DropzoneUploaderException("Property UploadDriver::\$onUploadBeginning must be array or Traversable, " . gettype($this->onUploadBeginning) . " given.");
			}
			foreach ($this->onUploadBeginning as $callback) {
				Nette\Utils\Callback::invoke($callback, $this, $file);
			}
		}

		if (!$file->isOk()) {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * @param string
	 * @param bool
	 * @return bool
	 */
	public function remove(string $file): bool {
		// callback onRemoveBeginning
		if ($this->onRemoveBeginning !== NULL) {
			if (!is_array($this->onRemoveBeginning) && !($this->onRemoveBeginning instanceof \Traversable)) {
				throw new AlesWita\DropzoneUploader\DropzoneUploaderException("Property UploadDriver::\$onRemoveBeginning must be array or Traversable, " . gettype($this->onRemoveBeginning) . " given.");
			}
			foreach ($this->onRemoveBeginning as $callback) {
				Nette\Utils\Callback::invoke($callback, $this, $file);
			}
		}

		return TRUE;
	}
}
