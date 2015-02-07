<?php

// Instantiate the Composer Autoloader
require_once('../vendor/autoload.php');

// Load the WordPress Mock and TestCase classes
require_once('WordPress/Mock.php');
\Xend\WordPress\Mock::setApiVersion(WORDPRESS_API_VERSION);
\Xend\WordPress\Mock::getInstance();
require_once('WordPress/TestCase.php');