<?php

function uuhc_install()
{
	uuhc_database_install();
}

function uuhc_uninstall()
{
	uuhc_database_uninstall();
}

defined( 'USOS_HOMEPAGE_COURSES_ABS_PATH' )
	|| define( 'USOS_HOMEPAGE_COURSES_ABS_PATH', WP_PLUGIN_DIR . '/usos-user-homepage/usos-user-homepage-courses' );
require_once( USOS_HOMEPAGE_COURSES_ABS_PATH . '/uuhc.database.php' );

require_once( USOS_HOMEPAGE_COURSES_ABS_PATH . '/uuhc-shortcode.php' );
?>
