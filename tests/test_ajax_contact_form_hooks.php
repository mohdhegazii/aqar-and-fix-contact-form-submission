<?php
// Ensure WordPress path constant is available
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__DIR__) . '/');
}

// Track registered actions
$registered_actions = [];

if (!function_exists('add_action')) {
    function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
        global $registered_actions;
        $registered_actions[] = $tag;
    }
}

if (!function_exists('add_filter')) {
    function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1) {}
}

if (!function_exists('carbon_get_theme_option')) {
    function carbon_get_theme_option($option_name) {
        return null;
    }
}

if (!function_exists('get_bloginfo')) {
    function get_bloginfo($show = 'name', $filter = 'raw') {
        return 'admin@example.com';
    }
}

if (!function_exists('get_page_link')) {
    function get_page_link($page_id) {
        return 'http://example.com/thank-you';
    }
}

if (!function_exists('wp_mail')) {
    function wp_mail($to, $subject, $message, $headers = '') {
        return true;
    }
}

if (!function_exists('wp_send_json_success')) {
    function wp_send_json_success($data) {}
}

if (!function_exists('wp_send_json_error')) {
    function wp_send_json_error($data) {}
}

if (!function_exists('wp_verify_nonce')) {
    function wp_verify_nonce($nonce, $action = -1) {
        return true;
    }
}

if (!function_exists('test_input')) {
    function test_input($data) {
        return $data;
    }
}

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($str) {
        return $str;
    }
}

if (!function_exists('sanitize_email')) {
    function sanitize_email($email) {
        return $email;
    }
}

if (!function_exists('esc_html')) {
    function esc_html($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('wp_redirect')) {
    function wp_redirect($location, $status = 302) {
        return true;
    }
}

// Load the form handler file which should register the AJAX hooks
require_once ABSPATH . 'app/functions/form_handler.php';

global $registered_actions;

$expected_hooks = [
    'wp_ajax_my_contact_form',
    'wp_ajax_nopriv_my_contact_form'
];

$missing_hooks = array_diff($expected_hooks, $registered_actions);

if (empty($missing_hooks)) {
    echo "AJAX Contact Form Hooks Test: PASS\n";
} else {
    echo "AJAX Contact Form Hooks Test: FAIL\n";
    echo 'Missing hooks: ' . implode(', ', $missing_hooks) . "\n";
}
