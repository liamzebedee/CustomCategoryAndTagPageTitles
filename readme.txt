=== Custom Category and Tag Page Titles ===
Contributors: liamzebedee
Donate link: http://cryptum.net/
Tags: plugin, custom, page, pages, tag, tags, category, categories, taxonomy
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds fields to set custom titles for category and tag archive pages

== Description ==

This plugins adds a field to all tag and category editing forms - 'Title' - which will allow a user to set a custom title to show for a category/tag, instead of the slug. 

== Installation ==

1. Upload plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I set a custom title? =

Login to WP-Admin, go to the **Posts** menu, and click either the **Categories** or **Tags** submenu. Then open a specific category/tag, where you will be shown a form with the title 'Edit Tag'. There is a field called 'Title', which is where you must put your preferred custom title. 

= Where does this title show? =
This title will show wherever Wordpress calls the **single_cat_title** and **single_tag_title** filters. This means it will show both in your <title> tag and your page title ('Category Archives: Foobar').

= How can I show both the 'default' archive title as well as custom titles, but without the prefix 'Category Archives: '? =
So you want to show the custom title WITHOUT the prefix ('Category Archives: ') on pages where it is set, but when it's not set, just show it normally (WITH the prefix). 

`<?php
if ( is_category() ) {
	$single_cat_title = single_cat_title( '', false );
	$single_cat_title_c = get_the_category();
	$single_cat_title_default = $single_cat_title_c[0]->cat_name;
	
	?><span><?php
	if( $single_cat_title_default != $single_cat_title ) { // Detecting customization via plugin (since there is no hook for modifying the prefix)
		echo $single_cat_title;
	} else {
		echo 'Category Archives: ' . $single_cat_title_default;
	}
	?></span><?php
}
?>`

== Changelog ==

= 1.0 =
* Initial release