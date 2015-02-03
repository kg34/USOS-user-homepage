<?php

function uuhs_shortcode_function($atts) {

	$atts = shortcode_atts(array(
		'description' => true,
		'date' => false,
		'start_hour' => true,
		'end_hour' => true,
		'visitors_info' => '',
		), $atts);
		
	$select_atts = array(
		'date' => $atts['date'],
		'start_hour' => $atts['start_hour'],
		'end_hour' => $atts['end_hour'],
		'title' => true,
		'description' => $atts['description'],
		);
		
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

	if (! $user_id)
		return $atts['visitors_info'];
	
	$monday_date = date('Y-m-d', last_monday(date('Y-m-d')));
	$dayNames = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
	$output = '<table class="form-table editcomment"><tr><h2>Schedule</h2></tr>';
	$date = $monday_date;
	for ($i = 0; $i < 7; $i++) {
		$events = uuhs_database_get_day_events($date, $user_id, $select_atts);
		$output = $output."<tr><td colspan=4>".__($dayNames[$i])." ".short_date($date)."</td></tr>";
		foreach ( $events as $event ) {
			$event->start_hour = substr($event->start_hour, 0, -3);
			$event->end_hour = substr($event->end_hour, 0, -3);
			$line = '';
			foreach ( $event as $value )
				$line = $line.'<td>'.$value.'</td>';
			$output = $output.'<tr>'.$line.'</tr>';
		}
		$date = date('Y-m-d', next_day($date));
	}
	$output = $output."</table>";
	return $output;
}

add_shortcode('uuh_schedule', 'uuhs_shortcode_function');
	
function uuhs_get_user_schedule($user_id, $adapter) {
	uuhs_database_delete_usos_events($user_id);
	
	$monday_date = date('Y-m-d', last_monday(date('Y-m-d')));

	$response = $adapter->api()->get( 'https://usosapps.uw.edu.pl/services/tt/user?start='.$monday_date.'&days=7&fields=start_time|end_time|name' );
	
	foreach ( $response as $event )
		uuhs_add_event($event, $user_id);
}

function uuhs_add_event($response, $user_id) {
	$event = array(
		'title' => $response->name->en,
		'user_id' => $user_id,
		'description' => '',
		'source' => 'usos'
	);
	$start = explode(" ", $response->start_time);
	$end = explode(" ", $response->end_time);
	$time = array(
		'date' => $start[0],
		'day_of_week' => 0,
		'start_hour'=> $start[1],
		'end_hour' => $end[1],
		'source' => 'usos'
	);
	$times = array(
		0 => $time,
	);
	uuhs_database_add_event($event, $times);
}

function short_date($date) {
	if (!is_numeric($date))
		$date = strtotime($date);
	return date('j.m', $date);
}

function next_day($date) {
	if (!is_numeric($date))
		$date = strtotime($date);
	return strtotime(
		'+1 day',
		$date
	);
}

function last_monday($date) {
	if (!is_numeric($date))
		$date = strtotime($date);
	if (date('w', $date) == 1)
		return $date;
	else
		return strtotime(
			'last monday',
			$date
		);
}
?>
