<?php

function add_action($tag, $callable, $priority = 10, $accepted_args = 10) {
    return \Xend\WordPress\Mock::getInstance()->invoke('add_action', func_get_args());
}

function add_filter($rag, $callable, $priority = 10, $accepted_args = 10) {
    return \Xend\WordPress\Mock::getInstance()->invoke('add_filter', func_get_args());
}

function apply_filters($filter, $value) {
    return \Xend\WordPress\Mock::getInstance()->invoke('apply_filters', func_get_args());
}

function comment_form($args = array(), $post = null) {
    return \Xend\WordPress\Mock::getInstance()->invoke('comment_form', func_get_args());
}

function do_action($action, $arg = '') {
    return \Xend\WordPress\Mock::getInstance()->invoke('do_action', func_get_args());
}

function dynamic_sidebar($index) {
    return \Xend\WordPress\Mock::getInstance()->invoke('dynamic_sidebar', func_get_args());
}

function get_bloginfo($show, $filter) {
    return \Xend\WordPress\Mock::getInstance()->invoke('get_bloginfo', func_get_args());
}

function get_body_class($class = null) {
    return \Xend\WordPress\Mock::getInstance()->invoke('get_body_class', func_get_args());
}

function get_comment_class($class = null, $comment_id = null, $post_id = null) {
    return \Xend\WordPress\Mock::getInstance()->invoke('get_comment_class', func_get_args());
}

function get_post_class($class = null, $post_id = null) {
    return \Xend\WordPress\Mock::getInstance()->invoke('get_post_class', func_get_args());
}

function get_stylesheet_directory() {
    return \Xend\WordPress\Mock::getInstance()->invoke('get_stylesheet_directory', func_get_args());
}

function get_stylesheet_uri() {
    return \Xend\WordPress\Mock::getInstance()->invoke('get_stylesheet_uri', func_get_args());
}

function get_template_directory() {
    return \Xend\WordPress\Mock::getInstance()->invoke('get_template_directory', func_get_args());
}

function get_template_uri() {
    return \Xend\WordPress\Mock::getInstance()->invoke('get_template_uri', func_get_args());
}

function register_nav_menus($args = array()) {
    return \Xend\WordPress\Mock::getInstance()->invoke('register_nav_menus', func_get_args());
}

function register_sidebar($args = array()) {
    return \Xend\WordPress\Mock::getInstance()->invoke('register_sidebar', func_get_args());
}

function wp_parse_args( $args, $defaults = '' ) {
    if ( is_object( $args ) )
        $r = get_object_vars( $args );
    elseif ( is_array( $args ) )
        $r =& $args;
    else
        wp_parse_str( $args, $r );

    if ( is_array( $defaults ) )
        return array_merge( $defaults, $r );
    return $r;
}

function wp_enqueue_script($handle, $uri = '', $dependencies = array(), $version = false, $inFooter = false) {
    return \Xend\WordPress\Mock::getInstance()->invoke('wp_enqueue_script', func_get_args());
}

function wp_enqueue_style($handle, $uri = '', $dependencies = array(), $version = false, $media = 'all') {
    return \Xend\WordPress\Mock::getInstance()->invoke('wp_enqueue_style', func_get_args());
}

function wp_footer() {
    return \Xend\WordPress\Mock::getInstance()->invoke('wp_footer', func_get_args());
}

function wp_head() {
    return \Xend\WordPress\Mock::getInstance()->invoke('wp_head', func_get_args());
}

function wp_nav_menu($args) {
    return \Xend\WordPress\Mock::getInstance()->invoke('wp_nav_menu', func_get_args());
}

function wp_print_scripts() {
    return \Xend\WordPress\Mock::getInstance()->invoke('wp_print_scripts', func_get_args());
}

function wp_print_styles() {
    return \Xend\WordPress\Mock::getInstance()->invoke('wp_print_styles', func_get_args());
}

function wp_register_script($handle, $uri, $dependencies = array(), $version = false, $inFooter = false) {
    return \Xend\WordPress\Mock::getInstance()->invoke('wp_register_script', func_get_args());
}

function wp_register_style($handle, $uri, $dependencies = array(), $version = false, $media = 'all') {
    return \Xend\WordPress\Mock::getInstance()->invoke('wp_register_style', func_get_args());
}

function wp_title($sep = "&raquo;", $display = true, $seplocation = "LEFT") {
    return \Xend\WordPress\Mock::getInstance()->invoke('wp_title',  func_get_args());
}