<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function uuhg_database_install()
{	
	global $wpdb;
	//create uuhg table
	$uuhggrades = "{$wpdb->prefix}uuhggrades";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	$sql = "CREATE TABLE IF NOT EXISTS $uuhggrades (
		`course_id`         BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		`user_id`           BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
		`name`              VARCHAR(75) NOT NULL DEFAULT '',
		`description`       TEXT NULL,
		`grade_value`       VARCHAR(15) NOT NULL,
		`grade_description` VARCHAR(25) NULL,
		`source`            VARCHAR(5) NOT NULL DEFAULT 'wp',
		PRIMARY KEY (`course_id`),
		KEY (`user_id`)
	) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1 ;";
	dbDelta( $sql );
	
}

function uuhg_database_delete_usos_grades($user_id)
{
	global $wpdb;
	$uuhggrades = "{$wpdb->prefix}uuhggrades";
	
	$course_atts = array(
		'source' => 'usos',
		'user_id' => $user_id
		);
		
	$course_where = uuh_database_get_where($course_atts) ;
	
	$wpdb->query( "DELETE FROM $uuhggrades $course_where");

}

function uuhg_database_add_grades($course)
{
	global $wpdb;
	$uuhggrades = "{$wpdb->prefix}uuhggrades";
	
	$wpdb->query( "INSERT INTO $uuhggrades (user_id, name, description, source, grade_value, grade_description) VALUES (".$course['user_id'].",'".$course['name']."','".$course['description']."','".$course['source']."','".$course['grade_value']."','".$course['grade_description']."')" );
}

function uuhg_database_get_current_grades($user_id, $select_atts = NULL)
{
	global $wpdb;
	$uuhggrades = "{$wpdb->prefix}uuhggrades";

	$course_atts = array(
		'user_id' => $user_id
	);
	
	return uuhg_database_get_grades($course_atts, $select_atts);
}

function uuhg_database_delete_grades($course_atts = NULL)
{
	global $wpdb;
	$uuhggrades = "{$wpdb->prefix}uuhggrades";
	$courses_where = uuh_database_get_where($course_atts);
	$wpdb->query( "DELETE FROM $uuhggrades $courses_where");
}


function uuhg_database_get_grades($course_atts = NULL, $select_atts = NULL)
{
	global $wpdb;
	$uuhggrades = "{$wpdb->prefix}uuhggrades";
	
	$courses_where = uuh_database_get_where($course_atts) ;
	$select =  uuh_database_get_select($select_atts);
	
	$courses = $wpdb->get_results( "SELECT $select FROM $uuhggrades $courses_where");
	
	return $courses;
}

function uuhg_database_uninstall()
{
	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}uuhggrades" );	
}
