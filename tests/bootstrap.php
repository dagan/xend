<?php

// Load the WordPress Mock and TestCase classes
require_once('WordPress/Mock.php');
\Xend\WordPress\Mock::setApiVersion(WORDPRESS_API_VERSION);
\Xend\WordPress\Mock::getInstance();
require_once('WordPress/TestCase.php');

// Set up the Xend Autoloader
require_once(__DIR__ . '/../src/Loader.php');
$loader = new \Xend\Loader();
$loader->register("0.0.0.0", function(){
    require_once(__DIR__ . "/../vendor/autoload.php");
    require_once(__DIR__ . "/../src/Autoloader.php");
    \Xend\Autoloader::init();
    return new \Xend\WordPress();
});
$loader->load();

// Prevent PHPUnit from globalizing $loader
unset($loader);