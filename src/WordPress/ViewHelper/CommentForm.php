<?php

namespace Xend\WordPress\ViewHelper;

/**
 * Class CommentForm
 *
 * @author Dagan
 * @property string $title The comment form title text
 * @property string $replyTitle The reply-to-comment title text
 * @property array $fields Array of form fields to render with meaningful names as keys and
 * full HTML to render as values
 * @property string $commentField The complete comment field HTML, including label element
 * @property string $mustLogInMessage The complete "must log in" HTML
 * @property string $loggedInAsMessage The complete "logged in as" HTML
 * @property string $beforeMessage Text or HTML to display above the comment form (if the
 * user is not logged in)
 * @property string $afterMessage Text or HTML to display below the comment fields and above
 * the submit button
 * @property string $formId The HTML ID to assign to the comment form
 * @property string $submitId The HTML ID to assign to the submit button
 * @property string $submitClass The HTML class attribute to apply to the submit button
 * @property string $submitName The HTML name attribute to assign to the submit button
 * @property string $submitText The text to use for the submit button
 * @property string $cancelText The cancel-comment text
 * @property string $format Either xhtml or html5
 *
 */
class CommentForm {

    protected $_title;
    protected $_replyTitle;
    protected $_fields;
    protected $_commentField;
    protected $_mustLogInMessage;
    protected $_loggedInAsMessage;
    protected $_beforeMessage;
    protected $_afterMessage;
    protected $_formId;
    protected $_submitId;
    protected $_submitClass;
    protected $_submitName;
    protected $_submitText;
    protected $_cancelText;
    protected $_format;

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
     * @return \Xend\WordPress\CommentForm
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

    protected function _getTitle() {
        return $this->_title;
    }

    protected function _setTitle($value) {
        $this->_title = $value;
    }

    protected function _getReplyTitle() {
        return $this->_replyTitle;
    }

    protected function _setReplyTitle($value) {
        $this->_replyTitle = $value;
    }

    protected function _getFields() {
        return $this->_fields;
    }

    protected function _setFields($value) {
        $this->_fields = $value;
    }

    protected function _getCommentField() {
        return $this->_commentField;
    }

    protected function _setCommentField($value) {
        $this->_commentField = $value;
    }

    protected function _getMustLogInMessage() {
        return $this->_mustLogInMessage;
    }

    protected function _setMustLogInMessage($value) {
        $this->_mustLogInMessage = $value;
    }

    protected function _getLoggedInAsMessage() {
        return $this->_loggedInAsMessage;
    }

    protected function _setLoggedInAsMessage($value) {
        $this->_loggedInAsMessage = $value;
    }

    protected function _getBeforeMessage() {
        return $this->_beforeMessage;
    }

    protected function _setBeforeMessage($value) {
        $this->_beforeMessage = $value;
    }

    protected function _getAfterMessage() {
        return $this->_afterMessage;
    }

    protected function _setAfterMessage($value) {
        $this->_afterMessage = $value;
    }

    protected function _getFormId() {
        return $this->_formId;
    }

    protected function _setFormId($value) {
        $this->_formId = $value;
    }

    protected function _getSubmitId() {
        return $this->_submitId;
    }

    protected function _setSubmitId($value) {
        $this->_submitId = $value;
    }

    protected function _getSubmitClass() {
        return $this->_submitClass;
    }

    protected function _setSubmitClass($value) {
        $this->_submitClass = $value;
    }

    protected function _getSubmitName() {
        return $this->_submitName;
    }

    protected function _setSubmitName($value) {
        $this->_submitName = $value;
    }

    protected function _getSubmitText() {
        return $this->_submitText;
    }

    protected function _setSubmitText($value) {
        $this->_submitText = $value;
    }

    protected function _getCancelText() {
        return $this->_cancelText;
    }

    protected function _setCancelText($value) {
        $this->_cancelText = $value;
    }

    protected function _getFormat() {
        return $this->_format;
    }

    protected function _setFormat($value) {
        $this->_format = $value;
    }
}
