<?php
session_start();
if (isset($_SESSION['id'])){
    if (time() - $_SESSION['timer']  > 3400 ){
        session_destroy();
        header('Location: ../');
    }else{
        $_SESSION['timer'] = time();
    }
}
$lng = $_SESSION['lang'];
    $section = 0;
    if($lng == 'fr'){
        $section = 1;
    }
if(isset($_SESSION['username']) && $_SESSION['username'] !== ""){
//include connection file
include "../includes/Model.php";
include "../includes/Lang.php";

$Model = new Model($section);
include_once('../lib/fpdf.php');
 
class PDF extends FPDF
{
// Page header
function Header()
    {
        // Logo
        $this->Image('../img/letterhead.png',2,2,200);
       
        // Line break
        $this->Ln(30);
    }
 
    // Page footer
    function Footer()
    {
        $this->Image('../img/footer.png',2,260,200);
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        
    }
}
///////////////////////////////////////////////////////////////////////////////
$student_code = $_GET['ref'];
$s = $Model->GetStudent($student_code, $section);
$pdf = new PDF();
$pdf->AddPage();
 $pdf->AliasNbPages();
 $pdf->SetFont('times','B',16);
 $pdf->Ln();
 $pdf->Cell(45,7,"",0);
 $pdf->Ln();
 $pdf->Cell(45,7,"",0);
$pdf->Cell(70,7,"TRANSFER CERTIFICATE",0);
$pdf->SetFont('times','',11);
$pdf->Ln();
$pdf->Cell(10,7,"I, the undersigned principal of ".$Model->GetSchoolInfo(1)[0]['name'].", certify that the student ",0);
$pdf->Ln();
$pdf->Cell(10,7,"whose name appears below, and who is a child of Mr. ".$s[0]['father_name']." and Mrs. ".$s[0]['mother_name'],0);
$pdf->Ln();
$year = $Model->GetCurrentYear();
$yearname = $year[0]['start']."/".$year[0]['end'];
$pdf->Cell(10,7,"had been duly transferred from this institution.",0);
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('times','B',11);
$pdf->Cell(40,7,"Name of student:",0);
$pdf->SetFont('times','',11);
$pdf->Cell(50,7,$s[0]['name'],0);
$pdf->SetFont('times','B',11);
$pdf->Ln();
$pdf->Cell(40,7,"Gender:",0);
$pdf->SetFont('times','',11);
$pdf->Cell(50,7,$s[0]['gender'],0);

$pdf->Ln();
$pdf->SetFont('times','B',11);
$pdf->Cell(40,7,"Date of Birth:",0);
$pdf->SetFont('times','',11);
$date = new DateTime($s[0]['dob']);
$pdf->Cell(25,7,date_format($date, "d - m - Y"),0);
$pdf->Ln();
$pdf->SetFont('times','B',11);
$pdf->Cell(40,7,"Admission number:",0);
$pdf->SetFont('times','',11);
$pdf->Cell(50,7,$s[0]['adm_num'],0);
$pdf->Ln();
$pdf->SetFont('times','B',11);
$pdf->Cell(40,7,"Class:",0);
$pdf->SetFont('times','',11);
$currentclass_id = $Model->StudentCurrentClass($student_code, $Model->GetCurrentYear()[0]['id'])[0]['class_id'];
$className = $Model->GetAClassName($currentclass_id);
$pdf->Cell(55,7,$className,0);
$pdf->Ln();
$pdf->SetFont('times','B',11);
$pdf->Cell(40,7,"Academic year:",0);
$pdf->SetFont('times','',11);
$pdf->Cell(55,7,$yearname,0);
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(10,7,"In testimony thereof, this certificate is issued to serve the purpose for which it is due.",0);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(10,7,"Done at________________, on the _______________                                                Principal__________________",0);
$pdf->Output();

}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}