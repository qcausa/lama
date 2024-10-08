<?php
/*
Plugin Name: ACF Options Page for Free Version
Description: Adds an options page feature to the free version of ACF.
Version: 1.0
Author: Your Name
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class ACF_Options_Page {
    private $pages = array();

    public function __construct() {
        add_action('admin_menu', array($this, 'register_options_pages'));
    }

    public function add_options_page($args) {
        $args = wp_parse_args($args, array(
            'page_title' => 'Options',
            'menu_title' => 'Options',
            'menu_slug'  => 'acf-options',
            'capability' => 'edit_posts',
            'parent_slug' => '',
            'position' => null,
            'icon_url' => 'dashicons-admin-generic',
        ));

        // Add page to pages list
        $this->pages[] = $args;

        return $args;
    }

    public function register_options_pages() {
        foreach ($this->pages as $page) {
            if ($page['parent_slug']) {
                add_submenu_page(
                    $page['parent_slug'],
                    $page['page_title'],
                    $page['menu_title'],
                    $page['capability'],
                    $page['menu_slug'],
                    array($this, 'render_options_page')
                );
            } else {
                add_menu_page(
                    $page['page_title'],
                    $page['menu_title'],
                    $page['capability'],
                    $page['menu_slug'],
                    array($this, 'render_options_page'),
                    $page['icon_url'],
                    $page['position']
                );
            }
        }
    }

    public function render_options_page() {
        // Get the current screen to determine which options page it is.
        $screen = get_current_screen();

        // Set the page title from the registered options page
        $page_title = $this->get_page_title_by_slug($screen->id);

        echo '<div class="wrap">';
        echo '<h1>' . esc_html($page_title) . '</h1>';
        echo '<form method="post" action="options.php">';
        
        settings_fields($screen->id);
        do_settings_sections($screen->id);
        submit_button();

        echo '</form>';
        echo '</div>';
    }

    // Add a getter method to access $pages
    public function get_pages() {
        return $this->pages;
    }

    // Method to get page title by slug
    private function get_page_title_by_slug($slug) {
        foreach ($this->pages as $page) {
            if ($page['menu_slug'] === $slug) {
                return $page['page_title'];
            }
        }
        return 'Theme Settings';
    }
}

// Initialize the options page handler
$acf_options_page = new ACF_Options_Page();

// Use the getter method to access pages in other parts of your plugin
function acf_register_settings() {
    global $acf_options_page;

    // Use the getter method to get pages
    $pages = $acf_options_page->get_pages();

    foreach ($pages as $page) {
        register_setting($page['menu_slug'], $page['menu_slug']);
        add_settings_section(
            'acf_options_section',
            'ACF Options',
            '__return_false',
            $page['menu_slug']
        );
    }
}
add_action('admin_init', 'acf_register_settings');

// Helper function to add options pages, similar to ACF Pro's acf_add_options_page
function acf_add_options_page($args = array()) {
    global $acf_options_page;
    return $acf_options_page->add_options_page($args);
}

// Example usage
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Site General Settings',
        'menu_title' => 'General Settings',
        'menu_slug'  => 'acf-site-general-settings',
        'capability' => 'manage_options',
        'position'   => 2,
        'icon_url'   => 'dashicons-admin-site',
    ));
}