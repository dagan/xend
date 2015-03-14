<?php

namespace Xend\WordPress\ViewHelper;

/**
 * Class CommentList
 *
 * @package WordPress\ViewHelper
 *
 * @property string $style Either 'div', 'ol', or 'ul'. Defines the element
 * that will wrap the list. Default is 'ul'.
 * @property string $type  Either 'all', 'comment', 'trackback', 'pingback',
 * or 'pings' ('pings' includes 'trackback' and 'pingback'). Default is 'all'.
 * @property string $format Either 'html5' or 'xhtml'. Default is html5 if the
 * theme supports it; otherwise the default is xhtml.
 * @property string $replyText The text to use in when building the reply link.
 * Default is 'Reply'.
 * @property int $avatarSize The width (in pixels) at which to display avatars.
 * @property bool $reverseTopLevel Whether to display top-level comments in
 * reverse chronological order.
 * @property bool $reverseChildren Whether to display replies in reverse
 * chronological order.
 * @property int $maxDepth The max. depth at which to fetch comment replies.
 * Default is null.
 * @property int $page The page to render.
 * @property int $perPage The number of comments to display per page.
 * @property bool $echo Whether to print the rendered list or return it as
 * a string. Default is true.
 */
class CommentList {

    protected $_style;
    protected $_type;
    protected $_format;
    protected $_replyText;
    protected $_avatarSize;
    protected $_reverseTopLevel;
    protected $_reverseChildren;
    protected $_maxDepth;
    protected $_page;
    protected $_perPage;
    protected $_echo;

    public function __construct($options = null) {
        if (isset($options)) {
            $this->setOptions($options);
        }
    }

    public function __get($property) {
        $getter = '_get' . ucfirst($property);
        return (method_exists($this, $getter)) ? $this->$getter() : null;
    }

    public function __set($property, $value) {
        $setter = '_set' . ucfirst($property);
        return (method_exists($this, $setter)) ? $this->$setter($value) : null;
    }

    public function __isset($property) {
        return ($this->{$property} !== null);
    }

    /**
     * Sets Menu Options
     *
     * @param array $args
     * @return \Xend\WordPress\CommentList
     */
    public function setOptions($args) {
        foreach ($args as $param => $value) {
            $setter = '_set' . ucfirst($param);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }

        return $this;
    }

    protected function _getStyle() {
        return $this->_style;
    }
    
    protected function _setStyle($style) {
        $this->_style = $style;
    }

    protected function _getReverseChildren() {
        return $this->_reverseChildren;
    }

    protected function _setReverseChildren($reverseChildren) {
        $this->_reverseChildren = $reverseChildren;
    }

    protected function _getType() {
        return $this->_type;
    }

    protected function _setType($value) {
        $this->_type = $value;
    }

    protected function _getFormat() {
        return $this->_format;
    }

    protected function _setFormat($value) {
        $this->_format = $value;
    }

    protected function _getReplyText() {
        return $this->_replyText;
    }

    protected function _setReplyText($value) {
        $this->_replyText = $value;
    }

    protected function _getAvatarSize() {
        return $this->_avatarSize;
    }

    protected function _setAvatarSize($avatarSize) {
        $this->_avatarSize = $avatarSize;
    }

    protected function _getReverseTopLevel() {
        return $this->_reverseTopLevel;
    }

    protected function _setReverseTopLevel($reverseTopLevel) {
        $this->_reverseTopLevel = $reverseTopLevel;
    }

    protected function _getMaxDepth() {
        return $this->_maxDepth;
    }

    protected function _setMaxDepth($maxDepth) {
        $this->_maxDepth = $maxDepth;
    }

    protected function _getPage() {
        return $this->_page;
    }

    protected function _setPage($page) {
        $this->_page = $page;
    }

    protected function _getPerPage() {
        return $this->_perPage;
    }

    protected function _setPerPage($perPage) {
        $this->_perPage = $perPage;
    }

    protected function _getEcho() {
        return $this->_echo;
    }

    protected function _setEcho($echo) {
        $this->_echo = $echo;
    }
}