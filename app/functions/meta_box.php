<?php

// Security Check
if ( ! defined( 'ABSPATH' ) ) {	die( 'Invalid request.' ); }

// Carbon_Fields
use Carbon_Fields\Container;
use Carbon_Fields\Field;

/* -------------------------------------------------------------------------
# Property Meta Boxes
------------------------------------------------------------------------- */

if ( !function_exists('jawda_meta_property') ) {

  add_action( 'carbon_fields_register_fields', 'jawda_meta_property' );
  function jawda_meta_property() {

    // Options
    $meta_package =
    Container::make( 'post_meta', 'Property Data' )
      ->where( 'post_type', '=', 'property' )
      ->add_tab( __( 'Property Details' ), array(

        // Gallery
        Field::make( 'separator', 'jawda_separator_002', __( 'Main Project' ) ),
        Field::make( 'multiselect', 'jawda_project', __( 'Main Project' ) )->add_options( 'get_my_projects_list' ),

        // Property details
        Field::make( 'separator', 'jawda_separator_003', __( 'Property details' ) ),
        Field::make( 'text', 'jawda_bedrooms', __( 'bedrooms' ) ),
        Field::make( 'text', 'jawda_bathrooms', __( 'bathrooms' ) ),
        Field::make( 'text', 'jawda_garage', __( 'garage' ) ),
        Field::make( 'text', 'jawda_price', __( 'price' ) ),
        Field::make( 'text', 'jawda_size', __( 'size' ) ),
        Field::make( 'text', 'jawda_year', __( 'Receipt date' ) ),
        Field::make( 'text', 'jawda_location', __( 'location' ) ),
        Field::make( 'text', 'jawda_payment_systems', __( 'Payment Systems' ) ),
        Field::make( 'text', 'jawda_finishing', __( 'finishing' ) ),

        Field::make( 'separator', 'jawda_separator_004', __( 'Property Plan' ) ),
        Field::make( 'image', 'jawda_priperty_plan', __( 'Plan' ) )

    ) )

    ->add_tab( __( 'Gallery' ), array(

      // Gallery
      Field::make( 'separator', 'jawda_separator_001', __( 'Property photos' ) ),
      Field::make( 'media_gallery', 'jawda_attachments', __( 'Property Gallery' ) ),


    ) )


    ->add_tab( __( 'Video' ), array(

      // map
      Field::make( 'separator', 'jawda_separator_0c1', __( 'Property Video' ) ),
      Field::make( 'text', 'jawda_video_url', __( 'youtube video url' ) ),


    ) )

    ->add_tab( __( 'Map' ), array(

      // map
      Field::make( 'separator', 'jawda_separator_0b1', __( 'Property On Map' ) ),
      Field::make( 'map', 'jawda_map', __( 'Map' ) )->set_position( '30.076224563542933','31.51153564453125','10' ),


    ) )


    ->add_tab( __( 'FAQ' ), array(

      Field::make( 'separator', 'jawda_separator_0d1', __( 'Frequently Asked Questions' ) ),

      Field::make( 'complex', 'jawda_faq', __( 'Questions' ) )
          ->add_fields( array(
              Field::make( 'text', 'jawda_faq_q', __( 'Question' ) ),
              Field::make( 'textarea', 'jawda_faq_a', __( 'Answer' ) ),
          )
        )


    ) );


  }

}








/* -----------------------------------------------------------------------------
# Term Meta
----------------------------------------------------------------------------- */


if ( !function_exists('jawda_meta_project') ) {

  add_action( 'carbon_fields_register_fields', 'jawda_meta_project' );
  function jawda_meta_project() {

    // Options
    $meta_package =
    Container::make( 'post_meta', 'Project Details' )
      ->where( 'post_type', '=', 'projects' )
      ->add_tab( __( 'Project Details' ), array(

        // Property details
        Field::make( 'separator', 'jawda_separator_003', __( 'Project details' ) ),
        Field::make( 'text', 'jawda_price', __( 'price' ) ),
        Field::make( 'text', 'jawda_installment', __( 'installment' ) ),
        Field::make( 'text', 'jawda_down_payment', __( 'down payment' ) ),
        Field::make( 'text', 'jawda_size', __( 'size' ) ),
        Field::make( 'text', 'jawda_year', __( 'Receipt date' ) ),
        Field::make( 'text', 'jawda_location', __( 'location' ) ),
        Field::make( 'text', 'jawda_unit_types', __( 'Unit types' ) ),

        Field::make( 'text', 'jawda_payment_systems', __( 'Payment Systems' ) ),
        Field::make( 'text', 'jawda_finishing', __( 'finishing' ) ),

        Field::make( 'separator', 'jawda_separator_004', __( 'Property Plan' ) ),
        Field::make( 'image', 'jawda_priperty_plan', __( 'Plan' ) )

      ) )

      ->add_tab( __( 'Gallery' ), array(

      // Gallery
      Field::make( 'separator', 'jawda_separator_001', __( 'Property photos' ) ),
      Field::make( 'media_gallery', 'jawda_attachments', __( 'Property Gallery' ) ),


      ) )


      ->add_tab( __( 'Video' ), array(

      // map
      Field::make( 'separator', 'jawda_separator_0c1', __( 'Property Video' ) ),
      Field::make( 'text', 'jawda_video_url', __( 'youtube video url' ) ),


      ) )

      ->add_tab( __( 'Map' ), array(

      // map
      Field::make( 'separator', 'jawda_separator_0b1', __( 'Property On Map' ) ),
      Field::make( 'map', 'jawda_map', __( 'Map' ) )->set_position( '30.076224563542933','31.51153564453125','10' ),


      ) )


      ->add_tab( __( 'FAQ' ), array(

      Field::make( 'separator', 'jawda_separator_0d1', __( 'Frequently Asked Questions' ) ),

      Field::make( 'complex', 'jawda_faq', __( 'Questions' ) )
          ->add_fields( array(
              Field::make( 'text', 'jawda_faq_q', __( 'Question' ) ),
              Field::make( 'textarea', 'jawda_faq_a', __( 'Answer' ) ),
          )
        )


      ) );

  }

}









