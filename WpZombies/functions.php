<?php
/**
 * Our Main Functions File
 *
 * @package      WPZ_Child_theme
 * @since        1.0.0
 * @author       Juan Rangel <juan@wpzombies.com>
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */

/**
 * Theme Setup
 * @since 1.0.0
 *
 * This setup function attaches all of the site-wide functions 
 * to the correct hooks and filters. All the functions themselves
 * are defined below this setup function.
 *
 */

add_action('genesis_setup','child_theme_setup', 15);
function child_theme_setup() {

	/**
	  * Here is were most of the non theme specific features are. Have a look here if you cannot find it in this file.
	  * @since      1.0
	  * @author     Juan Rangel <http://wpzombies.com>
	  */ 
	require_once( CHILD_DIR . '/lib/functions/clean-zombies.php' );
	
	define( 'CHILD_THEME_VERSION', filemtime( get_stylesheet_directory() . '/style.css' ) );

	/*	Theme Support
	----------------------------------------------- */	
	add_theme_support( 'html5' );
	add_theme_support( 'genesis-responsive-viewport' );
	add_theme_support( 'genesis-menus', array( 'primary' => 'Primary Navigation Menu' ) );
	add_theme_support( 'genesis-structural-wraps', array( 'header', 'menu-secondary', 'site-inner', 'footer-widgets', 'footer' ) );

	
	/*	Sidebars
	----------------------------------------------- */	
	unregister_sidebar( 'sidebar-alt' );
	add_theme_support( 'genesis-footer-widgets', 3 );
	genesis_register_sidebar( 
		array( 
			'name' => 'Slider Area', 
			'id' => 'slider-area', 
			'description' => 'This is the area for your slider!' ,
			)
	);	

	genesis_register_sidebar( 
		array( 
			'name' => 'Slider Sidebar', 
			'id' => 'slider-sidebar', 
			'description' => 'This is the area for your slider!' ,
			)
	);	
	/*	Call our theme settings
	----------------------------------------------- */
	include_once( CHILD_DIR . '/lib/functions/child-theme-settings.php' );


	/*	Define our custom image sizes
	----------------------------------------------- */
	// add_image_size( 'wpz_featured', 400, 100, true );


	/*	Place all actions and fillters here with the coresponding functions just below
	 ----------------------------------------------- */
}

/*	Action and Filter Functions
----------------------------------------------- */
