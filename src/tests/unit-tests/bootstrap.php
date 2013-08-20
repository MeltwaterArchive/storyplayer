<?php

// =========================================================================
//
// tests/bootstrap.php
//		A helping hand for running our unit tests
//
// Author	Stuart Herbert
//		(stuart@stuartherbert.com)
//
// Copyright	(c) 2011 Stuart Herbert
//		Released under the New BSD license
//
// =========================================================================

use Phix_Project\Autoloader4\PSR0_Autoloader;
use Phix_Project\Autoloader4\Autoloader_Path;

// step 1: create the APP_TOPDIR constant that all components require
define('APP_TOPDIR',  realpath(__DIR__ . '/../../php'));
define('APP_TESTDIR', realpath(__DIR__ . '/php'));
define('APP_LIBDIR',  realpath(__DIR__ . '/../../../vendor/php'));
define('APP_BINDIR',  realpath(APP_TOPDIR . '/../bin'));
define('APP_DATADIR', realpath(APP_TOPDIR . '/../data'));

// step 2: find the autoloader, and install it
require_once('vendor/autoload.php');

// step 3: enable autoloading
//
// the autoloader will automatically add the vendor folder to PHP's
// include_path
PSR0_Autoloader::startAutoloading();

// step 4: add the additional paths to the include path
Autoloader_Path::searchFirst(APP_TESTDIR);
Autoloader_Path::searchFirst(APP_TOPDIR);

// step 5: enable ContractLib if it is available
if (class_exists('Phix_Project\ContractLib\Contract'))
{
        \Phix_Project\ContractLib\Contract::EnforceWrappedContracts();
}
