<?php
/** 
 * The bottom layer (read: defaults + code) of a stack of classes for RPT'ing(register_post_type()) a new WordPress custom post type
 *
 * Somewhat inspired by (@link https://gist.github.com/justintadlock/6552000), as well as O3's CPT generator (@link http://www.weareo3.com/wordpress-custom-post-type-generator)
 *
 * PHP version 5.3
 *
 * LICENSE: MIT
 *
 * @package WP ezClasses
 * @author Mark Simchock <mark.simchock@alchemyunited.com>
 * @since 0.5.0
 * @license MIT
 */
 
/*
 * == Change Log == 
 *
 */

// Quick check for WP 
if ( ! defined('ABSPATH') ) {
	header('HTTP/1.0 403 Forbidden');
    die();
}

if ( ! class_exists('Class_WP_ezClasses_CPT_RPT_1' ) ){
	class Class_WP_ezClasses_CPT_RPT_1 extends Class_WP_ezClasses_Master_Singleton {
	
		protected $_rpt_name;
		protected $_args_all;
		
		
		protected function __construct() {	
			parent::__construct();
		}
		
		/*
		 * Takes the various arrays of defaults and custom args, merges them, addresses capabilities, rolls it all together and then calls the method custom_post_type_register() 
		 * which contains the WP register_post_type() function.
		 */
		public function ez__construct( $arr_args = array() ) {
		
			if ( WPezHelpers::ez_array_pass($arr_args) ){

				if ( isset($arr_args['rpt_name']) && WPezHelpers::ez_array_key_pass($arr_args, 'labels') && WPezHelpers::ez_array_key_pass($arr_args, 'supports') && WPezHelpers::ez_array_key_pass($arr_args, 'arguments') ){
							
					// name
					$this->_rpt_name = $arr_args['rpt_name'];
					
					// labels
					$arr_labels = WPezHelpers::ez_array_merge( array($this->labels_defaults(), $arr_args['labels']) );
					
					// supports - merge
					$arr_supports_assoc = WPezHelpers::ez_array_merge( array($this->supports_defaults(), $arr_args['supports']) );
					
					// use the array_keys_for_value_true() to get a simple array
					$arr_supports = WPezHelpers::ez_array_keys_for_value_true($arr_supports_assoc);
					
					// arguments - merge
					$arr_arguments = WPezHelpers::ez_array_merge( array($this->arguments_defaults(), $arr_args['arguments']) );					
					
					// arguments - add the labels 
					$arr_arguments['labels'] = $arr_labels;
					$arr_arguments['supports'] = $arr_supports;
					
					// do we have an custom capabilities?
					$arr_capabilities = $this->capabilities_settings();
					if ( WPezHelpers::ez_array_pass( $arr_capabilities ) ){
					
					  unnset($arr_arguments['capability_type']);   // TODO - is this unset necessary?
				      $arr_arguments['capabilities'] = $arr_capabilities;
					}
					
					if ( isset($arr_args['arguments']['rewrite']) && is_array($arr_args['arguments']['rewrite']) ){
						$arr_arguments['rewrite'] = $arr_args['arguments']['rewrite'];
					}
					
					$this->_args_all = $arr_arguments;
					
					
					$this->custom_post_type_register();
					

					return true; // TODO - ezC standard return msg format
				}	
				return false; // TODO - ezC standard return msg format
			}
			return false; // TODO - ezC standard return msg format
		}
		
		/*
		 * This is where the magic happens. Everything else is to simplify the lead up to this moment. If you see the end, then the means is easier to understand. 
		 */
		public function custom_post_type_register(){
			
			register_post_type( $this->_rpt_name, $this->_args_all );
		}
		
		
		/*
		 * The TODOs are taken care of by the class that extends this base class. They are noted here strictly for clarity. 
		 */
		public function labels_defaults () {
		
			$arr_labels = array(
								'name' => 'arg: name - TODO',
								'singular_name' => 'arg: singular_name - TODO',
								'add_new' => 'Add New',
								'all_items' => 'Show All',
								'add_new_item' => 'Add New',
								'edit_item' => 'Edit',
								'new_item' => 'Add New',
								'view_item' => 'View',
								'search_items' => 'Search ' . 'TODO',
								'not_found' =>  'No ' . 'TODO' . ' found',
								'not_found_in_trash' => 'No ' . 'TODO' . ' found in trash',
								'parent_item_colon' => 'Parent Post:',
								'menu_name' => 'arg: menu_name - TODO',
							);
							
			return $arr_labels;
		}
		
		
		/*
		 *
		 */
		public function supports_defaults(){
		
			$arr_supports = array(
								'title'				=> true,
								'editor'			=> true,
								'author'			=> true,
								'thumbnail' 		=> true,			// featured image, current theme must also support post-thumbnails
								'excerpt'			=> true,		
								'trackbacks'		=> false,
								'custom-fields'		=> false,			
								'comments'			=> false,			// also will see comment count balloon on edit screen
								'revisions'			=> false,			// will store revisions
								'page-attributes'	=> false, 			// menu order, hierarchical must be true to show Parent option
								'post-formats' 		=> false,			// add post formats
							);
		
			return $arr_supports;
		}
		
		
		/*
		 * http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		public function arguments_defaults( ){
		
			$str_desc = 'If you only got a Title and you\'re reading this description then something probably went wrong with your setup.';

			$arr_arguments = array(
																							// ** = Listed for completeness
																							// ---------------------------------------------
							//	'labels'				=									// NOTE: The class sorts this out **
							
								'description'			=> $str_desc,						// normal default is blank
								'public' 				=> false,							// default: false
								'exclude_from_search'	=> true,							// default: opposite of public (if this is not set)
								'publicly_queryable'	=> false,							// default: value of public
								'show_ui'				=> false, 							// default: value of public
								'show_in_nav_menus'		=> false, 							// dafault: value of public
								'show_in_menu'			=> false,							// default: value of show_ui
								'show_in_admin_bar' 	=> false,							// default: value of the show_in_menu argument
								'menu_position' 		=> 25,								// default: to below Comments
								'menu_icon' 			=> NULL,							// TODO: The url to the icon to be used for this menu.
								'capability_type'		=> 'post',							// default: post
								
							//	'capabilities'												// NOTE: There's a dedicated method for the capabilities array (see below). **
							
								'map_meta_cap'			=> false,							// default: false
								'hierarchical' 			=> false,							// default: false
								'supports' 				=> array('title'),					// default: title and editor PLEASE NOTE: just title is used here as an "error" flag of sorts. That is, if you get just a title something went wrong.
								
							//	'register_meta_box_cb'										// meta box setup is a different step in the WPezClasses architecture **
							// 	'taxonomies'												// 'taxonomies' is a different step in the WPezClasses architecture **
							
								'has_archive' 			=> false,							// default: false
								'permalink_epmask'		=> EP_PERMALINK,	
								
							//	'rewrite' 													// NOTE: The class sorts this out **
							
								'query_var' 			=> true,							// default: true - set to $post_type
								'can_export' 			=> true,							// default: true
								
							//	'_builtin'													// per the WP Codex, not for general use **
							//	'_edit_link'												// per the WP Codex, not for general use **
							);
							
			return $arr_arguments;
		}
		
		/*
		 *
		 */
		public function capabilities_settings($arr_args = array()){
		
			$arr_capabilities = $arr_args;
			
			return $arr_capabilities;
		}
		

	
	}
}