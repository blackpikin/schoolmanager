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
$page_title =  $exam_name." MASTER SHEET - ".$Model->GetAClassName($class_id)." - ".$Model->GetYearName($year_id);
$pdf = new PDF();
//header
    $pdf->AddPage('O');
//foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(30, 7, "", 0);
    $pdf->Ln();
    $pdf->Cell(30, 7, "", 0);
    $pdf->Cell(10,7,$page_title,0);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(82,7,'STUDENT',1);
    $subjects = $Model->ViewClassSubjects($class_id);
    foreach($subjects as $subject){
        if($subject['subject'] == 'PURE MATHS WITH STATS'){
            $pdf->Cell(12,7,'PMS',1);
        }else if($subject['subject'] == 'PURE MATHS WITH MECHS'){
            $pdf->Cell(12,7,'PMM',1);
        }else if($subject['subject'] == 'INFORMATION AND COMMUNICATION TECHNOLOGY'){
            $pdf->Cell(12,7,'ICT',1);
        }else if($subject['subject'] == 'RELIGIOUS STUDIES'){
            $pdf->Cell(12,7,'REL',1);
        }else if($subject['subject'] == 'ENGLISH LANGUAGE'){
            $pdf->Cell(12,7,'ENG',1);    
        }else{
            $subj = explode(' ',$subject['subject']);
            if(count($subj) >1){
                $subjectName = substr($subj[0], 0, 1).substr($subj[1], 0, 2);
                $pdf->Cell(12,7,$subjectName,1);  
            }else{
                $pdf->Cell(12,7,substr($subject['subject'], 0, 3),1);  
            }
        }
    }
    $pdf->Cell(18,7,"PAPERS",1);
    $pdf->Cell(18,7,"POINTS",1);

    $class_cycle = $Model->GetAClass($class_id)[0]['cycle'];
$students = $Model->GetStudentsSatForExam($year_id, $class_id, $exam_id);
$pdf->SetFont('Arial','B',9);
foreach($students as $student){
    $papers = 0; $points = 0;
    $pdf->Ln();
    $pdf->Cell(82,7,$Model->GetStudent($student['student_code'], $section)[0]['name'],1);

    foreach($subjects as $subject){
        if($class_cycle == 'FIRST'){
            $grade = $Model->OLGrade($student['student_code'], $year_id, $class_id, $exam_id, $subject['subject'] );
            if($grade == "A"){
                $papers++;
                $points = $points + 3;
            }elseif($grade == "B"){
                $papers++;
                $points = $points + 2;
            }elseif($grade == "C"){
                $papers++;
                $points = $points + 1;
            }
            $pdf->Cell(12,7,$grade,1);
        }else{
            $grade = $Model->ALGrade($student['student_code'], $year_id, $class_id, $exam_id, $subject['subject'] );
            if($grade == "A"){
                $papers++;
                $points = $points + 5;
            }elseif($grade == "B"){
                $papers++;
                $points = $points + 4;
            }elseif($grade == "C"){
                $papers++;
                $points = $points + 3;
            }elseif($grade == "D"){
                $papers++;
                $points = $points + 2;
            }elseif($grade == "E"){
                $papers++;
                $points = $points + 1;
            }
            $pdf->Cell(12,7,$grade,1);
        }
    } 
    $pdf->Cell(18,7,$papers,1);
    $pdf->Cell(18,7,$points,1);
}
$pdf->Output();
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}