<?php

namespace Xend\WordPress\Elements;

/**
 * Class Sidebar
 *
 * @property $id string sidebar's base ID
 * @property $name string The sidebar's name as shown to admins and used to render by name
 * @property $description (optional) string A friendly description of the sidebar that is shown to admins
 * @property $class string (optional) The CSS class to assign to each widget
 * @property $beforeWidget string (optional) The HTML to insert before each widget
 * @property $afterWidget string (optional) The HTML to insert after each widget
 * @property $beforeTitle string (optional) The HTML to insert before each widget title
 * @property $afterTitle string (optional) The HTML to insert after each widget title
 */
class Sidebar {

    protected $_id;
    protected $_name;
    protected $_description;
    protected $_class;
    protected $_beforeWidget;
    protected $_afterWidget;
    protected $_beforeTitle;
    protected $_afterTitle;

    public function __construct($name = null, $description = null) {

        if (isset($name)) {
            $this->name = $name;
        }

        if (isset($description)) {
            $this->description = $description;
        }
    }

    public function __get($property) {
        $getter = '_get' . ucfirst($property);
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }
    }

    public function __set($property, $value) {
        $setter = '_set' . ucfirst($property);
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        }
    }

    public function __isset($property) {
        return ($this->$property !== null);
    }

    protected function _getId() {
        return $this->_id;
    }

    protected function _setId($value) {
        $this->_id = $value;
    }

    protected function _getName() {
        return $this->_name;
    }

    protected function _setName($value) {
        $this->_name = $value;
    }

    protected function _getDescription() {
        return $this->_description;
    }

    protected function _setDescription($value) {
        $this->_description = $value;
    }

    protected function _getClass() {
        return $this->_class;
    }

    protected function _setClass($value) {
        $this->_class = $value;
    }

    protected function _getBeforeWidget() {
        return $this->_beforeWidget;
    }

    protected function _setBeforeWidget($value) {
        $this->_beforeWidget = $value;
    }

    protected function _getAfterWidget() {
        return $this->_afterWidget;
    }

    protected function _setAfterWidget($value) {
        $this->_afterWidget = $value;
    }

    protected function _getBeforeTitle() {
        return $this->_beforeTitle;
    }

    protected function _setBeforeTitle($value) {
        $this->_beforeTitle = $value;
    }

    protected function _getAfterTitle() {
        return $this->_afterTitle;
    }

    protected function _setAfterTitle($value) {
        $this->_afterTitle = $value;
    }
}