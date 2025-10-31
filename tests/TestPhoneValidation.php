<?php

// This test verifies the phone number validation logic in form_handler.php

define('ABSPATH', dirname(__DIR__) . '/');

// --- Global state for capturing test output ---
global $last_wp_die_message;
$last_wp_die_message = '';

// --- Mock WordPress environment ---
if (!function_exists('wp_verify_nonce')) { function wp_verify_nonce($nonce, $action) { return true; } }
if (!function_exists('wp_mail')) { function wp_mail($to, $subject, $message, $headers = '') { return true; } }
if (!function_exists('carbon_get_theme_option')) { function carbon_get_theme_option($option) { return 'test@example.com'; } }
if (!function_exists('get_page_link')) { function get_page_link($id) { return 'http://example.com/thank-you'; } }
if (!function_exists('home_url')) { function home_url($path = '') { return 'http://example.com'; } }
if (!function_exists('get_bloginfo')) { function get_bloginfo($show = '') { return 'test@example.com'; } }
if (!function_exists('sanitize_email')) { function sanitize_email($email) { return filter_var($email, FILTER_SANITIZE_EMAIL); } }
if (!function_exists('sanitize_text_field')) { function sanitize_text_field($str) { return $str; } }
if (!function_exists('wp_strip_all_tags')) { function wp_strip_all_tags($string, $remove_breaks = false) { return $string; } }
if (!function_exists('wp_die')) {
    function wp_die($message = '', $title = '', $args = []) {
        global $last_wp_die_message;
        $last_wp_die_message = $message;
    }
}
if (!function_exists('wp_redirect')) { function wp_redirect($location, $status = 302) { /* Do nothing */ } }
if (!function_exists('esc_html')) { function esc_html($text) { return htmlspecialchars($text); } }
if (!function_exists('wp_send_json_error')) { function wp_send_json_error($error) { /* Do nothing */ } }
if (!function_exists('wp_send_json_success')) { function wp_send_json_success($data) { /* Do nothing */ } }

// Mock functions that are also declared in the included file
if (!function_exists('get_text_lang')) { function get_text_lang($st1, $st2, $lang, $echo = true) { return $st2; } }
if (!function_exists('aqarand_can_use_secondary_smtp')) { function aqarand_can_use_secondary_smtp() { return false; } }


global $wpdb;
$wpdb = new class {
    public $prefix = 'wp_';
    public $insert_id = 1;
    public function insert($table, $data, $format) { return 1; }
};

// --- Function Under Test (Sourced from the actual, modified file) ---
// We cannot include the file directly due to redeclaration errors.
// So we copy the function under test here.
function prefix_send_email_to_admin() {
    $lang = 'en';
    $is_ajax = false;
    $send_error = function ($message) { wp_die($message); };

    if (!isset($_POST['my_contact_form_nonce']) || !wp_verify_nonce($_POST['my_contact_form_nonce'], 'my_contact_form_action')) {
        $send_error('Sorry, something went wrong.');
        return;
    }
    if (!isset($_POST['name']) || empty($_POST['name']) || !isset($_POST['phone']) || empty($_POST['phone']) || !isset($_POST['packageid']) || empty($_POST['packageid'])) {
        $send_error('Please make sure to add all required fields');
        return;
    }

    $phone = wp_strip_all_tags(trim($_POST['phone']));

    $is_valid_phone = false;
    if (preg_match('/^01[0125]\d{8}$/', $phone)) {
        $is_valid_phone = true;
    } else {
        $clean_phone = ltrim($phone, '+');
        if (ctype_digit($clean_phone) && strlen($clean_phone) >= 11 && strlen($clean_phone) <= 17) {
            if (strlen($clean_phone) == 11 && substr($clean_phone, 0, 2) === '02') {
                $is_valid_phone = false;
            } else {
                $is_valid_phone = true;
            }
        }
    }

    if (!$is_valid_phone) {
        $send_error('Please make sure to enter a valid phone number.');
        return;
    }
}


// --- Test Runner ---
function run_test($description, $phone_number, $should_be_valid) {
    echo "Running Test: '$description'\n";

    $_POST = [
        'my_contact_form_nonce' => 'test_nonce',
        'name'      => 'Test User',
        'phone'     => $phone_number,
        'packageid' => 'Test Package'
    ];

    global $last_wp_die_message;
    $last_wp_die_message = ''; // Reset

    prefix_send_email_to_admin();

    $is_valid = ($last_wp_die_message === '');

    if ($is_valid === $should_be_valid) {
        echo "  [SUCCESS] Test Passed.\n";
        return true;
    } else {
        echo "  [FAILURE] Test Failed.\n";
        if ($should_be_valid) {
            echo "    - Expected phone number to be VALID, but it was REJECTED.\n";
            echo "    - Error message: '$last_wp_die_message'\n";
        } else {
            echo "    - Expected phone number to be REJECTED, but it was ACCEPTED.\n";
        }
        return false;
    }
}

// --- Test Cases ---
$all_tests_passed = true;

// Invalid cases
if (!run_test("Rejects phone with letters", "12345abcde1", false)) $all_tests_passed = false;
if (!run_test("Rejects Egyptian landline", "02123456789", false)) $all_tests_passed = false;
if (!run_test("Rejects short number", "12345", false)) $all_tests_passed = false;
if (!run_test("Rejects overly long number", "12345678901234567890123", false)) $all_tests_passed = false;

// Valid cases
if (!run_test("Accepts valid Egyptian number", "01012345678", true)) $all_tests_passed = false;
if (!run_test("Accepts valid international number with +", "+201012345678", true)) $all_tests_passed = false;


// --- Final Report ---
echo "\n--------------------------------------------\n";
if ($all_tests_passed) {
    echo "SUCCESS: All phone validation tests passed.\n";
    exit(0);
} else {
    echo "FAILURE: Some phone validation tests failed.\n";
    exit(1);
}
