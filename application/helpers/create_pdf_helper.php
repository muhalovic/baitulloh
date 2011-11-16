<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function pdf_manifest($html, $head, $filename)
{
    require_once('mpdf/mpdf.php');
   
    $mpdf = new mPDF('c','A4-L','','',10,10,10,10,5,5);
	
	$mpdf->SetHTMLHeader($head);
	$mpdf->WriteHTML($html);	
	$mpdf->Output($filename,'D');
}
?>