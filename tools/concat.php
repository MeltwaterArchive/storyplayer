#! /usr/bin/env php
<?php

// A quick and dirty script to turn the book into a single Markdown document,
// to feed into pandoc.
//
// Much more reliable than maintaining all of it by hand!
//
// $argv[1] : path to the folder to build

function dieMsg($msg)
{
	echo "*** error: " . $msg . "\n";
	exit(1);
}

$pathToBook = realpath(__DIR__ . '/..');

$tocFilename = str_replace('//', '/', $pathToBook . "/toc.json");

if (!file_exists($tocFilename))
{
	dieMsg("Unable to find book's table of contents file $tocFilename");
}

// our table of contents tells us what pages go in what order
$json = json_decode(file_get_contents($pathToBook . "/toc.json"));
$toc = $json->contents;

// this is the file that we will write to
$output = fopen($pathToBook . '/one-page.md', 'w+');

foreach ($toc as $pageName)
{
	// where is the source page?
	$page['MdFilename'] = $pathToBook . "/$pageName.md";

	// load the HTML version of the page
	// we don't add this to the page[] array because we don't want to
	// run out of memory if the site gets too big
	$pageMarkdown = file_get_contents($page['MdFilename']);

	// strip out the YAML at the top of the file
	$pageMarkdown = preg_replace('/---.*---/sU', '', $pageMarkdown);

	// write the output to the concat file
	fwrite($output, $pageMarkdown . "\n");

	// we're finished with the HTML
	unset($pageMarkdown);
}

// all done
fclose($output);