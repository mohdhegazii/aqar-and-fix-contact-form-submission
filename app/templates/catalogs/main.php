<?php

// files are not executed directly
if ( ! defined( 'ABSPATH' ) ) {	die( 'Invalid request.' ); }

/* -----------------------------------------------------------------------------
# cat_posts
----------------------------------------------------------------------------- */


function get_my_catalogs_main()
{

  ob_start();
  $lang = is_rtl() ? 'ar' : 'en';
  $catalog_id = get_the_ID();
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

        if( $catalog_type === 1 ) {

          $args = get_catalog_projects($project_city,$project_type,$project_price_from,$project_price_to);
          $the_query = new WP_Query( $args );

        }
        if( $catalog_type === 2 ) {

          $args = get_catalog_properties($property_city,$property_type,$jawda_property_main_project);

          /*
          echo "<pre>";
          var_dump($args);
          die();
          */

          $the_query = new WP_Query( $args );

        }

        ?>

        <?php
        //
        if ( $the_query->have_posts() ) :
          while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
          <div class="col-md-4 projectbxspace">
            <?php if ( $catalog_type === 1 ): ?>
              <?php get_my_project_box(get_the_ID()); ?>
            <?php elseif( $catalog_type === 2 ): ?>
              <?php get_my_property_box(get_the_ID()); ?>
            <?php endif; ?>
          </div>
          <?php endwhile; ?>
       <?php  endif;
        ?>

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




function get_catalog_projects($city,$type,$price_from,$price_to)
{

  $return = [];
  $return['post_type'] = ['projects'];
  $return['posts_per_page'] = 36; 
  $return['orderby'] = 'rand';
  $return['tax_query']['relation'] = 'AND';
  if ( is_numeric($city) && $city != '0' ) {
  $return['tax_query'][] = ['taxonomy' => 'projects_area','field' => 'term_id','terms' => $city];
  }
  if ( is_numeric($type) && $type != '0' ) {
  $return['tax_query'][] = ['taxonomy' => 'projects_type','field' => 'term_id','terms' => $type];
  }
  $return['meta_query'][] = ['key' => 'jawda_price','value' => [$price_from,$price_to],'type' => 'numeric','compare' => 'BETWEEN'];
  return $return;
}

function get_catalog_properties($city,$type,$project)
{

  $return = [];

  $return['post_type'] = 'property';
  $return['posts_per_page'] = 30;
  $return['orderby'] = 'rand';
  if ( is_numeric($city) AND is_numeric($type) ) {
    $return['tax_query']['relation'] = 'AND';
  }
  if ( is_numeric($city) && $city != '0' ) {
    $return['tax_query'][] = ['taxonomy' => 'property_city','field' => 'term_id','terms' => $city];
  }
  if ( is_numeric($type) && $type != '0' ) {
    $return['tax_query'][] = ['taxonomy' => 'property_type','field' => 'term_id','terms' => $type];
  }
  if ( !empty($project) ) {
    $return['meta_query'][] = ['key' => 'jawda_project','value' => $project];
  }
  return $return;
}
