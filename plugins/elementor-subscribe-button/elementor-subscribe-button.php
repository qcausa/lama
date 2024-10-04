<?php
/**
 * Plugin Name: Elementor Subscribe Button
 * Description: Adds a custom Elementor widget for a subscribe/unsubscribe button with AJAX functionality.
 * Version: 1.0
 * Author: Your Name
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Register the custom widget
function esb_register_subscribe_button_widget( $widgets_manager ) {
    require_once( __DIR__ . '/widgets/subscribe-button-widget.php' );
    $widgets_manager->register( new \Elementor_Subscribe_Button_Widget() );
}
add_action( 'elementor/widgets/register', 'esb_register_subscribe_button_widget' );

// Enqueue scripts and styles
function esb_enqueue_scripts() {
    wp_enqueue_script(
        'esb-subscribe-button',
        plugins_url( 'assets/js/subscribe-button.js', __FILE__ ),
        array( 'jquery' ),
        '1.0',
        true
    );

    wp_localize_script( 'esb-subscribe-button', 'esb_ajax_object', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'esb_nonce' ),
    ) );

    wp_enqueue_style(
        'esb-subscribe-button-style',
        plugins_url( 'assets/css/subscribe-button.css', __FILE__ ),
        array(),
        '1.0'
    );
}
add_action( 'wp_enqueue_scripts', 'esb_enqueue_scripts' );

// AJAX handler for subscribe/unsubscribe
function esb_handle_subscribe() {
    check_ajax_referer( 'esb_nonce', 'nonce' );

    $user_id = get_current_user_id();

    if ( ! $user_id ) {
        wp_send_json_error( 'User not logged in.' );
    }

    $is_subscribed = get_user_meta( $user_id, 'esb_is_subscribed', true );

    if ( $is_subscribed ) {
        delete_user_meta( $user_id, 'esb_is_subscribed' );
        wp_send_json_success( array( 'subscribed' => false ) );
    } else {
        update_user_meta( $user_id, 'esb_is_subscribed', true );
        wp_send_json_success( array( 'subscribed' => true ) );
    }
}
add_action( 'wp_ajax_esb_handle_subscribe', 'esb_handle_subscribe' );
