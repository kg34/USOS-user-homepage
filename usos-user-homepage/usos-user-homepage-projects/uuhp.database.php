<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function uuhp_database_install()
{	
	global $wpdb;
	//create uuhp table
	$uuhpproject = "{$wpdb->prefix}uuhpproject";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	$sql = "CREATE TABLE IF NOT EXISTS $uuhpproject (
		`project_id`      BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		`user_id`         BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
		`name`            VARCHAR(75) NULL,
		`description`     TEXT NOT NULL DEFAULT '',
		`job`             VARCHAR(45) NULL,
		`link`            VARCHAR(255) NULL,
		PRIMARY KEY (`project_id`),
		KEY (`user_id`)
		) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1 ;";
	dbDelta( $sql );
	
}

function uuhp_database_delete_project($project_atts = NULL) {
	global $wpdb;
	$uuhpproject = "{$wpdb->prefix}uuhpproject";
	$project_where = uuh_database_get_where($project_atts);
	$wpdb->query( "DELETE FROM $uuhpproject $project_where");
}

function uuhp_database_add_project($project)
{
	global $wpdb;
	$uuhpproject = "{$wpdb->prefix}uuhpproject";
	$wpdb->query( "INSERT INTO $uuhpproject (user_id, name, description, job, link) VALUES (".$project['user_id'].",'".$project['name']."','".$project['description']."','".$project['job']."','".$project['link']."')" );
}

function uuhp_database_get_user_project($user_id, $select_atts = NULL)
{
	global $wpdb;
	$uuhpproject = "{$wpdb->prefix}uuhpproject";
	$project_atts = array(
		'user_id' => $user_id
	);
	return uuhp_database_get_project($project_atts, $select_atts);
}

function uuhp_database_get_project($project_atts = NULL, $select_atts = NULL)
{
	global $wpdb;
	$uuhpproject = "{$wpdb->prefix}uuhpproject";
	
	$project_where = uuh_database_get_where($project_atts) ;
	$select =  uuh_database_get_select($select_atts);
	$project = $wpdb->get_results( "SELECT $select FROM $uuhpproject $project_where");
	return $project;
}

function uuhp_database_uninstall()
{
	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}uuhpproject" );	
}
