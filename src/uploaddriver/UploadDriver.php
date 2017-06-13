<?php

/**
 * This file is part of the AlesWita\Components\DropzoneUploader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\Components\DropzoneUploader\UploadDriver;

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

	/**
	 * @param array
	 * @return AlesWita\Components\DropzoneUploader\UploadDriver\IUploadDriver
	 */
	public function setSettings(array $settings): AlesWita\Components\DropzoneUploader\UploadDriver\IUploadDriver {
		$this->settings = array_replace($this->settings, $settings);
		return $this;
	}

	/**
	 * @return array
	 */
	public function getSettings(): array {
		return $this->settings;
	}

	/**
	 * @param Nette\Http\FileUpload
	 * @return bool
	 */
	public function upload(Nette\Http\FileUpload $file): bool {
		// callback onUploadBeginning
		if ($this->onUploadBeginning !== NULL) {
			if (!is_array($this->onUploadBeginning) && !($this->onUploadBeginning instanceof \Traversable)) {
				throw new AlesWita\Components\DropzoneUploader\DropzoneUploaderException("Property UploadDriver::\$onUploadBeginning must be array or Traversable, " . gettype($this->onUploadBeginning) . " given.");
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
				throw new AlesWita\Components\DropzoneUploader\DropzoneUploaderException("Property UploadDriver::\$onRemoveBeginning must be array or Traversable, " . gettype($this->onRemoveBeginning) . " given.");
			}
			foreach ($this->onRemoveBeginning as $callback) {
				Nette\Utils\Callback::invoke($callback, $this, $file);
			}
		}

		return TRUE;
	}
}
