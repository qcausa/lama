<?php
/**
 * Plugin Name: My Elementor Extension
 * Description: An Elementor extension to add a post_id control to the template widget.
 * Version: 1.0
 * Author: Your Name
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Hook to initialize the Elementor extension
// add_action( 'elementor/widgets/widgets_registered', 'register_my_elementor_extension' );

// function register_my_elementor_extension() {
//     // Include the custom template widget control
//     include_once( __DIR__ . '/template-widget-post-id-control.php' );
//     include_once( __DIR__ . '/button-widget-post-id-control.php' );
// }

// Hook to register the custom widget
add_action( 'elementor/widgets/register', function( $widgets_manager ) {
    BugFu::log("widgets_registered");
    // Require the custom widget class file
    require_once __DIR__ . '/template-widget-post-id-control.php';

    // Register the custom widget class
    $widgets_manager->register( new \Elementor_Template_Post_ID_Control() );
});

// Handle AJAX requests for dynamic post ID loading
add_action( 'wp_ajax_load_elementor_template_content', 'load_elementor_template_content' );
add_action( 'wp_ajax_nopriv_load_elementor_template_content', 'load_elementor_template_content'  );

// Handle AJAX requests to load the content dynamically without a full page refresh
function load_elementor_template_content() {
    BugFu::log("load_elementor_template_content called");
    BugFu::log(get_the_ID());
    // Ensure a post ID is passed
    if ( isset( $_POST['post_id'] ) && is_numeric( $_POST['post_id'] ) ) {
        $post_id = intval( $_POST['post_id'] );

        // Load the content for the specified post ID
        global $post;
        $post = get_post( $post_id );
        setup_postdata( $post );

        // Render the Elementor template dynamically for the selected post
        echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( get_the_ID() ); // Automatically render selected template

        wp_reset_postdata();
    }

    wp_die(); // Properly terminate the function
}
