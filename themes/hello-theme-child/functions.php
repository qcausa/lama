<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);

}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );

add_action('elementor/query/mens_world_champions', function($query) {
    // Get the Pods object for the settings page
    $pods = pods('moto-touring_settings');
    
    // Ensure the Pods object is valid and fetch the first (or only) settings page entry
    if ($pods) {
        $pods->fetch();
        
        // Get the related post IDs from the relationship fields
        $mens_championship = $pods->field('mens_world_champions'); // Array of post IDs
		$post_ids = !empty($mens_championship) ? wp_list_pluck($mens_championship, 'ID') : [];

        // Set the post__in parameter for the query if there are valid post IDs
        if (!empty($post_ids)) {
            $query->set('post__in', $post_ids);
			$query->set('orderby', 'post__in');
        }
    }
});

add_action('elementor/query/womens_world_champions', function($query) {
    // Get the Pods object for the settings page
    $pods = pods('moto-touring_settings');
    
    // Ensure the Pods object is valid and fetch the first (or only) settings page entry
    if ($pods) {
        $pods->fetch();
        
        // Get the related post IDs from the relationship fields
        $womens_championship = $pods->field('womens_world_champions'); // Array of post IDs
		$post_ids = !empty($womens_championship) ? wp_list_pluck($womens_championship, 'ID') : [];
		BugFu::log($post_ids);

        // Set the post__in parameter for the query if there are valid post IDs
        if (!empty($post_ids)) {
            $query->set('post__in', $post_ids);
			$query->set('orderby', 'post__in');
        }
    }
});

// Shortcode to output custom PHP in Elementor
function wpc_elementor_shortcode( $atts ) {
	// Get the Pods object for the settings page
    $pods = pods('moto-touring_settings');

	// Ensure the Pods object is valid and fetch the first (or only) settings page entry
    if ($pods) {
		BugFu::log("PASS");
        $pods->fetch();
        
        // Get the related post IDs from the relationship fields
        $mens_championship = $pods->field('mens_world_champions'); // Array of post IDs
		// Extract post IDs from mens_championship field if it has data
        $mens_championship_ids = !empty($mens_championship) ? wp_list_pluck($mens_championship, 'ID') : [];
		BUgFu::log($mens_championship_ids);
        // $womens_championship = $pods->field('womens-championship'); // Array of post IDs

        // // Combine both fields into a single array of post IDs
        // $post_ids = array_merge(
        //     !empty($mens_championship) ? (array) $mens_championship : [],
        //     !empty($womens_championship) ? (array) $womens_championship : []
        // );

        // // Set the post__in parameter for the query if there are valid post IDs
        // if (!empty($post_ids)) {
        //     $query->set('post__in', $post_ids);
        // }
    }


    $settings = pods('moto-touring_settings');
	echo $settings->display('mens_world_champions');
	
}
add_shortcode( 'my_elementor_php_output', 'wpc_elementor_shortcode');

// // Function to convert a number to an ordinal number (e.g. 1 => 1st, 2 => 2nd, 3 => 3rd)
// function ordinal_suffix($num) {
//     $num = (int) $num;
//     $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
//     if ((($num % 100) >= 11) && (($num % 100) <= 13)) {
//         return $num . 'th';
//     } else {
//         return $num . $ends[$num % 10];
//     }
// }

// function elementor_loop_index_shortcode($atts) {
//     static $index = 0;

//     // Reset the index if 'reset' attribute is passed
//     if (isset($atts['reset']) && $atts['reset'] === 'true') {
//         $index = 0;
//     }

//     $index++;

//     return ordinal_suffix($index);
// }
// add_shortcode('loop_index', 'elementor_loop_index_shortcode');


