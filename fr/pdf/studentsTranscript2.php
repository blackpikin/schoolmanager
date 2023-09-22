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
        $this->Image('../img/letterhead.png',50,2,200);
       
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
//Get all the First cycle classes
//For each class get the subjects for that class
//For each subject get the Totals of the said student
$student_code = $_GET['ref'];
$s = $Model->GetStudent($student_code, $section);
$pdf = new PDF();
//header
$pdf->AddPage('O');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30, 5, "", 0);
$pdf->Ln();
$pdf->Cell(30, 5, "", 0);
$pdf->SetFont('Times','B',14);
$pdf->Cell(70, 5, "", 0);
$pdf->Cell(10,5,"HIGH SCHOOL TRANSCRIPT",0,0);
$pdf->Ln();
$pdf->SetFont('Arial','B',9);
$pdf->Cell(130,5,'NAME: '.$s[0]['name'],0);
$pdf->Cell(30,5,'Gender: '.$s[0]['gender'],0);
$pdf->Cell(40,5,'DOB: '.$s[0]['dob'],0);
$pdf->Cell(40,5,'POB: '.$s[0]['pob'],0);
$pdf->Cell(10,5,'ADMISSION #: '.$s[0]['adm_num'],0);
$pdf->Ln();
$pdf->Cell(40,5,'SUBJECT',1);
$classes = $Model->TranscriptClasses($student_code, 'SECOND');
//Get subjects from Mark Sheet
$subjects = $Model->AllMarkSheetSubjects($student_code);
foreach ($classes as $class){
    $pdf->Cell(47,5,$Model->GetAClassName($class['class_id']).' - '.$Model->YearNameDigits($class['academic_year_id']),1);
}

$pdf->Ln();
$pdf->SetFont('Arial','',6);
$pdf->Cell(40,5,'',1);
for($i=0; $i<count($classes); $i++){
    $pdf->Cell(15,5,'First Term',1);
    $pdf->Cell(17,5,'Second Term',1);
    $pdf->Cell(15,5,'Third Term',1);
}

foreach($subjects as $sub){
    $pdf->Ln();
    $pdf->SetFont('Arial','',8);
    if(strlen($sub['subject']) > 20){
        $pdf->Cell(40,5,substr($sub['subject'], 0, 20),1);
    }else{
        $pdf->Cell(40,5,$sub['subject'],1);
    }
    foreach($classes as $class){
        $pdf->Cell(15,5,$Model->GetTranscriptMark('First', $sub['subject'], $student_code, $class['academic_year_id'], $class['class_id']),1);
        $pdf->Cell(17,5,$Model->GetTranscriptMark('Second', $sub['subject'], $student_code, $class['academic_year_id'], $class['class_id']),1);
        $pdf->Cell(15,5,$Model->GetTranscriptMark('Third', $sub['subject'], $student_code, $class['academic_year_id'], $class['class_id']),1);
    }
}
$pdf->Ln();
$pdf->Cell(40,5,"AVERAGE",1);
foreach($classes as $class){
    $term1_av = $Model->TermAverage($student_code, 'First', $class['academic_year_id'], $class['class_id']);
    $term2_av = $Model->TermAverage($student_code, 'Second', $class['academic_year_id'], $class['class_id']);;
    $term3_av = $Model->TermAverage($student_code, 'Third', $class['academic_year_id'], $class['class_id']);;
    $pdf->Cell(15,5,$term1_av,1);
    $pdf->Cell(17,5,$term2_av,1);
    $pdf->Cell(15,5,$term3_av,1);
}

$pdf->Ln();
$pdf->Cell(40,5,"POSITION",1);
foreach($classes as $class){
    $pdf->Cell(15,5,$Model->GetTranscriptPosition($class['academic_year_id'], $class['class_id'], 'First', $student_code),1);
    $pdf->Cell(17,5,$Model->GetTranscriptPosition($class['academic_year_id'], $class['class_id'], 'Second', $student_code),1);
    $pdf->Cell(15,5,$Model->GetTranscriptPosition($class['academic_year_id'], $class['class_id'], 'Third', $student_code),1);
}


$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(10,5,"Done at________________, on the _______________                                                Principal__________________",0);

$pdf->Output();
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}