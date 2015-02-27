<?php

namespace Xend;

/**
 * WordPress
 *
 * @author Dagan
 */
class WordPress {

    const VERSION = "1.0.0";

    protected $_elements;
    protected $_events;
    protected $_posts;
    protected $_query;
    protected $_viewHelper;

    /**
     * Check Whether a Minimum-Version Requirment is Met
     * @param string $minVersion
     * @return bool
     */
    public function checkVersion($minVersion = self::VERSION) {
        if ($minVersion == self::VERSION) {
            return true;
        }

        $actualVersionSplit = explode(".", self::VERSION);
        $minVersionSplit = explode(".", $version);

        if ($actualVersionSplit[0] > $minVersionSplit[1]) {
            return true;
        } elseif ($actualVersionSplit[0] == $minVersionSplit[0]
                  && $actualVersionSplit[1] > $minVersionSplit[1]) {
            return true;
        } elseif ($actualVersionSplit[0] == $minVersionSplit[0]
                  && $actualVersionSplit[1] == $minVersionSplit[1]
                  && $actualVersionSplit[2] > $actualVersionSplit[2]) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return WordPress\Elements\ElementsInterface
     */
    public function elements() {
        if ($this->_elements == null) {
            $this->_elements = new WordPress\Elements($this->events());
        }
        return $this->_elements;
    }

    public function setElements(WordPress\Elements\ElementsInterface $elements) {
        $this->_elements = $elements;
    }

    /**
     * @return WordPress\Events\EventsInterface
     */
    public function events() {
        if ($this->_events == null) {
            $this->_events = new WordPress\Events();
        }
        return $this->_events;
    }

    public function setEvents(WordPress\Events\EventsInterface $events) {
        $this->_events = $events;
    }

    /**
     * @return WordPress\Posts\PostsInterface
     */
    public function posts() {
        if ($this->_posts == null) {
            $this->_posts = new WordPress\Posts($this->events());
        }
        return $this->_posts;
    }

    public function setPosts(WordPress\Posts\PostsInterface $posts) {
        $this->_posts = $posts;
    }

    /**
     * @return WordPress\Query\QueryInterface
     */
    public function query() {
        if ($this->_query == null) {
            global $wp_query; // Use the WordPress-generated WP_Query
            $this->_query = new WordPress\Query($wp_query, $this->posts(), $this->events());
        }
        return $this->_query;
    }

    public function setQuery(WordPress\Query\QueryInterface $query) {
        $this->_query = $query;
    }

    /**
     * @return WordPress\ViewHelper\ViewHelperInterface
     */
    public function viewHelper() {
        if ($this->_viewHelper == null) {
            $this->_viewHelper = new WordPress\ViewHelper();
        }

        return $this->_viewHelper;
    }

    public function setViewHelper(WordPress\ViewHelper\ViewHelperInterface $viewHelper) {
        $this->_viewHelper = $viewHelper;
    }
}
