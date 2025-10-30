<?php

// Define ABSPATH to bypass the security check in form_handler.php
define('ABSPATH', dirname(__DIR__) . '/');
define('WP_TESTS_DOMAIN', 'example.org'); //
// Mock global $wpdb object
global $wpdb;
$wpdb = new class {
    public $prefix = 'wp_';
    public $insert_data = null;

    public function insert($table, $data, $format) {
        $this->insert_data = $data;
        return 1;
    }
};

// Mock WordPress functions
if (!function_exists('add_action')) { function add_action($tag, $function_to_add) {} }
if (!function_exists('add_filter')) { function add_filter($tag, $function_to_add) {} }
if (!function_exists('wp_verify_nonce')) { function wp_verify_nonce($nonce, $action) { return true; } }
if (!function_exists('wp_mail')) { function wp_mail($to, $subject, $message, $headers) { return true; } }
if (!function_exists('carbon_get_theme_option')) { function carbon_get_theme_option($option) { return 'test@example.com'; } }
if (!function_exists('get_page_link')) { function get_page_link($id) { return 'http://example.com/thank-you'; } }
if (!function_exists('wp_send_json_success')) { function wp_send_json_success($data) { /* Do nothing */ } }
if (!function_exists('wp_redirect')) { function wp_redirect($location) { /* Do nothing */ } }
if (!function_exists('sanitize_text_field')) { function sanitize_text_field($str) { return $str; } }
if (!function_exists('sanitize_email')) { function sanitize_email($email) { return $email; } }
if (!function_exists('esc_html')) { function esc_html($text) { return $text; } }
if (!function_exists('wp_doing_ajax')) { function wp_doing_ajax() { return false; } }
if (!function_exists('wp_die')) { function wp_die($message) { throw new Exception("wp_die called: $message"); } }

// Include the function to test
require_once ABSPATH . 'app/functions/form_handler.php';

// --- Test Case ---
function test_stripslashes_bug_in_form_handler() {
    global $wpdb;

    // Simulate form submission with a name containing a backslash
    $_POST = [
        'name' => 'Tech\Solutions',
        'phone' => '12345678901',
        'packageid' => 'Test Package',
        'my_contact_form_nonce' => 'a_nonce_value',
        'langu' => 'en'
    ];

    // Call the function
    prefix_send_email_to_admin();

    // Check the data that was prepared for the database
    $submitted_name = $wpdb->insert_data['name'] ?? null;
    $expected_name_before_fix = 'TechSolutions'; // The buggy behavior
    $expected_name_after_fix = 'Tech\Solutions';  // The correct behavior

    echo "Running test: 'test_stripslashes_bug_in_form_handler'\n";
    echo "-----------------------------------------------------\n";
    echo "Input name: 'Tech\Solutions'\n";
    echo "Name captured for DB insertion: '$submitted_name'\n";

    if ($submitted_name === $expected_name_before_fix) {
        echo "\nResult: TEST FAILED (as expected)\n";
        echo "The backslash was incorrectly stripped from the name, confirming the bug.\n";
        exit(1); // Exit with a failure code to indicate the test failed
    } elseif ($submitted_name === $expected_name_after_fix) {
        echo "\nResult: TEST PASSED\n";
        echo "The backslash was correctly preserved in the name.\n";
        exit(0); // Exit with a success code
    } else {
        echo "\nResult: UNEXPECTED OUTPUT\n";
        echo "The output did not match the expected buggy or fixed behavior.\n";
        exit(2); // Exit with a different error code
    }
}

// Run the test
test_stripslashes_bug_in_form_handler();
