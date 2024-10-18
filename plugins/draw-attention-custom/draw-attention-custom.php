<?php
/**
* Plugin Name: Draw Attention - Custom
* Plugin URI: https://www.qcausa.com/
* Description: Custom code for Draw Attention Interactive Map and integration with Elementor.
* Version: 0.1
* Author: QCAUSA
* Author URI: https://www.qcausa.com/
**/

// Register and enqueue the script
function custom_plugin_enqueue_scripts() {
    wp_enqueue_script('custom-plugin-js', plugins_url('js/draw-attention-custom.js', __FILE__), array('jquery'), null, false);

    // Localize the script with new data
    wp_localize_script('custom-plugin-js', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'custom_plugin_enqueue_scripts');

add_action('wp_ajax_fetch_elementor_content', 'fetch_elementor_content');
add_action('wp_ajax_nopriv_fetch_elementor_content', 'fetch_elementor_content');

function fetch_elementor_content() {
    if (isset($_POST['post_id']) && is_numeric($_POST['post_id'])) {
        $post_id = intval($_POST['post_id']);
        
    }

    // Ensure the global post data is setup correctly
    global $post;
    $original_post = $post;
    $post = get_post($post_id);
    setup_postdata($post);

    // Render your Elementor template
    echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display(1009);
    
    $post = $original_post;
    setup_postdata($post);

    wp_reset_postdata();
    wp_die(); // Proper termination
}

function my_query_by_post_types( $query ) {
    $postid = get_the_ID();


    // global $post;
    // $original_post = $post;   
    // BugFu::log($original_post);

    // Initialize the Pods object for the chapter post type with a specific ID
    $pod = pods( 'chapter', $postid );  // Assuming 947 is the chapter ID dynamically passed
    
    if ( $pod ) {
        // Get the related contacts field from the Pods relationship
        $contacts = $pod->field( 'contacts' );
        
        // Extract the contact post IDs into an array
        $contact_ids = wp_list_pluck( $contacts, 'ID' );

        // If we have valid contact IDs, modify the query to include only those posts
        if ( !empty( $contact_ids ) ) {
    
            // Set the post type and filter by specific post IDs
           
            $query->set( 'post_type', [ 'contact' ] );
            $query->set( 'post__in', $contact_ids ); // Limit the query to the contact IDs
            // $query->set( 'p', 947);
            
        }
       
    }
    // $post = $original_post;
    // setup_postdata($post);
    wp_reset_postdata();
    // BugFu::log($post);

    // After the loop finishes, reset the global post data
     // Reset post data to the original query

    // Manually reset the post object to the original chapter post
    // $post = $original_post;
    // BugFu::log($post->ID);
    // setup_postdata( $post );
     // Ensure dynamic fields outside the loop use the correct chapter post ID
}
add_action( 'elementor/query/chapter_contacts', 'my_query_by_post_types' );

function display_elementor_post_id() {
    global $post;

    if ( isset( $post ) ) {
        return 'Current Post ID: ' . $post->ID;
    } else {
        return 'No post found.';
    }
}
add_shortcode( 'display_post_id', 'display_elementor_post_id' );


// add_action( 'elementor/frontend/container/before_render', function( $element ) {
//     // Ensure that the element is a loop item and has the correct post ID context
//     BugFu::log($element);
    
//     if ( 'loop-grid' === $element->get_name() ) {
//         BugFu::log($element);
//         // global $post;
        
//         // // Make sure the global $post is valid and we're inside a loop
//         // if ( isset( $post ) && is_a( $post, 'WP_Post' ) ) {
//         //     // Get the current post ID from the global post object
//         //     $post_id = $post->ID;

//         //     // Add a data attribute for the post ID
//         //     $element->add_render_attribute( '_wrapper', 'data-post-id', $post_id );
//         // }
//     }
// }, 10, 1 );



// add_action( 'elementor/element/after_add_attributes', function( $element ) {
//     // Check if the current element is a loop item
//     if ( 'loop-grid' === $element->get_name() ) {
//         BugFu::log($element);
//     }
    
// }, 10, 1 );