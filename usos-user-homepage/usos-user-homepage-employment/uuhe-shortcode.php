<?php

function uuhe_shortcode_function($atts) {

	$atts = shortcode_atts(array(
		'company_name' => true,
		'link' => true,
		'start_date' => true,
		'end_date' => true,
		'job' => true,
		'visitors_info' => '',
		), $atts);
		
	$select_atts = array(
		'company_name' => $atts['company_name'],
		'link' => $atts['link'],
		'start_date' => $atts['start_date'],
		'end_date' => $atts['end_date'],
		'job' => $atts['job'],
		);
		
 	$current_user = wp_get_current_user();
 	$user_id = $current_user->ID;

	if (! $user_id)
		return $atts['visitors_info'];
		
	$employment = uuhe_database_get_user_employment($user_id, $select_atts);
	$output = '<table class="form-table editcomment"><tr><h2>Eployment</h2></tr>';
	foreach ( $employment as $emp ) {
			$line = '';
			$emp['start_date'] = 'from '.$emp['start_date'];
			$emp['end_date'] = 'from '.$emp['end_date'];
			foreach ( $emp as $value ) {
				$line = $line.'<td>'.$value.'</td>';
			}
			$output = $output.'<tr>'.$line.'</tr>';
		}
	$output = $output."</table>";
	
	return $output;
}

add_shortcode('uuh-employment', 'uuhe_shortcode_function');

require_once( USOS_HOMEPAGE_EMPLOYMENT_ABS_PATH . '/uuhe.database.php' );
?>
