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

$page_title = $exam_name." RESULTS - ".$Model->GetAClassName($class_id)." - ".$Model->GetYearName($year_id);

$class_cycle = $Model->GetAClass($class_id)[0]['cycle'];

$exam_id = $Model->GetMockExam($year_id, $exam_name)[0]['id'];

$pdf = new PDF();


$student_codes = $Model->GetStudentsInClass($class_id, $year_id);
foreach($student_codes as $student){
    $marks = $Model-> GetStudentsMarks($year_id, $class_id, $exam_id, $student['student_code']);
    $sat = count($marks);
    $passed = 0;
    foreach($marks as $mark){
        if($class_cycle == "FIRST"){
            if($mark['mark'] >= 10){
                $passed++;
            }
        }else{
            if($mark['mark'] >= 9){
                $passed++;
            }
        }
    }
    $pdf->AddPage();
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',11);

    $s = $Model->GetStudent($student['student_code'],$section);
    
    $pdf->Cell(30, 7, "", 0);
    $pdf->Ln();
    $pdf->Cell(30, 7, "", 0);
    $pdf->Cell(10, 7, $page_title, 0);
    $pdf->Ln();
    $pdf->Cell(80, 7, "", 0);
    $pdf->Cell(10, 7, "RESULT SLIP", 0);
    $pdf->Ln();
    $pdf->Cell(85,7,"Name: ".$s[0]['name'],0);
    $pdf->Cell(60,7,'Date of Birth: '.$s[0]['dob'],0);
    $pdf->Cell(30,7,'Gender: '.$s[0]['gender'],0);
    $pdf->Ln();
    $pdf->Cell(70,7,'Registered: '.$sat,0);
    $pdf->Cell(50,7,'Sat: '.$sat,0);
    $pdf->Cell(30,7,'Passed: '.$passed,0);
    $pdf->Ln();
    $pdf->Ln();
    //$pdf->Cell(90,7,'Subject',1,0,'',true);
    $pdf->Cell(10, 7, "", 0);
    $pdf->Cell(90,7,'Subject',1);
    $pdf->Cell(30,7,'Grade',1);
    $pdf->Cell(50,7,'Remark',1);
    $limits = $Model->Grade();
    foreach($marks as $mark){
        $remark = ""; $decision = "";
        if($class_cycle == "FIRST"){
            if($mark['mark'] <= $limits['OL']['OLUmax']){
                $remark = "U";
                $decision = "FAILED";
            }elseif($mark['mark'] >= $limits['OL']['OLEmin'] && $mark['mark'] <= $limits['OL']['OLEmax']){
                $remark = "E";
                $decision = "FAILED";
            }elseif($mark['mark'] >= $limits['OL']['OLDmin'] && $mark['mark'] <= $limits['OL']['OLDmax']){
                $remark = "D";
                $decision = "FAILED";
            }elseif($mark['mark'] >= $limits['OL']['OLCmin'] && $mark['mark'] <= $limits['OL']['OLCmax']){
                $remark = "C";
                $decision = "PASSED";
            }elseif($mark['mark'] >= $limits['OL']['OLBmin'] && $mark['mark'] <= $limits['OL']['OLBmax']){
                $remark = "B";
                $decision = "PASSED";
            }elseif($mark['mark'] >= $limits['OL']['OLAmin']){
                $remark = "A";
                $decision = "PASSED";
            }
        }else{
            if($mark['mark'] <= $limits['AL']['ALFmax']){
                $remark = "F";
                $decision = "FAILED";
            }elseif($mark['mark'] >= $limits['AL']['ALOmin'] && $mark['mark'] <= $limits['AL']['ALOmax']){
                $remark = "O";
                $decision = "FAILED";
            }elseif($mark['mark'] >= $limits['AL']['ALEmin'] && $mark['mark'] <= $limits['AL']['ALEmax']){
                $remark = "E";
                $decision = "PASSED";
            }elseif($mark['mark'] >= $limits['AL']['ALDmin'] && $mark['mark'] <= $limits['AL']['ALDmax']){
                $remark = "D";
                $decision = "PASSED";
            }elseif($mark['mark'] >= $limits['AL']['ALCmin'] && $mark['mark'] <= $limits['AL']['ALCmax']){
                $remark = "C";
                $decision = "PASSED";
            }elseif($mark['mark'] >= $limits['AL']['ALBmin'] && $mark['mark'] <= $limits['AL']['ALBmax']){
                $remark = "B";
                $decision = "PASSED";
            }elseif($mark['mark'] >= $limits['AL']['ALAmin']){
                $remark = "A";
                $decision = "PASSED";
            }
        }

    $pdf->Ln();
    $pdf->Cell(10, 7, "", 0);
    if(strlen($mark['subject']) > 35){
        $pdf->Cell(90,7,substr($mark['subject'], 0, 35).'.',1);
    }else{
        $pdf->Cell(90,7,$mark['subject'],1);
    }
    $pdf->Cell(30,7,$remark,1);
    $pdf->Cell(50,7,$decision,1);
    }
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Cell(80, 7, "", 0);
    $pdf->Cell(90,7,"Principal",0);
}
$pdf->Output();
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}