add_action( 'carbon_fields_register_fields', 'jawda_terms_meta' );
function jawda_terms_meta() {

  // Options
  $basic_options_container =
  Container::make( 'term_meta', __( 'Photo' ) )
    ->where( 'term_taxonomy', 'IN', ['projects_type','projects_category','projects_tag','projects_developer','projects_area','property_label','property_type','property_feature','property_city','property_area','property_state','property_country','property_status'] )
    ->add_fields( array(
        Field::make( 'image', 'jawda_thumb', __( 'Cover photo' ) ),
    )
  );



}



add_action( 'carbon_fields_register_fields', 'jawda_city_terms_meta' );
function jawda_city_terms_meta() {

  // Options
  $basic_options_container =
  Container::make( 'term_meta', __( 'State' ) )
    ->where( 'term_taxonomy', 'IN', ['property_city'] )
    ->add_fields( array(
      Field::make( 'select', 'jawda_city_state', __( 'Choose State' ) )->set_options( 'get_my_states_list' ),
    )
  );



}




/* ----------------------------------------------------------------------------
# initiative
---------------------------------------------------------------------------- */

add_action( 'carbon_fields_register_fields', 'jawda_meta_page_catalog' );
function jawda_meta_page_catalog() {

  // Options
  $meta_package =
  Container::make( 'post_meta', 'Initiative Details' )
    ->where( 'post_type', '=', 'catalogs' )
    ->add_fields( array(

      // Cataloug Type
      Field::make( 'separator', 'jawda_separator_1', __( 'Cataloug Type' ) ),
      Field::make( 'select', 'jawda_catalog_type', __( 'Cataloug Type' ) )->set_options( array('1' => 'مشروعات','2' => 'وحدات') ),

      // IF Project
      Field::make( 'separator', 'jawda_separator_2', __( 'If Project' ) ),
      Field::make( 'select', 'jawda_project_city', __( 'Project city' ) )->set_options( 'get_my_projects_cities_list' ),
      Field::make( 'select', 'jawda_project_type', __( 'Project Type' ) )->set_options( 'get_my_projects_types_list' ),
      Field::make( 'text', 'jawda_project_price_from', __( 'Price From' ) ),
      Field::make( 'text', 'jawda_project_price_to', __( 'Price to' ) ),


      // IF Property
      Field::make( 'separator', 'jawda_separator_3', __( 'If Property' ) ),
      //Field::make( 'select', 'jawda_property_state', __( 'Property state' ) )->set_options( 'get_my_properties_state_list' ),
      Field::make( 'select', 'jawda_property_city', __( 'Property city' ) )->set_options( 'get_my_properties_cities_list' ),
      Field::make( 'select', 'jawda_property_type', __( 'Property Type' ) )->set_options( 'get_my_properties_types_list' ),
      //Field::make( 'text', 'jawda_property_price_from', __( 'Price From' ) ),
      //Field::make( 'text', 'jawda_property_price_to', __( 'Price to' ) ),
      Field::make( 'multiselect', 'jawda_property_main_project', __( 'Main Project' ) )->add_options( 'get_my_projects_list' ),

    ));

}



/* ------  ----------- */

function get_my_projects_cities_list(){
  $return = [];
  $terms = get_terms( 'projects_area', array('hide_empty' => false,) );
  $return[] = '';
  foreach ($terms as $term) {
    $return[$term->term_id] = $term->name;
  }
  return $return;
}

function get_my_projects_types_list(){
  $return = [];
  $terms = get_terms( 'projects_type', array('hide_empty' => false,) );
  $return[] = '';
  foreach ($terms as $term) {
    $return[$term->term_id] = $term->name;
  }
  return $return;
}

function get_my_properties_cities_list(){
  $return = [];
  $terms = get_terms( 'property_city', array('hide_empty' => false,) );
  $return[] = '';
  foreach ($terms as $term) {
    $return[$term->term_id] = $term->name;
  }
  return $return;
}

function get_my_properties_state_list(){
  $return = [];
  $terms = get_terms( 'property_state', array('hide_empty' => false,) );
  $return[] = '';
  foreach ($terms as $term) {
    $return[$term->term_id] = $term->name;
  }
  return $return;
}

function get_my_properties_types_list(){
  $return = [];
  $terms = get_terms( 'property_type', array('hide_empty' => false,) );
  $return[] = '';
  foreach ($terms as $term) {
    $return[$term->term_id] = $term->name;
  }
  return $return;
}
// carbon_get_post_meta( get_the_ID(), 'jawda_location' );
