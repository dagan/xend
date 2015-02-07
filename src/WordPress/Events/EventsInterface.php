<?php

namespace Xend\WordPress\Events;

/**
 * Events Interface
 *
 * @author Dagan
 */
interface EventsInterface {

    const HIGH_PRIORITY = 0;
    const NORMAL_PRIORITY = 10;
    const LOW_PRIORITY = 99;
    const LOWEST_PRIORITY = 100;

    /**
     * Trigger a WordPress Action
     *
     * @param string $action
     * @param mixed $arg,...
     */
    public function doAction($action, $arg = '');

    /**
     * Apply a WordPress Filter
     *
     * @param string $filter
     * @param mixed $value
     * @param mixed $arg,...
     * @return mixed
     */
    public function applyFilters($filter, $value);

    /**
     * Register a Callable to Be Executed When a WordPress Action Occurs
     *
     * @param string $action The action to hook to
     * @param callable $callable The callable to execute
     * @param int $priority The priority of execution. Lower numbers are executed earlier.
     * @return \Xend\WordPress\Events
     * @throws \Xend\WordPress\Events\Exception
     */
    public function addAction($action, $callable, $priority = EventsInterface::NORMAL_PRIORITY);

    /**
     * Register a Callable to Filter a WordPress Value
     *
     * @param string $filter The filter to hook to
     * @param callable $callable The callable to register
     * @param int $priority The priority of execution. Lower numbers are executed earlier
     * @return \Xend\WordPress\Events
     * @throws \Xend\WordPress\Events\Exception
     */
    public function addFilter($filter, $callable, $priority = EventsInterface::NORMAL_PRIORITY);
}
