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
final class Move extends UploadDriver
{
	/** @var array */
	protected $settings = [
		"dir" => NULL,
	];

	/**
	 * @param array
	 * @return AlesWita\Components\DropzoneUploader\UploadDriver\IUploadDriver
	 */
	public function setSettings(array $settings): AlesWita\Components\DropzoneUploader\UploadDriver\IUploadDriver {
		$settings["dir"] = Nette\Utils\Strings::trim($settings["dir"], "\\/");
		parent::setSettings($settings);
		return $this;
	}

	/**
	 * @param Nette\Http\FileUpload
	 * @return bool
	 */
	public function upload(Nette\Http\FileUpload $file): bool {
		$parent = parent::upload($file);

		if ($parent === TRUE) {
			try {
				$dest = ($this->folder === NULL ? "{$this->settings["dir"]}/{$file->getName()}" : "{$this->settings["dir"]}/{$this->folder}/{$file->getName()}");
				$file->move($dest);
				return TRUE;
			} catch (Nette\InvalidStateException $e) {
			}
		}

		return FALSE;
	}

	/**
	 * @param string
	 * @return bool
	 */
	public function remove(string $file): bool {
		$parent = parent::remove($file);

		if ($parent === TRUE) {
			try {
				$path = ($this->folder === NULL ? "{$this->settings["dir"]}/{$file}" : "{$this->settings["dir"]}/{$this->folder}/{$file}");
        		Nette\Utils\FileSystem::delete($path);
				return TRUE;
			} catch (Nette\IOException $e) {
			}
		}

		return FALSE;
	}
}
