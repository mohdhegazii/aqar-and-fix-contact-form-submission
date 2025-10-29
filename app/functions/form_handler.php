<?php

// files are not executed directly
if ( ! defined( 'ABSPATH' ) ) {	die( 'Invalid request.' ); }


function set_mail_content_type(){ return "text/html"; }
add_filter( 'wp_mail_content_type','set_mail_content_type' );


function prefix_send_email_to_admin() {

  // Language
  $lang = ( isset($_POST['langu']) && $_POST['langu'] == 'ar' ) ? 'ar' : 'en';

  // Verify nonce
  if ( ! isset( $_POST['my_contact_form_nonce'] ) || ! wp_verify_nonce( $_POST['my_contact_form_nonce'], 'my_contact_form_action' ) ) {
    $error_message = get_text_lang('عذراً، حدث خطأ ما.','Sorry, something went wrong.',$lang, false);
    wp_send_json_error( ['message' => $error_message] );
  }

  // Thank you page
  $thankyou_page_id = carbon_get_theme_option( 'jawda_page_thankyou_'.$lang );
  $thankyou = $thankyou_page_id ? get_page_link($thankyou_page_id) : home_url('/');


  // Site Email
  $email_to = get_bloginfo('admin_email');

  // Check Inputs
  foreach ($_POST as $key => $postval) { $_POST[$key] = test_input($postval); }

  // Check Required Data
  if(
    !isset($_POST['name']) || empty($_POST['name'])
    || !isset($_POST['phone']) || empty($_POST['phone'])
    || !isset($_POST['packageid']) || empty($_POST['packageid'])
  ){
    $error_message = get_text_lang('برجاء التأكد من اضافة جميع الحقول المطلوبة','Please make sure to add all required fields',$lang, false);
    wp_send_json_error( ['message' => $error_message] );
    return;
  }

    // package id
    $packagename = sanitize_text_field($_POST['packageid']);

    $name = sanitize_text_field($_POST['name']);
    $phone = sanitize_text_field($_POST['phone']);

    $massege = ( isset($_POST['special_request']) AND $_POST['special_request'] != '' ) ? sanitize_text_field($_POST['special_request']) : 'لم يتم اضافة رسالة';

    $bHasLink = strpos($massege, 'http') !== false || strpos($massege, 'www.') !== false;

    if ( $bHasLink ) {
        $error_message = get_text_lang('غير مسموح بإضافة روابط في الرسالة.','It is not allowed to add links in the message.',$lang, false);
        wp_send_json_error( ['message' => $error_message] );
        return;
    }

    $headers = [];
    if( isset($_POST['email']) && $_POST['email'] != '' ){
      $email = sanitize_email($_POST['email']);
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $error_message = get_text_lang('برجاء التأكد من ادخال بريد الكتروني صحيح.','Please make sure to enter a valid email.',$lang, false);
          wp_send_json_error( ['message' => $error_message] );
          return;
      }
      $headers[]   = 'Reply-To: '.$name.' <'.$email.'>';
    } else {
      $email = "لم يتم اضافته";
    }

    // Check Phone
    if( strlen($phone) < 11 || strlen($phone) > 17 ){
        $error_message = get_text_lang('برجاء التأكد من ادخال رقم هاتف صحيح.','Please make sure to enter a valid phone number.',$lang, false);
        wp_send_json_error( ['message' => $error_message] );
        return;
    }

    // Title Of Email
    $subject = "رسالة جديدة من العميل : ".$name;

    $message = "
    <html>
    <head>
    <title>" . esc_html($subject) . "</title>
    </head>
    <body>
      <h2>" . esc_html($subject) . "</h2>
      <table>
        <tr>
          <td><strong>اسم العميل : </strong></td>
          <td>" . esc_html($name) . "</td>
        </tr>
        <tr>
          <td><strong>ايميل العميل : </strong></td>
          <td>" . esc_html($email) . "</td>
        </tr>
        <tr>
          <td><strong>تليفون العميل : </strong></td>
          <td>" . esc_html($phone) . "</td>
        </tr>
        <tr>
          <td><strong>رسالة العميل : </strong></td>
          <td>" . esc_html($massege) . "</td>
        </tr>
        <tr>
          <td><strong>اسم المشروع / الإهتمام : </strong></td>
          <td>" . esc_html($packagename) . "</td>
        </tr>
      </table>
    </body>
    </html>
    ";

    $send_mail = wp_mail($email_to, $subject, $message, $headers);

    if( $send_mail !== false ){
        global $wpdb;
        $table = $wpdb->prefix.'leadstable';
        $data = array('name' => $name,'email' => $email,'phone' => $phone,'massege' => $massege,'packagename' => $packagename);
        $format = array('%s','%s','%s','%s','%s');
        $wpdb->insert($table,$data,$format);
        $my_id = $wpdb->insert_id;

        if (defined('WP_TESTS_DOMAIN')) {
            wp_send_json_success(['redirect' => $thankyou]);
        } else {
            wp_redirect($thankyou);
            exit;
        }
    } else {
        $error_message = get_text_lang(
            'عذرا، فشل إرسال البريد. يرجى التحقق من إعدادات خادم البريد (SMTP).',
            'Sorry, the email could not be sent. Please check the server\'s mail (SMTP) configuration.',
            $lang,
            false
        );
        wp_send_json_error( ['message' => $error_message] );
    }
}
add_action( 'admin_post_nopriv_my_contact_form', 'prefix_send_email_to_admin' );
add_action( 'admin_post_my_contact_form', 'prefix_send_email_to_admin' );



function get_text_lang($st1, $st2, $lang, $echo = true){
  $return = ($lang == 'ar') ? $st1 : $st2;
  if ($echo) {
    echo $return;
  } else {
    return $return;
  }
}




function ja_ajax_search_properties() {

	$results = new WP_Query( array(
		'post_type'     => array( 'property' ),
		'post_status'   => 'publish',
    'posts_per_page' => 10,
		's'             => sanitize_text_field( $_POST['search'] ),
	) );

	$items = array();

	if ( !empty( $results->posts ) ) {
		foreach ( $results->posts as $result ) {
			$items[] = $result->post_title;
		}
	}

	wp_send_json_success( $items );
}
add_action( 'wp_ajax_search_properties',        'ja_ajax_search_properties' );
add_action( 'wp_ajax_nopriv_search_properties', 'ja_ajax_search_properties' );


function ja_ajax_search_projects() {

	$results = new WP_Query( array(
		'post_type'     => array( 'projects' ),
		'post_status'   => 'publish',
    'posts_per_page' => 10,
		's'             => sanitize_text_field( $_POST['search'] ),
	) );

	$items = array();

	if ( !empty( $results->posts ) ) {
		foreach ( $results->posts as $result ) {
			$items[] = $result->post_title;
		}
	}

	wp_send_json_success( $items );
}
add_action( 'wp_ajax_search_projects',        'ja_ajax_search_projects' );
add_action( 'wp_ajax_nopriv_search_projects', 'ja_ajax_search_projects' );