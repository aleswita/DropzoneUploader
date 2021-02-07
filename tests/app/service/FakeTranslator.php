<?php

/**
 * This file is part of the AlesWita\DropzoneUploader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\DropzoneUploader\Tests\App\Service;

use Nette;


/**
 * @author Ales Wita
 * @license MIT
 */
final class FakeTranslator implements Nette\Localization\Translator
{

	/**
	 * @param mixed $message
	 * @param mixed ...$parameters
	 *
	 * @return string
	 */
	public function translate($message, ...$parameters): string
	{
		return $message;
	}
}
