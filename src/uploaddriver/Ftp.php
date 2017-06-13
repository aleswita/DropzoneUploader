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
final class Ftp extends UploadDriver
{
	/** @var array */
	protected $settings = [
		"url" => NULL,
	];

	/** @var string */
	private $folder;

	/** @var Ftp */
	private $ftpConnection;

	/**
	 * @param string
	 * @return AlesWita\Components\DropzoneUploader\UploadDriver\IUploadDriver
	 */
	public function setFolder(string $folder): AlesWita\Components\DropzoneUploader\UploadDriver\IUploadDriver {
		$this->folder = Nette\Utils\Strings::trim($folder, "\\/");
		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getFolder(): ?string {
		return $this->folder;
	}

	/**
	 * @return Ftp
	 */
	private function getFtpConnection(): \Ftp {
		if (!$this->ftpConnection) {
			$this->ftpConnection = new \Ftp($this->settings["url"]);
		}
		return $this->ftpConnection;
	}

	/**
	 * @param Nette\Http\FileUpload
	 * @return bool
	 */
	public function upload(Nette\Http\FileUpload $file): bool {
		$parent = parent::upload($file);

		if ($parent === TRUE) {
			try {
				$ftp = $this->getFtpConnection();

				if ($ftp->nlist($this->folder) === FALSE) {
					$ftp->mkdir($this->folder);
				}

				$fileName = ($this->folder === NULL ? $file->getSanitizedName() : "{$this->folder}/{$file->getName()}");
				return $ftp->put($fileName, $file->getTemporaryFile(), FTP_BINARY);
			} catch (\FtpException $e) {
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
				$ftp = $this->getFtpConnection();

				$fileName = ($this->folder === NULL ? $file : "{$this->folder}/{$file}");
				$return = $ftp->delete($fileName);

				if (count($ftp->nList(dirname($fileName))) === 0) {
					$ftp->rmDir(dirname($fileName));// remove empty folder
				}

				return $return;
			} catch (\FtpException $e) {
			}
		}

		return FALSE;
	}

	public function __destruct()
	{
		// disconnect from ftp
		unset($this->ftpConnection);
	}
}
