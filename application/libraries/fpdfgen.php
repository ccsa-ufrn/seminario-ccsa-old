<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Fpdfgen {
	
	function __construct() {
		
		// Get FPDF
		include(APPPATH.'/third_party/fpdf/fpdf.php');
        include(APPPATH.'/third_party/fpdf/fpdfi/fpdi.php');
		
	}
	
}