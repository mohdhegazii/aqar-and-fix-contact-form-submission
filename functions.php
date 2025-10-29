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