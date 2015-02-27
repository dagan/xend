<?php

namespace Xend\WordPress\ViewHelper;

/**
 * Menu Options
 *
 * @author Dagan
 * @property string          $ref            The menu to retrieve. If $refBy is set to REF_BY_LOCATION (default),
 *                                           then $ref must be set to a valid theme location. If $refBy is set to
 *                                           REF_BY_OTHER, $ref will be checked against menu IDs, slugs, and names
 *                                           (in that order).
 * @property int             $refBy          Either Menu::REF_BY_LOCATION or Menu::REF_BY_OTHER
 * @property string|bool     $container      The type of container element to use (or false to use none)
 * @property string          $containerId    The value of the container's ID attribute
 * @property string          $containerClass The value of the container's class attribute
 * @property string          $menuId         The value of the menu's ID attribute
 * @property string          $menuClass      The value of the menu's class attribute
 * @property string          $beforeLink     A string to insert before each menu item's <a> element
 * @property string          $afterLink      A string to insert after each menu items's <a> element
 * @property string          $beforeLinkText A string to insert before each menu item's link text
 * @property string          $afterLinkText  A string to insert after each menu item's link text
 * @property string          $renderPattern  The sprintf pattern used to render the menu. The default is
 *                                           '<ul id="%1$s" class="%2$s">%3$s</ul>'. %1$s matches the menuId.
 *                                           %2$s mtaches the menuClass. %3$s matches the list items. To exculde
 *                                           the wrapper and only output the menu items, set to '%3$s'.
 * @property int             $maxDepth       The maximum depth of items to retrieve. Default is 0 (retrieve all).
 * @property callable|string $fallback       Either a callback to execute or a string to print if the menu is
 *                                           not defined. The default is to render the first non-empty menu.
 */
class Menu {

    const REF_BY_LOCATION = 0;
    const REF_BY_OTHER = 1;

    protected $_ref;
    protected $_refBy;
    protected $_container = 'div';
    protected $_containerClass;
    protected $_containerId;
    protected $_menuClass;
    protected $_menuId;
    protected $_before;
    protected $_after;
    protected $_linkBefore;
    protected $_linkAfter;
    protected $_renderPattern;
    protected $_maxDepth = 0;
    protected $_fallback;

    public function __construct($ref = null, $args = null, $refBy = Menu::REF_BY_LOCATION) {
        $this->_ref = $ref;
        $this->_refBy = $refBy;
        if (isset($args)) {
            $this->setOptions($args);
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
        return ($this->{$property} != null);
    }

    /**
     * Sets Menu Options
     *
     * @param array $args
     * @return \Xend\WordPress\Menu
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

    protected function _getRef() {
        return $this->_ref;
    }

    protected function _getRefBy() {
        return $this->_refBy;
    }

    protected function _getContainer() {
        return $this->_container;
    }

    protected function _setContainer($value) {
        $this->_container = $value;
    }

    protected function _getContainerId() {
        return $this->_containerId;
    }

    protected function _setContainerId($value) {
        $this->_containerId = $value;
    }

    protected function _getContainerClass() {
        return $this->_containerClass;
    }

    protected function _setContainerClass($value) {
        $this->_containerClass = $value;
    }

    protected function _getMenuId() {
        return $this->_menuId;
    }

    protected function _setMenuId($value) {
        $this->_menuId = $value;
    }

    protected function _getMenuClass() {
        return $this->_menuClass;
    }

    protected function _setMenuClass($value) {
        $this->_menuClass = $value;
    }

    protected function _getBeforeLink() {
        return $this->_before;
    }

    protected function _setBeforeLink($value) {
        $this->_before = $value;
    }

    protected function _getAfterLink() {
        return $this->_after;
    }

    protected function _setAfterLink($value) {
        $this->_after = $value;
    }

    protected function _getBeforeLinkText() {
        return $this->_linkBefore;
    }

    protected function _setBeforeLinkText($value) {
        $this->_linkBefore = $value;
    }

    protected function _getAfterLinkText() {
        return $this->_linkAfter;
    }

    protected function _setAfterLinkText($value) {
        $this->_linkAfter = $value;
    }

    protected function _getRenderPattern() {
        return $this->_renderPattern;
    }

    protected function _setRenderPattern($value) {
        $this->_renderPattern = $value;
    }

    protected function _getMaxDepth() {
        return $this->_maxDepth;
    }

    protected function _setMaxDepth($value) {
        $this->_maxDepth = $value;
    }

    protected function _getFallback() {
        return (is_null($this->_fallback)) ? '' : call_user_func($this->_fallback);
    }

    protected function _setFallback($value) {
        if (is_callable($value)) {
            $this->_fallback = $value;
        } else {
            $this->_fallback = function () use ($value) {
                return $value;
            };
        }
    }
}
