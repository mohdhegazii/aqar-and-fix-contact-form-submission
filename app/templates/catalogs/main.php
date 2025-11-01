<?php

// files are not executed directly
if ( ! defined( 'ABSPATH' ) ) {	die( 'Invalid request.' ); }

/* -----------------------------------------------------------------------------
# cat_posts
----------------------------------------------------------------------------- */


function get_my_catalogs_main()
{

  ob_start();
  $paged = max( 1, (int) get_query_var('paged'), (int) get_query_var('page') );
  $lang = is_rtl() ? 'ar' : 'en';
  $catalog_id = get_the_ID();
  $catalog_permalink = get_permalink($catalog_id);
  $title = get_the_title($catalog_id);
  $catalog_type = carbon_get_post_meta( $catalog_id, 'jawda_catalog_type' );
  $project_city = carbon_get_post_meta( $catalog_id, 'jawda_project_city' );
  $project_type = carbon_get_post_meta( $catalog_id, 'jawda_project_type' );

  $property_city = carbon_get_post_meta( $catalog_id, 'jawda_property_city' );
  // $property_state = carbon_get_post_meta( $catalog_id, 'jawda_property_state' );
  $property_type = carbon_get_post_meta( $catalog_id, 'jawda_property_type' );

  $project_price_from = carbon_get_post_meta( $catalog_id, 'jawda_project_price_from' );
  $project_price_to = carbon_get_post_meta( $catalog_id, 'jawda_project_price_to' );

  $jawda_page_projects = carbon_get_theme_option( 'jawda_page_projects_'.$lang );
  $jawda_page_properties = carbon_get_theme_option( 'jawda_page_properties_'.$lang );

  $jawda_property_main_project = [];
  if ( isset(carbon_get_post_meta( $catalog_id, 'jawda_property_main_project' )[0]) ) {
    $jawda_property_main_project = carbon_get_post_meta( $catalog_id, 'jawda_property_main_project' )[0];
  }


  if( $catalog_type === 1 ) {
    $breadcrumbspage = $jawda_page_projects;
    get_projects_top_search();
  }
  if( $catalog_type === 2 ) {
    $breadcrumbspage = $jawda_page_properties;
    get_properties_top_search();
  }

  $thumbnail_url = 'https://masharf.com/wp-content/uploads/2023/12/Masharf-real-estate.jpg';
  if (has_post_thumbnail()) {
    $thumbnail_url = get_the_post_thumbnail_url();
  }


  ?>
  <style>
    .hero-photo {height:150px;width:auto;float:left;border-radius:5px;}
    @media screen AND (max-width:720px) {
      .hero-photo {height:250px;width:100%;object-fit:cover;}
    }
  </style>
  <div class="unit-hero">
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<div class="unit-info">
						<!--Breadcrumbs-->
            <div class="breadcrumbs" itemscope="" itemtype="http://schema.org/BreadcrumbList">
              <?php $i = 1; ?>
              <span itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                <a class="breadcrumbs__link" href="<?php echo siteurl; ?>" itemprop="item"><span itemprop="name"><?php echo sitename; ?></span></a>
                <meta itemprop="position" content="<?php echo $i; $i++; ?>">
              </span>
              <span class="breadcrumbs__separator">›</span>
              <span itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                <a class="breadcrumbs__link" href="<?php echo esc_url( get_page_link($breadcrumbspage) ); ?>" itemprop="item">
                    <span itemprop="name"><?php echo esc_html( get_the_title( $breadcrumbspage ) ); ?></span>
                  </a>
                <meta itemprop="position" content="2">
              </span>
              <span class="breadcrumbs__separator">›</span>
            </div>
						<h1 class="project-headline"><?php echo esc_html( $title ); ?></h1>
					</div>
				</div>
        <div class="col-md-4">
        <?php echo '<img class="hero-photo" src="' . esc_url($thumbnail_url) . '" alt="'.esc_attr($title).'" width="500" height="350">'; ?>
        </div>
			</div>
		</div>
	</div>

  <div class="units-page">
    <div class="container">
      <div class="row">

        <?php

          $args = get_unified_catalog_args( $catalog_id, $paged );
          $the_query = new WP_Query( $args );

        ?>

        <?php
        //
        if ( $the_query->have_posts() ) :
          while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
          <div class="col-md-4 projectbxspace">
            <?php if ( get_post_type() === 'projects' ): ?>
              <?php get_my_project_box(get_the_ID()); ?>
            <?php elseif( get_post_type() === 'property' ): ?>
              <?php get_my_property_box(get_the_ID()); ?>
            <?php endif; ?>
          </div>
          <?php endwhile; ?>
       <?php  endif;
        ?>

        <?php if ($the_query->max_num_pages > 1) : ?>
          <div class="col-md-12 center">
            <div class="blognavigation">
              <?php
              $base = rtrim($catalog_permalink, '/') . '/page/%#%/';
              $pagination_links = paginate_links([
                'base'      => $base,
                'format'    => '',
                'current'   => $paged,
                'total'     => (int) $the_query->max_num_pages,
                'mid_size'  => 2,
                'end_size'  => 1,
                'prev_text' => __('« Previous'),
                'next_text' => __('Next »'),
                'type'      => 'plain',
              ]);

              // Remove /page/1/ from the first page link
              $pagination_links = str_replace( "page/1/'", "'", $pagination_links );
              $pagination_links = str_replace( 'page/1/"', '"', $pagination_links );

              echo $pagination_links;
              ?>
            </div>
          </div>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
      </div>
    </div>
  </div>

  <?php if ( !empty(get_the_content()) || get_the_content() !== "" ): ?>
  <div class="project-main">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="content-box">
            <?php wpautop(the_content()); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>


  <?php

  $content = ob_get_clean();
  echo minify_html($content);

}


