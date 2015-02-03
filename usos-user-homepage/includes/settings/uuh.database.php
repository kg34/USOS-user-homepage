<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function uuh_database_install()
{
/*	global $wpdb;
	//create uuh tables
	$uuhevents = "{$wpdb->prefix}uuhevents";
	$uuhtimes = "{$wpdb->prefix}uuhtimes";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	$sql = "CREATE TABLE IF NOT EXISTS $uuhevents (
		`event_id`        BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		`user_id`         BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
		`title`           VARCHAR(45) NOT NULL DEFAULT '',
	       	`description`     TEXT NULL,
		PRIMARY KEY (`event_id`),
		KEY (`user_id`)
		) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;";
	dbDelta( $sql );

	$sql = "CREATE TABLE IF NOT EXISTS $uuhtimes (
		`time_id`         BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		`event_id`        BIGINT(20) UNSIGNED NOT NULL,
		`date`            DATE NULL,
		`day_of_week`     INT NULL,
		`start_hour`      TIME NULL,
		`completion_hour` TIME NULL,
		PRIMARY KEY (`time_id`),
		KEY (`event_id`)
		) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;";
	dbDelta( $sql );
 */
}

function uuh_database_uninstall()
{
/*	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}uuhevents" );
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}uuhtimes" ); 	
*/
}

function uuh_usos_user($user_id)
{
	global $wpdb;
	$wslusersprofiles = "{$wpdb->prefix}wslusersprofiles";
	$result = $wpdb->get_results( "SELECT EXISTS (SELECT user_id FROM $wslusersprofiles WHERE user_id=$user_id) AS usos_user");
	return $result[0]->usos_user;
}

function uuh_get_user_uosos_profile($user_id)
{
	global $wpdb;
	$wslusersprofiles = "{$wpdb->prefix}wslusersprofiles";
	return $wpdb->get_results( "SELECT * FROM $wslusersprofiles WHERE user_id=$user_id");
}

function uuh_database_get_where($atts) 
{
	$where = '';
	foreach ($atts as $key => $value)
	{
		$where = $where." $key='$value' AND";
	}
	if (! $where == '') 
	{
		$where = substr($where, 0, -3);
		$where = ' WHERE'.$where;
	}
	return $where;
}

function uuh_database_get_select($atts)
{
	$select = '';
	foreach ($atts as $key => $value)
	{
		if ($value)
			$select = $select." $key,";
	}
	if ($select == '')
		$select = '*';
	else
		$select = rtrim($select, ",");
	return $select;
}