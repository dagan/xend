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
        if (!isset($this->_wordpress)) {
            $this->_wordpress = \Xend\WordPress\Mock::getInstance();
            $this->_wordpress->reset();
        }
        return $this->_wordpress;
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
