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
        $this->Image('../img/map.jpg',3,45,205);
        $this->Image('../img/letterhead.png',2,5,200);
       
        // Line break
        $this->Ln(30);
    }
 
    // Page footer
    function Footer()
    {
        $this->Image('../img/footer.png',3,260,200);
        //Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        //$this->SetFont('Arial','I',8);
        // Page number
        //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        
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
$pdf->Cell(70,9,"SCHOOL ATTENDANCE CERTIFICATE",0);
$pdf->SetFont('times','',13);
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(10,9,"I, the undersigned ________________________________, Principal of ".$Model->GetSchoolInfo(1)[0]['name'].",",0);
$pdf->Ln();
$pdf->Cell(10,9,"certify that the student whose name appears below, and who is a child of ",0);
$pdf->Ln();
$pdf->Cell(10,9,"Mr. ".$s[0]['father_name'],0);
$pdf->Ln();
$pdf->Cell(10,9,"and Mrs. ".$s[0]['mother_name'],0);
$pdf->Ln();
$year = $Model->GetCurrentYear();
$yearname = $year[0]['start']."/".$year[0]['end'];
$pdf->Cell(10,9,"is a registered student in this institution for the ".$yearname." academic year.",0);
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('times','B',13);
$pdf->Cell(40,9,"Name of student:",0);
$pdf->SetFont('times','',13);
$pdf->Cell(50,9,$s[0]['name'],0);
$pdf->SetFont('times','B',13);
$pdf->Ln();
$pdf->Cell(40,9,"Gender:",0);
$pdf->SetFont('times','',13);
$pdf->Cell(50,9,$s[0]['gender'],0);

$pdf->Ln();
$pdf->SetFont('times','B',13);
$pdf->Cell(40,9,"Date of Birth:",0);
$pdf->SetFont('times','',13);
$date = new DateTime($s[0]['dob']);
$pdf->Cell(25,9,date_format($date, "d - m - Y"),0);
$pdf->Ln();
$pdf->SetFont('times','B',13);
$pdf->Cell(40,9,"Place of Birth:",0);
$pdf->SetFont('times','',13);
$pdf->Cell(25,9,$s[0]['pob'],0);
$pdf->Ln();
$pdf->SetFont('times','B',13);
$pdf->Cell(40,9,"Admission number:",0);
$pdf->SetFont('times','',13);
$pdf->Cell(50,9,$s[0]['adm_num'],0);
$pdf->Ln();
$pdf->SetFont('times','B',13);
$pdf->Cell(40,9,"Class:",0);
$pdf->SetFont('times','',13);
$currentclass_id = $Model->StudentCurrentClass($student_code, $Model->GetCurrentYear()[0]['id'])[0]['class_id'];
$className = $Model->GetAClassName($currentclass_id);
$pdf->Cell(55,9,$className,0);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(10,9,"In testimony thereof, this certificate is issued to serve the purpose for which it is due.",0);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(10,9,"Done at________________, on the_______________                          Principal__________________",0);
$pdf->Output();

}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}
