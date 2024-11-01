<?php
/*
Plugin Name:    Sky Remove Attached Files And Featured Images Automatically
Plugin URI:     http://skygame.mobi
Description:    Automatically eliminate attached media from posts, metabox and featured images uploaded via Media button.
Version:        1.0.0
Author:         KENT
Author URI:     http://skygame.mobi
*/ 

/**
 * Create class Sky_Remove_Attached_Media
 */

if ( !class_exists( 'Sky_Remove_Attached_Media' ) ) :

	class Sky_Remove_Attached_Media{

		/**
		 * Auto load
		 */
		public function __construct() {

			/**
			 * Load action/filter
			 */
			add_action( 'before_delete_post', array( &$this, 'sky_delete_attachment' ) );
			add_action( 'before_delete_post', array( &$this, 'sky_delete_featured_images' ) );

		}

		/**
		 * [sky_delete_attachment description]
		 * @return [type] [description]
		 */
		public function sky_delete_attachment( $post_ID ) {

			global $wpdb;

			$attachments = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE `post_parent` = {$post_ID} AND `post_type` = 'attachment'" );

	        foreach ( $attachments as $attachment ) :

	       		wp_delete_attachment( $attachment->ID, true );
	        
	        endforeach;

		}

		public function sky_delete_featured_images( $post_ID ) {

			global $wpdb;

			$images = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE `meta_key` = '_thumbnail_id' AND `post_id` = {$post_ID}" );
	        
	        foreach ( $images as $image ) :
	        
	        	wp_delete_attachment( $image->meta_value, true );
	        
	        endforeach;

	        $wpdb->query( "DELETE FROM $wpdb->postmeta WHERE `post_id` = {$post_ID}" );

		}

	}

	// ==== Call class Sky_Remove_Attached_Media
	new Sky_Remove_Attached_Media();

endif;

/**
 * End class Sky_Remove_Attached_Media
 */