<?php

// Mock WordPress environment
define('ABSPATH', dirname(__DIR__) . '/');

// Mock global $wpdb object
global $wpdb;
$wpdb = new class {
    public $prefix = 'wp_';
    public $insert_calls = [];
    public $insert_id = 0;

    public function insert($table, $data, $format) {
        $this->insert_calls[] = ['table' => $table, 'data' => $data, 'format' => $format];
        $this->insert_id = 1;
        return 1;
    }
};

// Mock WordPress functions
if (!function_exists('add_action')) { function add_action($tag, $function_to_add) {} }
if (!function_exists('add_filter')) { function add_filter($tag, $function_to_add) {} }
if (!function_exists('get_page_link')) { function get_page_link($page_id) { return 'http://example.com/thank-you'; } }
if (!function_exists('carbon_get_theme_option')) { function carbon_get_theme_option($option_name) { return 'admin@example.com'; } }
if (!function_exists('sanitize_text_field')) { function sanitize_text_field($str) { return $str; } }
if (!function_exists('sanitize_email')) { function sanitize_email($email) { return $email; } }
if (!function_exists('wp_mail')) { function wp_mail($to, $subject, $message, $headers) { return true; } }
if (!function_exists('check_referrer')) { function check_referrer() {} }
if (!function_exists('test_input')) { function test_input($data) { return $data; } }

// --- Test-specific mocks for AJAX ---
$last_json_response = null;
if (!function_exists('wp_send_json_success')) {
    function wp_send_json_success($data) {
        global $last_json_response;
        $last_json_response = ['success' => true, 'data' => $data];
        // In a real WP environment, this would die. We don't die here to allow the test to continue.
    }
}
if (!function_exists('wp_send_json_error')) {
    function wp_send_json_error($data) {
        global $last_json_response;
        $last_json_response = ['success' => false, 'data' => $data];
    }
}


// Include the function file to be tested
require_once ABSPATH . 'app/functions/form_handler.php';

// --- Test Runner ---

function run_test($name, $test_function) {
    global $last_json_response, $wpdb;
    // Reset state before each test
    $last_json_response = null;
    $wpdb->insert_calls = [];

    echo "Running test: $name... ";
    $test_function();
    echo "\n";
}

// --- Test Cases ---

// Test 1: Successful form submission
run_test("Successful Submission", function() {
    global $last_json_response;
    $_POST = [
        'name' => 'Test User',
        'phone' => '12345678901',
        'packageid' => 'Test Package',
        'email' => 'test@example.com',
        'special_request' => 'This is a test message.',
        'langu' => 'en',
    ];

    prefix_send_email_to_admin();

    if ($last_json_response && $last_json_response['success'] === true && !empty($last_json_response['data']['redirect'])) {
        echo "PASS";
    } else {
        echo "FAIL\n";
        print_r($last_json_response);
    }
});

// Test 2: Missing required field (phone)
run_test("Missing Required Field", function() {
    global $last_json_response;
    $_POST = [
        'name' => 'Test User',
        'phone' => '', // Missing phone
        'packageid' => 'Test Package',
        'langu' => 'ar',
    ];

    prefix_send_email_to_admin();

    if ($last_json_response && $last_json_response['success'] === false && strpos($last_json_response['data']['message'], 'الحقول المطلوبة') !== false) {
        echo "PASS";
    } else {
        echo "FAIL\n";
        print_r($last_json_response);
    }
});

// Test 3: Invalid email format
run_test("Invalid Email Format", function() {
    global $last_json_response;
    $_POST = [
        'name' => 'Test User',
        'phone' => '12345678901',
        'packageid' => 'Test Package',
        'email' => 'invalid-email',
        'langu' => 'en',
    ];

    prefix_send_email_to_admin();

    if ($last_json_response && $last_json_response['success'] === false && strpos($last_json_response['data']['message'], 'valid email') !== false) {
        echo "PASS";
    } else {
        echo "FAIL\n";
        print_r($last_json_response);
    }
});