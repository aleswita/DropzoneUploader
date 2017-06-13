# Dropzone Uploader
Dropzone Uploader for [Nette Framework](https://nette.org) and [DropzoneJs](http://www.dropzonejs.com).

[![Build Status](https://travis-ci.org/aleswita/DropzoneUploader.svg?branch=master)](https://travis-ci.org/aleswita/DropzoneUploader)
[![Coverage Status](https://coveralls.io/repos/github/aleswita/DropzoneUploader/badge.svg?branch=master)](https://coveralls.io/github/aleswita/DropzoneUploader?branch=master)

#### TODO
Vytvořeno pro vlastní potřebu, proto je hotov pouze FTP upload driver, pokud bude zájem o použití s jiným typem uploadu, založte issue nebo pošlete pull request.

## Installation
The best way to install AlesWita/WebLoader is using [Composer](http://getcomposer.org/):
```sh
# For PHP 7.1, Nette Framework 2.4/3.0 and DropzoneJs 5.0
$ composer require aleswita/dropzoneuploader:dev-master
```


## Usage

#### Config
```neon
extensions:
	webloader: AlesWita\Components\DropzoneUploader\Extension

dropzoneuploader:
	dropzoneTemplate: ::constant(AlesWita\Components\DropzoneUploader\Factory::BOOTSTRAP_V4_TEMPLATE)
	uploadDriver:
		driver: AlesWita\Components\DropzoneUploader\UploadDriver\Ftp
		settings:
			url: ftp://user:password@my-ftp.cz
	settings:
		maxFilesize: 1mb
		acceptedFiles:
			xls: application/vnd.ms-excel
			xlsx: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
		addRemoveLinks: TRUE
	messages:
		dictDefaultMessage: "dropzone.dictDefaultMessage"
```

#### Presenter
```php
use AlesWita;
use Nette\Application;


final class DropzonePresenter extends Application\UI\Presenter
{
	/** @var AlesWita\Components\DropzoneUploader\Factory @inject */
	public $dropzoneFactory;

	...
	...

	/**
	 * @return AlesWita\Components\DropzoneUploader\DropzoneUploader
	 */
	protected function createComponentDropzoneForm(): AlesWita\Components\DropzoneUploader\DropzoneUploader {
		$form = $this->dropzoneFactory->getDropzoneUploader();

		$form->getUploadDriver()->onUploadBeginning[] = function (AlesWita\Components\DropzoneUploader\UploadDriver\IUploadDriver $uploadDriver, Nette\Http\FileUpload $file): void {
			$uploadDriver->setFolder("foo");
		};

		$form->getUploadDriver()->onRemoveBeginning[] = function (AlesWita\Components\DropzoneUploader\UploadDriver\IUploadDriver $uploadDriver, string $file): void {
			$uploadDriver->setFolder("foo");
		};

		return $form;
	}
}
```

#### Template
```latte
{control dropzoneForm}
```
