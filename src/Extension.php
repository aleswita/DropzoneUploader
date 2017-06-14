<?php

/**
 * This file is part of the AlesWita\Components\DropzoneUploader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\Components\DropzoneUploader;

use Nette;


/**
 * @author Ales Wita
 * @license MIT
 */
class Extension extends Nette\DI\CompilerExtension
{
	/** @var array */
	public $defaults = [
		"dropzoneTemplate" => NULL,
		"uploadDriver" => [
			"driver" => NULL,
			"settings" => [],
		],
		"settings" => [
			"method" => "POST",
			"parallelUploads" => 1,
			"uploadMultiple" => FALSE,
			"maxFilesize" => NULL,
			"paramName" => "file",
			"maxFiles" => NULL,
			"acceptedFiles" => NULL,
			"addRemoveLinks" => FALSE,
		],
		"messages" => [
			"dictDefaultMessage" => "Drop files here to upload",
			"dictFallbackMessage" => "Your browser does not support drag'n'drop file uploads.",
			"dictFallbackText" => "Please use the fallback form below to upload your files like in the olden days.",
			"dictFileTooBig" => "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.",
			"dictInvalidFileType" => "You can't upload files of this type.",
			"dictResponseError" => "Server responded with {{statusCode}} code.",
			"dictCancelUpload" => "Cancel upload",
			"dictCancelUploadConfirmation" => "Are you sure you want to cancel this upload?",
			"dictRemoveFile" => "Remove file",
			"dictRemoveFileConfirmation" => NULL,
			"dictMaxFilesExceeded" => "You can not upload any more files.",
		],
	];

	/**
	 * @return void
	 */
	public function loadConfiguration(): void {
		$config = $this->getConfig($this->defaults);
		$container = $this->getContainerBuilder();

		$dropzoneUploader = $container->addDefinition($this->prefix("dropzoneuploader"))
			->setClass("AlesWita\\Components\\DropzoneUploader\\Factory");

		if ($config["dropzoneTemplate"] !== NULL) {
			if ($config["dropzoneTemplate"] instanceof Nette\DI\Statement) {
				$config["dropzoneTemplate"] = constant($config["dropzoneTemplate"]->arguments[0]);
			}

			if (!is_string($config["dropzoneTemplate"])) {
				throw new DropzoneUploaderException("Parameter 'dropzoneTemplate' must be string!");
			} elseif (!is_file($config["dropzoneTemplate"])) {
				throw new DropzoneUploaderException("Can not find template '{$config["dropzoneTemplate"]}'!");
			} else {
				$dropzoneUploader->addSetup("\$service->setDropzoneTemplate(?)", [$config["dropzoneTemplate"]]);
			}
		}

		// upload driver
		if ($config["uploadDriver"]["driver"] === NULL) {
			throw new DropzoneUploaderException("Upload driver must be set!");
		} elseif (!class_exists($config["uploadDriver"]["driver"])) {
			throw new DropzoneUploaderException("Upload driver '{$config["uploadDriver"]["driver"]}' no exists!");
		} else {
			$uploadDriver = new $config["uploadDriver"]["driver"];

			if (!array_key_exists("AlesWita\\Components\\DropzoneUploader\\UploadDriver\\IUploadDriver", class_implements($uploadDriver))) {
				throw new DropzoneUploaderException("Upload driver must implements AlesWita\\Components\\DropzoneUploader\\UploadDriver\\IUploadDriver interface!");
			} else {
				if (!is_array($config["uploadDriver"]["settings"])) {
					throw new DropzoneUploaderException("Upload driver settings must be array!");
				} else {
					foreach ($config["uploadDriver"]["settings"] as $key => $val) {
						if (!in_array($key, array_keys($uploadDriver->getSettings()))) {
							throw new DropzoneUploaderException("Unknow upload driver settings '{$key}'!");
						}
					}

					$uploadDriver->setSettings($config["uploadDriver"]["settings"]);
					$dropzoneUploader->addSetup("\$service->setUploadDriver(?)", [$uploadDriver]);
				}
			}
		}

		// settings
		if ($config["settings"]["maxFilesize"] !== NULL) {
			if (!is_string($config["settings"]["maxFilesize"]) && !is_integer($config["settings"]["maxFilesize"]) && !is_float($config["settings"]["maxFilesize"])) {
				throw new DropzoneUploaderException("Maximum file size settings must be integer, float or string!");
			}
			$config["settings"]["maxFilesize"] = $this->convertToBytes($config["settings"]["maxFilesize"]);

			if ($config["settings"]["maxFilesize"] === NULL) {
				throw new DropzoneUploaderException("Maximum file size settings is unknown!");
			}
		}
		if ($config["settings"]["acceptedFiles"] !== NULL) {
			if (!is_string($config["settings"]["acceptedFiles"]) && !is_array($config["settings"]["acceptedFiles"])) {
				throw new DropzoneUploaderException("Accepted files settings must be string or array type!");
			}

			if (is_string($config["settings"]["acceptedFiles"])) {
				$config["settings"]["acceptedFiles"] = (array) $config["settings"]["acceptedFiles"];
			}
		}

		$dropzoneUploader->addSetup("\$service->setSettings(?)", [$config["settings"]]);

		// messages
		if ($config["messages"]["dictRemoveFileConfirmation"] === NULL) {// this is function of dropzone.js: http://www.dropzonejs.com/#config-dictRemoveFileConfirmation
			unset($config["messages"]["dictRemoveFileConfirmation"]);
		}

		$dropzoneUploader->addSetup("\$service->setMessages(?)", [$config["messages"]]);
	}

	/**
	 * @return void
	 */
	public function beforeCompile(): void {
		$config = $this->getConfig($this->defaults);
		$container = $this->getContainerBuilder();

		$dropzoneUploader = $container->getDefinitionByType("AlesWita\\Components\\DropzoneUploader\\Factory");
		$dropzoneUploader->addSetup("\$service->setTranslator(?)", ["@" . $container->getByType("Nette\\Localization\\ITranslator")]);
	}

	/**
	 * @param string|int
	 * @return int|float|NULL
	 */
	private function convertToBytes($from)
	{
		if (Nette\Utils\Validators::isNumericInt($from)) {
			return (int) $from;
		} else {
			$num = Nette\Utils\Strings::trim(Nette\Utils\Strings::substring($from, 0, -2));
			$unit = Nette\Utils\Strings::lower(Nette\Utils\Strings::substring($from, -2));

			if (Nette\Utils\Validators::isNumericInt($num)) {
				$num = (int) $num;
			} else {
				$num = (float) $num;
			}

			switch ($unit) {
				case "kb":
					return $num * 1024;
				case "mb":
					return $num * pow(1024, 2);
				case "gb":
					return $num * pow(1024, 3);
				case "tb":
					return $num * pow(1024, 4);
				default:
					return NULL;
			}
		}
	}
}
