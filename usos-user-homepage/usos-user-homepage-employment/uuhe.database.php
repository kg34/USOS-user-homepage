<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

$uuhe_types = array(
	'employment_id' => 'd',
	'user_id' => 'd',
	'company_name' => 's',
	'link' => 's',
	'start_date' => 's',
	'end_date' => 's',
	'job' => 's'
	);

function uuhe_database_install()
{	
	global $wpdb;
	//create uuhe table
	$uuheemployment = "{$wpdb->prefix}uuheemployment";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	$sql = "CREATE TABLE IF NOT EXISTS $uuheemployment (
		`employment_id`   BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		`user_id`         BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
		`company_name`    VARCHAR(45) NOT NULL DEFAULT '',
		`link`            VARCHAR(255) NULL,
		`start_date`      DATE NOT NULL DEFAULT '0000-00-00',
		`end_date`        DATE NULL,
		`job`             VARCHAR(45) NOT NULL,
		PRIMARY KEY (`employment_id`),
		KEY (`user_id`)
		) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1 ;";
	dbDelta( $sql );
	
}

function uuhe_database_delete_employment($employment_atts = NULL) {
	global $wpdb, $uuhe_types;
	$uuheemployment = "{$wpdb->prefix}uuheemployment";
	$employment_where = uuh_database_get_where($employment_atts, $uuhe_types);
	$prepare_atts = uuh_database_get_prepare_atts($employment_atts);
	$wpdb->query($wpdb->prepare("DELETE FROM $uuheemployment $employment_where", $prepare_atts));
}

function uuhe_database_add_employment($employment)
{
	global $wpdb;
	$uuheemployment = "{$wpdb->prefix}uuheemployment";
	$wpdb->query($wpdb->prepare("INSERT INTO $uuheemployment (user_id, company_name, job, link, start_date, end_date) VALUES (%d, %s, %s, %s, %s, %s)", $employment['user_id'], $employment['company_name'], $employment['job'], $employment['link'], $employment['start_date'], $employment['end_date']));
}

function uuhe_database_get_user_employment($user_id, $select_atts = NULL)
{
	global $wpdb;
	$uuheemployment = "{$wpdb->prefix}uuheemployment";
	$employment_atts = array(
		'user_id' => $user_id
	);
	return uuhe_database_get_employment($employment_atts, $select_atts);
}

function uuhe_database_get_employment($employment_atts = NULL, $select_atts = NULL)
{
	global $wpdb, $uuhe_types;
	$uuheemployment = "{$wpdb->prefix}uuheemployment";
	
	$employment_where = uuh_database_get_where($employment_atts, $uuhe_types);
	$prepare_atts = uuh_database_get_prepare_atts($employment_atts);
	$select =  uuh_database_get_select($select_atts);
	$employment = $wpdb->get_results($wpdb->prepare("SELECT $select FROM $uuheemployment $employment_where", $prepare_atts));
	return $employment;
}

function uuhe_database_uninstall()
{
	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}uuheemployment" );	
}
