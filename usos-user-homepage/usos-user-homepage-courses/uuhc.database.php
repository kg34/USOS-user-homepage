<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function uuhc_database_install()
{	
	global $wpdb;
	//create uuhc table
	$uuhccourses = "{$wpdb->prefix}uuhccourses";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	$sql = "CREATE TABLE IF NOT EXISTS $uuhccourses (
		`course_id`       BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		`user_id`         BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
		`name`            VARCHAR(75) NOT NULL DEFAULT '',
		`description`     TEXT NULL,
		`source`          VARCHAR(5) NOT NULL DEFAULT 'wp',
		PRIMARY KEY (`course_id`),
		KEY (`user_id`)
	) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1 ;";
	dbDelta( $sql );
	
}

function uuhc_database_delete_usos_courses($user_id)
{
	global $wpdb;
	$uuhccourses = "{$wpdb->prefix}uuhccourses";
	
	$course_atts = array(
		'source' => 'usos',
		'user_id' => $user_id
		);
		
	$course_where = uuh_database_get_where($course_atts) ;
	
	$wpdb->query( "DELETE FROM $uuhccourses $course_where");

}

function uuhc_database_add_course($course)
{
	global $wpdb;
	$uuhccourses = "{$wpdb->prefix}uuhccourses";
	
	$wpdb->query( "INSERT INTO $uuhccourses (user_id, name, description, source) VALUES (".$course['user_id'].",'".$course['name']."','".$course['description']."','".$course['source']."')" );
}

function uuhc_database_get_current_courses($user_id, $select_atts = NULL)
{
	global $wpdb;
	$uuhccourses = "{$wpdb->prefix}uuhccourses";

	$course_atts = array(
		'user_id' => $user_id
	);
	
	return uuhc_database_get_courses($course_atts, $select_atts);
}

function uuhc_database_delete_courses($course_atts = NULL)
{
	global $wpdb;
	$uuhccourses = "{$wpdb->prefix}uuhccourses";
	$courses_where = uuh_database_get_where($course_atts);
	$wpdb->query( "DELETE FROM $uuhccourses $courses_where");
}

function uuhc_database_get_courses($course_atts = NULL, $select_atts = NULL)
{
	global $wpdb;
	$uuhccourses = "{$wpdb->prefix}uuhccourses";
	
	$courses_where = uuh_database_get_where($course_atts) ;
	$select =  uuh_database_get_select($select_atts);
	
	$courses = $wpdb->get_results( "SELECT $select FROM $uuhccourses $courses_where");
	
	return $courses;
}

function uuhc_database_uninstall()
{
	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}uuhccourses" );	
}
