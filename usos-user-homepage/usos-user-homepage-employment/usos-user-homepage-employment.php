<?php

function uuhe_install()
{
	uuhe_database_install();
}

function uuhe_uninstall()
{
	uuhe_database_uninstall();
}

defined( 'USOS_HOMEPAGE_EMPLOYMENT_ABS_PATH' )
	|| define( 'USOS_HOMEPAGE_EMPLOYMENT_ABS_PATH', WP_PLUGIN_DIR . '/usos-user-homepage/usos-user-homepage-employment' );
require_once( USOS_HOMEPAGE_EMPLOYMENT_ABS_PATH . '/uuhe.database.php' );

require_once( USOS_HOMEPAGE_EMPLOYMENT_ABS_PATH . '/uuhe-shortcode.php' );
?>
