<?php

namespace Xend\WordPress;

abstract class TestCase extends \PHPUnit_Framework_TestCase {

    private $_wordpress;

    /**
     * Retrieve the WordPress Mock Object
     *
     * @return \Xend\WordPress\Mock
     */
    protected function wordpress() {
        return \Xend\WordPress\Mock::getInstance();
    }

    protected function verifyMockObjects() {
        parent::verifyMockObjects();
        try {
            $this->wordpress()->verifyFunctionCalls();
            $this->wordpress()->reset();
        } catch (\Exception $ex) {
            $this->wordpress()->reset();
            throw $ex;
        }
    }
}
