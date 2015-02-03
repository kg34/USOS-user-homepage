<?php

function uuhp_install()
{
	uuhp_database_install();
}

function uuhp_uninstall()
{
	uuhp_database_uninstall();
}

defined( 'USOS_HOMEPAGE_PROJECTS_ABS_PATH' )
	|| define( 'USOS_HOMEPAGE_PROJECTS_ABS_PATH', WP_PLUGIN_DIR . '/usos-user-homepage/usos-user-homepage-projects' );
require_once( USOS_HOMEPAGE_PROJECTS_ABS_PATH . '/uuhp.database.php' );

require_once( USOS_HOMEPAGE_PROJECTS_ABS_PATH . '/uuhp-shortcode.php' );
?>
