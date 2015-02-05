<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function uuh_usos_user($user_id)
{
	global $wpdb;
	$wslusersprofiles = "{$wpdb->prefix}wslusersprofiles";
	$result = $wpdb->get_results($wpdb->prepare("SELECT EXISTS (SELECT user_id FROM $wslusersprofiles WHERE user_id=%d) AS usos_user", $user_id));
	return $result[0]->usos_user;
}

function uuh_get_user_uosos_profile($user_id)
{
	global $wpdb;
	$wslusersprofiles = "{$wpdb->prefix}wslusersprofiles";
	return $wpdb->get_results($wpdb->prepare("SELECT * FROM $wslusersprofiles WHERE user_id=%d", $user_id));
}

function uuh_database_get_prepare_atts($atts)
{
	$prepare_atts = array();
	foreach ($atts as $key => $value)
		$prepare_atts[] = $value;
	return $prepare_atts;
}

function uuh_database_get_where($atts, $atts_types) 
{
	$where = '';
	foreach ($atts as $key => $value)
	{
		$where = $where." $key=%$atts_types[$key] AND";
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