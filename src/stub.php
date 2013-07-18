<?php
Phar::mapPhar('storyplayer.phar');

$basePaths = array("src", "vendor");

foreach ($basePaths as $path){
spl_autoload_register(function ($className) use ($path) {
	$libPath = 'phar://storyplayer.phar/'.$path.'/';
	$classFile = str_replace(array('\\', '_'),DIRECTORY_SEPARATOR,$className).'.php';
	$classPath = $libPath.$classFile;
	if (file_exists($classPath)) {
		require $classPath ;
	}
});
}
require 'phar://storyplayer.phar/src/bin/storyplayer';
__HALT_COMPILER();
