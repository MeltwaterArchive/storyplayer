#! /usr/bin/env php
<?php

// A quick and dirty script to generate the sidebar, and all of the
// 'prev' / 'next' navigation hints
//
// Much more reliable than maintaining all of it by hand!

function dieMsg($msg)
{
	echo "*** error: " . $msg . "\n";
	exit(1);
}

$pathToBook = realpath(__DIR__ . '/../');

$tocFilename = str_replace('//', '/', $pathToBook . "/toc.json");

if (!file_exists($tocFilename))
{
	dieMsg("Unable to find book's table of contents file $tocFilename");
}

// our table of contents tells us what pages go in what order
$json = json_decode(file_get_contents($pathToBook . "/toc.json"));

// our table of contents
$toc = $json->contents;

// how much info do we want in our sidebar?
$navDepth = $json->navDepth;

// this is the list of data we've learned about each page
$pages = array();

foreach ($toc as $pageName)
{
	// build up the information about each page
	$page = array();

	// where is the source page?
	$page['MdFilename'] = $pathToBook . "/$pageName.md";

	// load the page from the _site folder
	$page['HtmlFilename'] = __DIR__ . "/../_site/$pageName.html";
	if (!file_exists($page['HtmlFilename']))
	{
		// the page may not have been written next
		echo "Cannot find filename " . $page['HtmlFilename'] . "\n";
		continue;
	}

	// load the HTML version of the page
	// we don't add this to the page[] array because we don't want to
	// run out of memory if the site gets too big
	$pageHtml = file_get_contents($page['HtmlFilename']);

	// extract the page title
	preg_match("|<title>(.*)</title>|", $pageHtml, $matches);
	$page['title'] = $matches[1];

	// extract the h2 headings
	if ($navDepth > 1) {
		preg_match_all('|<h2 id=\'(.*)\'>(.*)</h2>|', $pageHtml, $matches);
		if (isset($matches[1]))
		{
			$i = 0;

			foreach ($matches[1] as $id)
			{
				$page['h2'][] = array(
					'id' => $id,
					'text' => $matches[2][$i]
				);

				$i++;
			}
		}
	}

	// we're finished with the HTML
	unset($pageHtml);

	// all done
	$page['name'] = $pageName;
	$pages[] = $page;
}

// output our finished sidebar
$sidebar = "<h3>Contents</h3>\n<ol>";
foreach ($pages as $page)
{
	// add the links into the sidebar
	$sidebar .= '<li><a href="' . $page['name'] . '.html">' . $page['title'] . "</a></li>\n";
	echo $page['title'] . "\n";

	if (isset($page['h2']))
	{
		$sidebar .="<ul>\n";
		foreach ($page['h2'] as $h2)
		{
			$sidebar .= '<li><a href="' . $page['name'] . '.html#' . $h2['id'] . '">' . $h2['text'] . "</a></li>\n";
			echo "- " . $h2["text"] . "\n";
		}

		$sidebar .= "</ul>\n";
	}
}

$sidebar .= "\n</ol>\n";
file_put_contents(__DIR__ . "/../_includes/sidebar/sidebar.html", $sidebar);

function findInYaml($key, $source)
{
	$i = 1;
	while ($i < count($source))
	{
		// have we hit the end?
		if ($source[$i] == '---')
		{
			// yes we have
			return $i;
		}

		// have we found the item we want?
		if (substr($source[$i], 0, strlen($key) + 1) == $key . ':')
		{
			// yes we have
			return $i;
		}

		// if we get here, we need to carry on
		$i++;
	}
}

function getFromYaml($key, $source)
{
	$i = findInYaml($key, $source);

	// is this the end?
	if ($source[$i] == '---') {
		return null;
	}

	// we have data to return
	return substr($source[$i], strlen($key) + 2);
}

function putInYaml($key, $value, $source)
{
	$line = "$key: '$value'";

	$i = findInYaml($key, $source);

	// is this the end of the Yaml?
	if ($source[$i] == '---')
	{
		// inject the line into the array
		array_splice($source, $i, 0, $line);
		return $source;
	}

	// we have data to replace
	$source[$i] = $line;

	return $source;
}

// update the 'prev' / 'next' navigation in all of the source pages
for($i = 0; $i < count($pages); $i++)
{
	// shortcut to save typing
	$page = $pages[$i];

	// what should the 'prev' line be?
	if ($i == 0)
	{
		$prev='&nbsp;';
	}
	else
	{
		$prev='<a href="' . $pages[$i-1]['name'] . '.html">Prev: ' . $pages[$i-1]['title'] . "</a>";
	}

	// what should the 'next' line be?
	if ($i == count($pages) - 1)
	{
		$next = '<a href="' . $pages[0]['name'] . '.html">Back to: ' . $pages[0]['title'] . "</a>";
	}
	else
	{
		$next='<a href="' . $pages[$i+1]['name'] . '.html">Next: ' . $pages[$i+1]['title'] . "</a>";
	}

	$pageSource = explode("\n", file_get_contents($page['MdFilename']));

	// search the nav at the top for the 'prev' element
	$pageSource = putInYaml('prev', $prev, $pageSource);
	$pageSource = putInYaml('next', $next, $pageSource);

	// all done
	$pageSource = implode("\n", $pageSource);
	file_put_contents($page['MdFilename'], $pageSource);
}
