<?php

// files are not executed directly
if ( ! defined( 'ABSPATH' ) ) {	die( 'Invalid request.' ); }

/* -----------------------------------------------------------------------------
# project_main
----------------------------------------------------------------------------- */

function get_my_project_main(){

  ob_start();

  $phone = carbon_get_theme_option( 'jawda_phone' );
  $whatsapp = carbon_get_theme_option( 'jawda_whatsapp' );
  $mail = carbon_get_theme_option( 'jawda_email' );
  $address = carbon_get_theme_option( 'jawda_address' );
  $whatsapplink = get_whatsapp_link($whatsapp);


  $post_id = get_the_ID();
  $title = get_the_title($post_id);
  $price = carbon_get_post_meta( $post_id, 'jawda_price' );
  $installment = carbon_get_post_meta( $post_id, 'jawda_installment' );
  $down_payment = carbon_get_post_meta( $post_id, 'jawda_down_payment' );
  $size = carbon_get_post_meta( $post_id, 'jawda_size' );
  $year = carbon_get_post_meta( $post_id, 'jawda_year' );

  $location = carbon_get_post_meta( $post_id, 'jawda_location' );
  $unit_types = carbon_get_post_meta( $post_id, 'jawda_unit_types' );
  $payment_systems = carbon_get_post_meta( $post_id, 'jawda_payment_systems' );
  $finishing = carbon_get_post_meta( $post_id, 'jawda_finishing' );
  $priperty_plan = carbon_get_post_meta( $post_id, 'jawda_priperty_plan' );
  $video_url = carbon_get_post_meta( $post_id, 'jawda_video_url' );
  $map = carbon_get_post_meta( $post_id, 'jawda_map' );

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

  $features = get_the_terms( get_the_ID(), 'projects_features' );
  $projects_type = get_the_terms( get_the_ID(), 'projects_type' );
  $projects_category = get_the_terms( get_the_ID(), 'projects_category' );
  $projects_tag = get_the_terms( get_the_ID(), 'projects_tag' );


  ?>




  <!--Project Main-->
	<div class="project-main">
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<div class="headline-p"><?php get_text('تفاصيل','Details'); echo ' '.$title; ?></div>

					<div class="content-box" style="padding: 0">

						<table class="infotable">
							<tbody>
								<tr>
									<th class="ttitle"><?php get_text('اسم المشروع','project name'); ?></th>
									<td class="tvalue"><?php echo get_the_title(get_the_ID()); ?></td>
								</tr>
                <?php if ( $location !== NULL AND $location != '' ): ?>
                  <tr>
                    <th class="ttitle"><?php get_text('موقع المشروع','project Location'); ?></th>
                    <td class="tvalue"><?php echo $location; ?></td>
                  </tr>
                <?php endif; ?>
                <?php if ( $unit_types !== NULL AND $unit_types != '' ): ?>
                  <tr>
                    <th class="ttitle"><?php get_text('وحدات المشروع','project units'); ?></th>
                    <td class="tvalue"><?php echo $unit_types; ?></td>
                  </tr>
                <?php endif; ?>
                <?php if ( $year !== NULL AND $year != '' ): ?>
                  <tr>
                    <th class="ttitle"><?php get_text('موعد التسليم','Delivery date'); ?></th>
                    <td class="tvalue"><?php echo $year; ?></td>
                  </tr>
                <?php endif; ?>
                <?php if ( $payment_systems !== NULL AND $payment_systems != '' ): ?>
                  <tr>
                    <th class="ttitle"><?php get_text('انظمة سداد','Payment Systems'); ?></th>
                    <td class="tvalue"><?php echo $payment_systems; ?></td>
                  </tr>
                <?php endif; ?>
                <?php if ( $finishing !== NULL AND $finishing != '' ): ?>
                  <tr>
                    <th class="ttitle"><?php get_text('نوع التشطيب','Finishing type'); ?></th>
                    <td class="tvalue"><?php echo $finishing; ?></td>
                  </tr>
                <?php endif; ?>
                <?php if ( $size !== NULL AND $size != '' ): ?>
                  <tr>
                    <th class="ttitle"><?php get_text('مساحات تبدا من','Spaces starting from'); ?></th>
                    <td class="tvalue"><?php echo $size; ?></td>
                  </tr>
                <?php endif; ?>
                <?php if ( $price !== NULL AND $price != '' ): ?>
                  <tr>
                    <th class="ttitle"><?php get_text('اسعار تبدأ من','Prices starting from'); ?></th>
                    <td class="tvalue"><?php echo number_format($price); ?></td>
                  </tr>
                <?php endif; ?>
							</tbody>
						</table>
					</div>

          <div class="content-box contact-form hide-pc">
						<div class="headline-p"><?php get_text('للحجز او الاستفسار','For reservations or inquiries'); ?></div>
						<?php my_contact_form(); ?>
					</div>
					<!--cta-btns-->
          <div class="cta-btns hide-pc">
						<a target="_blank" href="<?php echo $whatsapplink; ?>" class="wts-btn"><i class="icon-whatsapp"></i><?php txt('whatsapp'); ?></a>
						<a href="tel:<?php echo $phone; ?>" class="call-btn"><i class="icon-phone"></i><?php txt('phone'); ?></a>
					</div>
					<!--Video-->
          <?php if ( $video_url !== NULL AND $video_url != '' ): ?>
            <?php $videoid = get_youtube_id($video_url); ?>
            <div class="headline-p"><?php get_text('فيديو','Video'); echo ' '.$title; ?></div>
            <div class="content-box">
              <div class="video">
                <iframe
                  width="560"
                  height="315"
                  src="https://www.youtube.com/embed/<?php echo esc_attr($videoid); ?>"
                  srcdoc="<style>*{padding:0;margin:0;overflow:hidden}html,body{height:100%}img,span{position:absolute;width:100%;top:0;bottom:0;margin:auto}span{height:1.5em;text-align:center;font:48px/1.5 sans-serif;color:white;text-shadow:0 0 0.5em black}</style><a href=https://www.youtube.com/embed/<?php echo esc_attr($videoid); ?>?autoplay=1><img loading=lazy src=https://img.youtube.com/vi/<?php echo esc_attr($videoid); ?>/hqdefault.jpg alt='<?php the_title(); ?>'><span>▶</span></a>"
                  frameborder="0"
                  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                  title="<?php the_title(); ?>"
                  style="width:100%;height:auto;min-height:350px;"
                ></iframe>
                <script type="application/ld+json">
                {
                  "@context": "https://schema.org",
                  "@type": "VideoObject",
                  "name": "<?php the_title(); ?>",
                  "description": "<?php the_title(); ?>",
                  "thumbnailUrl": [
                    "https://img.youtube.com/vi/<?php echo esc_attr($videoid); ?>/hqdefault.jpg"
                   ],
                  "uploadDate": "<?php echo get_the_date('Y-m-d'); ?>T08:00:00+08:00",
                  "duration": "PT1M54S",
                  "contentUrl": "<?php echo get_permalink(); ?>",
                  "interactionStatistic": {
                    "@type": "InteractionCounter",
                    "interactionType": { "@type": "http://schema.org/WatchAction" },
                    "userInteractionCount": <?php echo rand(15, 35); ?>
                  }
                }
                </script>
              </div>
            </div>
          <?php endif; ?>
					<!--Master Plan-->
          <?php if ( $priperty_plan !== NULL AND $priperty_plan != '' ): ?>
            <?php $planimg = wp_get_attachment_image_src($priperty_plan,'medium_large'); ?>
            <div class="headline-p"><?php get_text('مخطط','plan of'); echo " ".$title; ?></div>
  					<div class="content-box">
  						<div class="master-plan">
  							<img loading="lazy" src="<?php echo $planimg[0]; ?>" width="2332" height="1240" alt="<?php the_title(); ?>" />
  						</div>
  					</div>
          <?php endif; ?>

          <!-- Map -->
          <?php if ( is_array($map) AND is_numeric($zoom = $map['zoom']) ): ?>
            <?php
            $lat = $map['lat'];
            $lng = $map['lng'];
            $zoom = $map['zoom'];

            $lat = number_format((float)$lat, 4, '.', '');
            $lng = number_format((float)$lng, 4, '.', '');

            $xtile = floor((($lng + 180) / 360) * pow(2, $zoom));
            $ytile = floor((1 - log(tan(deg2rad($lat)) + 1 / cos(deg2rad($lat))) / pi()) /2 * pow(2, $zoom));
            $n = pow(2, $zoom);
            $lon_deg = ($xtile / $n) * 360.0 - 180.0;
            $lat_deg = rad2deg(atan(sinh(pi() * (1 - 2 * $ytile / $n))));

            $mapurl = 'https://www.openstreetmap.org/export/embed.html?bbox='.$lng.','.$lat.','.$lon_deg.','.$lat_deg.'&amp;layer=mapquest&amp;marker='.$lat.','.$lng.'&amp;zoom=12';
            $mapimg = 'https://tile.openstreetmap.org/'.$zoom.'/'.$xtile.'/'.$ytile.'.png';
            ?>
            <div class="headline-p"><?php get_text('خريطة','map of'); echo " ".$title; ?></div>
            <div class="content-box">
              <div class="google-location">
                <iframe
                  width="100%"
                  height="400"
                  src="<?php echo $mapurl; ?>"
                  srcdoc="<style>
                    *{padding:0;margin:0;overflow:hidden}
                    html,body{height:100%}
                    img,span{position:absolute;width:100%;top:0;bottom:0;margin:auto}
                    span{height:1.5em;text-align:center;font:48px/1.5 sans-serif;color:#000;text-shadow:0 0 5px black;font-family:Cairo,Arial,Ubuntu;}</style>
                    <a href=<?php echo $mapurl; ?>><img loading=lazy src=<?php echo $mapimg; ?> alt='<?php the_title(); ?>'><span><?php echo 'View Map'; /*get_text('عرض الخريطة','View Map');*/ ?></span></a>"
                  frameborder="0"
                  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                  title="<?php the_title(); ?>"
                  style="width:100%;height:auto;min-height:350px;"
                ></iframe>

              </div>
            </div>
          <?php endif; ?>

					<!--Project Units-->
            <?php project_properties($post_id); ?>

					<!--post-content-->
					<div class="content-box maincontent">
						<div class="headline-p"><span id="11111"><?php get_text('تفاصيل المشروع','Project details'); ?></span></div>
            <div class="entry-content">
              <?php wpautop(the_content()); ?>
            </div>
            <div class="contact-center">
              <p><?php get_text('اعجبك المقال؟! شارك','Did you like the article?! Share it'); ?></p>
              <div class="sharing-buttons"><?php theme_share_buttons(); ?></div>
            </div>
            <!--cta-btns-->
            <div class="cta-btns hide-pc">
              <a href="<?php echo $whatsapplink; ?>" class="wts-btn"><i class="icon-whatsapp"></i><?php txt('whatsapp'); ?></a>
              <a href="tel:<?php echo $phone; ?>" class="call-btn"><i class="icon-phone"></i><?php txt('phone'); ?></a>
            </div>
					</div>

					<!--project-features-->
          <?php if ( is_array($features) AND !empty($features) ): ?>
            <div class="headline-p"><?php get_text('مميزات','Features of'); echo " ".$title; ?></div>
  					<div class="content-box">
  						<div class="project-features">
  							<ul>
                  <?php foreach ($features as $feature): ?>
                    <li><?php echo $feature->name; ?></li>
                  <?php endforeach; ?>
  							</ul>
  						</div>
  					</div>
          <?php endif; ?>

					<!--End-->
          <?php if( isset($faqs[0]['jawda_faq_q']) ): ?>
          <div class="faq">
            <h2 class="headline-p"><?php get_text("أسئلة شائعة","Frequently Asked Questions"); ?></h2>
            <div class="content">
              <div class="acc">
              <?php
                $i = 1;
                if( isset($faqs) && !empty($faqs) ):
                foreach ($faqs as $faq) {
                  $active_class = $i === 1 ? 'active' : '';
                  $active_style = $i === 1 ? 'style="display: block"' : '';
                  ?>
                  <div class="acc__card">
                    <div class="acc__title <?php echo $active_class; ?>"><?php echo esc_html($faq['jawda_faq_q']); ?></div>
                    <div class="acc__panel" <?php echo $active_style; ?>><?php echo esc_html($faq['jawda_faq_a']); ?></div>
                  </div>
                  <?php
                  $i++;
                }
                endif;
                ?>
                <script type="application/ld+json">{"@context": "https://schema.org","@type": "FAQPage","mainEntity": [<?php $i = 0; foreach ($faqs as $faq): ?><?php if( $i != 0 ){ echo ','; } $i++; ?>{"@type": "Question","name": <?php echo json_encode($faq['jawda_faq_q'], JSON_UNESCAPED_UNICODE); ?>,"acceptedAnswer": {"@type": "Answer","text": <?php echo json_encode($faq['jawda_faq_a'], JSON_UNESCAPED_UNICODE); ?>}}<?php endforeach; ?>]}</script>
              </div>
            </div>
          </div>
          <?php endif; ?>

				</div>
        <div class="col-md-4 sticky">
          <?php get_my_sidbar(2,$post_id,$post_id); ?>
				</div>
			</div>
		</div>
	</div>
	<!--End main-->








  <?php
  $content = ob_get_clean();
  echo minify_html($content);


}
