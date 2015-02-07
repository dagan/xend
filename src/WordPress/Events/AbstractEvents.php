<?php

namespace Xend\WordPress\Events;

use Xend\WordPress\Events\Exception;

/**
 * Default Events
 *
 * @author Dagan
 */
abstract class AbstractEvents implements \Xend\WordPress\Events\EventsInterface {

    /**
     * @var array
     */
    protected $_actionHooks = array();

    /**
     * @var array
     */
    protected $_filterHooks = array();

    public function __call($function, $args) {

        // Determine the priority key that is being invoked
        $separator = strrpos($function, '_');
        $priority  = (int)substr($function, $separator + 1);
        $function  = substr($function, 0, $separator);

        // Execute Actions & Filters
        if ('do_' == substr($function, 0, 3)) {
            return $this->_do(substr($function, 3), $priority, $args);
        } elseif ('filter_' == substr($function, 0, 7)) {
            return $this->_filter(substr($function, 7), $priority, $args);
        }
    }

    protected function _do($action, $priority, $args) {
        if (array_key_exists($action, $this->_actionHooks)
            && array_key_exists($priority, $this->_actionHooks[$action])) {

            foreach ($this->_actionHooks[$action][$priority] as $callable) {
                call_user_func_array($callable, $args);
            }
        }
    }

    protected function _filter($filter, $priority, $args) {
        if (array_key_exists($filter, $this->_filterHooks)
            && array_key_exists($priority, $this->_filterHooks[$filter])) {

            foreach ($this->_filterHooks[$filter][$priority] as $callable) {
                $args[0] = call_user_func_array($callable, $args);
            }
        }
        return $args[0];
    }

    public function doAction($action, $arg = '') {

        if (!function_exists('do_action')) {
            throw new Exception("The native WordPress function do_action() is not defined");
        }

        call_user_func_array('do_action', func_get_args());
    }

    public function applyFilters($filter, $value) {

        if (!function_exists('apply_filters')) {
            throw new Exception("The native WordPress function apply_filters() is not defined");
        }

        return call_user_func_array('apply_filters', func_get_args());
    }

    public function addAction($action, $callable, $priority = EventsInterface::NORMAL_PRIORITY) {

        if (!function_exists('add_action'))
            throw new Exception('The WordPress function add_action() is not defined.');

        if (!is_callable($callable))
            throw new Exception('The second argument passed to addAction() must be a callable.');

        // If the action hasn't been registered yet, add the array to actionHooks
        if (!array_key_exists($action, $this->_actionHooks)) {
            $this->_actionHooks[$action] = array();
        }

        // If the action priority hasn't been registered yet, add the priority and register the hook
        if (!array_key_exists($priority, $this->_actionHooks[$action])) {
            $this->_actionHooks[$action][$priority] = array();
            add_action($action, array($this, "do_{$action}_{$priority}"), $priority, 10);
        }

        $this->_actionHooks[$action][$priority][] = $callable;

        return $this;
    }

    public function addFilter($filter, $callable, $priority = EventsInterface::NORMAL_PRIORITY) {

        if (!function_exists('add_filter'))
            throw new Exception('The WordPress function add_filter() is not defined.');

        if (!is_callable($callable))
            throw new Exception('The second argument passed to addAction() must be a callable.');

        // The the filter hasn't been registered yet, add the array to filterHooks
        if (!array_key_exists($filter, $this->_filterHooks)) {
            $this->_filterHooks[$filter] = array();
        }

        // If the filter priority hasn't been registered yet, add the priority and register the hook
        if (!array_key_exists($priority, $this->_filterHooks[$filter])) {
            $this->_filterHooks[$filter][$priority] = array();
            add_filter($filter, array($this, "filter_{$filter}_{$priority}"), $priority, 10);
        }

        $this->_filterHooks[$filter][$priority][] = $callable;

        return $this;
    }
}
