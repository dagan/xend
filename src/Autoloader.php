<?php

namespace Xend;

class Autoloader implements \Zend_Loader_Autoloader_Interface {

    protected static $_self;

    public static function init() {
        if (!isset(self::$_self)) {
            if (!class_exists("Zend_Loader_Autoloader")) {
                set_include_path(get_include_path() . PATH_SEPARATOR . realpath("../lib"));
                require_once("Zend/Loader/Autoloader.php");
            }
            self::$_self = new static();
            \Zend_Loader_Autoloader::getInstance()->pushAutoloader(self::$_self);
        }
        return self::$_self;
    }

    protected $_base;

    protected function __construct() {
        $this->_base = __DIR__ . DIRECTORY_SEPARATOR;
    }

    public function autoload($class) {

        if (!"Xend\\" == substr($class, 0, 5)) {
            return false;
        }

        $path = substr($class, 5);
        $path = $this->_base . str_replace("\\", DIRECTORY_SEPARATOR, $path) . '.php';
        if (is_readable($path)) {
            require_once($path);
            return true;
        } else {
            return false;
        }
    }
}