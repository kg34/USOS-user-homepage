<?php

add_action( 'admin_post_uuh_cv', 'uuh_create_cv' );

require_once(dirname(__FILE__). '/includes/fpdf17/fpdf.php');

function uuh_create_cv() {
	$pdf = new FPDF();
	$pdf->AddPage();
	$user = wp_get_current_user();
	$usos_user = uuh_get_user_uosos_profile($user->ID);
	$pdf->SetFont('Arial','',16);
	$pdf->Cell(0,15,"CURRICULUM VITAE",0,1,"C");
	$pdf->SetFont('Arial','B',14);
	if ($usos_user)
		$pdf->Write(8, $usos_user[0]->displayname);
	else
		$pdf->Write(8, $user->data->display_name);
	$pdf->Ln();
	$pdf->Write(8, $user->data->user_email);
	$pdf->Ln();
	$pdf->Ln();
	
	$includes = uuh_menu_page_print_get_data();
	foreach ($includes as $include) {
		$pdf->SetFont('Arial','',14);
		$pdf->Write(10, $include['title']);
		$pdf->Ln();
		$pdf->SetFont('Arial','',12);
		foreach ($include['list'] as $content)	{
			$pdf->Write(10, $content);
			$pdf->Ln();
		}
		$pdf->Ln();
	}
	$pdf->Output();
}
?>