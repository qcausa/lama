<?php
/**
 * Add a custom control for specifying the post_id in the Elementor template widget.
 */

class Elementor_Button_Post_ID_Control {

    public function __construct() {
        // Hook into Elementor's widget registration
        add_action( 'elementor/element/button/section_button/before_section_end', [ $this, 'add_post_id_control' ], 10, 2 );
        // add_action( 'elementor/frontend/widget/before_render', [ $this, 'before_template_render' ] );
    }

    // Add a control for post_id in the template widget
    public function add_post_id_control( $element, $args ) {
        // add a control
        $element->add_control(
            'btn_style',
            [
                'label' => 'Button Style',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'fancy' => 'Fancy',
                    'stylish' => 'Stylish',
                    'rounded' => 'Rounded',
                    'square' => 'Square',
                ],
                'prefix_class' => 'btn-style-',
            ]
        );
    }

    // Modify the query to use the specified post_id before rendering
    // public function before_template_render( \Elementor\Element_Base $element ) {
    //     if ( 'template' === $element->get_name() ) {
    //         $settings = $element->get_settings_for_display();
    //         $post_id = $settings['custom_post_id'];

    //         if ( ! empty( $post_id ) && is_numeric( $post_id ) ) {
    //             // Set the global post to the specified post ID
    //             global $post;
    //             $post = get_post( $post_id );
    //             setup_postdata( $post );
    //         }
    //     }
    // }
}

// Instantiate the control class
new Elementor_Button_Post_ID_Control();
