<?php

function uuhg_install()
{
	uuhg_database_install();
}

function uuhg_uninstall()
{
	uuhg_database_uninstall();
}

defined( 'USOS_HOMEPAGE_GRADES_ABS_PATH' )
	|| define( 'USOS_HOMEPAGE_GRADES_ABS_PATH', WP_PLUGIN_DIR . '/usos-user-homepage/usos-user-homepage-grades' );
require_once( USOS_HOMEPAGE_GRADES_ABS_PATH . '/uuhg.database.php' );

require_once( USOS_HOMEPAGE_GRADES_ABS_PATH . '/uuhg-shortcode.php' );
?>
