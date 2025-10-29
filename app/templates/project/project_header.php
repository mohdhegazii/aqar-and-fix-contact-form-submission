<?php

// files are not executed directly
if ( ! defined( 'ABSPATH' ) ) {	die( 'Invalid request.' ); }

/* -----------------------------------------------------------------------------
# project_header
----------------------------------------------------------------------------- */

function get_my_project_header(){

  ob_start();

  $project_city = Myterml('city');

  $post_id = get_the_ID();
  $price = carbon_get_post_meta( $post_id, 'jawda_price' );
  $installment = carbon_get_post_meta( $post_id, 'jawda_installment' );
  $down_payment = carbon_get_post_meta( $post_id, 'jawda_down_payment' );
  $size = carbon_get_post_meta( $post_id, 'jawda_size' );
  $year = carbon_get_post_meta( $post_id, 'jawda_year' );
  $attachments = carbon_get_post_meta( $post_id, 'jawda_attachments' );
  $faqs = carbon_get_post_meta( $post_id, 'jawda_faq' );

  // Developer
  $developer = get_the_terms( get_the_ID(), 'projects_developer' );
  $dev_name = $dev_link = NULL;
  if( isset($developer[0]->term_id) )
  {
    $dev_name = $developer[0]->name;
    $dev_link = get_term_link($developer[0]);
  }

  // City
  $area = get_the_terms( get_the_ID(), 'projects_area' );
  $city_name = $city_link = NULL;
  if( isset($area[0]->term_id) )
  {
    $city_name = $area[0]->name;
    $city_link = get_term_link($area[0]);
  }

  ?>

  <?php get_projects_top_search(); ?>

  <div class="project-hero">
		<div class="container">
			<div class="row no-padding">
				<div class="col-md-5">
					<div class="project-info">

						<!--Breadcrumbs-->
            <div class="breadcrumbs" itemscope="" itemtype="http://schema.org/BreadcrumbList">
              <span itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                <a class="breadcrumbs__link" href="<?php echo siteurl; ?>" itemprop="item">
                  <span itemprop="name"><?php echo sitename; ?></span>
                </a>
                <meta itemprop="position" content="1">
              </span>
              <span class="breadcrumbs__separator">›</span>
              <?php if ( $city_name !== NULL ): ?>
                <span itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                  <a class="breadcrumbs__link" href="<?php echo $city_link; ?>" itemprop="item">
                      <span itemprop="name"><?php echo $city_name; ?></span>
                    </a>
                  <meta itemprop="position" content="2">
                </span>
                <span class="breadcrumbs__separator">›</span>
              <?php endif; ?>
            </div>

						<h1 class="project-headline"><?php echo get_the_title(get_the_ID()); ?></h1>

            <?php if ( $city_name !== NULL ): ?>
              <div class="location"><a href="<?php echo $city_link; ?>"> <i class="icon-location"></i> <?php echo $city_name; ?></a></div>
            <?php endif; ?>

						<!--Prices-->
            <?php if ( $price !== NULL AND $price != '' ): ?>
              <div class="start-price"> <?php get_text('الأسعار تبدأ من','Prices start from'); ?> <span><?php echo number_format($price); ?></span> <?php get_text('ج.م','EGP'); ?></div>
            <?php endif; ?>

						<!--details-->
						<div class="project-payment">
              <?php if ( $installment !== NULL AND $installment != '' ): ?>
                <div class="payment-details"><?php echo $installment; ?> <?php get_text('سنوات تقسيط','installment years'); ?></div>
              <?php endif; ?>
              <?php if ( $down_payment !== NULL AND $down_payment != '' ): ?>
                <div class="payment-details"><?php echo get_text('المقدم','Down payment'); echo ' '.$down_payment; ?></div>
              <?php endif; ?>
              <?php if ( $year !== NULL AND $year != '' ): ?>
                <div class="payment-details"><?php get_text('التسليم','Delivery'); echo ' '.$year; ?></div>
              <?php endif; ?>
              <?php if ( $size !== NULL AND $size != '' ): ?>
                <div class="payment-details"><?php get_text('مساحات تبدأ من','Spaces starting from'); echo ' '.$size; ?></div>
              <?php endif; ?>
						</div>
						<div class="price-update"><?php get_text('أخر تحديث','Last updated'); echo ' '.jawda_last_updated_date(); ?></div>
						<!--developer-->
            <?php if ( $dev_name !== NULL ): ?>
              <div class="project-developer"><?php get_text('المطور العقاري','project developer'); ?><a href=<?php echo $dev_link; ?>><?php echo $dev_name; ?></a> </div>
            <?php endif; ?>

					</div>

				</div>

				<div class="col-md-7">

          <?php if( is_array($attachments) and count($attachments) > 0 ): ?>
            <div class="hero-banner">
  						<div id="project-slider">
                <?php foreach ($attachments as $galleryphoto) {
                    $photourl = wp_get_attachment_image_src($galleryphoto,'medium_large');
                    echo '<img loading="lazy" src='.$photourl[0].' alt="'.get_the_title().'" width="500" height="300">';
                } ?>
  						</div>

  						<div class="slider-nav">
                <?php foreach ($attachments as $galleryphoto) {
                    $photourl = wp_get_attachment_image_src($galleryphoto,'thumbnail');
                    echo '<img loading="lazy" class="item-slick" src='.$photourl[0].' alt="'.get_the_title().'" width="500" height="300">';
                } ?>
  						</div>
  					</div>
          <?php endif; ?>

				</div>
			</div>
		</div>
	</div>

  <?php
  $content = ob_get_clean();
  echo minify_html($content);


}


function get_projects_top_search()
{
  ?>
  <div class="topsearchbar">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <?php jawda_projects_search_box(); ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
