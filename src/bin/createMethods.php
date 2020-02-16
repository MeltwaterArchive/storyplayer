<?php

use DataSift\WebDriver\WebDriverElement;

$indexTypes = [
    '',
    'first',
    'second',
    'third',
    'fourth',
    'fifth',
    'sixth',
    'seventh',
    'eighth',
    'ninth',
    'tenth',
    'eleventh',
    'twelfth',
    'thirteenth',
    'fourteenth',
    'fifteenth',
    'sixteenth',
    'seventeenth',
    'eighteenth',
    'nineteenth',
    'twentieth',
];

$targetTypes = [
    'box',
    'boxes',
    'button',
    'buttons',
    'cell',
    'cells',
    'dropdown',
    'dropdowns',
    'element',
    'elements',
    'field',
    'fields',
    'heading',
    'headings',
    'link',
    'links',
    'orderedlist',
    'span',
    'unorderedlist',
];

$searchTypes = [
    'withId' => 'string $id',
    'withLabel' => 'string $label',
    'labelled' => 'string $label',
    'named' => 'string $name',
    'withName' => 'string $name',
    'withText' => 'string $text',
    'withClass' => 'string $class',
    'withPlaceholder' => 'string $placeholder',
    'withTitle' => 'string $title',
    'withLabelIdOrText' => 'string $labelIdText',
];

foreach ($indexTypes as $indexType) {
    foreach ($targetTypes as $targetType) {
    foreach ($searchTypes as $searchType => $parameter) {
            echo ' * @method WebDriverElement ' . lcfirst($indexType . ucfirst($targetType) . ucfirst($searchType)) . "({$parameter})\n";
        }
    }
}
