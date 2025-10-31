<?php

/* -----------------------------------------------------------------------------
-- pagination Function
----------------------------------------------------------------------------- */

if (!function_exists('theme_pagination')) {
  function theme_pagination($args = []) {
    $force        = false;
    $current_page = null;
    $total_pages  = null;
    $custom_query = null;

    if (is_bool($args)) {
        $force = $args;
        $args  = [];
    }

    if (is_array($args)) {
        if (isset($args['force'])) {
            $force = (bool) $args['force'];
            unset($args['force']);
        }

        if (isset($args['current'])) {
            $current_page = absint($args['current']);
            unset($args['current']);
        }

        if (isset($args['total'])) {
            $total_pages = (int) $args['total'];
            unset($args['total']);
        }

        if (isset($args['query']) && $args['query'] instanceof WP_Query) {
            $custom_query = $args['query'];
            unset($args['query']);
        }
    }

    global $wp_query;

    $active_query = $custom_query instanceof WP_Query ? $custom_query : $wp_query;

    if (!$force && is_singular()) {
        return;
    }

    if (!$active_query instanceof WP_Query) {
        return;
    }

    if (null === $total_pages) {
        $total_pages = (int) $active_query->max_num_pages;
    }

    if ($total_pages <= 1) {
        return;
    }

    if (null === $current_page) {
        if (function_exists('aqarand_get_current_paged')) {
            $current_page = absint(aqarand_get_current_paged());
        } else {
            $paged_var   = absint(get_query_var('paged'));
            $page_var    = absint(get_query_var('page'));
            $current_page = max(1, $paged_var, $page_var);
        }
    }

    if ($current_page < 1) {
        $current_page = 1;
    }

    $current_page = min($current_page, $total_pages);

    $original_query = null;

    if ($custom_query instanceof WP_Query) {
        $original_query = $wp_query;
        $wp_query       = $custom_query;
        $GLOBALS['wp_query'] = $custom_query;
    }

    $links = [];

    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i === 1 || $i === $total_pages || ($i >= $current_page - 1 && $i <= $current_page + 1)) {
            $links[] = $i;
        }
    }

    echo '<div class="navigation"><ul>';

    $previous_link = get_previous_posts_link(is_rtl() ? 'السابق' : __('« Previous', 'aqarand'));
    if ($previous_link) {
        printf('<li class="prev">%s</li>', $previous_link);
    }

    $current_link = 0;
    foreach ($links as $link) {
        if ($current_link + 1 < $link) {
            echo '<li>…</li>';
        }

        $class = ($current_page === $link) ? ' class="active"' : '';
        printf('<li%s><a href="%s">%s</a></li>', $class, esc_url(get_pagenum_link($link)), $link);
        $current_link = $link;
    }

    $next_link = get_next_posts_link(is_rtl() ? 'التالي' : __('Next »', 'aqarand'), $total_pages);
    if ($next_link) {
        printf('<li class="next">%s</li>', $next_link);
    }

    echo '</ul></div>';

    if ($original_query instanceof WP_Query) {
        $wp_query = $original_query;
        $GLOBALS['wp_query'] = $original_query;
    }
  }
}
