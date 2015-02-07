<?php

namespace Xend\WordPress;

class Mock {

    const WORDPRESS_LATEST = "4.1";

    private static $_self;
    private static $_apiVersion = self::WORDPRESS_LATEST;

    /**
     * Retrieve the Static WordPress\Mock Instance
     *
     * @return \Xend\WordPress\Mock
     */
    public static function getInstance() {
        if (static::$_self == null) {
            $folder = sprintf(__DIR__ . "/wp-v%s", static::$_apiVersion);
            require_once("$folder/functions.php");
            require_once("$folder/post.php");
            require_once("$folder/query.php");
            require_once("$folder/widgets.php");
            static::$_self = new static();
        }

        return static::$_self;
    }

    /**
     * Retrieve the Current API Version
     *
     * @return string The loaded API version
     */
    public static function getApiVersion() {
        return static::$_apiVersion;
    }

    /**
     * Set the API Version
     *
     * @param $version
     * @throws \Exception If the API functions and classes have already been loaded
     */
    public static function setApiVersion($version) {
        if (!isset(static::$_self)) {
            self::$_apiVersion = (strtolower($version) == "latest") ? static::WORDPRESS_LATEST : $version;
        } else {
            throw new \Exception("Cannot set WordPress API version after the static instance has been instantiated");
        }
    }


    /**
     * @var \PHPUnit_Framework_MockObject_InvocationMocker
     */
    private $_invocationMocker;

    protected function __construct() {
        $this->reset();
    }

    public function reset() {
        $this->_invocationMocker = new \PHPUnit_Framework_MockObject_InvocationMocker();
    }

    /**
     * Define a WordPress Function Invocation Expectation
     *
     * @param string $function The function that is expected to be invoked
     * @param \PHPUnit_Framework_MockObject_Matcher_Invocation $invocation
     * @return \PHPUnit_Framework_MockObject_Builder_InvocationMocker
     */
    public function expects($function, \PHPUnit_Framework_MockObject_Matcher_Invocation $matcher) {
        return $this->_invocationMocker->expects($matcher)->method($function);
    }

    /**
     * Declare That a Funciton has Been Invoked
     *
     * @param string $function The function that has been invoked
     * @param array $args An indexed array of arguments passed to the function
     * @return mixed The return value of the first matching invocation matcher
     */
    public function invoke($function, $args = array()) {
        return $this->_invocationMocker->invoke(new \PHPUnit_Framework_MockObject_Invocation_Object(
                                                   'WordPressMock', $function, $args, $this));
    }

    /**
     * Verify Each of the Expected Invocations Occurred
     *
     * @return void
     * @throws PHPUnit exception
     */
    public function verifyFunctionCalls() {
        $this->_invocationMocker->verify();
    }
}
