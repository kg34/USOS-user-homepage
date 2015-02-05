<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

$uuhs_types = array(
	'event_id' => 'd',
	'user_id' => 'd',
	'title' => 's',
	'description' => 's',
	'source' => 's',
	'time_id' => 'd',
	'date' => 's',
	'day_of_week' => 'd',
	'start_hour' => 's',
	'end_hour' => 's'
	);

function uuhs_database_install()
{	
	global $wpdb;
	//create uuhs tables
	$uuhsevents = "{$wpdb->prefix}uuhsevents";
	$uuhstimes = "{$wpdb->prefix}uuhstimes";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	$sql = "CREATE TABLE IF NOT EXISTS $uuhsevents (
		`event_id`        BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		`user_id`         BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
		`title`           VARCHAR(75) NOT NULL DEFAULT '',
		`description`     TEXT NULL,
		`source`          VARCHAR(5) NOT NULL DEFAULT 'wp',
		PRIMARY KEY (`event_id`),
		KEY (`user_id`)
		) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;";
	dbDelta( $sql );

	$sql = "CREATE TABLE IF NOT EXISTS $uuhstimes (
		`time_id`         BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		`event_id`        BIGINT(20) UNSIGNED NOT NULL,
		`date`            DATE NULL,
		`day_of_week`     INT NULL,
		`start_hour`      TIME NULL,
		`end_hour`        TIME NULL,
		PRIMARY KEY (`time_id`),
		KEY (`event_id`)
		) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;";
	dbDelta( $sql );

}

function uuhs_database_delete_usos_events($user_id)
{
	global $wpdb, $uuhs_types;
	$uuhsevents = "{$wpdb->prefix}uuhsevents";
	$uuhstimes = "{$wpdb->prefix}uuhstimes";
	
	$event_atts = array(
		'source' => 'usos',
		'user_id' => $user_id
		);
		
	$events_where = uuh_database_get_where($event_atts, $uuhs_types);
	$prepare_atts = uuh_database_get_prepare_atts($event_atts);
	
	$wpdb->query($wpdb->prepare("DELETE FROM $uuhstimes WHERE event_id IN (SELECT event_id FROM $uuhsevents $events_where)", $prepare_atts));
	$wpdb->query($wpdb->prepare("DELETE FROM $uuhsevents $events_where", $prepare_atts));
}

function uuhs_database_add_event($event, $times)
{
	global $wpdb;
	$uuhsevents = "{$wpdb->prefix}uuhsevents";
	$uuhstimes = "{$wpdb->prefix}uuhstimes";
	$wpdb->query($wpdb->prepare("INSERT INTO $uuhsevents (user_id, title, description, source) VALUES (%d, %s, %s, %s)", $event['user_id'], $event['title'], $event['description'], $event['source']));
	$event_id = $wpdb->insert_id;
	foreach( $times as $key => $time )
	{	
		$wpdb->query($wpdb->prepare("INSERT INTO $uuhstimes (event_id, date, day_of_week, start_hour, end_hour) VALUES (%d, %s, %d, %s, %s)", $event_id, $time['date'], $time['day_of_week'], $time['start_hour'], $time['end_hour']));
	}
}

function uuhs_database_get_day_events($date, $user_id, $select_atts = NULL)
{
	global $wpdb;
	$uuhsevents = "{$wpdb->prefix}uuhsevents";
	$uuhstimes = "{$wpdb->prefix}uuhstimes";

	$event_atts = array(
		'user_id' => $user_id
	);
	$time_atts = array(
		'date' => $date
	);
	return uuhs_database_get_events($event_atts, $time_atts, $select_atts);
}

function uuhs_database_prepare($atts1, $atts2)
{
	$prepare_atts1 = uuh_database_get_prepare_atts($atts1);
	$prepare_atts2 = uuh_database_get_prepare_atts($atts2);
	$prepare_atts = array_merge($prepare_atts1, $prepare_atts2);
	return $prepare_atts;
}

function uuhs_database_delete_events($event_atts = NULL)
{
	global $wpdb, $uuhs_types;
	$uuhsevents = "{$wpdb->prefix}uuhsevents";
	$uuhstimes = "{$wpdb->prefix}uuhstimes";
	
	$events_where = uuh_database_get_where($event_atts, $uuhs_types);
	$prepare_atts = uuh_database_get_prepare_atts($event_atts);
	$select =  uuh_database_get_select($select_atts);
	
	$prepare_atts = uuhs_database_prepare($event_atts);
	
	$wpdb->query($wpdb->prepare("DELETE FROM $uuhstimes WHERE event_id IN (SELECT event_id FROM $uuhsevents $events_where)", $prepare_atts));
	$wpdb->query($wpdb->prepare("DELETE FROM $uuhsevents $events_where", $prepare_atts));
}

function uuhs_database_get_events($event_atts = NULL, $time_atts = NULL, $select_atts = NULL)
{
	global $wpdb, $uuhs_types;
	$uuhsevents = "{$wpdb->prefix}uuhsevents";
	$uuhstimes = "{$wpdb->prefix}uuhstimes";
	
	$events_where = uuh_database_get_where($event_atts, $uuhs_types);
	$times_where = uuh_database_get_where($time_atts, $uuhs_types);
	$select = uuh_database_get_select($select_atts);

	$prepare_atts = uuhs_database_prepare($time_atts, $event_atts);
	
	$events = $wpdb->get_results($wpdb->prepare("SELECT $select
	FROM 
	(SELECT * FROM $uuhstimes $times_where) AS times 
	INNER JOIN 
	(SELECT * FROM $uuhsevents $events_where) AS events 
	ON 
	times.event_id=events.event_id ORDER BY date, start_hour",
	$prepare_atts
	));
	
	return $events;
}

function uuhs_database_uninstall()
{
	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}uuhsevents" );
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}uuhstimes" ); 	
}
