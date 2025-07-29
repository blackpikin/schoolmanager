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
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}
///////////////////////////////////////////////////////////////////////////////
$year_id = $_GET['year_id'];
$class_id = $_GET['class_id'];
$exam_name = $_GET['exam_name'];
$exam_id = $Model->GetMockExam($year_id, $exam_name)[0]['id'];
$page_title = $exam_name."MASTER SHEET - ".$Model->GetAClassName($class_id)." - ".$Model->GetYearName($year_id);
$class_cycle = $Model->GetAClass($class_id)[0]['cycle'];
if($class_cycle == 'FIRST'){
    $pdf = new PDF();
//header
    $pdf->AddPage();
//foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(30, 7, "", 0);
    $pdf->Ln();
    $pdf->Cell(30, 7, "", 0);
    $pdf->Cell(10,7,$page_title,0);
    $pdf->Ln();
    $pdf->Cell(60,7,'Subject',1);
    $pdf->Cell(20,7,'A',1);
    $pdf->Cell(20,7,'B',1);
    $pdf->Cell(20,7,'C',1);
    $pdf->Cell(20,7,'D',1);
    $pdf->Cell(20,7,'E',1);
    $pdf->Cell(20,7,'U',1);
    $subjects = $Model->ViewClassSubjects($class_id);
    foreach($subjects as $subject){
        $pdf->Ln();
        if(strlen($subject['subject']) > 23){
            $pdf->Cell(60,7,substr($subject['subject'], 0, 23).'.',1);
        }else{
            $pdf->Cell(60,7,$subject['subject'],1);
        }
        
        $pdf->Cell(20,7,$Model->CountOLevelGrade('A', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']),1);
        $pdf->Cell(20,7,$Model->CountOLevelGrade('B', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']),1);
        $pdf->Cell(20,7,$Model->CountOLevelGrade('C', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']),1);
        $pdf->Cell(20,7,$Model->CountOLevelGrade('D', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']),1);
        $pdf->Cell(20,7,$Model->CountOLevelGrade('E', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']),1);
        $pdf->Cell(20,7,$Model->CountOLevelGrade('U', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']),1);
    }
}else{
    $pdf = new PDF();
//header
    $pdf->AddPage();
//foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(30, 7, "", 0);
    $pdf->Ln();
    $pdf->Cell(30, 7, "", 0);
    $pdf->Cell(10,7,$page_title,0);
    $pdf->Ln();
    $pdf->Cell(60,7,'Subject',1);
    $pdf->Cell(20,7,'A',1);
    $pdf->Cell(20,7,'B',1);
    $pdf->Cell(20,7,'C',1);
    $pdf->Cell(20,7,'D',1);
    $pdf->Cell(20,7,'E',1);
    $pdf->Cell(20,7,'O',1);
    $pdf->Cell(20,7,'F',1);
    $subjects = $Model->ViewClassSubjects($class_id);
    foreach($subjects as $subject){
        $pdf->Ln();
        if(strlen($subject['subject']) > 23){
            $pdf->Cell(60,7,substr($subject['subject'], 0, 23).'.',1);
        }else{
            $pdf->Cell(60,7,$subject['subject'],1);
        }
        $pdf->Cell(20,7,$Model->CountALevelGrade('A', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']),1);
        $pdf->Cell(20,7,$Model->CountALevelGrade('B', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']),1);
        $pdf->Cell(20,7,$Model->CountALevelGrade('C', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']),1);
        $pdf->Cell(20,7,$Model->CountALevelGrade('D', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']),1);
        $pdf->Cell(20,7,$Model->CountALevelGrade('E', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']),1);
        $pdf->Cell(20,7,$Model->CountALevelGrade('O', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']),1);
        $pdf->Cell(20,7,$Model->CountALevelGrade('F', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']),1);
    }
}
$pdf->Output();

}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}