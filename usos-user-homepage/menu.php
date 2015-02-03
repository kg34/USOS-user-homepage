<?php

require_once( WP_PLUGIN_DIR. '/usos-user-homepage/usos-user-homepage-schedule/uuhs-menu.php' );
require_once( WP_PLUGIN_DIR. '/usos-user-homepage/usos-user-homepage-courses/uuhc-menu.php' );
require_once( WP_PLUGIN_DIR. '/usos-user-homepage/usos-user-homepage-grades/uuhg-menu.php' );
require_once( WP_PLUGIN_DIR. '/usos-user-homepage/usos-user-homepage-employment/uuhe-menu.php' );
require_once( WP_PLUGIN_DIR. '/usos-user-homepage/usos-user-homepage-projects/uuhp-menu.php' );
require_once( WP_PLUGIN_DIR. '/usos-user-homepage/cv.php' );

function uuh_menu_page_callback() {
	uuhs_menu_page_callback();
	uuhc_menu_page_callback();
	uuhg_menu_page_callback();
	uuhe_menu_page_callback();
	uuhp_menu_page_callback();
}

function uuh_menu_page_cv_callback() {
	?>
	<h1>
	Choose what information you want to include in your CV:
	</h1>
	<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
		<input type="hidden" name="action" value="uuh_cv">
	<?php uuh_menu_page_print_cv_page();
	submit_button( 'Send' ); ?>
	</form>
	<?php
	
}

function uuh_menu_page_print_cv_page() {
	uuh_menu_page_print_fields(uuhe_menu_page_get_employment());
	uuh_menu_page_print_fields(uuhp_menu_page_get_project());
}
function uuh_menu_page_print_fields($atts) {
	echo '</br><div>';
	echo '<h2>'.$atts['title'].'</h2>';
	foreach ($atts['fields'] as $field)
		echo '<input type="checkbox" name="'.$atts['name'].'[]" value="'.$field['value'].'"/>'.$field['content'].'</br>';
	echo '</div>';
	
}

function uuh_menu_page_print_get_data() {
	return array (uuhe_menu_page_get_choosen(), uuhp_menu_page_get_choosen());
}


function uuh_menu_page_print_page($div_title, $atts) {
	?>
	<div>
		<div align="center" id="header">
			<h1>
				<?php echo $div_title ?>
			</h1>
		</div>
		<div class="stuffbox" id="namediv">
			<table>
				<tr>
					<td>
						<?php uuh_menu_page_print_table($atts['add']); ?>
					</td>
					<td>
						<?php uuh_menu_page_print_table($atts['find']); ?>
						<?php uuh_menu_page_print_table($atts['delete']); ?>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<?php
}

function uuh_menu_page_print_table($atts) {
	?>
	<form action="<?php echo admin_url( 'admin-post.php' ); ?>">
		<input type="hidden" name="action" value="<?php echo $atts['page']?>">
		<?php wp_nonce_field('my-nonce'); ?>
		<div class="inside">
			<table class="form-table editcomment">
				<tbody>
					<tr>
						<td align="center" colspan="2">
							<h3>
								<?php echo $atts['title']; ?>
							</h3>
						</td>
					</tr>
					<?php
						foreach ($atts['fields'] as $name => $title) {
							echo '<tr valign="top">';
							echo "<td>$title</td>";
							echo '<td><input dir="ltr" type="text" name="'.$name.'" value="'.$name.'" ></td>';
							echo '</tr>';
						}
						echo $atts['code'];
					?>
				</tbody>
				<tr>
					<td align="center" colspan="2">
						<?php submit_button( 'Send' ); ?>
					</td>
				</tr>
			</table>
		</div>
	</form>
	<?php
}

function uuh_menu_page_get_atts($atts) {
	$nonce = $_REQUEST['_wpnonce'];
	if ( ! wp_verify_nonce( $nonce, 'my-nonce') ) {
		wp_die( 'Security check' );
		return NULL;
	}
	foreach ($atts as $key => $type) {
		if ((! isset ( $_GET[$key] )) or
			($type == 'date' and ! validate_date($_GET[$key], 'Y-m-d')) or
			($type == 'time' and ! validate_date($_GET[$key], 'H:i'))) {
		wp_die("Invalid arguments");
		return NULL;}
	}
	$result = array();
	foreach ($atts as $key => $type) {
		$result[$key] = esc_html( $_GET[$key] );
	}
	return $result;
}

function validate_date($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function uuh_menu_page_post_header($message) {
	echo '<div align="center"><h2>'.$message.'</h2></br><table class="form-table editcomment">';
}

function uuh_menu_page_post_footer() {
	echo '</table></br>';
	?>
	<a href="<?php echo admin_url( 'admin.php?page=uuh_settings' ); ?>">Return</a>
	</div>
	<?php
}

function uuh_menu_page_get_date_input($title, $field_name) {
	return '<tr valign="top">
				<td>'.$title.'</td>
				<td><input dir="ltr" type="date" name="'.$field_name.'" min = "'.date('Y-m-d').'" max="9999-12-31" value="'.date('Y-m-d').'"></td>
			</tr>';
}

function uuh_menu_page_get_time_input($title, $field_name) {
	return '<tr valign="top">
				<td>'.$title.'</td>
				<td><input dir="ltr" type="time" name="'.$field_name.'" value="00:00"></td>
			</tr>';
}
?>
