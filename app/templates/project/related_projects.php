<?php

// files are not executed directly
if ( ! defined( 'ABSPATH' ) ) {	die( 'Invalid request.' ); }

/* -----------------------------------------------------------------------------
# related_projects - MODIFIED
----------------------------------------------------------------------------- */

function get_my_related_projects() {

  ob_start();

  ?>

  <div>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="headline">
						<h2><?php txt('Similar projects'); ?></h2>
						<div class="separator"></div>
					</div>
				</div>
			</div>
			<div class="row">

        <?php
        $current_post_id = get_the_ID();
        $related_area = get_the_terms( $current_post_id, 'projects_area' );
        $related_category = get_the_terms( $current_post_id, 'project_category' );

        $tax_query = array('relation' => 'AND'); // يجب أن تحقق المشاريع نفس المنطقة والتصنيف

        if ( ! empty( $related_area ) && is_array( $related_area ) ) {
            $tax_query[] = array(
                'taxonomy' => 'projects_area',
                'field'    => 'term_id',
                'terms'    => wp_list_pluck( $related_area, 'term_id' ),
            );
        }

        if ( ! empty( $related_category ) && is_array( $related_category ) ) {
            $tax_query[] = array(
                'taxonomy' => 'project_category',
                'field'    => 'term_id',
                'terms'    => wp_list_pluck( $related_category, 'term_id' ),
            );
        }

        $args = array(
            'post_type'      => 'projects', // تأكد من أن هذا هو الـ slug الصحيح لنوع منشورات المشاريع
            'post__not_in'   => array( $current_post_id ),
            'posts_per_page' => 5,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'tax_query'      => $tax_query,
            'date_query'     => array(
                'before' => get_the_date('Y-m-d H:i:s', $current_post_id),
            ),
        );

        $query = new WP_Query( $args );
        if( $query->have_posts() ) : while( $query->have_posts() ) : $query->the_post(); ?>

  				<div class="col-md-4">
            <?php get_my_project_box(get_the_ID()); ?>
  				</div>

          <?php endwhile; endif; wp_reset_postdata(); ?>

			</div>
		</div>
	</div>
	<?php

  $content = ob_get_clean();
  echo minify_html($content);

}