<?php
/*
Plugin Name: Elementor Loop Index Shortcode
Description: A plugin that adds a shortcode to show the loop index with ordinal numbers (1st, 2nd, 3rd, etc.) for Elementor loop items.
Version: 1.0
Author: Your Name
*/

// Register the shortcode that outputs the placeholder
function elementor_loop_index_shortcode() {
    return '<span class="loop-index-placeholder"></span>';
}
add_shortcode('loop_index', 'elementor_loop_index_shortcode');

// Enqueue the JavaScript file
function enqueue_elementor_loop_script() {
    // Ensure jQuery is loaded
    wp_enqueue_script( 'jquery' );

    // Register and enqueue the custom JavaScript file
    wp_enqueue_script( 'elementor-loop-grid-index', plugin_dir_url(__FILE__) . 'js/elementor-loop-grid-index.js', array( 'jquery' ), null, true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_elementor_loop_script' );
