<?php

function uuhc_shortcode_function($atts) {

	$atts = shortcode_atts(array(
		'description' => true,
		'visitors_info' => '',
		), $atts);
		
	$select_atts = array(
		'name' => true,
		'description' => $atts['description'],
		);
		
 	$current_user = wp_get_current_user();
 	$user_id = $current_user->ID;

	if (! $user_id)
		return $atts['visitors_info'];
		
	$courses = uuhc_database_get_current_courses($user_id, $select_atts);
	$output = '<table class="form-table editcomment"><tr><h2>Courses</h2></tr>';
	foreach ( $courses as $course ) {
			$line = '';
			foreach ( $course as $value )
				$line = $line.'<td>'.$value.'</td>';
			$output = $output.'<tr>'.$line.'</tr>';
		}
	$output = $output."</table>";
	
	return $output;
}

add_shortcode('uuh-courses', 'uuhc_shortcode_function');
	
function uuhc_get_user_courses($user_id, $adapter) {
	uuhc_database_delete_usos_courses($user_id);
	$response = $adapter->api()->get( 'https://usosapps.uw.edu.pl/services/courses/user?fields=course_editions' );
	foreach ( $response->course_editions as $course_term )
		foreach ( $course_term as $course )
			uuhc_add_course($course, $user_id);
}

require_once( USOS_HOMEPAGE_COURSES_ABS_PATH . '/uuhc.database.php' );

function uuhc_add_course($response, $user_id) {
	$course = array(
		'name' => $response->course_name->en,
		'user_id' => $user_id,
		'description' => $response->term_id,
		'source' => 'usos'
	);
	uuhc_database_add_course($course);
}
?>