<?php

function uuhs_menu_page_callback() {
	$date_code = uuh_menu_page_get_date_input('Event date', 'date');

	$add_code = $date_code.uuh_menu_page_get_time_input('Event start hour', 'start_hour').
							uuh_menu_page_get_time_input('Event end hour', 'end_hour');
	$add_fields = array(
		'title' => 'Event title',
		'description' => 'Event description',
		);
	$add_atts = array (
		'title' => 'Add',
		'code' => $add_code,
		'page' => 'add_new_event',
		'fields' => $add_fields
		);
	$find_code = $date_code;
	$find_fields = array(
		'title' => 'Event title (optional)'
		);
	$find_atts = array (
		'title' => 'Find',
		'code' => $find_code,
		'page' => 'find_events',
		'fields' => $find_fields
		);

	$delete_fields = $find_fields;
	$delete_code = $find_code;
	$delete_atts = array (
		'title' => 'Delete',
		'code' => $delete_code,
		'page' => 'delete_events',
		'fields' => $delete_fields
		);	
	$atts = array (
		'add' => $add_atts,
		'find' => $find_atts,
		'delete' => $delete_atts
		);
		
	$div_title = 'Events';

	uuh_menu_page_print_page($div_title, $atts);
}

add_action( 'admin_post_add_new_event', 'uuhs_menu_page_add_event' );
add_action( 'admin_post_find_events', 'uuhs_menu_page_find_events' );
add_action( 'admin_post_delete_events', 'uuhs_menu_page_delete_events' );

function uuhs_menu_page_add_event() {
	$atts = array(
		'title' => 'text',
		'description' => 'text',
		'date' => 'date',
		'start_hour' => 'time',
		'end_hour' => 'time'
		);
	$result = uuh_menu_page_get_atts($atts);
	if ($result == NULL) {
		echo "Oszust";
		return;
	}
	$event = array(
		'title' => $result['title'],
		'description' => $result['description'],
		'user_id' => wp_get_current_user()->ID,
		'source' => 'wp'
	);
	if ($event['title'] == '')
		$event['title'] = 'Event';
	$time = array(
		'date' => $result['date'],
		'day_of_week' => 0,
		'start_hour' => $result['start_hour'].':00',
		'end_hour' => $result['end_hour'].':00'
	);
	$times = array(
		0 => $time
		);
	uuhs_database_add_event($event, $times);
	uuh_menu_page_post_header('Have been added:');
	echo '<tr><td>'.$time['date'].'</td><td>'.$event['title'].'</td><td>'.$event['description'].'</td><td>'.$time['start_hour'].'</td><td>'.$time['end_hour'].'</td></tr>';
	uuh_menu_page_post_footer();
}

function uuhs_menu_page_print_events($message) {
	$atts = array(
		'title' => 'text',
		'date' => 'date',
		);
	$result = uuh_menu_page_get_atts($atts);
	if ($result == NULL) {
		echo "Invalid arguments";
		return;
	}
	$time_atts = array(
		'date' => $result['date'],
		);
	$event_atts = array(
		'user_id' => wp_get_current_user()->ID,
		'source' => 'wp'
	);
	if ($result['title'] != '')
		$event_atts['title'] = $result['title'];
	$select_atts = array(
		'date' => true,
		'title' => true,
		'description' => true,
		'start_hour' => true,
		'end_hour' => true
	);
	$events = uuhs_database_get_events($event_atts, $time_atts, $select_atts);
	uuh_menu_page_post_header($message);
	foreach ( $events as $event ) {
		echo '<tr>';
		$event->start_hour = substr($event->start_hour, 0, -3);
		$event->end_hour = substr($event->end_hour, 0, -3);
		foreach ( $event as $value )
			echo '<td>'.$value.'</td>';
		echo '</tr>';
	}
	uuh_menu_page_post_footer();
}

function uuhs_menu_page_delete_events() {
	uuhs_menu_page_print_events('Have been deleted:');
	$event_atts = array(
		'user_id' => wp_get_current_user()->ID,
		'source' => 'wp'
	);
	if ( isset ( $_GET['title'] ) and esc_html( $_GET['title'] ) != '')
		$event_atts['title'] = esc_html( $_GET['title'] );
	uuhs_database_delete_events($event_atts);
}

function uuhs_menu_page_find_events() {
	uuhs_menu_page_print_events('Have been found:');
}

?>