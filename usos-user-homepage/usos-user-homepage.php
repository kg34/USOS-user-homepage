<?php
/*
 * Plugin Name: USOS user homepage
 * Version: 1.0
 * Author: Klaudia Grygoruk
 */

/**
 * Check for Wordpress Social Login
 */
function uuh_activate(){

	//Require parent plugin
	if ( ! is_plugin_active( 'wordpress-social-login/wp-social-login.php' ) and current_user_can( 'activate_plugins' ) ) 
	{
		// Stop activation redirect and show error
		wp_die('Sorry, but this plugin requires the Wordpress Social Login to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
	}
}

/**
 * Attempt to install UUH upon activation
 * Create uuh tables
 */ 

defined( 'USOS_USER_HOMEPAGE_ABS_PATH' )
	|| define( 'USOS_USER_HOMEPAGE_ABS_PATH', WP_PLUGIN_DIR . '/usos-user-homepage' );
require_once( USOS_USER_HOMEPAGE_ABS_PATH . '/includes/settings/uuh.database.php' );

require_once( USOS_USER_HOMEPAGE_ABS_PATH . '/usos-user-homepage-schedule/usos-user-homepage-schedule.php' );
require_once( USOS_USER_HOMEPAGE_ABS_PATH . '/usos-user-homepage-courses/usos-user-homepage-courses.php' );	
require_once( USOS_USER_HOMEPAGE_ABS_PATH . '/usos-user-homepage-grades/usos-user-homepage-grades.php' );	
require_once( USOS_USER_HOMEPAGE_ABS_PATH . '/usos-user-homepage-employment/usos-user-homepage-employment.php' );
require_once( USOS_USER_HOMEPAGE_ABS_PATH . '/usos-user-homepage-projects/usos-user-homepage-projects.php' );

register_activation_hook( __FILE__, 'uuh_activate' );

function uuh_install()
{
	uuhs_install();
	uuhc_install();
	uuhg_install();
	uuhe_install();
	uuhp_install();
}

register_activation_hook( __FILE__, 'uuh_install' );

function uuh_get_usos_data($user_login, $user)
{
	$user_id = $user->ID;
	if (! uuh_usos_user($user_id))
		return;
	
	if( ! class_exists( 'Hybrid_Auth', false ) ) {
		require_once WP_PLUGIN_DIR . "/wordpress-social-login/hybridauth/Hybrid/Auth.php";
	}
	$config = wsl_process_login_build_provider_config("Usosweb");
	try {
		Hybrid_Auth::initialize( $config );
		if( ! Hybrid_Auth::storage()->get( "hauth_session.Usosweb.is_logged_in" ) ){
			uuhs_database_delete_usos_events($user_id);
			uuhg_database_delete_usos_courses($user_id);
			uuhg_database_delete_usos_grades($user_id);
			return;
		}
		else
			$adapter = Hybrid_Auth::getAdapter( "Usosweb" );
	}
	catch( Exception $e ) {
		return "Ooophs, we got an error: " . $e->getMessage();
	}
	
	uuhs_get_user_schedule($user_id, $adapter);
	uuhc_get_user_courses($user_id, $adapter);
	uuhg_get_user_grades($user_id, $adapter);
}

add_action('wp_login', 'uuh_get_usos_data', 12, 2);

function uuh_uninstall()
{
	uuhs_uninstall();
	uuhc_uninstall();
	uuhg_uninstall();
	uuhe_uninstall();
	uuhp_uninstall();
}

register_deactivation_hook( __FILE__, 'uuh_uninstall' );

require_once( USOS_USER_HOMEPAGE_ABS_PATH . '/menu.php' );	
function uuh_add_menu_page() {
	add_menu_page('USOS user homepage', 'USOS user homepage', 'read', 'uuh_settings', 'uuh_menu_page_callback');
	add_submenu_page( 'uuh_settings', 'CV', 'CV', 'read', 'create-cv', 'uuh_menu_page_cv_callback' );
};

add_action('admin_menu', 'uuh_add_menu_page');

?>
