<?php

function uuhs_install()
{
	uuhs_database_install();
}

function uuhs_uninstall()
{
	uuhs_database_uninstall();
}

defined( 'USOS_HOMEPAGE_SCHEDULE_ABS_PATH' )
	|| define( 'USOS_HOMEPAGE_SCHEDULE_ABS_PATH', WP_PLUGIN_DIR . '/usos-user-homepage/usos-user-homepage-schedule' );
require_once( USOS_HOMEPAGE_SCHEDULE_ABS_PATH . '/uuhs.database.php' );

require_once( USOS_HOMEPAGE_SCHEDULE_ABS_PATH . '/uuhs-shortcode.php' );

?>
