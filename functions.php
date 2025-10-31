<?php

use Tracy\OutputDebugger;

/* -----------------------------------------------------
# Define Directories
----------------------------------------------------- */
define('ROOT_DIR', dirname(__FILE__));
define('CLASS_DIR', ROOT_DIR . '/app/classes/');
define('FUNC_DIR', ROOT_DIR . '/app/functions/');
define('TEMP_DIR', ROOT_DIR . '/app/templates/');

/* -----------------------------------------------------
# Define URLs and Paths
----------------------------------------------------- */
define('siteurl', get_site_url());
define('sitename', get_bloginfo('name'));
define('wpath', get_template_directory());
define('wurl', get_template_directory_uri());
define('wcssurl', wurl . '/assets/css/');
define('wfavurl', wurl . '/assets/favicons/');
define('wfonturl', wurl . '/assets/fonts/');
define('wimgurl', wurl . '/assets/images/');
define('wjsurl', wurl . '/assets/js/');

/* -----------------------------------------------------
# Define Secret Key
----------------------------------------------------- */
define('scrtky', 'SaBrY2585Trmd_df@#!ki5&5d8d*_8');

/* -----------------------------------------------------
# Load Composer Autoload
----------------------------------------------------- */
include_once(wpath . '/app/vendor/autoload.php');

/* -----------------------------------------------------
# Load Functions
----------------------------------------------------- */
$functionslist = [
    'basics', 'helper', 'menus', 'minifier', 'settings', 'post_types',
    'meta_box', 'styles', 'form_handler', 'tgm', 'schema',
    'pagination', 'shortcodes', 'editor_buttons', 'jawda_leads',
    'jawda_leads_download', 'translate', 'smtp_settings', 'smtp_mailer'
];
load_my_files($functionslist, FUNC_DIR);

/* -----------------------------------------------------
# Load Templates
----------------------------------------------------- */
load_all_files(TEMP_DIR);

/* -----------------------------------------------------
# Loader Functions
----------------------------------------------------- */

// Load multiple PHP files
function load_my_files($files, $path) {
    foreach ($files as $filename) {
        $filepath = $path . $filename . '.php';
        if (file_exists($filepath)) {
            include_once($filepath);
        }
    }
}

// Load all PHP files in a directory recursively
function load_all_files($directory) {
    if (is_dir($directory)) {
        $scan = scandir($directory);
        unset($scan[0], $scan[1]);
        foreach ($scan as $file) {
            if (is_dir($directory . '/' . $file)) {
                load_all_files($directory . '/' . $file);
            } elseif (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                include_once($directory . '/' . $file);
            }
        }
    }
}

/* -----------------------------------------------------
# Custom Project Permalinks
----------------------------------------------------- */

// Add custom rewrite tag for projects_area
function custom_project_rewrite_tag() {
    add_rewrite_tag('%projects_area%', '([^/]+)', 'projects_area=');
}
add_action('init', 'custom_project_rewrite_tag');

// Modify permalink structure for 'projects' only - مع دعم تعدد اللغات (مثال WPML)
function custom_project_permalink($permalink, $post) {
    if ($post->post_type !== 'projects') {
        return $permalink;
    }

    $terms = wp_get_post_terms($post->ID, 'projects_area');

    if (!empty($terms) && !is_wp_error($terms)) {
        $language = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : ''; // الحصول على كود اللغة إذا كانت WPML مفعلة
        $home_url = rtrim(home_url(), '/');
        $project_slug = $post->post_name;
        $area_slug = $terms[0]->slug;

        if ($language === 'en') {
            return $home_url . '/en/' . $area_slug . '/project/' . $project_slug . '/';
        } else {
            return $home_url . '/' . $area_slug . '/project/' . $project_slug . '/';
        }
    } else {
        return $permalink;
    }
}
add_filter('post_type_link', 'custom_project_permalink', 10, 2);

// Modify the rewrite rules for the 'projects' post type
function custom_project_rewrite_rules($rewrite_rules) {
    $new_rules = array();
    $projects = get_post_type_object('projects');
    $slug = $projects->rewrite['slug']; // يجب أن تكون 'projects'

    // إنشاء قواعد إعادة الكتابة للنمط الجديد: /value-of-projects_area/project/project-slug/
    $new_rules['([^/]+)/project/([^/]+)/?$'] = 'index.php?projects_area=$matches[1]&projects=$matches[2]';
    $new_rules['([^/]+)/project/([^/]+)/page/([0-9]+)/?$'] = 'index.php?projects_area=$matches[1]&projects=$matches[2]&paged=$matches[3]';

    // قواعد إعادة الكتابة للغة الإنجليزية (إذا كنت تستخدم WPML وقد تحتاج إلى قواعد منفصلة)
    $new_rules['en/([^/]+)/project/([^/]+)/?$'] = 'index.php?projects_area=$matches[1]&projects=$matches[2]&lang=en';
    $new_rules['en/([^/]+)/project/([^/]+)/page/([0-9]+)/?$'] = 'index.php?projects_area=$matches[1]&projects=$matches[2]&paged=$matches[3]&lang=en';


    return $new_rules + $rewrite_rules;
}
add_filter('rewrite_rules_array', 'custom_project_rewrite_rules');

/* -----------------------------------------------------------------------------
# Pagination helpers
----------------------------------------------------------------------------- */

if ( ! function_exists( 'aqarand_get_current_paged' ) ) {
    /**
     * Retrieve the current paged value while supporting static front pages.
     */
    function aqarand_get_current_paged() {
        return max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
    }
}

