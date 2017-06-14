<?php

/**
 * This file is part of the AlesWita\Components\DropzoneUploader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\Components\DropzoneUploader\Tests\App\Presenters;

use AlesWita;
use Nette;


/**
 * @author Ales Wita
 * @license MIT
 */
final class BaseFormPresenter extends Nette\Application\UI\Presenter
{
	/** @var AlesWita\Components\DropzoneUploader\Factory @inject */
	public $dropzoneUploader;

	/**
	 * @return void
	 */
	public function actionOne(): void {
		$this->setView("default");
	}

	/**
	 * @return AlesWita\Components\DropzoneUploader\DropzoneUploader
	 */
	protected function createComponentDropzoneUploader(): AlesWita\Components\DropzoneUploader\DropzoneUploader {
		return $this->dropzoneUploader->getDropzoneUploader();
	}
}
