<?php 
/**
 * Lets keep the main functions.php file as clean as possible
 */

// Remove Unused Page Layouts
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );

// Remove Unused User Settings
remove_action( 'show_user_profile', 'genesis_user_options_fields' );
remove_action( 'edit_user_profile', 'genesis_user_options_fields' );
remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );
remove_action( 'show_user_profile', 'genesis_user_seo_fields' );
remove_action( 'edit_user_profile', 'genesis_user_seo_fields' );
remove_action( 'show_user_profile', 'genesis_user_layout_fields' );
remove_action( 'edit_user_profile', 'genesis_user_layout_fields' );

// Reposition Genesis Metaboxes
remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );
add_action( 'admin_menu', 'wpz_add_inpost_seo_box' );
remove_action( 'admin_menu', 'genesis_add_inpost_layout_box' );
add_action( 'admin_menu', 'wpz_add_inpost_layout_box' );

// Remove Genesis Widgets
add_action( 'widgets_init', 'wpz_remove_genesis_widgets', 20 );

// Remove Genesis Theme Settings Metaboxes
add_action( 'genesis_theme_settings_metaboxes', 'wpz_remove_genesis_metaboxes' );

// Don't update theme
add_filter( 'http_request_args', 'wpz_dont_update_theme', 5, 2 );

// Editor Styles
add_editor_style( 'editor-style.css' );

// Add scripts to WordPress
// Backend Scripts
add_action('admin_enqueue_scripts', 'wpz_admin_scripts' );
//Front End scripts
add_action( 'wp_enqueue_scripts', 'wpz_scripts', 20 );

/*	Functions
----------------------------------------------- */

function wpz_admin_scripts() {
	wp_enqueue_script( 'custom-admin', get_stylesheet_directory_uri() . '/js/admin-main.js', array('jquery') );

	wp_enqueue_style( 'custom-admin', get_stylesheet_directory_uri() . '/css/admin-main.css' );
}

function wpz_scripts() {
	wp_enqueue_style( 'custom-woo', get_stylesheet_directory_uri() . '/css/woo.css' );
	wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . '/fonts/font-awesome/css/font-awesome.min.css' );
}

/** 
 * Remove Genesis widgets
 *
 * @since 1.0.0
 */
function wpz_remove_genesis_widgets() {
    unregister_widget( 'Genesis_eNews_Updates'          );
    unregister_widget( 'Genesis_Featured_Page'          );
    unregister_widget( 'Genesis_Featured_Post'          );
    unregister_widget( 'Genesis_Latest_Tweets_Widget'   );
    unregister_widget( 'Genesis_User_Profile_Widget'    );
}

/**
 * Remove Genesis Theme Settings Metaboxes
 *
 * @since 1.0.0
 * @param string $_genesis_theme_settings_pagehook
 */
function wpz_remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {
	//remove_meta_box( 'genesis-theme-settings-feeds',      $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-header',     $_genesis_theme_settings_pagehook, 'main' );
	// remove_meta_box( 'genesis-theme-settings-nav',        $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-breadcrumb', $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-comments',   $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-posts',      $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-blogpage',   $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-scripts',    $_genesis_theme_settings_pagehook, 'main' );
}

/**
 * Don't Update Theme
 * @since 1.0.0
 *
 * If there is a theme in the repo with the same name, 
 * this prevents WP from prompting an update.
 *
 * @author Mark Jaquith
 * @link http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
 *
 * @param array $r, request arguments
 * @param string $url, request url
 * @return array request arguments
 */

function wpz_dont_update_theme( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/themes/update-check' ) )
		return $r; // Not a theme update request. Bail immediately.
	$themes = unserialize( $r['body']['themes'] );
	unset( $themes[ get_option( 'template' ) ] );
	unset( $themes[ get_option( 'stylesheet' ) ] );
	$r['body']['themes'] = serialize( $themes );
	return $r;
}

/**
 * Register a new meta box to the post / page edit screen, so that the user can
 * set SEO options on a per-post or per-page basis.
 *
 * @category Genesis
 * @package Admin
 * @subpackage Inpost-Metaboxes
 *
 * @since 0.1.3
 *
 * @see genesis_inpost_seo_box() Generates the content in the meta box
 */
function wpz_add_inpost_seo_box() {

	if ( genesis_detect_seo_plugins() )
		return;
		
	foreach ( (array) get_post_types( array( 'public' => true ) ) as $type ) {
		if ( post_type_supports( $type, 'genesis-seo' ) )
			add_meta_box( 'genesis_inpost_seo_box', __( 'Theme SEO Settings', 'genesis' ), 'genesis_inpost_seo_box', $type, 'normal', 'default' );
	}

}

/**
 * Register a new meta box to the post / page edit screen, so that the user can
 * set layout options on a per-post or per-page basis.
 *
 * @category Genesis
 * @package Admin
 * @subpackage Inpost-Metaboxes
 *
 * @since 0.2.2
 *
 * @see genesis_inpost_layout_box() Generates the content in the boxes
 *
 * @return null Returns null if Genesis layouts are not supported
 */
function wpz_add_inpost_layout_box() {

	if ( ! current_theme_supports( 'genesis-inpost-layouts' ) )
		return;

	foreach ( (array) get_post_types( array( 'public' => true ) ) as $type ) {
		if ( post_type_supports( $type, 'genesis-layouts' ) )
			add_meta_box( 'genesis_inpost_layout_box', __( 'Layout Settings', 'genesis' ), 'genesis_inpost_layout_box', $type, 'normal', 'default' );
	}

}

if ( !function_exists('wp_new_user_notification') ) {
	function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
		$user = new WP_User($user_id);
		$user_login = stripslashes($user->user_login);
		$user_email = stripslashes($user->user_email);
		$user_first_name = get_user_meta( $user_id, 'first_name', true );
		$user_last_name = get_user_meta( $user_id, 'last_name', true );
		$fullname = ($user_first_name." ". $user_last_name);

		// Admin Notification Email
		$message  = sprintf(__('New user registration on your blog %s:'), get_option('blogname')) . "\r\n\r\n";
		$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
		$message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";
		@wp_mail(get_option('admin_email'), sprintf(__('%s - New User Registration'), get_option('blogname')), $message);

		if ( empty($plaintext_pass) )
		return;
		
		// Users Notification Email
		$message = sprintf(__('Dear, %s'), $fullname ) . "\r\n\r\n";
		$message .= sprintf(__("Thanks for registering your details at %s! website, your user login details are below:"), get_option('blogname')) . "\r\n\r\n";
		
		$message .= wp_login_url() . "\r\n";
		$message .= sprintf(__('Username: %s'), $user_login). "\r\n";
		$message .= sprintf(__('Password: %s'), $plaintext_pass). "\r\n\r\n";
		
		$message .= sprintf(__('If you have any questions or problems, please contact us at %s.'), get_option('admin_email')) . "\r\n\r\n";

		$message .= __('Thank you,'). "\r\n\r\n";
		$message .= get_option('blogname'). "\r\n";
		$message .= get_option('home'). "\r\n\r\n";

                        $message .=  __('Snippet By http://www.awkreativ.com,'). "\r\n\r\n";
		wp_mail($user_email, sprintf(__('%s - Username & Password'), get_option('blogname')), $message);

	}
}