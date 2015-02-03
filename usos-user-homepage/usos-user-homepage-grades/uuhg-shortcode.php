<?php

function uuhg_shortcode_function($atts) {

	$atts = shortcode_atts(array(
		'description' => true,
		'grade_description' => true,
		'grade_value' => true,
		'visitors_info' => '',
		), $atts);
		
	$select_atts = array(
		'name' => true,
		'description' => $atts['description'],
		'grade_value' => $atts['grade_value'],
		'grade_description' => $atts['grade_description'],
		);
		
 	$current_user = wp_get_current_user();
 	$user_id = $current_user->ID;

	if (! $user_id)
		return $atts['visitors_info'];
		
	$courses = uuhg_database_get_current_grades($user_id, $select_atts);
	$output = '<table class="form-table editcomment"><tr><h2>Grades</h2></tr>';
	foreach ( $courses as $course ) {
			$line = '';
			foreach ( $course as $value )
				$line = $line.'<td>'.$value.'</td>';
			$output = $output.'<tr>'.$line.'</tr>';
		}
	$output = $output."</table>";
	
	return $output;
}

add_shortcode('uuh-grades', 'uuhg_shortcode_function');

function uuhg_get_user_grades($user_id, $adapter) {
	uuhg_database_delete_usos_grades($user_id);
	$response = $adapter->api()->get( 'https://usosapps.uw.edu.pl/services/courses/user?active_terms_only=false&fields=course_editions[term_id|course_name|grades]' );	
	foreach ( $response->course_editions as $course_term )
		foreach ( $course_term as $course )
			uuhg_add_grade($course, $user_id);

}

require_once( USOS_HOMEPAGE_GRADES_ABS_PATH . '/uuhg.database.php' );

function uuhg_add_grade($response, $user_id) {
	$course_grades = array(
		'name' => $response->course_name->en,
		'user_id' => $user_id,
		'description' => $response->term_id,
		'grade_value',
		'grade_description',
		'source' => 'usos',
	);
	foreach($response->grades->course_grades as $grade) {
		$course_grades['grade_value'] = $grade->value_symbol;
		$course_grades['grade_description'] = $grade->value_description->en;
		uuhg_database_add_grades($course_grades);
	}
	return print_r($course_grades, true);
}
?>
