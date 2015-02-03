<?php

function uuhp_shortcode_function($atts) {

	$atts = shortcode_atts(array(
		'name' => true,
		'description' => true,
		'job' => true,
		'link' => true,
		'visitors_info' => '',
		), $atts);
		
	$select_atts = array(
		'name' => $atts['name'],
		'link' => $atts['link'],
		'description' => $atts['description'],
		'job' => $atts['job'],
		);
		
 	$current_user = wp_get_current_user();
 	$user_id = $current_user->ID;

	if (! $user_id)
		return $atts['visitors_info'];
		
	$projects = uuhp_database_get_user_project($user_id, $select_atts);
	$output = '<table class="form-table editcomment"><tr><h2>Projects</h2></tr>';
	foreach ( $projects as $project ) {
			$line = '';
			foreach ( $project as $value ) {
				$line = $line.'<td>'.$value.'</td>';
			}
			$output = $output.'<tr>'.$line.'</tr>';
		}
	$output = $output."</table>";
	
	return $output;
}

add_shortcode('uuh-projects', 'uuhp_shortcode_function');

require_once( USOS_HOMEPAGE_PROJECTS_ABS_PATH . '/uuhp.database.php' );
?>
