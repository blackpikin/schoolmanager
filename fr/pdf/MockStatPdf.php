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
$page_title = $exam_name." STATISTICS - ".$Model->GetYearName($year_id);
$classes = $Model->GetMockableClasses($section);

if(!empty($classes)){
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
    $pdf->Cell(60,7,'Class',1);
    $pdf->Cell(30,7,'On roll',1);
    $pdf->Cell(10,7,'Sat',1);
    $pdf->Cell(30,7,'Passed',1);
    $pdf->Cell(40,7,'Percentage pass',1);
    

    foreach($classes as $class){
        $pdf->Ln();
        $pdf->Cell(60,7,$Model->GetAClassName($class['id']),1);
        $pdf->Cell(30,7,count($Model->GetStudentsInClass($class['id'], $year_id)),1);
        $sat = 0;
                $sat = count($Model->GetStudentsSatForExam($year_id, $class['id'], $exam_id));
        $pdf->Cell(10,7,$sat,1);
        $pass = 0;
                if ($Model->GetAClass($class['id'])[0]['cycle'] == "FIRST"){
                    $students = $Model->GetStudentsSatForExam($year_id, $class['id'], $exam_id);
                    foreach($students as $student){
                        $papers = $Model->GetStudentsPassPapers($year_id, $class['id'], $exam_id,$exam_name, $student['student_code']);
                        if($papers >= 4){
                            $pass++;
                        }
                    }
                }else{
                    $students = $Model->GetStudentsSatForExam($year_id, $class['id'], $exam_id);
                    foreach($students as $student){
                        $papers = $Model->GetStudentsPassPapers($year_id, $class['id'], $exam_id, $exam_name, $student['student_code']);
                        if($papers >= 2){
                            $pass++;
                        }
                    }
                }
        $pdf->Cell(30,7,$pass,1);
        if($sat > 0){
            $Percent_pass = round(($pass/$sat)*100, 2);
            $pdf->Cell(40,7,$Percent_pass,1);
        }else{
            $pdf->Cell(40,7,'0.00',1);
        }
    }
}
$pdf->Output();
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}