<?php
// namespace YourNamespace\CustomElementor;

use ElementorPro\Modules\Library\Widgets\Template as ElementorProTemplate;

class Elementor_Template_Post_ID_Control extends ElementorProTemplate {

    public function __construct( $data = [], $args = null ) {
        BugFu::log("Elementor_Template_Post_ID_Control");
        parent::__construct( $data, $args );

        // Handle AJAX requests for dynamic post ID loading
        // add_action( 'wp_ajax_load_elementor_template_content', [ $this, 'load_elementor_template_content' ] );
        // add_action( 'wp_ajax_nopriv_load_elementor_template_content', [ $this, 'load_elementor_template_content' ] );
       
        add_action( 'elementor/frontend/widget/before_render', [ $this, 'enqueue_custom_script' ] );
        add_action( 'elementor/frontend/widget/before_render', [ $this, 'before_template_render' ] );
        add_action( 'elementor/element/template/section_template/before_section_end', [ $this, 'add_post_id_control' ], 10, 2 );

    }
    // Enqueue the custom script for this widget
    public function enqueue_custom_script( $element ) {
        // Ensure we're targeting the correct widget
        if ( 'template' === $element->get_name() ) {

            wp_enqueue_script( 
                'template-widget-post-id-control-js', // Script handle
                plugin_dir_url( __FILE__ ) . 'js/template-widget-post-id-control.js', // Path to JS file
                [ 'jquery', 'elementor-frontend' ], // Dependencies
                null, // Version
                true // Load in footer
            );

            // Localize the script with new data
            wp_localize_script('template-widget-post-id-control-js', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
        }
    }

    // Function to retrieve the post ID from the URL (i.e., ?pid=123)
    public function get_post_id_from_url() {
        if ( isset( $_GET['pid'] ) && is_numeric( $_GET['pid'] ) ) {
            return intval( $_GET['pid'] );
        }
        return false; // Return false if no valid post_id is found
    }

    // Modify the query to use the specified post_id before rendering
    public function before_template_render( \Elementor\Element_Base $element ) {
        if ( 'template' === $element->get_name() ) {
            $settings = $element->get_settings_for_display();
            $post_id = $this->get_post_id_from_url();

            // If no post_id is found in the URL, do not render the template
            if ( empty( $post_id ) || ! is_numeric( $post_id ) ) {
                // Prevent rendering of the section
                $element->add_render_attribute( '_wrapper', 'style', 'display:none;' );
                return;
            }

            // If a valid post_id exists, update the global post object
            if ( ! empty( $post_id ) && is_numeric( $post_id ) ) {
                global $post;
                $post = get_post( $post_id );
                setup_postdata( $post );
            }
        }
    }

    // Enqueue the script when the widget is used
    // public function get_script_depends() {
    //     BugFu::log("get_script_depends called");
    //     return [ 'template-widget-post-id-control-js' ];
    // }

    // Add the custom post_id control
    public function register_controls() {
        // Call the parent controls first to include all original controls
        parent::register_controls();

        // Add the new control for Post ID
        $this->add_control(
            'custom_post_id',
            [
                'label' => __( 'Custom Post ID', 'your-text-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true, // Enable dynamic tags for this field
                ],
                'description' => __( 'Enter a specific post ID to dynamically load content.', 'your-text-domain' ),
            ]
        );
    }

    // Modify the render method to handle the custom post_id logic
    public function render() {
        // Get the post ID from the widget settings
        $settings = $this->get_settings_for_display();
        $custom_post_id = $settings['custom_post_id'];

        // If a custom post ID is set, load the content for that post
        if ( ! empty( $custom_post_id ) && is_numeric( $custom_post_id ) ) {
            global $post;
            $post = get_post( $custom_post_id );
            setup_postdata( $post );
        }

        // Render the template widget as usual
        echo '<div class="custom-template-wrapper">';
        parent::render(); // Call the original render method
        echo '</div>';

        // Reset the post data after rendering
        if ( ! empty( $custom_post_id ) && is_numeric( $custom_post_id ) ) {
            wp_reset_postdata();
        }
    }

    // Add a control for post_id in the template widget
    public function add_post_id_control( $element, $args ) {
        $element->add_control(
            'custom_post_id',
            [
                'label' => __( 'Post ID', 'my-elementor-extension' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true, // Enable dynamic tags for this field
                ],
                'default' => '',
                'description' => __( 'Enter a specific post ID to use for dynamic content fields or use dynamic tags.', 'my-elementor-extension' ),
            ]
        );
    }

    // Handle AJAX requests to load the content dynamically without a full page refresh
    public function load_elementor_template_content() {
        BugFu::log("load_elementor_template_content called");
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
}
