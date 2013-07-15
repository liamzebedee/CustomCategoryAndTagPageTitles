<?php
/*
Plugin Name: Custom Category and Tag Page Titles
Plugin URI: http://github.com/liamzebedee/wp-custom-cat-tag-page-titles
Description: This plugin adds fields to set custom titles for category and tag pages
Version: 1.0
Author: Liam (liamzebedee) Edwards-Playne
Author URI: http://cryptum.net
License: GPLv2
*/

/*  Copyright 2012  Liam (liamzebedee) Edwards-Playne  (liamzebedee@yahoo.com.au)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class Custom_Cat_Tag_Titles {
	const option_prefix = 'cctt_';
	const option_prefix_cat = 'cctt_cat_';
	const option_prefix_tag = 'cctt_tag_';
	
	function __construct() {
		add_action('init', array( $this, 'run' ));
	}
	
	function run() {
		// Category
		add_action('edit_category_form', array( $this, 'edit_category_form_custom' ), 1, 1);
		add_action('edit_category', array( $this, 'edit_category_custom' ), 1, 1);
		add_action('create_category', array( $this, 'create_category_custom' ), 1, 1);
		
		add_filter('single_cat_title', array( $this, 'get_title' ), 1, 1);
		
		
		// Tags
		add_action('edit_tag_form', array( $this, 'edit_tag_form_custom' ), 1, 1);
		add_action('edit_post_tag', array( $this, 'edit_tag_custom' ), 1, 1);
		add_action('create_post_tag', array( $this, 'create_tag_custom' ), 1, 1);
		
		add_filter('single_tag_title', array( $this, 'get_title' ), 1, 1);
	}
	
	function get_title($title) {
		if ( is_category() ) {
			return $this->get_single_cat_title($title);
		} else if( is_tag() ) {
			return $this->get_single_tag_title($title);
		}
	}
	
	/* =Category specific functions
	----------------------------------------------- */
	// Shows the form for adding a custom title to a category in Wordpress Admin
	function edit_category_form_custom( $category ) {
		// TODO only show when a category is selected
		if(!empty( $category )) {
			if( $category->parent == 0 ) { return; }
			
			if (!isset( $category->cat_ID )) {
				$category->cat_ID = $category->term_id;
			}
			$cat_title = get_option('cctt_cat_' . $category->cat_ID);
			
			include( dirname(__FILE__) . '/views/edit-category.php' );
		}
	}
	
	// Runs when a category is updated/edited
	// Processes the custom title field
	function edit_category_custom($cat_ID) {
		// Get option
		$option = self::option_prefix_cat . $cat_ID;
		$cat_title_current = get_option( $option );
		$title = stripslashes($_POST['title']);
		
		if ($cat_title_current != $title) {
			update_option( $option, $title );
			
		} else if(empty ($title) ) {
			delete_option($option);
		}
		
		return $cat_ID;
	}
	
	// Runs when a new category is created.
	// Creates the option
	function create_category_custom($cat_ID) {
		$option = self::option_prefix_cat . $cat_ID;
		$title = stripslashes($_POST['title']);
		if (!empty( $title )) {	
			add_option($option, $title);
		}
	}
	
	function get_single_cat_title($cat_title) {
		$cat_title_custom = get_option( self::option_prefix_cat . intval(get_query_var('cat')) );
		if (empty( $cat_title_custom )) {
			return $cat_title;
		}
		return $cat_title_custom;
	}
	
	/* =Tag specific functions
	----------------------------------------------- */
	// Shows the form for adding a custom title to a tag in Wordpress Admin
	function edit_tag_form_custom( $tag ) {
		if(!empty( $tag )) {
			if (!isset( $tag->tag_ID )) {
				$tag->tag_ID = $tag->term_id;
			}
			$tag_title = get_option(self::option_prefix_tag . $tag->tag_ID);
			
			include( dirname(__FILE__) . '/views/edit-tag.php' );
		}
	}
	
	// Runs when a tag is updated/edited
	// Processes the custom title field
	function edit_tag_custom($tag_ID) {
		// Get option
		$option = self::option_prefix_tag . $tag_ID;
		$tag_title_current = get_option( $option );
		$title = stripslashes($_POST['title']);
		
		if ($tag_title_current != $title) {
			update_option( $option, $title );
			
		} else if( empty( $title )) {
			delete_option($option);
		}
	}
	
	// Runs when a new tag is created.
	// Creates the option
	function create_tag_custom($tag_ID) {
		$option = self::option_prefix_tag . $tag_ID;
		$title = stripslashes($_POST['title']);
		if (!empty( $title )) {	
			add_option($option, $title);
		}
	}
	
	function get_single_tag_title($tag_title) {
		$tag_title_custom = get_option( self::option_prefix_tag . intval(get_query_var('tag_id')) );
		if (empty( $tag_title_custom )) {
			return $tag_title;
		}
		return $tag_title_custom;
	}
	
}

new Custom_Cat_Tag_Titles();
?>