if ( ! function_exists( 'aqarand_get_english_ordinal' ) ) {
    /**
     * Convert a number to its English ordinal representation.
     */
    function aqarand_get_english_ordinal( $number ) {
        $number = (int) $number;

        if ( $number % 100 >= 11 && $number % 100 <= 13 ) {
            return $number . 'th page';
        }

        switch ( $number % 10 ) {
            case 1:
                return $number . 'st page';
            case 2:
                return $number . 'nd page';
            case 3:
                return $number . 'rd page';
            default:
                return $number . 'th page';
        }
    }
}

if ( ! function_exists( 'aqarand_get_arabic_page_label' ) ) {
    /**
     * Get the Arabic translation for the current page number label.
     */
    function aqarand_get_arabic_page_label( $number ) {
        $number = (int) $number;

        $map = array(
            2  => 'الصفحة الثانية',
            3  => 'الصفحة الثالثة',
            4  => 'الصفحة الرابعة',
            5  => 'الصفحة الخامسة',
            6  => 'الصفحة السادسة',
            7  => 'الصفحة السابعة',
            8  => 'الصفحة الثامنة',
            9  => 'الصفحة التاسعة',
            10 => 'الصفحة العاشرة',
        );

        if ( isset( $map[ $number ] ) ) {
            return $map[ $number ];
        }

        if ( $number > 1 ) {
            return 'الصفحة رقم ' . $number;
        }

        return '';
    }
}

if ( ! function_exists( 'aqarand_get_page_suffix' ) ) {
    /**
     * Build the multilingual suffix that should be appended to meta values.
     */
    function aqarand_get_page_suffix( $number ) {
        $number = (int) $number;

        if ( $number <= 1 ) {
            return '';
        }

        $arabic  = aqarand_get_arabic_page_label( $number );
        $english = aqarand_get_english_ordinal( $number );

        if ( ! $arabic && ! $english ) {
            return '';
        }

        if ( ! $arabic ) {
            return $english;
        }

        if ( ! $english ) {
            return $arabic;
        }

        return $arabic . ' | ' . $english;
    }
}

if ( ! function_exists( 'aqarand_append_suffix_to_string' ) ) {
    /**
     * Append suffix text to a string while avoiding duplication.
     */
    function aqarand_append_suffix_to_string( $text, $suffix ) {
        if ( '' === $suffix ) {
            return $text;
        }

        if ( false !== strpos( $text, $suffix ) ) {
            return $text;
        }

        if ( '' !== $text ) {
            return $text . ' — ' . $suffix;
        }

        return $suffix;
    }
}

if ( ! function_exists( 'aqarand_generate_base_description' ) ) {
    /**
     * Generate a fallback description based on the current query context.
     */
    function aqarand_generate_base_description() {
        $description = trim( wp_strip_all_tags( get_the_archive_description() ) );

        if ( '' === $description && is_singular() ) {
            $post_id = get_queried_object_id();
            if ( $post_id ) {
                $excerpt = get_post_field( 'post_excerpt', $post_id );
                if ( ! $excerpt ) {
                    $excerpt = get_post_field( 'post_content', $post_id );
                }
                $description = wp_trim_words( wp_strip_all_tags( $excerpt ), 30, '…' );
            }
        }

        if ( '' === $description ) {
            $description = get_bloginfo( 'description', 'display' );
        }

        return trim( wp_strip_all_tags( $description ) );
    }
}

if ( ! function_exists( 'aqarand_filter_document_title' ) ) {
    /**
     * Append the page suffix to the document title when needed.
     */
    function aqarand_filter_document_title( $title ) {
        $paged = aqarand_get_current_paged();
        $suffix = aqarand_get_page_suffix( $paged );

        if ( '' === $suffix ) {
            return $title;
        }

        return aqarand_append_suffix_to_string( $title, $suffix );
    }
    add_filter( 'pre_get_document_title', 'aqarand_filter_document_title', 50 );
}

if ( ! function_exists( 'aqarand_filter_meta_description' ) ) {
    /**
     * Append the page suffix to SEO plugin meta descriptions.
     */
    function aqarand_filter_meta_description( $description ) {
        $paged = aqarand_get_current_paged();
        $suffix = aqarand_get_page_suffix( $paged );

        if ( '' === $suffix ) {
            return $description;
        }

        if ( '' === trim( $description ) ) {
            $description = aqarand_generate_base_description();
        }

        return aqarand_append_suffix_to_string( $description, $suffix );
    }

    add_filter( 'wpseo_metadesc', 'aqarand_filter_meta_description', 50 );
    add_filter( 'rank_math/frontend/description', 'aqarand_filter_meta_description', 50 );
}

if ( ! function_exists( 'aqarand_output_meta_description_suffix' ) ) {
    /**
     * Output a fallback meta description with suffix when no SEO plugin handles it.
     */
    function aqarand_output_meta_description_suffix() {
        if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
            return;
        }

        $paged = aqarand_get_current_paged();
        $suffix = aqarand_get_page_suffix( $paged );

        if ( '' === $suffix ) {
            return;
        }

        $description = aqarand_generate_base_description();
        if ( '' === $description ) {
            return;
        }

        $description = aqarand_append_suffix_to_string( $description, $suffix );

        if ( '' !== $description ) {
            echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
        }
    }

    add_action( 'wp_head', 'aqarand_output_meta_description_suffix', 90 );
}
