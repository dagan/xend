<?php

namespace Xend\WordPress;

class EventsTest extends TestCase {

    /**
     * @var Events
     */
    protected $fixture;

    public function setUp() {
        $this->fixture = new Events();
    }

    public function testDoAction() {
        $this->wordpress()->expects('do_action', $this->once())->with('test_run', 'arg1', 'arg2', 'arg3');
        $this->fixture->doAction('test_run', 'arg1', 'arg2', 'arg3');
    }

    public function testApplyFilters() {
        $this->wordpress()
             ->expects('apply_filters', $this->once())
             ->with('test_filter', 'value', 'arg1', 'arg2')
             ->willReturnArgument(1);
        $this->assertEquals('value', $this->fixture->applyFilters('test_filter', 'value', 'arg1', 'arg2'));
    }

    public function testAddAction() {
        $this->wordpress()
             ->expects('add_action', $this->exactly(3))
             ->withConsecutive(array('action_one', array($this->fixture, 'do_action_one_10'), 10, 10),
                               array('action_one', array($this->fixture, 'do_action_one_0'), 0, 10),
                               array('action_two', array($this->fixture, 'do_action_two_99'), 99, 10));
        $this->fixture->addAction('action_one', function() {})
                      ->addAction('action_one', function() {}, Events::NORMAL_PRIORITY)
                      ->addAction('action_one', function() {}, Events::HIGH_PRIORITY)
                      ->addAction('action_two', function() {}, Events::LOW_PRIORITY);
    }

    /**
     * @depends testAddAction
     */
    public function testInvokeActionCallbacks() {
        $results = array();
        $first = function() use (&$results) {
            $results[] = array_merge(array('first'), func_get_args());
        };
        $second = function() use (&$results) {
            $results[] = array_merge(array('second'), func_get_args());
        };
        $third = function () use (&$results) {
            $results[] = array_merge(array('third'), func_get_args());
        };
        $fourth = function () use (&$results) {
            $results[] = array_merge(array('fourth'), func_get_args());
        };

        $this->fixture->addAction('test_run', $first, Events::HIGH_PRIORITY);
        $this->fixture->addAction('test_run', $second);
        $this->fixture->addAction('test_run', $third, Events::NORMAL_PRIORITY);
        $this->fixture->addAction('test_run', $fourth, Events::LOW_PRIORITY);

        $this->fixture->do_test_run_0('arg1', 'arg2');
        $this->fixture->do_test_run_10('arg1', 'arg2');
        $this->fixture->do_test_run_99('arg1', 'arg2');

        $this->assertEquals(
            array(
                array('first', 'arg1', 'arg2'),
                array('second', 'arg1', 'arg2'),
                array('third', 'arg1', 'arg2'),
                array('fourth', 'arg1', 'arg2'),
            ),
            $results);
    }

    public function testAddFilter() {
        $this->wordpress()
             ->expects('add_filter', $this->exactly(3))
             ->withConsecutive(array('filter_one', array($this->fixture, 'filter_filter_one_10'), 10, 10),
                               array('filter_one', array($this->fixture, 'filter_filter_one_0'), 0, 10),
                               array('filter_two', array($this->fixture, 'filter_filter_two_99'), 99, 10));
        $this->fixture->addFilter('filter_one', function() {})
                      ->addFilter('filter_one', function() {}, Events::NORMAL_PRIORITY)
                      ->addFilter('filter_one', function() {}, Events::HIGH_PRIORITY)
                      ->addFilter('filter_two', function() {}, Events::LOW_PRIORITY);
    }

    /**
     * @depends testAddFilter
     */
    public function testInvokeFilterCallbacks() {
        $results = array();
        $first = function($value) use (&$results) {
            $results[] = array_merge(array('first'), func_get_args());
            return "{$value}:one";
        };
        $second = function($value) use (&$results) {
            $results[] = array_merge(array('second'), func_get_args());
            return "{$value}:two";
        };
        $third = function ($value) use (&$results) {
            $results[] = array_merge(array('third'), func_get_args());
            return "{$value}:three";
        };
        $fourth = function ($value) use (&$results) {
            $results[] = array_merge(array('fourth'), func_get_args());
            return "{$value}:four";
        };

        $this->fixture->addFilter('test_run', $first, Events::HIGH_PRIORITY);
        $this->fixture->addFilter('test_run', $second);
        $this->fixture->addFilter('test_run', $third, Events::NORMAL_PRIORITY);
        $this->fixture->addFilter('test_run', $fourth, Events::LOW_PRIORITY);

        $this->assertEquals("test:one", $this->fixture->filter_test_run_0('test', 'arg1', 'arg2'));
        $this->assertEquals("test:two:three", $this->fixture->filter_test_run_10('test', 'arg1', 'arg2'));
        $this->assertEquals("test:four", $this->fixture->filter_test_run_99('test', 'arg1', 'arg2'));

        $this->assertEquals(
            array(
                array('first', 'test', 'arg1', 'arg2'),
                array('second', 'test', 'arg1', 'arg2'),
                array('third', 'test:two', 'arg1', 'arg2'),
                array('fourth', 'test', 'arg1', 'arg2'),
            ),
            $results);
    }
}
