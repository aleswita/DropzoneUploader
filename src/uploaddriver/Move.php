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
class Move extends UploadDriver
{
	/** @var array */
	protected $settings = [
		'dir' => null,
	];


	/**
	 * @param array
	 * @return AlesWita\DropzoneUploader\UploadDriver\IUploadDriver
	 */
	public function setSettings(array $settings): AlesWita\DropzoneUploader\UploadDriver\IUploadDriver
	{
		$settings['dir'] = (isset($settings['dir']) ? Nette\Utils\Strings::trim($settings['dir'], '\\/') : null);
		parent::setSettings($settings);
		return $this;
	}


	/**
	 * @return array
	 */
	public function getUploadedFiles(): array
	{
		$uploadedFiles = [];
		$path = ($this->folder === null ? $this->settings['dir'] : $this->settings['dir'] . '/' . $this->folder);

		try {
			foreach (Nette\Utils\Finder::findFiles('*')->from($path) as $file) {
				$uploadedFiles[] = [
					'name' => $file->getBasename(),
					'size' => $file->getSize(),
					'accepted' => true,
				];
			}
		} catch (\UnexpectedValueException $e) {
		}

		return $uploadedFiles;
	}


	/**
	 * @param Nette\Http\FileUpload
	 * @return bool
	 */
	public function upload(Nette\Http\FileUpload $file): bool
	{
		$parent = parent::upload($file);

		if ($parent === true) {
			try {
				$dest = $this->folder === null ? $this->settings['dir'] . '/' . $file->getName() : $this->settings['dir'] . '/' . $this->folder . '/' . $file->getName();
				$file->move($dest);
				return true;
			} catch (Nette\InvalidStateException $e) {
			}
		}

		return false;
	}


	/**
	 * @param string
	 * @return callable
	 */
	public function download(string $file): callable
	{
		return function ($httpRequest, $httpResponse) use ($file): void {
			$fileResponse = new Nette\Application\Responses\FileResponse(($this->folder === null ? $this->settings['dir'] . '/' . $file : $this->settings['dir'] . '/' . $this->folder . '/' . $file));
			$fileResponse->send($httpRequest, $httpResponse);
		};
	}


	/**
	 * @param string
	 * @return bool
	 */
	public function remove(string $file): bool
	{
		try {
			$path = $this->folder === null ? $this->settings['dir'] . '/' . $file : $this->settings['dir'] . '/' . $this->folder . '/' . $file;
			Nette\Utils\FileSystem::delete($path);

			if (count(scandir(dirname($path))) === 2) {// 2, because '.' and '..'
				Nette\Utils\FileSystem::delete(dirname($path));// remove empty folder
			}
			return true;
		} catch (Nette\IOException $e) {
		}

		return false;
	}
}
