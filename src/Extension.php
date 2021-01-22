<?php

/**
 * This file is part of the AlesWita\DropzoneUploader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\DropzoneUploader;

use AlesWita\DropzoneUploader\UploadDriver\IUploadDriver;
use Nette;
use Nette\Schema\Expect;



/**
 * @author Ales Wita
 * @license MIT
 */
class Extension extends Nette\DI\CompilerExtension
{

	public function getConfigSchema(): Nette\Schema\Schema {
		return Expect::structure([
			'dropzoneTemplate' => Expect::mixed()->default(null),
			'uploadDriver' => Expect::structure([
				'driver' => Expect::string()->required(),
				'settings' => Expect::array(),
			]),
			'settings' => Expect::structure([
				'method' => Expect::anyOf("POST","post","GET","get")->default("POST"), //Expect::string('POST'),
				'parallelUploads' => Expect::int(1),
				'uploadMultiple' => Expect::bool(false),
				'maxFilesize' => Expect::anyOf(Expect::string(), Expect::int(), Expect::float())->nullable(),
				'paramName' => Expect::string('file'),
				'acceptedFiles' => Expect::array(),
				'addRemoveLinks' => Expect::bool(false),
			]),
			'messages' => Expect::structure([
				'dictDefaultMessage' => Expect::string('Drop files here to upload'),
				'dictFallbackMessage' => Expect::string('Your browser does not support drag\'n\'drop file uploads.'),
				'dictFallbackText' => Expect::string('Please use the fallback form below to upload your files like in the olden days.'),
				'dictFileTooBig' => Expect::string('File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.'),
				'dictInvalidFileType' => Expect::string('You can\'t upload files of this type.'),
				'dictResponseError' => Expect::string('Server responded with {{statusCode}} code.'),
				'dictCancelUpload' => Expect::string('Cancel upload'),
				'dictCancelUploadConfirmation' => Expect::string('Are you sure you want to cancel this upload?'),
				'dictRemoveFile' => Expect::string('Remove file'),
				'dictRemoveFileConfirmation' => Expect::string()->nullable(),
				'dictMaxFilesExceeded' => Expect::string('You can not upload any more files.'),
			])
		]);
	}

