<?php

function uuhg_menu_page_callback() {
	$add_fields = array(
		'name' => 'Course name',
		'description' => 'Course description',
		'grade-value' => 'Grade value',
		'grade-description' => 'Grade description'
		);
	$add_atts = array (
		'title' => 'Add',
		'code' => '',
		'page' => 'add_new_grade',
		'fields' => $add_fields
		);
	$find_fields = array(
		'name' => 'Course name',
		'grade-value' => 'Grade value'
		);
	$find_atts = array (
		'title' => 'Find',
		'code' => '',
		'page' => 'find_grades',
		'fields' => $find_fields
		);
	$delete_fields = $find_fields;
	$delete_atts = array (
		'title' => 'Delete',
		'code' => '',
		'page' => 'delete_grades',
		'fields' => $delete_fields
		);	
	$atts = array (
		'add' => $add_atts,
		'find' => $find_atts,
		'delete' => $delete_atts
		);
	$div_title = 'Grades';

	uuh_menu_page_print_page($div_title, $atts);
}

add_action( 'admin_post_add_new_grade', 'uuhg_menu_page_add_grade' );
add_action( 'admin_post_find_grades', 'uuhg_menu_page_find_grades' );
add_action( 'admin_post_delete_grades', 'uuhg_menu_page_delete_grades' );

function uuhg_menu_page_add_grade() {
	$atts = array(
		'name' => 'text',
		'description' => 'text',
		'grade-value' => 'text',
		'grade-description' => 'text'
		);
	$result = uuh_menu_page_get_atts($atts);
	if ($result == NULL) {
		wp_die("Invalid arguments");
		return;
	}
	$course = array(
		'name' => $result['name'],
		'description' => $result['description'],
		'grade_value' => $result['grade-value'],
		'grade_description' => $result['grade-description'],
		'user_id' => wp_get_current_user()->ID,
		'source' => 'wp'
	);
	
	if ($course['name'] == '')
		$course['name'] = 'Course';
	uuhg_database_add_grades($course);
	uuh_menu_page_post_header('Have been added:');
	echo '<tr><td>'.$course['name'].'</td><td>'.$course['description'].'</td><td>'.$course['grade_value'].'</td><td>'.$course['grade_description'].'</td></tr>';
	uuh_menu_page_post_footer();
}

function uuhg_menu_page_print_grades($message) {
	$atts = array(
		'name' => 'text',
		'grade-value' => 'text'
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
	if ($result['name'] != '')
		$course_atts['name'] = $result['name'];
	if ($result['grade-value'] != '')
		$course_atts['grade_value'] = $result['grade-value'];
	$select_atts = array(
		'name' => true,
		'description' => true,
		'grade_value' => true,
		'grade_description' => true
	);
	$grades = uuhg_database_get_grades($course_atts, $select_atts);
	uuh_menu_page_post_header($message);
	foreach ( $grades as $grade ) {
		echo '<tr>';
		foreach ( $grade as $value )
			echo '<td>'.$value.'</td>';
		echo '</tr>';
	}
	uuh_menu_page_post_footer();
}

function uuhg_menu_page_delete_grades() {
	uuhg_menu_page_print_grades('Have been deleted:');
	$course_atts = array(
		'user_id' => wp_get_current_user()->ID,
		'source' => 'wp'
	);
	if ( isset ( $_GET['name'] ) and esc_html( $_GET['name'] ) != '')
		$course_atts['name'] = esc_html( $_GET['name'] );
	uuhg_database_delete_grades($course_atts);
}

function uuhg_menu_page_find_grades() {
	uuhg_menu_page_print_grades('Have been found');
}
?>