<?php
/**
 * Child Theme Settings
 *
 * This file registers all of this child theme's specific Theme Settings, accessible from
 * Genesis > Child Theme Settings.
 *
 * @package     WPZ_Genesis_Child
 * @since       1.0.0
 * @author      Juan Rangel <juan@wpzombies.com>
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 */ 
 
/**
 * Registers a new admin page, providing content and corresponding menu item
 * for the Child Theme Settings page.
 *
 * @since 1.0.0
 *
 * @package WPZ_Genesis_Child
 * @subpackage Child_Theme_Settings
 */
class Child_Theme_Settings extends Genesis_Admin_Boxes {
	
	/**
	 * Create an admin menu item and settings page.
	 * @since 1.0.0
	 */
	function __construct() {
		
		// Specify a unique page ID. 
		$page_id = 'child';
		
		// Set it as a child to genesis, and define the menu and page titles
		$menu_ops = array(
			'submenu' => array(
				'parent_slug' => 'genesis',
				'page_title'  => 'Vegas Vapory Settings',
				'menu_title'  => 'Vegas Vapory Settings',
			)
		);
		
		// Set up page options. These are optional, so only uncomment if you want to change the defaults
		$page_ops = array(
		//	'screen_icon'       => 'options-general',
		//	'save_button_text'  => 'Save Settings',
		//	'reset_button_text' => 'Reset Settings',
		//	'save_notice_text'  => 'Settings saved.',
		//	'reset_notice_text' => 'Settings reset.',
		);		
		
		// Give it a unique settings field. 
		// You'll access them from genesis_get_option( 'option_name', 'child-settings' );
		$settings_field = 'child-settings';
		
		// Set the default values
		$default_settings = array(
			'footer-left'   => 'Copyright &copy; ' . date( 'Y' ) . ' All Rights Reserved',
			'footer-right'  => 'Site by <a href="http://www.wpzombies.com">Juan Rangel</a>',
			'featured-1'    => '<h1>Free</h1><h2>shipping</h2><p>on orders over $99</p>',
			'featured-2'    => '<h1>Special</h1><h2>Gift Cards</h2><p>Give the perfect gift.</p>',
			'featured-3'    => '<h1>Order</h1><h2>Online</h2><p>Hours: 8am - 11pm </p>'
		);
		
		// Create the Admin Page
		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );

		// Initialize the Sanitization Filter
		add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitization_filters' ) );
			
	}

	/** 
	 * Set up Sanitization Filters
	 * @since 1.0.0
	 *
	 * See /lib/classes/sanitization.php for all available filters.
	 */	
	function sanitization_filters() {
		
		genesis_add_option_filter( 'safe_html', $this->settings_field,
			array(
				'footer-left',
				'footer-right',
				'logo',
				'featured-1',
				'featured-2',
				'featured-3',
			) );
	}
	
	/**
	 * Set up Help Tab
	 * @since 1.0.0
	 *
	 * Genesis automatically looks for a help() function, and if provided uses it for the help tabs
	 * @link http://wpdevel.wordpress.com/2011/12/06/help-and-screen-api-changes-in-3-3/
	 */
	 function help() {
	 	$screen = get_current_screen();

		$screen->add_help_tab( array(
			'id'      => 'sample-help', 
			'title'   => 'Sample Help',
			'content' => '<p>Help content goes here.</p>',
		) );
	 }
	
	/**
	 * Register metaboxes on Child Theme Settings page
	 * @since 1.0.0
	 */
	function metaboxes() {
		
		add_meta_box('featured_metabox', 'Featured', array( $this, 'featured_metabox' ), $this->pagehook, 'main', 'high');
		add_meta_box('footer_metabox', 'Footer', array( $this, 'footer_metabox' ), $this->pagehook, 'main', 'high');
		
	}

	/**
	 * Logo Metabox
	 * @since 2.0.0
	 */

	function featured_metabox() {
		echo '<p><strong>Featured Box 1</strong></p>';
		wp_editor( $this->get_field_value( 'featured-1' ), $this->get_field_id( 'featured-1' ), array( 'textarea_rows' => 5 ) );	

		echo '<p><strong>Featured Box 2</strong></p>';
		wp_editor( $this->get_field_value( 'featured-2' ), $this->get_field_id( 'featured-2' ), array( 'textarea_rows' => 5 ) );	

		echo '<p><strong>Featured Box 3</strong></p>';
		wp_editor( $this->get_field_value( 'featured-3' ), $this->get_field_id( 'featured-3' ), array( 'textarea_rows' => 5 ) );	

	}

	/**
	 * Footer Metabox
	 * @since 1.0.0
	 */
	function footer_metabox() {
		
	echo '<p><strong>Footer Left:</strong></p>';
	wp_editor( $this->get_field_value( 'footer-left' ), $this->get_field_id( 'footer-left' ), array( 'textarea_rows' => 5 ) );	

	echo '<p><strong>Footer Right:</strong></p>';
	wp_editor( $this->get_field_value( 'footer-right' ), $this->get_field_id( 'footer-right' ), array( 'textarea_rows' => 5 ) ); 
	}
	
	
}

/**
 * Add the Theme Settings Page
 * @since 1.0.0
 */
function be_add_child_theme_settings() {
	global $_child_theme_settings;
	$_child_theme_settings = new Child_Theme_Settings;	 	
}
add_action( 'genesis_admin_menu', 'be_add_child_theme_settings' );

/************************************************************************************************************************
	All display functions will go below here!!!!! 
************************************************************************************************************************/
	/*	Front END
	----------------------------------------------- */

//Custom Logo

// add_action( 'genesis_header', 'wpz_custom_logo');

// Footer
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'wpz_footer' );


// Footer 
function wpz_footer() {
	echo '<div class="one-half first" id="footer-left">' . wpautop( genesis_get_option( 'footer-left', 'child-settings' ) ) . '</div>';
	echo '<div class="one-half" id="footer-right">' . wpautop( genesis_get_option( 'footer-right', 'child-settings' ) ) . '</div>';
}