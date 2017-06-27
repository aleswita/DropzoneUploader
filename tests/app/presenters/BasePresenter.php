<?php

/**
 * This file is part of the AlesWita\DropzoneUploader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\DropzoneUploader\Tests\App\Presenters;

use AlesWita;
use Nette;


/**
 * @author Ales Wita
 * @license MIT
 */
final class BasePresenter extends Nette\Application\UI\Presenter
{
	/** @var AlesWita\DropzoneUploader\Factory @inject */
	public $dropzoneUploader;

	/**
	 * @return void
	 */
	public function actionOne(): void {
		$this["dropzoneUploader"]->onBeginning[] = function (AlesWita\DropzoneUploader\DropzoneUploader $uploader): void {
			$uploader->setFolder("foo");
		};

		$this->setView("default");
	}

	/**
	 * @return void
	 */
	public function actionTwo(): void {
		$this->setView("one");
	}

	/**
	 * @return AlesWita\DropzoneUploader\DropzoneUploader
	 */
	protected function createComponentDropzoneUploader(): AlesWita\DropzoneUploader\DropzoneUploader {
		return $this->dropzoneUploader->getDropzoneUploader();
	}
}
