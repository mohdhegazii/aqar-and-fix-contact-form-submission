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
         $paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
         $loop  = new WP_Query(
             array(
                 'post_type'      => 'catalogs',
                 'posts_per_page' => 9,
                 'paged'          => $paged,
                 'post_status'    => 'publish',
                 'orderby'        => 'date', // modified | title | name | ID | rand
                 'order'          => 'DESC',
                 'no_found_rows'  => false,
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
           <div class="blognavigation">
             <?php
             global $wp_query;
               $orig_query = $wp_query; // fix for pagination to work
               $wp_query = $loop;
               $big = 999999999;
               echo paginate_links(array(
                   'base'    => str_replace($big, '%#%', esc_url( get_pagenum_link($big) ) ),
                   'format'  => '',
                   'current' => $paged,
                   'total'   => (int) $wp_query->max_num_pages,
               ));
               $wp_query = $orig_query; // fix for pagination to work
             ?>
           </div>
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