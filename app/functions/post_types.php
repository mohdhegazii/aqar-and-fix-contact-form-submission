<?php

// Security Check
if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/* -----------------------------------------------------------------------------
# PostTypes
----------------------------------------------------------------------------- */

// Import PostTypes
use PostTypes\PostType;
use PostTypes\Taxonomy;

/* -----------------------------------------------------
# projects (جمع) - العودة إلى الاسم الأصلي
----------------------------------------------------- */

// تعديل الـ rewrite structure للـ projects ليكون ثابت
$projects_options = [
    'supports' => array('title', 'editor', 'thumbnail', 'author'),
    'rewrite'   => [
        'slug'      => 'projects', // هذا ما سيظهر في الرابط افتراضيًا
        'with_front' => false
    ],
    'has_archive' => true,
    'public'      => true,
];

$projects_names = [
    'name'      => 'projects', // جمع - العودة إلى الاسم الأصلي
    'singular'  => 'Project',
    'plural'    => 'Projects',
    'slug'      => 'projects' // جمع - العودة إلى الاسم الأصلي
];

$projects = new PostType($projects_names, $projects_options);
$projects->icon('dashicons-location-alt');
$projects->register();

// تصنيفات (Taxonomies) للمشاريع
$project_taxonomies = [
    ['projects_category', 'Project Category', 'Projects Categories', 'projects-cat'],
    ['projects_tag', 'Project Tag', 'Projects Tags', 'projects_tag'],
    ['projects_developer', 'Project Developer', 'Projects Developers', 'developer'],
    ['projects_area', 'Project Area', 'Projects Area', 'place'],
    ['projects_type', 'Project Type', 'Projects Type', 'project-type'],
    ['projects_features', 'Project Features', 'Projects Features', 'project-features'],
];

foreach ($project_taxonomies as $taxonomy) {
    list($name, $singular, $plural, $slug) = $taxonomy;
    $tax = new Taxonomy([
        'name'      => $name,
        'singular'  => $singular,
        'plural'    => $plural,
        'slug'      => $slug
    ]);
    $tax->posttype('projects'); // استخدام 'projects' الجمع
    $tax->register();
}

/* -----------------------------------------------------
# properties
----------------------------------------------------- */

$property_options = [
    'supports' => array('title', 'editor', 'thumbnail', 'author')
];
$property_names = [
    'name'      => 'property',
    'singular'  => 'Property',
    'plural'    => 'Properties',
    'slug'      => 'property'
];
$property = new PostType($property_names, $property_options);
$property->icon('dashicons-admin-multisite');
$property->register();

// تصنيفات (Taxonomies) للعقارات
$property_taxonomies = [
    ['property_label', 'Listing Project', 'Listing Projects', 'listing'],
    ['property_type', 'Type', 'Types', 'property-type'],
    ['property_feature', 'Feature', 'Features', 'feature'],
    ['property_city', 'City', 'Cities', 'city'],
    ['property_state', 'State', 'States', 'state'],
    ['property_status', 'Status', 'Status', 'status'],
];

foreach ($property_taxonomies as $taxonomy) {
    list($name, $singular, $plural, $slug) = $taxonomy;
    $tax = new Taxonomy([
        'name'      => $name,
        'singular'  => $singular,
        'plural'    => $plural,
        'slug'      => $slug
    ]);
    $tax->posttype('property');
    $tax->register();
}

/* -----------------------------------------------------
# catalogs
----------------------------------------------------- */

$catalogs_options = [
    'supports' => array('title', 'editor', 'thumbnail')
];
$catalogs_names = [
    'name'      => 'catalogs',
    'singular'  => 'Catalog',
    'plural'    => 'Catalogs',
    'slug'      => 'catalog'
];
$catalogs = new PostType($catalogs_names, $catalogs_options);
$catalogs->icon('dashicons-format-aside');
$catalogs->register();