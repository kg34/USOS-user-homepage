<?php

function uuhp_menu_page_callback() {
	$add_fields = array(
		'name' => 'Project name',
		'description' => 'Project description',
		'job' => 'Job',
		'link' => 'Project link',
		);
	$add_atts = array (
		'title' => 'Add',
		'code' => '',
		'page' => 'add_new_project',
		'fields' => $add_fields
		);
	$find_fields = array(
		'name' => 'Project name',
		);
	$find_atts = array (
		'title' => 'Find',
		'code' => '',
		'page' => 'find_project',
		'fields' => $find_fields
		);
	$delete_fields = $find_fields;
	$delete_atts = array (
		'title' => 'Delete',
		'code' => '',
		'page' => 'delete_project',
		'fields' => $delete_fields
		);	
	$atts = array (
		'add' => $add_atts,
		'find' => $find_atts,
		'delete' => $delete_atts
		);
	$div_title = 'Projects';

	uuh_menu_page_print_page($div_title, $atts);
}

add_action( 'admin_post_add_new_project', 'uuhp_menu_page_add_project' );
add_action( 'admin_post_find_project', 'uuhp_menu_page_find_project' );
add_action( 'admin_post_delete_project', 'uuhp_menu_page_delete_project' );

function uuhp_menu_page_add_project() {
	$atts = array(
		'name' => 'text',
		'description' => 'text',
		'link' => 'text',
		'job' => 'text',
		);
	$result = uuh_menu_page_get_atts($atts);
	if ($result == NULL) {
		wp_die("Invalid arguments");
		return;
	}
	$project = array(
		'name' => $result['name'],
		'description' => $result['description'],
		'link' => $result['link'],
		'job' => $result['job'],
		'user_id' => wp_get_current_user()->ID,
	);
	
	if ($project['name'] == '')
		$course['name'] = 'Project';
	uuhp_database_add_project($project);
	uuh_menu_page_post_header('Have been added:');
	echo '<tr><td>'.$project['name'].'</td><td>'.$project['description'].'</td><td>'.$project['job'].'</td><td>'.$project['link'].'</td></tr>';
	uuh_menu_page_post_footer();
}

function uuhp_menu_page_print_project($message) {
	$atts = array(
		'name' => 'text',
		);
	$result = uuh_menu_page_get_atts($atts);
	if ($result == NULL) {
		echo "Invalid arguments";
		return;
	}
	$project_atts = array(
		'user_id' => wp_get_current_user()->ID,
	);
	if ($result['name'] != '')
		$project_atts['name'] = $result['name'];
	$select_atts = array(
		'name' => true,
		'description' => true,
		'job' => true,
		'link' => true,
	);
	$project = uuhp_database_get_project($project_atts, $select_atts);
	uuh_menu_page_post_header($message);
	foreach ( $project as $emp ) {
		echo '<tr>';
		foreach ( $emp as $value )
			echo '<td>'.$value.'</td>';
		echo '</tr>';
	}
	uuh_menu_page_post_footer();
}

function uuhp_menu_page_delete_project() {
	uuhp_menu_page_print_project('Have been deleted:');
	$project_atts = array(
		'user_id' => wp_get_current_user()->ID,
	);
	if ( isset ( $_GET['name'] ) and esc_html( $_GET['name'] ) != '')
		$project_atts['name'] = esc_html( $_GET['name'] );
	uuhp_database_delete_project($project_atts);
}

function uuhp_menu_page_find_project() {
	uuhp_menu_page_print_project('Have been found:');
}

function uuhp_menu_page_get_project() {
	$select_atts = array(
		'project_id' => true,
		'name' => true,
		'job' => true,
		'description' => true,
		'link' => true,
	);
	$projects = uuhp_database_get_user_project(wp_get_current_user()->ID, $select_atts);
	$fields = array();
	foreach ( $projects as $project ) {
		$value = $project->project_id;
		$content = $project->name.' - '.$project->job.': '.$project->description.' ('.$project->link.')';
		$fields[] = array(
			'value' => $value,
			'content' => $content
		);
	}
	return array (
		'name' => 'uuhp',
		'title' => 'Projects',
		'fields' => $fields
	);
}

function uuhp_menu_page_get_choosen() {
	$select_atts = array(
		'name' => true,
		'job' => true,
		'description' => true,
		'link' => true,
	);
	$uuhp = $_POST['uuhp'];
	$projects = array();
	foreach ($uuhp as $id) {
		$project_atts = array(
			'project_id' => $id
		);
		$project = uuhp_database_get_project($project_atts, $select_atts);
		$project = $project[0];
		$content = $project->name.' - '.$project->job.': '.$project->description.' ('.$project->link.')';
		$projects[] = $content;
	}
	if (empty($projects))
		return NULL;
	return array(
		'title' => 'Projects',
		'list' => $projects
		);
}
?>