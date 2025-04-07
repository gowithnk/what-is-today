<?php
/*
Plugin Name: What is Today
Plugin URI: https://mithilait.com/
Description: Display national and special days in India based on the current date.
Version: 1.0
Author: Niranjan Chourasia
Author URI: https://mithilait.com/
License: GPL2
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define constants
define('WIT_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WIT_PLUGIN_URL', plugin_dir_url(__FILE__));

// Load assets
function wit_enqueue_assets() {
    wp_enqueue_style('wit-style', WIT_PLUGIN_URL . 'assets/css/style.css');
    wp_enqueue_script('wit-script', WIT_PLUGIN_URL . 'assets/js/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'wit_enqueue_assets');

// Register shortcode
function wit_shortcode($atts) {
    ob_start();
    include WIT_PLUGIN_PATH . 'templates/frontend-display.php';
    return ob_get_clean();
}
add_shortcode('what_is_today', 'wit_shortcode');

// Admin Menu
function wit_register_admin_menu() {
    add_menu_page(
        'What is Today Settings',
        'What is Today',
        'manage_options',
        'what-is-today',
        'wit_settings_page',
        'dashicons-calendar-alt'
    );
}
add_action('admin_menu', 'wit_register_admin_menu');

function wit_settings_page() {
    include WIT_PLUGIN_PATH . 'templates/admin-settings.php';
}

// Register settings
function wit_register_settings() {
    register_setting('wit_settings_group', 'wit_settings');
}
add_action('admin_init', 'wit_register_settings');

// Load JSON data (this will be used to fetch national/special days)
function wit_get_special_days() {
    $json_path = WIT_PLUGIN_PATH . 'data/special-days.json';
    if (file_exists($json_path)) {
        $json = file_get_contents($json_path);
        return json_decode($json, true);
    }
    return [];
}

// REST API endpoint
add_action('rest_api_init', function () {
    register_rest_route('what-is-today/v1', '/today', array(
        'methods' => 'GET',
        'callback' => 'wit_rest_get_today',
    ));
});

function wit_rest_get_today() {
    $today = date('m-d');
    $days = wit_get_special_days();
    $matches = array_filter($days, function ($day) use ($today) {
        return $day['date'] === $today;
    });
    return array_values($matches);
}

// Create plugin folder structure and files on activation
register_activation_hook(__FILE__, function() {
    $upload_dir = WIT_PLUGIN_PATH . 'data';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Create sample JSON if not exists
    $sample_json = $upload_dir . '/special-days.json';
    if (!file_exists($sample_json)) {
        $sample_data = [
            ["date" => "01-26", "title" => "Republic Day", "description" => "Celebration of the Constitution of India coming into effect.", "category" => "National Holiday"],
            ["date" => "08-15", "title" => "Independence Day", "description" => "India's independence from British rule.", "category" => "National Holiday"],
            ["date" => "10-02", "title" => "Gandhi Jayanti", "description" => "Birth anniversary of Mahatma Gandhi.", "category" => "National Holiday"]
        ];
        file_put_contents($sample_json, json_encode($sample_data, JSON_PRETTY_PRINT));
    }
});
