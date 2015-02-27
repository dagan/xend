<?php

namespace Xend;

if (!class_exists("Xend\\Loader", false)) {

    class Loader {

        protected $_version;
        protected $_callback;
        protected $_xend;

        public function register($version, $callback) {

            if (isset($this->_xend)) {
                throw new Exception("Xend::register() cannot be called after Xend::loader() has been called");
            }

            $version = explode('.', $version);
            if (!isset($this->_version)) {
                $this->_version  = $version;
                $this->_callback = $callback;
            } elseif ($version[0] > $this->_version[0]) {
                $this->_version  = $version;
                $this->_callback = $callback;
            } elseif ($version[0] == $this->_version[0] && $version[1] > $this->_version[1]) {
                $this->_version  = $version;
                $this->_callback = $callback;
            } elseif ($version[0] == $this->_version[0] && $version[1] == $this->_version[1] && $version[2] > $this->_version[2]) {
                $this->_version  = $version;
                $this->_callback = $callback;
            }
        }

        public function load() {
            if (!isset($this->_xend)) {
                $this->_xend = call_user_func($this->_callback);
                do_action('xend_loaded', $this->_xend, implode(".", $this->_version));
            }
            return $this->_xend;
        }
    }

    $loader = new Loader();
    add_action('xend_register', array($loader, 'register'), 10, 2);
    add_action('after_setup_theme', array($loader, 'load'), 0, 0);
}

do_action('xend_register', "1.0.0", function() {
    require_once("Autoloader.php");
    Autoloader::init();
    return new \Xend\WordPress();
});