<?php
/*
Template Name: catalogs Page
Template Post Type: page
*/

// files are not executed directly
if ( ! defined( 'ABSPATH' ) ) {	die( 'Invalid request.' ); }

/* -----------------------------------------------------------------------------
# Front Page
----------------------------------------------------------------------------- */

// Jawda header
get_my_header();

// Post Loop
while ( have_posts() ) : the_post();

// Page Header
get_my_page_header();

// End Loop
endwhile;

// Reset My Data
wp_reset_postdata();

?>
    <div class="projectspage">
      <div class="container">
    		<div class="row">
            <?php
         $current_page = function_exists( 'aqarand_get_current_paged' ) ? aqarand_get_current_paged() : 1;
         $current_page = max( 1, absint( $current_page ) );

         $loop = new WP_Query(
             array(
                 'post_type'           => 'catalogs',
                 'posts_per_page'      => 9,
                 'paged'               => $current_page,
                 'post_status'         => 'publish',
                 'orderby'             => 'date', // modified | title | name | ID | rand
                 'order'               => 'DESC',
                 'no_found_rows'       => false,
                 'ignore_sticky_posts' => true,
             )
         );
       ?>
       <?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>
         <div class="col-md-4 projectbxspace">
            <?php get_my_article_box(); ?>
         </div>
            <?php endwhile; ?>
            <?php if ($loop->max_num_pages > 1) : // custom pagination  ?>
         <div class="col-md-12 center">
           <?php
             $pagination_markup = '';
             $pagination_page   = max( 1, min( $current_page, (int) $loop->max_num_pages ) );

             if ( function_exists( 'theme_pagination' ) ) {
                 ob_start();
                 theme_pagination(
                     array(
                         'force'   => true,
                         'query'   => $loop,
                         'current' => $pagination_page,
                         'total'   => (int) $loop->max_num_pages,
                     )
                 );
                 $pagination_markup = trim( ob_get_clean() );
             }

             if ( '' === $pagination_markup ) {
                 $fallback_links = paginate_links(
                     array(
                         'current'   => $pagination_page,
                         'total'     => (int) $loop->max_num_pages,
                         'mid_size'  => 1,
                         'end_size'  => 1,
                         'prev_text' => is_rtl() ? 'السابق' : __( '« Previous', 'aqarand' ),
                         'next_text' => is_rtl() ? 'التالي' : __( 'Next »', 'aqarand' ),
                         'type'      => 'array',
                     )
                 );

                 if ( ! empty( $fallback_links ) ) {
                     $pagination_markup = '<div class="navigation"><ul>';

                     foreach ( $fallback_links as $link ) {
                         $pagination_markup .= '<li>' . $link . '</li>';
                     }

                     $pagination_markup .= '</ul></div>';
                 }
             }

             if ( $pagination_markup ) {
                 echo '<div class="blognavigation">' . $pagination_markup . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Contains sanitized pagination markup.
             }
           ?>
        </div>
      <?php endif; endif; wp_reset_postdata(); ?>
    		</div>
    	</div>
    </div>

    <?php if ( !empty(get_the_content()) || get_the_content() !== "" ): ?>
    <div class="project-main">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="content-box maincontent">
            <?php wpautop(the_content()); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
<?php

// Jawda header
get_my_footer();