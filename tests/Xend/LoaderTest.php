<?php

namespace Xend;

/**
 * Class LoaderTest
 *
 * @package Xend
 * @group Loader
 */
class LoaderTest extends WordPress\TestCase {

    public function testIncludingFile() {

        require_once(__DIR__ . "/../../src/WordPress.php");

        $this->wordpress()->expects("do_action")->with("xend_register", WordPress::VERSION, $this->isType("callable"));
        $this->wordpress()->expects("add_action", $this->exactly(2))
                          ->withConsecutive(
                              array("xend_register",$this->isType("callable"), 10, 2),
                              array("after_setup_theme", $this->isType("callable"), 0, 0));

        require_once(__DIR__ . "/../../src/Loader.php"); /* @var \Xend\Loader $loader) */


        $this->assertInstanceOf("Xend\\Loader", $loader);
    }

    /**
     * @depends testIncludingFile
     */
    public function testRegister() {

        $fixture = new \Xend\Loader();
        $fixture->register("1.0.0", function() { return false; });
        $fixture->register("1.0.1", function() { return true; });

        $this->wordpress()->expects("do_action")->with("xend_loaded", true, "1.0.1");
        $this->isTrue($fixture->load());
    }

    /**
     * @depends testIncludingFile
     */
    public function testRegister2() {

        $fixture = new \Xend\Loader();
        $fixture->register("1.0.0", function() { return false; });
        $fixture->register("1.0.1", function() { return true; });

        $this->wordpress()->expects("do_action")->with("xend_loaded", true, "1.0.1");
        $this->isTrue($fixture->load());
    }

    /**
     * @depends testIncludingFile
     */
    public function testRegister3() {

        $fixture = new \Xend\Loader();
        $fixture->register("1.0.0", function() { return false; });
        $fixture->register("1.1.0", function() { return true; });

        $this->wordpress()->expects("do_action")->with("xend_loaded", true, "1.1.0");
        $this->isTrue($fixture->load());
    }

    /**
     * @depends testIncludingFile
     */
    public function testRegister4() {

        $fixture = new \Xend\Loader();
        $fixture->register("1.0.0", function() { return false; });
        $fixture->register("2.0.0", function() { return true; });

        $this->wordpress()->expects("do_action")->with("xend_loaded", true, "2.0.0");
        $this->isTrue($fixture->load());
    }

    /**
     * @depends testIncludingFile
     */
    public function testRegister5() {

        $fixture = new \Xend\Loader();
        $fixture->register("1.9.9", function() { return false; });
        $fixture->register("2.0.0", function() { return true; });

        $this->wordpress()->expects("do_action")->with("xend_loaded", true, "2.0.0");
        $this->isTrue($fixture->load());
    }

    /**
     * @depends testIncludingFile
     */
    public function testRegister6() {

        $fixture = new \Xend\Loader();
        $fixture->register("2.0.0", function() { return true; });
        $fixture->register("1.0.0", function() { return false; });

        $this->wordpress()->expects("do_action")->with("xend_loaded", true, "2.0.0");
        $this->isTrue($fixture->load());
    }

    /**
     * @depends testIncludingFile
     */
    public function testRegister7() {

        $fixture = new \Xend\Loader();
        $fixture->register("2.0.0", function() { return true; });
        $fixture->register("1.1.0", function() { return false; });

        $this->wordpress()->expects("do_action")->with("xend_loaded", true, "2.0.0");
        $this->isTrue($fixture->load());
    }

    /**
     * @depends testIncludingFile
     */
    public function testRegister8() {

        $fixture = new \Xend\Loader();
        $fixture->register("2.0.0", function() { return true; });
        $fixture->register("1.0.1", function() { return false; });

        $this->wordpress()->expects("do_action")->with("xend_loaded", true, "2.0.0");
        $this->isTrue($fixture->load());
    }
}