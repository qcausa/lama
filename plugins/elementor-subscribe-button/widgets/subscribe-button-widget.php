<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Elementor_Subscribe_Button_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'subscribe_button';
    }

    public function get_title() {
        return __( 'Subscribe Button', 'elementor-subscribe-button' );
    }

    public function get_icon() {
        return 'eicon-mail';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function render() {
        $user_id = get_current_user_id();
        $is_subscribed = get_user_meta( $user_id, 'esb_is_subscribed', true );
        $button_text = $is_subscribed ? __( 'Unsubscribe', 'elementor-subscribe-button' ) : __( 'Subscribe', 'elementor-subscribe-button' );
        ?>
        <button class="esb-subscribe-button"><?php echo esc_html( $button_text ); ?></button>
        <?php
    }
}
