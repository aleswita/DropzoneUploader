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
interface IUploadDriver
{
	/**
	 * @return AlesWita\Components\DropzoneUploader\UploadDriver\IUploadDriver
	 */
	function setSettings(array $settings): AlesWita\Components\DropzoneUploader\UploadDriver\IUploadDriver;

	/**
	 * @return AlesWita\Components\DropzoneUploader\UploadDriver\IUploadDriver
	 */
	function setFolder(string $folder): AlesWita\Components\DropzoneUploader\UploadDriver\IUploadDriver;

	/**
	 * @return array
	 */
	function getSettings(): array;

	/**
	 * @return string|NULL
	 */
	function getFolder(): ?string;

	/**
	 * @param Nette\Http\FileUpload
	 * @return bool
	 */
	function upload(Nette\Http\FileUpload $file): bool;

	/**
	 * @param string
	 * @return bool
	 */
	function remove(string $file): bool;
}
