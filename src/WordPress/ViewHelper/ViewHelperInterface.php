<?php

namespace Xend\WordPress\ViewHelper;

interface ViewHelperInterface {

    /**
     * Return a Reference to the ViewHelperInterface Implementation
     *
     * This method is required to register objects implementing the interface as Zend_View helpers,
     * which will then be available by invoking $this->wordpress() from within a view script.
     *
     * @returns ViewHelperInterface
     */
    public function wordpress();

    public function getBlogInfo($info = 'name', $filter = 'raw');

    public function blogInfo($info = 'name');

    public function title($separator = '&raquo;', $return = false, $separatorLocation = '');

    public function getThemeDirectory();

    public function getThemeUri();

    public function getChildThemeDirectory();

    public function getChildThemeUri();

    public function headAction();

    public function footerAction();

    /**
     * Register a Stylesheet with WordPress
     *
     * @param string $handle
     * @param string $uri
     * @param array $dependencies
     * @param string|bool $version
     * @param string $media
     * @return \Xend\WordPress\ViewHelper\ViewHelperInterface
     */
    public function registerStyle($handle, $uri, $dependencies = array(), $version = false, $media = 'all');

    /**
     * Enqueue a Stylesheet
     *
     * @param string $handle
     * @param string $uri
     * @param array $dependencies
     * @param string|bool $version
     * @param string $media
     * @return \Xend\WordPress\ViewHelper\ViewHelperInterface
     */
    public function enqueueStyle($handle, $uri = '', $dependencies = array(), $version = false, $media = 'all');

    /**
     * Print WordPress-Enqueued Styles
     */
    public function printStyles();

    /**
     * Register a Script with WordPress
     *
     * @param string $handle
     * @param string $uri
     * @param array $dependencies
     * @param string|bool $version
     * @param bool $inFooter
     * @return \Xend\WordPress\ViewHelper\ViewHelperInterface
     */
    public function registerScript($handle, $uri, $dependencies = array(), $version = false, $inFooter = false);

    /**
     * Enqueue a Script with WordPress
     *
     * @param string $handle
     * @param string $uri
     * @param array $dependencies
     * @param string|bool $version
     * @param bool $inFooter
     * @return \Xend\WordPress\ViewHelper\ViewHelperInterface
     */
    public function enqueueScript($handle, $uri = false, $dependencies = array(), $version = false, $inFooter = false);

    /**
     * Print WordPress-Enqueued Scripts
     */
    public function printScripts();

    /**
     * Retrieve the Document Body Class Attribute
     * @param string $class
     * @return string
     */
    public function getBodyClass($class = null);

    /**
     * Retrieve the Class Attribute for a Post
     * @param \Xend\WordPress\Posts\Post $post
     * @param string $additionalClasses
     * @return string
     */
    public function getPostClass(\Xend\WordPress\Posts\Post $post, $additionalClasses = '');

    /**
     * Retrieve the Class Attribute for a Comment
     * @param \Xend\WordPress\Posts\Comment $comment
     * @param string $additionalClasses
     * @return string
     */
    public function getCommentClass(\Xend\WordPress\Posts\Comment $comment, $additionalClasses = '');

    /**
     * Render a WordPress Menu
     * @param MenuOptions $options
     */
    public function renderMenu(Menu $options);

    /**
     * Render a WordPress Sidebar
     * @param string|int $ref Either the sidebar's name or ID (preferebly, the name)
     * @param bool $return Whether to return or echo the rendered string. Default is echo.
     * @returns bool|string By default, returns true if the sidebar rendered
     *                      successfully and false if it did not. If $return is true,
     *                      returns the rendered string on success and false on failure.
     */
    public function renderSidebar($ref, $return = false);

    /**
     * Render a Comment Form
     *
     * @param CommentForm|string $form The comment form to render (default is WordPress defaults)
     * @param int|null $postId The ID of the post the comment form targets (default is current post)
     * @return void
     */
    public function renderCommentForm($form = null, $postId = null);

    /**
     * Register a Comment Form to Enable Rendering it by Name
     * @param CommentForm $form
     * @param string $name
     * @return void
     */
    public function registerCommentForm(CommentForm $form, $name);

    /**
     * Set the Comment Form to use By Default
     * @param string $name
     * @return void
     */
    public function setDefaultCommentForm($name);
}