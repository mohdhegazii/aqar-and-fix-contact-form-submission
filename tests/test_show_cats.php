<?php
use PHPUnit\Framework\TestCase;

// Mock WordPress functions that are called directly in helper.php
if (!function_exists('add_filter')) {
    function add_filter($tag, $function_to_add) {
        // Mock implementation
    }
}
if (!function_exists('add_shortcode')) {
    function add_shortcode($tag, $callback) {
        // Mock implementation
    }
}
if (!function_exists('is_rtl')) {
    function is_rtl() {
        return false;
    }
}
if (!function_exists('__')) {
    function __($text, $domain = 'default') {
        return $text;
    }
}
if (!function_exists('esc_html')) {
    function esc_html($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}


require_once(dirname(__DIR__) . '/app/functions/helper.php');

class test_show_cats extends TestCase
{
    public function testShowCatsXss()
    {
        $malicious_name = '<script>alert("XSS");</script>';

        // Mock WordPress functions needed for show_cats
        if (!function_exists('get_the_ID')) {
            function get_the_ID() {
                return 1;
            }
        }
        if (!function_exists('wp_get_post_categories')) {
            function wp_get_post_categories($post_id) {
                return [1];
            }
        }
        if (!function_exists('get_category')) {
            function get_category($category_id) {
                $category = new stdClass();
                $category->term_id = 1;
                $category->name = '<script>alert("XSS");</script>';
                return $category;
            }
        }
        if (!function_exists('get_tag_link')) {
            function get_tag_link($tag_id) {
                return 'tag-link';
            }
        }

        // Capture output
        ob_start();
        show_cats();
        $output = ob_get_clean();

        // Assert that the output does NOT contain the malicious script
        $this->assertStringNotContainsString($malicious_name, $output);
    }
}
