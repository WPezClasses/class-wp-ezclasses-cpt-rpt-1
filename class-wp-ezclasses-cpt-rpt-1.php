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
 
	protected $_str_action;
	protected $_int_priority;
	protected $_arr_args_preset_key;
	
    protected $_str_post_type;
	protected $_arr_args_all;
	
	protected function __construct() {
	  parent::__construct();
	}
	
	/**
	 *
	 */
	 public function ez__construct() {
	 
	   $this->_str_action = 'init';
	   $this->_int_priority = 50;
	   $this->_arr_args_preset_key = '';
	 
	   $arr_cpt_todo = $this->cpt_todo();
	   
	   // name
	   $this->_str_post_type = $arr_cpt_todo['post_type'];
					
	   // labels
	   $arr_labels = WPezHelpers::ez_array_merge( array($this->labels_defaults(), $arr_cpt_todo['arguments']['labels']) );
	   
	   // supports - merge
	   $arr_supports_assoc = WPezHelpers::ez_array_merge( array($this->supports_defaults(), $arr_cpt_todo['arguments']['supports']) );
	   
	   // use the array_keys_for_value_true() to get a simple array
	   $arr_supports = WPezHelpers::ez_array_keys_for_value_true($arr_supports_assoc);
	   
	   // arguments - merge
	   $arr_args_preset = $this->arguments_presets($this->_arr_args_preset_key);
	   $arr_arguments = WPezHelpers::ez_array_merge( array($this->arguments_defaults(), $arr_args_preset, $arr_cpt_todo['arguments']) );
	   
	   // arguments - add the labels 
	   $arr_arguments['labels'] = $arr_labels;
	   $arr_arguments['supports'] = $arr_supports;
	   
	   $this->_arr_args_all = $arr_arguments;
	    
	   add_action( $this->_str_action, array($this, 'register_post_type_do'), $this->_int_priority);
	   
	}
		
	/**
	 * This is where your magic happens. The idea here is to (re) define as little as possible. 
	 *
	 * It is recommended you use this  boilerplate: https://github.com/WPezClasses/class-wp-ezclasses-cpt-rpt-1-boilerplate-1
	 *
	 * This method remains simply as an example. 
	 *
	 * Ref: http://codex.wordpress.org/Function_Reference/register_post_type
	 */			
    public function cpt_todo(){
	  
	  // $this->_str_action = 'init';
	  // $this->_int_priority = 50;
	  
	  /**
	  $str_args_labels_name_plural = 'TODO';		// just saves a couple key strokes. 
	  
	  $arr_labels = array(
	    'name'					=> 'TODO',			// typically plural
		'singular_name' 		=> 'TODO',
		'search_items' 			=> 'Search ' . $str_args_labels_name_plural,
		'not_found' 			=> 'No ' . $str_args_labels_name_plural . ' found',
		'not_found_in_trash'	=> 'No ' . $str_args_labels_name_plural . ' found in trash',
		'menu_name'				=> 'TODO'
		);
		
		
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
		
	  $arr_capabilities = array();
	  
	  
	  $this->_arr_args_preset_key = 'TODO'; 	// the key of the preset (that can override the arguments_defaults but before your custom settings)
		
	  $arr_cpt_todo = array(
	  
	    'post_type'		=> 'TODO',
	
		'arguments'		=> array(
		
		  'description'		=> 'TODO',
		  'rewrite' 		=> array(
		    'slug' => 'TODO',
			),
		  'menu_position' 	=> 30,	// TODO
		  'menu_icon' 		=> NULL,
		  
		  'labels'			=> $arr_labels,
		  'supports'		=> $arr_supports,
		  'capabilities'	=> $arr_capabilities, 
		  			
		  ),
	    );
	  
	  return $arr_cpt_todo;
	  */		
    }
	

	/**
	 * Often a CTP usage will need a common set of arguments. That collection is this set of presets.
	 *
	 * Note: Typically only the changes to defaults are used but there are times defaults are repeated for clarity.
	 *
	 * For example: preset_1 > 'exclude_from_search' => true. The default for 'exclude_from_search' is true. This
	 * is a friendly - and obvious - reminder.
	 */
	protected function arguments_presets( $str_key = ''){
	
	  $arr_args_presets = array(
	  
	    // for example: slider, testimonials, etc. things you want to admin and see public side, but not be searched.
	    'cpt_1'		=> array(
		  'public' 					=> true,			// default: false
		  'exclude_from_search'		=> true,			// default: opposite of public (if this is not set)
		  'publicly_queryable'		=> false,			// default: value of public
		  
		  'show_ui'					=> true, 			// default: value of public
		  'show_in_nav_menus'		=> true, 			// dafault: value of public
		  'show_in_menu'			=> true,			// default: value of show_ui
		  ),
		  
		// for example, some sort of CPT that's like a page / post
	    'cpt_2'		=> array(
		  'public' 					=> true,			// default: false
		  'exclude_from_search'		=> false,			// default: opposite of public (if this is not set)
		  'publicly_queryable'		=> true,			// default: value of public
		  
		  'show_ui'					=> true, 			// default: value of public
		  'show_in_nav_menus'		=> true, 			// dafault: value of public
		  'show_in_menu'			=> true,			// default: value of show_ui
		  ),
	  );
	  
	  if ( isset($arr_args_presets[$str_key]) ){
	    return $arr_args_presets[$str_key];
	  } 
	  
	  return array();
	}

	
	/**
	 * http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	protected function arguments_defaults( ){
	
	  $str_desc = 'If you only got a Title and you\'re reading this description then something probably went wrong with your setup.';
	  
	  $arr_arguments = array(
														// ** = Listed for completeness
														// ---------------------------------------------
	    // 'labels'			=> array(),				// NOTE: The class sorts this out **
		
		'description'			=> $str_desc,			// normal default is blank
		'public' 				=> false,				// default: false
		'exclude_from_search'	=> true,				// default: opposite of public (if this is not set)
		'publicly_queryable'	=> false,				// default: value of public
		'show_ui'				=> false, 				// default: value of public
		'show_in_nav_menus'		=> false, 				// dafault: value of public
		'show_in_menu'			=> false,				// default: value of show_ui
		'show_in_admin_bar' 	=> false,				// default: value of the show_in_menu argument
		'menu_position' 		=> 25,					// default: to below Comments
		'menu_icon' 			=> NULL,				// TODO: The url to the icon to be used for this menu.
		'capability_type'		=> 'post',				// default: post
		
		// 'capabilities'								// NOTE: There's a dedicated method for the capabilities array (see below).
		
		'map_meta_cap'			=> NULL,				// default: false
		'hierarchical' 			=> false,				// default: false
		'supports' 				=> array('comments'),		// default: title and editor PLEASE NOTE: comments is used here as an "error" flag of sorts. That is, if you only get comments something went wrong.
								
		// 'register_meta_box_cb'						// meta box setup is a different step in the WPezClasses architecture **
		// 'taxonomies'									// 'taxonomies' is a different step in the WPezClasses architecture **
							
		'has_archive' 			=> false,				// default: false
		'permalink_epmask'		=> EP_PERMALINK,	
								
		// 'rewrite' 									// NOTE: The class sorts this out **
		
		'query_var' 			=> true,				// default: true - set to $post_type
		'can_export' 			=> true,				// default: true
		
		// '_builtin'									// per the WP Codex, not for general use **
		// '_edit_link'									// per the WP Codex, not for general use **
		);
		
	  return $arr_arguments;
	}
	
	/**
	 * Let's do this! Everything else is to simplify the lead up to this moment of CPT truth. 
	 */
	public function register_post_type_do(){
	
	  register_post_type( $this->_str_post_type, $this->_arr_args_all );
	
	}
	
	/**
	 * The TODOs are taken care of by the class that extends this base class. They are noted here strictly for clarity. 
	 */
	public function labels_defaults () {
	
	  $arr_labels = array(
	    
		'name' => 'TODO - label: name',
		'singular_name' => 'TODO - label: singular_name',
		'add_new' => 'Add New',
		'all_items' => 'Show All',
		'add_new_item' => 'Add New',
		'edit_item' => 'Edit',
		'new_item' => 'Add New',
		'view_item' => 'View',
		'search_items' => 'Search ' . 'TODO - label: search_items',
		'not_found' =>  'No ' . 'TODO - label: not_found' . ' found',
		'not_found_in_trash' => 'No ' . 'TODO - label: not_found_in_trash' . ' found in trash',
		'parent_item_colon' => 'Parent Post:',
		'menu_name' => 'TODO - label: menu_name',
		);
		
	  return $arr_labels;
	}
	
	/**
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
	

  }
}