function get_unified_catalog_args( $catalog_id, $paged = 1 ) {
    $catalog_type = carbon_get_post_meta( $catalog_id, 'jawda_catalog_type' );

    $args = [
        'post_type'      => ['projects', 'property'],
        'posts_per_page' => 9,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
		'suppress_filters' => false,
		'no_found_rows'  => false,
    ];

    $tax_query = ['relation' => 'AND'];
    $meta_query = ['relation' => 'AND'];

    if ( $catalog_type === 1 ) { // Projects
        $project_city = carbon_get_post_meta( $catalog_id, 'jawda_project_city' );
        $project_type = carbon_get_post_meta( $catalog_id, 'jawda_project_type' );
        $project_price_from = carbon_get_post_meta( $catalog_id, 'jawda_project_price_from' );
        $project_price_to = carbon_get_post_meta( $catalog_id, 'jawda_project_price_to' );

        if ( is_numeric($project_city) && $project_city != '0' ) {
            $tax_query[] = ['taxonomy' => 'projects_area', 'field' => 'term_id', 'terms' => $project_city];
        }
        if ( is_numeric($project_type) && $project_type != '0' ) {
            $tax_query[] = ['taxonomy' => 'projects_type', 'field' => 'term_id', 'terms' => $project_type];
        }
        if ( ! empty($project_price_from) && ! empty($project_price_to) ) {
            $meta_query[] = ['key' => 'jawda_price', 'value' => [$project_price_from, $project_price_to], 'type' => 'numeric', 'compare' => 'BETWEEN'];
        }

    } elseif ( $catalog_type === 2 ) { // Properties
        $property_city = carbon_get_post_meta( $catalog_id, 'jawda_property_city' );
        $property_type = carbon_get_post_meta( $catalog_id, 'jawda_property_type' );
        $property_main_project_raw = carbon_get_post_meta( $catalog_id, 'jawda_property_main_project' );
        $property_main_project = !empty($property_main_project_raw[0]) ? $property_main_project_raw[0] : null;


        if ( is_numeric($property_city) && $property_city != '0' ) {
            $tax_query[] = ['taxonomy' => 'property_city', 'field' => 'term_id', 'terms' => $property_city];
        }
        if ( is_numeric($property_type) && $property_type != '0' ) {
            $tax_query[] = ['taxonomy' => 'property_type', 'field' => 'term_id', 'terms' => $property_type];
        }
        if ( !empty($property_main_project) ) {
            $meta_query[] = ['key' => 'jawda_project', 'value' => $property_main_project];
        }
    }

    if ( count( $tax_query ) > 1 ) {
        $args['tax_query'] = $tax_query;
    }

    if ( count( $meta_query ) > 1 ) {
        $args['meta_query'] = $meta_query;
    }

    return $args;
}
