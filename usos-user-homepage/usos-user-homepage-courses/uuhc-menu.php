<?php

function uuhc_menu_page_callback() {
	$add_fields = array(
		'name' => 'Course name',
		'description' => 'Course description',
		);
	$add_atts = array (
		'title' => 'Add',
		'code' => '',
		'page' => 'add_new_course',
		'fields' => $add_fields
		);
	$find_fields = array(
		'name' => 'Course name'
		);
	$find_atts = array (
		'title' => 'Find',
		'code' => '',
		'page' => 'find_courses',
		'fields' => $find_fields
		);
	$delete_fields = $find_fields;
	$delete_atts = array (
		'title' => 'Delete',
		'code' => '',
		'page' => 'delete_courses',
		'fields' => $delete_fields
		);	
	$atts = array (
		'add' => $add_atts,
		'find' => $find_atts,
		'delete' => $delete_atts
		);
	$div_title = 'Courses';

	uuh_menu_page_print_page($div_title, $atts);
}

add_action( 'admin_post_add_new_course', 'uuhc_menu_page_add_course' );
add_action( 'admin_post_find_courses', 'uuhc_menu_page_find_courses' );
add_action( 'admin_post_delete_courses', 'uuhc_menu_page_delete_courses' );

function uuhc_menu_page_add_course() {
	$atts = array(
		'name' => 'text',
		'description' => 'text',
		);
	$result = uuh_menu_page_get_atts($atts);
	if ($result == NULL) {
		wp_die("Invalid arguments");
		return;
	}
	$course = array(
		'name' => $result['name'],
		'description' => $result['description'],
		'user_id' => wp_get_current_user()->ID,
		'source' => 'wp'
	);
	if ($course['name'] == '')
		$course['name'] = 'Course';
	uuhc_database_add_course($course);
	uuh_menu_page_post_header('Have been added:');
	echo '<tr><td>'.$course['name'].'</td><td>'.$course['description'].'</td></tr>';
	uuh_menu_page_post_footer();
}

function uuhc_menu_page_print_courses($message) {
	$atts = array(
		'name' => 'text',
		);
	$result = uuh_menu_page_get_atts($atts);
	if ($result == NULL) {
		echo "Invalid arguments";
		return;
	}
	$course_atts = array(
		'user_id' => wp_get_current_user()->ID,
		'source' => 'wp'
	);
	$select_atts = array(
		'name' => true,
		'description' => true,
	);
	$courses = uuhc_database_get_courses($course_atts, $select_atts);
	uuh_menu_page_post_header($message);
	foreach ( $courses as $course ) {
		echo '<tr>';
		foreach ( $course as $value )
			echo '<td>'.$value.'</td>';
		echo '</tr>';
	}
	uuh_menu_page_post_footer();
}

function uuhc_menu_page_delete_courses() {
	uuhc_menu_page_print_courses('Have been deleted:');
	$course_atts = array(
		'user_id' => wp_get_current_user()->ID,
		'source' => 'wp'
	);
	if ( isset ( $_GET['name'] ) and esc_html( $_GET['name'] ) != '')
		$course_atts['name'] = esc_html( $_GET['name'] );
	uuhc_database_delete_courses($course_atts);
}

function uuhc_menu_page_find_courses() {
	uuhc_menu_page_print_courses('Have been found:');
}
?>