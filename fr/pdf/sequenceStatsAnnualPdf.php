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

function SequencePass(array $students_in_subject, object $Model){
    $lng = $_SESSION['lang'];
    $section = 0;
    if($lng == 'fr'){
        $section = 1;
    }
    //Get those who passed
    $passed = 0; $failed = 0;
    $sat = count($students_in_subject);
    $male_pass = 0; $female_pass = 0;
    foreach ($students_in_subject as $student){
        if ($student['mark']> 10){
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

    return [$percentage_pass, $percentage_fail];

}

$year_id = $_GET['year_id']; //The academic year Id
$subject = $_GET['subject']; // The name of the subject
$t1_id = $Model->GetAllWithCriteria('exams', ['term'=>'First', 'academic_year'=>$year_id, 'sequence'=>'ONE', 'section'=>$section]);
$t2_id = $Model->GetAllWithCriteria('exams', ['term'=>'First', 'academic_year'=>$year_id, 'sequence'=>'TWO', 'section'=>$section]);
$t3_id = $Model->GetAllWithCriteria('exams', ['term'=>'Second', 'academic_year'=>$year_id, 'sequence'=>'ONE', 'section'=>$section]);
$t4_id = $Model->GetAllWithCriteria('exams', ['term'=>'Second', 'academic_year'=>$year_id, 'sequence'=>'TWO', 'section'=>$section]);
$t5_id = $Model->GetAllWithCriteria('exams', ['term'=>'Third', 'academic_year'=>$year_id, 'sequence'=>'ONE', 'section'=>$section]);
$t6_id = $Model->GetAllWithCriteria('exams', ['term'=>'Third', 'academic_year'=>$year_id, 'sequence'=>'TWO', 'section'=>$section]);
$classes = $Model->GetAllClasses($section);
//Generate PDF file//
///////////////////
$pdf = new PDF();
//header
    $pdf->AddPage();
//foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',9);
    
    $pdf->Cell(10,7,$Model->YearNameDigits($year_id)." - ANNUAL SEQUENTIAL STATISTICS (%PASS)", 0);
    $pdf->Ln();
    $pdf->Cell(10,7,$lang[$_SESSION['lang']]["SubjectStats"].' - '.$subject,0);
    $pdf->Ln();
    $pdf->Cell(20,7,$lang[$_SESSION['lang']]["Teacher"],1);
    $pdf->Cell(48,7,$lang[$_SESSION['lang']]["Class"],1);
    $pdf->Cell(15,7,$lang[$_SESSION['lang']]["Onroll"],1);
    $pdf->Cell(10,7,'M',1);
    $pdf->Cell(10,7,'F',1);
    $pdf->Cell(10,7,'T1(%)',1);
    $pdf->Cell(10,7,'T2(%)',1);
    $pdf->Cell(10,7,'T3(%)',1);
    $pdf->Cell(10,7,'T4(%)',1);
    $pdf->Cell(10,7,'T5(%)',1);
    $pdf->Cell(10,7,'T6(%)',1);
    $pdf->Cell(25,7,$lang[$_SESSION['lang']]["Average"].'(%)',1);

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

        if (isset($t1_id[0]['id'])){
            $p1 = SequencePass($Model->GetSubjectInMarkSheet($year_id, $class['id'], $t1_id[0]['id'], $subject), $Model)[0];
        }else{
            $p1 =0;
        }

        if(isset($t2_id[0]['id'])){
            $p2 = SequencePass($Model->GetSubjectInMarkSheet($year_id, $class['id'], $t2_id[0]['id'], $subject), $Model)[0];
        }else{
            $p2 = 0;
        }

        if (isset($t3_id[0]['id'])){
            $p3 = SequencePass($Model->GetSubjectInMarkSheet($year_id, $class['id'], $t3_id[0]['id'], $subject), $Model)[0];
        }else{
            $p3 = 0;
        }

        if (isset($t4_id[0]['id'])){
            $p4 = SequencePass($Model->GetSubjectInMarkSheet($year_id, $class['id'], $t4_id[0]['id'], $subject), $Model)[0];
        }else{
            $p4 = 0;
        }
        
        if (isset($t5_id[0]['id'])){
            $p5 = SequencePass($Model->GetSubjectInMarkSheet($year_id, $class['id'], $t5_id[0]['id'], $subject), $Model)[0];
        }else{
            $p5 = 0;
        }
        
        if (isset($t6_id[0]['id'])){
            $p6 = SequencePass($Model->GetSubjectInMarkSheet($year_id, $class['id'], $t6_id[0]['id'], $subject), $Model)[0];
        }else{
            $p6 =0;
        }

        $annual = round(($p1 + $p2 + $p3 + $p4 + $p5 + $p6)/6, 2);

        $teacher_id = $Model->GetSubjectTeacher($subject, $class['id'], $year_id);
        $pdf->SetFont('Arial','',8);
        $pdf->Ln();
        $pdf->Cell(20,7,$Model->GetStaffName($teacher_id),1);
        $pdf->Cell(48,7,$Model->GetAClassName($class['id']),1);
        $pdf->Cell(15,7,$onroll,1);
        $pdf->Cell(10,7,$males,1);
        $pdf->Cell(10,7,$females,1);
        $pdf->Cell(10,7,$p1,1);
        $pdf->Cell(10,7,$p2,1);
        $pdf->Cell(10,7,$p3,1);
        $pdf->Cell(10,7,$p4,1);
        $pdf->Cell(10,7,$p5,1);
        $pdf->Cell(10,7,$p6,1);
        $pdf->Cell(25,7,$annual,1);
    }
    $pdf->Output();
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}