	/**
	 * @return void
	 * @throws DropzoneUploaderException
	 */
	public function loadConfiguration(): void
	{
		$config = $this->getConfig();
		$container = $this->getContainerBuilder();

		$dropzoneUploader = $container->addDefinition($this->prefix('dropzoneuploader'))
			->setFactory(Factory::class);

		if ($config->dropzoneTemplate !== null) {
			if ($config->dropzoneTemplate instanceof Nette\DI\Definitions\Statement) {
				$config->dropzoneTemplate = constant($config->dropzoneTemplate->arguments[0]);
			}
			if($config->dropzoneTemplate instanceof \stdClass){
				$config->dropzoneTemplate = (array) $config->dropzoneTemplate;
			}

			if (!is_array($config->dropzoneTemplate) ||
			    !isset($config->dropzoneTemplate['main']) ||
			    !isset($config->dropzoneTemplate['form']) ||
			    !isset($config->dropzoneTemplate['files']) ||
			    !isset($config->dropzoneTemplate['js'])
			) {
				throw new DropzoneUploaderException('Parameter "dropzoneTemplate" must be array with keys "main", "form", "files" and "js"!');
			} elseif (!is_file($config->dropzoneTemplate['main'])) {
				throw new DropzoneUploaderException('Can not find template "' . $config->dropzoneTemplate['main'] . '"!');
			} elseif (!is_file($config->dropzoneTemplate['form'])) {
				throw new DropzoneUploaderException('Can not find template "' . $config->dropzoneTemplate['form'] . '"!');
			} elseif (!is_file($config->dropzoneTemplate['files'])) {
				throw new DropzoneUploaderException('Can not find template "' . $config->dropzoneTemplate['files'] . '"!');
			} elseif (!is_file($config->dropzoneTemplate['js'])) {
				throw new DropzoneUploaderException('Can not find template "' . $config->dropzoneTemplate['js'] . '"!');
			} else {
				$dropzoneUploader->addSetup('$service->setDropzoneTemplate(?)', [$config->dropzoneTemplate]);
			}
		}

		// upload driver
		if (!class_exists($config->uploadDriver->driver)) {
			throw new DropzoneUploaderException('Upload driver "' . $config->uploadDriver->driver . '" no exists!');
		} else {
			$uploadDriver = new $config->uploadDriver->driver;

			if (!array_key_exists('AlesWita\\DropzoneUploader\\UploadDriver\\IUploadDriver', class_implements($uploadDriver))) {
				throw new DropzoneUploaderException('Upload driver must implements ' . IUploadDriver::class . ' interface!');
			} else {
				foreach ($config->uploadDriver->settings as $key => $val) {
					if (!in_array($key, array_keys($uploadDriver->getSettings()), true)) {
						throw new DropzoneUploaderException('Unknow upload driver setting "' . $key . '"!');
					}
				}

				$uploadDriver->setSettings($config->uploadDriver->settings);
				$dropzoneUploader->addSetup('$service->setUploadDriver(?)', [$uploadDriver]);
			}
		}

		// setting maxFilesize
		if($config->settings->maxFilesize !== null) {
			$config->settings->maxFilesize = $this->convertToBytes( $config->settings->maxFilesize );
			if ( $config->settings->maxFilesize === null ) {
				throw new DropzoneUploaderException( 'Maximum file size setting is unknown!' );
			}
		}



		// setting acceptedFiles
		/*if ($config->settings->acceptedFiles !== null) {
			if (!is_string($config->settings->acceptedFiles) && !is_array($config->settings->acceptedFiles)) {
				throw new DropzoneUploaderException('Accepted files setting must be string or array type!');
			}

			if (is_string($config->settings->acceptedFiles)) {
				$config->settings->acceptedFiles = (array) $config->settings->acceptedFiles;
			}
		}*/

		$config->settings->acceptedFiles = (array) $config->settings->acceptedFiles;
		$dropzoneUploader->addSetup('$service->setSettings(?)', [(array) $config->settings]);

		// messages
		if ($config->messages->dictRemoveFileConfirmation === null) {// this is function of dropzone.js: http://www.dropzonejs.com/#config-dictRemoveFileConfirmation
			unset($config->messages->dictRemoveFileConfirmation);
		}

		$dropzoneUploader->addSetup('$service->setMessages(?)', [(array) $config->messages]);
	}


	/**
	 * @return void
	 */
	public function beforeCompile(): void
	{
		$container = $this->getContainerBuilder();

		$dropzoneUploader = $container->getDefinitionByType(Factory::class);
		$dropzoneUploader->addSetup('$service->setTranslator(?)', ['@' . $container->getByType(Nette\Localization\Translator::class)]);
	}


	/**
	 * @param string|int
	 * @return int|float|null
	 */
	private function convertToBytes($from)
	{
		if (Nette\Utils\Validators::isNumericInt($from)) {
			return (int) $from;
		} else {
			$num = Nette\Utils\Strings::trim( Nette\Utils\Strings::substring($from, 0, -2));
			$unit = Nette\Utils\Strings::lower( Nette\Utils\Strings::substring($from, -2));

			if (Nette\Utils\Validators::isNumericInt($num)) {
				$num = (int) $num;
			} else {
				$num = (float) $num;
			}

			switch ($unit) {
				case 'kb':
					return $num * 1024;
				case 'mb':
					return $num * pow(1024, 2);
				case 'gb':
					return $num * pow(1024, 3);
				case 'tb':
					return $num * pow(1024, 4);
				default:
					return null;
			}
		}
	}
}
