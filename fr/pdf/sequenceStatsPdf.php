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
 
    
$year_id = $_GET['year_id']; //The academic year Id
$subject = $_GET['subject']; // The name of the subject
$exam_id = $_GET['exam_id']; //The exam id
//Get all classes
$classes = $Model->GetAllClasses($section);

//Generate PDF file//
///////////////////
$pdf = new PDF();
//header
    $pdf->AddPage();
//foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',9);
    
    $pdf->Cell(10,7,$Model->YearNameDigits($year_id)." - ".$lang[$_SESSION['lang']][$Model->GetTermName($exam_id)]." - EVALUATION ".$lang[$_SESSION['lang']][$Model->GetSequenceName($exam_id)], 0);
    $pdf->Ln();
    $pdf->Cell(10,7,$lang[$_SESSION['lang']]["SubjectStats"].' - '.$subject,0);
    $pdf->Ln();
    $pdf->Cell(20,7,$lang[$_SESSION['lang']]["Teacher"],1);
    $pdf->Cell(48,7,$lang[$_SESSION['lang']]["Class"],1);
    $pdf->Cell(15,7,$lang[$_SESSION['lang']]["Onroll"],1);
    $pdf->Cell(10,7,'M',1);
    $pdf->Cell(10,7,'F',1);
    $pdf->Cell(10,7,$lang[$_SESSION['lang']]["Sat"],1);
    $pdf->Cell(18,7,$lang[$_SESSION['lang']]["MalesPassed"],1);
    $pdf->Cell(18,7,$lang[$_SESSION['lang']]["FemalesPassed"],1);
    $pdf->Cell(20,7,'Total',1);
    $pdf->Cell(25,7,$lang[$_SESSION['lang']]["PercentPass"],1);

    foreach ($classes as $class){
        $students = $Model->StudentCodesPerYear($class['id'], $year_id);
        $onroll = count($students); //Students in the class
        $males = 0; $females = 0; 
    
        //Get males and females
        foreach($students as $student){
            if($Model->GetStudent($student['student_code'], $section)[0]['gender'] == 'M'){
                $males++;
            }else{
                $females++;
            }
        }
    
        //Get those who passed
        $passed = 0; $failed = 0;
        $students_in_subject = $Model->GetSubjectInMarkSheet($year_id, $class['id'], $exam_id, $subject);
        $sat = count($students_in_subject);
        $male_pass = 0; $female_pass = 0;
        foreach ($students_in_subject as $student){
            if ($student['mark'] >= 10){
                $passed++;
                if($Model->GetStudent($student['student_code'], $section)[0]['gender'] == 'M'){
                    $male_pass++;
                }else{
                    $female_pass++;
                }
            }else{
                $failed++;
            }
        }
    
        if ($sat != 0){
            $percentage_pass = round(($passed/$sat)*100, 2);
            $percentage_fail = round(($failed/$sat)*100, 2);
        }else{
            $percentage_pass = 0;
            $percentage_fail = 0;
        }
    
        $teacher_id = $Model->GetSubjectTeacher($subject, $class['id'], $year_id);
        $pdf->SetFont('Arial','',8);
        $pdf->Ln();
        $pdf->Cell(20,7,$Model->GetStaffName($teacher_id),1);
        $pdf->Cell(48,7,$Model->GetAClassName($class['id']),1);
        $pdf->Cell(15,7,$onroll,1);
        $pdf->Cell(10,7,$males,1);
        $pdf->Cell(10,7,$females,1);
        $pdf->Cell(10,7,$sat,1);
        $pdf->Cell(18,7,$male_pass,1);
        $pdf->Cell(18,7,$female_pass,1);
        $pdf->Cell(20,7,$passed,1);
        $pdf->Cell(25,7,$percentage_pass,1);
    }
    $pdf->Output();
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}
?>