<?php

function uuhe_menu_page_callback() {
	$add_code = uuh_menu_page_get_date_input('Start date', 'start_date').
				uuh_menu_page_get_date_input('End date', 'end_date');
	$add_fields = array(
		'company-name' => 'Company name',
		'link' => 'Company link',
		'job' => 'Job',
		);
	$add_atts = array (
		'title' => 'Add',
		'code' => $add_code,
		'page' => 'add_new_employment',
		'fields' => $add_fields
		);
	$find_code = '';
	$find_fields = array(
		'company-name' => 'Company name',
		'job' => 'Job',
		);
	$find_atts = array (
		'title' => 'Find',
		'code' => $find_code,
		'page' => 'find_employment',
		'fields' => $find_fields
		);
	$delete_fields = $find_fields;
	$delete_code = $find_code;
	$delete_atts = array (
		'title' => 'Delete',
		'code' => $delete_code,
		'page' => 'delete_employment',
		'fields' => $delete_fields
		);	
	$atts = array (
		'add' => $add_atts,
		'find' => $find_atts,
		'delete' => $delete_atts
		);
	$div_title = 'Employment';

	uuh_menu_page_print_page($div_title, $atts);
}

add_action( 'admin_post_add_new_employment', 'uuhe_menu_page_add_employment' );
add_action( 'admin_post_find_employment', 'uuhe_menu_page_find_employment' );
add_action( 'admin_post_delete_employment', 'uuhe_menu_page_delete_employment' );

function uuhe_menu_page_add_employment() {
	$atts = array(
		'company-name' => 'text',
		'link' => 'text',
		'start_date' => 'date',
		'end_date' => 'date',
		'job' => 'text',
		);
	$result = uuh_menu_page_get_atts($atts);
	if ($result == NULL) {
		wp_die("Invalid arguments");
		return;
	}
	$employment = array(
		'company_name' => $result['company-name'],
		'link' => $result['link'],
		'job' => $result['job'],
		'start_date' => $result['start_date'],
		'end_date' => $result['end_date'],
		'user_id' => wp_get_current_user()->ID,
	);
	
	if ($employment['company_name'] == '')
		$course['company_name'] = 'Company';
	uuhe_database_add_employment($employment);
	uuh_menu_page_post_header('Have been added:');
	echo '<tr><td>'.$employment['company_name'].'</td><td>'.$employment['job'].'</td><td>'.$employment['start_date'].'</td><td>'.$employment['end_date'].'</td><td>'.$employment['link'].'</td></tr>';
	uuh_menu_page_post_footer();
}

function uuhe_menu_page_print_employment($message) {
	$atts = array(
		'company-name' => 'text',
		'job' => 'text',
		);
	$result = uuh_menu_page_get_atts($atts);
	if ($result == NULL) {
		echo "Invalid arguments";
		return;
	}
	$employment_atts = array(
		'user_id' => wp_get_current_user()->ID,
	);
	if ($result['company_name'] != '')
		$employment_atts['company_name'] = $result['company_name'];
	if ($result['job'] != '')
		$employment_atts['job'] = $result['job'];
	$select_atts = array(
		'company_name' => true,
		'job' => true,
		'start_date' => true,
		'end_date' => true,
		'link' => true,
	);
	$employment = uuhe_database_get_employment($employment_atts, $select_atts);
	uuh_menu_page_post_header($message);
	foreach ( $employment as $emp ) {
		echo '<tr>';
		foreach ( $emp as $value )
			echo '<td>'.$value.'</td>';
		echo '</tr>';
	}
	uuh_menu_page_post_footer();
}

function uuhe_menu_page_delete_employment() {
	uuhe_menu_page_print_employment('Have been deleted:');
	$employment_atts = array(
		'user_id' => wp_get_current_user()->ID,
	);
	if ( isset ( $_GET['company-name'] ) and esc_html( $_GET['company-name'] ) != '')
		$employment_atts['company_name'] = esc_html( $_GET['company-name'] );
	if ( isset ( $_GET['job'] ) and esc_html( $_GET['job'] ) != '')
		$employment_atts['job'] = esc_html( $_GET['job'] );
	uuhe_database_delete_employment($employment_atts);
}

function uuhe_menu_page_find_employment() {
	uuhe_menu_page_print_employment('Have been found:');
}

function uuhe_menu_page_get_employment() {
	$select_atts = array(
		'employment_id' => true,
		'company_name' => true,
		'job' => true,
		'start_date' => true,
		'end_date' => true,
		'link' => true,
	);
	$employment = uuhe_database_get_user_employment(wp_get_current_user()->ID, $select_atts);
	$fields = array();
	foreach ( $employment as $emp ) {
		$value = $emp->employment_id;
		$content = $emp->company_name.' - '.$emp->job.' from '.$emp->start_date;
		if ($emp->end_date and ! $emp->end_date == '')
			$content = $content.' to '.$emp->end_date;
		$content = $content.' ('.$emp->link.')';
		$fields[] = array(
			'value' => $value,
			'content' => $content
		);
	}
	return array (
		'name' => 'uuhe',
		'title' => 'Employment',
		'fields' => $fields
	);
}

function uuhe_menu_page_get_choosen() {
	$select_atts = array(
		'company_name' => true,
		'job' => true,
		'start_date' => true,
		'end_date' => true,
		'link' => true,
	);
	$uuhe = $_POST['uuhe'];
	$employment = array();
	foreach ($uuhe as $id) {
		$employment_atts = array(
			'employment_id' => $id
		);
		$emp = uuhe_database_get_employment($employment_atts, $select_atts);
		$emp = $emp[0];
		$content = $emp->company_name.' - '.$emp->job.' from '.$emp->start_date;
		if ($emp->end_date and ! $emp->end_date == '')
			$content = $content.' to '.$emp->end_date;
		$content = $content.' ('.$emp->link.')';
		$employment[] = $content;
	}
	if (empty($employment))
		return NULL;
	return array(
		'title' => 'Employment',
		'list' => $employment
		);
}
?>
