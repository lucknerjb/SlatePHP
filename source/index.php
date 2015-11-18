<?php

/*
 * @TODO: Need to ensure that some files exist before attempting to load them:
 *  - config.json
 *  - source/layout.php
 *  - In the foreach menu loop: each file. Do the check in SlatePHP.php
*/

// Set the project base path
$projectBase = dirname(dirname(__FILE__));

// Include the main script
require_once $projectBase . '/SlatePHP.php';

// Fetch the config
$config = json_decode(file_get_contents($projectBase . '/config.json'));

// Build the docs in the order specified by the menu items in the config
// Individual pages will include sub-pages as needed
$pages = [];
$slatePHP = new SlatePHP;
$slatePHP->setSourceDirectory($projectBase . "/source");
foreach($config->menu as $page) {
    $pages[$page] = $slatePHP->setFile($page)->parse();
}

if ($config->menu_includes) {
    $slatePHP->setSourceDirectory($projectBase . "/source/includes");
    foreach($config->menu_includes as $page) {
        $pages[$page] = $slatePHP->setFile($page)->parse();
    }
}

// Inclide the layout file, which will actually render the page
include_once $projectBase . '/source/layouts/layout.php';
