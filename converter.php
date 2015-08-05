#!/usr/bin/env php
<?php
$extensions = ['mp4'];
$rootPath = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
$savePath = $rootPath . 'mp3';
if ( !file_exists($savePath) ) {
	echo "Save dir $savePath do not exists, attempt to create it...\n";
	if ( !mkdir($savePath) ) {
		die("Target save dir $savePath cannot be created!\n");
	}
}
$savePath .= DIRECTORY_SEPARATOR;

$files = [];
$dirIterator = new DirectoryIterator($rootPath);
foreach ( $dirIterator as $dir ) {
	if ( !$dir->isDot() && !$dir->isDir() && in_array($dir->getExtension(), $extensions) ) {
		$tmpName = explode('-', $dir->getFilename());
		$mp3name = str_replace(' ', '', $tmpName[0]).'-'.str_replace(' ','', $tmpName[1]).'.mp3';
		$_h = substr(sha1($mp3name),0,6);

		$files[$_h] = [
			'in' => $rootPath . $dir->getFilename(), //str_replace(' ','\ ', $dir->getFilename()),
			'out' => $savePath . $mp3name,
			'name' => $dir->getFilename()
		];
	}
}

foreach ( $files as $hash => $file ) {
	echo "Converting ".$file['name']." ...\n\n";
	system( 'ffmpeg -i "'.$file['in'].'" -ab 320k "'. $file['out'].'"' );
	echo "\nDONE !\n\n";
}