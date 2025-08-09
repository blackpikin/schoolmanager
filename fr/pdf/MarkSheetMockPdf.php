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
include '../includes/Lang.php';
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
    $year_id = $_GET['year_id'];
    $class_id = $_GET['class_id'];
    $subject = $_GET['subject'];
    $students = $Model->StudentCodesPerYear($class_id, $year_id);
    $result = [];
    $mixed_stds = [];
    if(!empty($students)){
        foreach($students as $student){
            $std = $Model->GetStudent($student['student_code'], $section);
            $mixed_stds[$student['student_code']] = $std[0]['name'];
        }

        asort($mixed_stds);

        $sn = 1;

        foreach($mixed_stds as $code => $student){
            array_push($result, ['sn'=>$sn++,'name'=>$student, 'gender'=>$Model->GetStudent($code, $section)[0]['gender'],'Premock'=>' ', 'Mock'=>' ', 'code'=>$code ]);
        }
    }

    $className = $Model->GetAClassName($class_id);
    if($Model->IsMockable($class_id)){
        $premock = $Model->GetAllWithCriteria('exams', ['sequence'=>'PRE-MOCK']);
        $mock = $Model->GetAllWithCriteria('exams', ['sequence'=>'MOCK']);
//Generate PDF file//
///////////////////
$pdf = new PDF();
//header
    $pdf->AddPage();
//foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(10,5,'PRE-MOCK/MOCK Examinations: '.$subject.' '.$lang[$_SESSION['lang']]["MarksheetFor"].' '.$className." - ".$Model->YearNameDigits($year_id),0);
    $pdf->Ln();
    $pdf->Cell(10,5,'SN',1);
    $pdf->Cell(90,5,$lang[$_SESSION['lang']]["Name"],1);
    $pdf->Cell(20,5,$lang[$_SESSION['lang']]["Gender"],1);
    $pdf->Cell(26,5,'Premock',1);
    $pdf->Cell(26,5,'Mock',1);
    $pdf->SetFont('Arial','',9);
    foreach($result as $row) {
        $pdf->Ln(5);
        foreach($row as $key=> $column){
            if($key == 'sn'){
                $pdf->Cell(10,5,$column,1);
            }elseif ($key == 'name'){
                $pdf->Cell(90,5,$column,1);
            }elseif($key == 'gender'){
                $pdf->Cell(20,5,$column,1);
            }elseif($key == 'Premock'){
                if(!empty($premock)){
                    $pdf->Cell(26,5,$Model->GetStudentsMarksForSubject($year_id, $class_id, $premock[0]['id'], $row['code'], $subject),1);
                }else{
                    $pdf->Cell(26,5,$column,1);
                }
            }elseif($key == 'Mock'){
                if(!empty($mock)){
                    $pdf->Cell(26,5,$Model->GetStudentsMarksForSubject($year_id, $class_id, $mock[0]['id'], $row['code'], $subject),1);
                }else{
                    $pdf->Cell(26,5,$column,1);
                }
            }
        }
    }
    $pdf->Ln();
    $pdf->Ln();
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(10,5,utf8_decode($lang[$_SESSION['lang']]["AnalysesSeq"]),0);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(15,5,'Eval.',1);
    $pdf->Cell(25,5,utf8_decode($lang[$_SESSION['lang']]["HighestMark"]),1);
    $pdf->Cell(25,5,utf8_decode($lang[$_SESSION['lang']]["LowestMark"]),1);
    $pdf->Cell(25,5,$lang[$_SESSION['lang']]["AverageMark"],1);
    $pdf->Cell(10,5,utf8_decode($lang[$_SESSION['lang']]["Sat"]),1);
    $pdf->Cell(15,5,$lang[$_SESSION['lang']]["Passed"],1);
    $pdf->Cell(25,5,$lang[$_SESSION['lang']]["MalesPassed"],1);
    $pdf->Cell(28,5,$lang[$_SESSION['lang']]["FemalesPassed"],1);
    $pdf->Cell(20,5,$lang[$_SESSION['lang']]["PercentPass"],1);
    $pdf->Ln();
    $pdf->SetFont('Arial','',9);
    if(!empty($premock)){
        $sat = count($Model->GetStudentsSatForExam($year_id, $class_id, $premock[0]['id']));
        $passed = $Model->GetStudentsPassedExam($year_id, $class_id, $premock[0]['id'], $subject);
        $percent = '';
        if($sat != 0){
            $percent = round(($passed/$sat)*100, 2);
        }
        
        $pdf->Cell(15,5,'Pre-mock',1);
        $pdf->Cell(25,5,$Model->HighestMark($year_id, $class_id, $premock[0]['id'], $subject),1);
        $pdf->Cell(25,5,$Model->LowestMark($year_id, $class_id, $premock[0]['id'], $subject),1);
        $avMark = $Model->AverageMark($year_id, $class_id, $premock[0]['id'], $subject);
        if(is_numeric($avMark)){
            $pdf->Cell(25,5,round($Model->AverageMark($year_id, $class_id, $premock[0]['id'], $subject), 2),1);
        }else{
            $pdf->Cell(25,5,' ',1);
        }
        
        if($sat != 0){
            $pdf->Cell(10,5,$sat,1);
        }else{
            $pdf->Cell(10,5,'',1);
        }

        if($passed != 0){
            
        }else{
            $pdf->Cell(15,5,'',1);
        }

        $genderPassed = $Model->GetGenderPassedExam($year_id, $class_id, $premock[0]['id'], $subject);
        if ($genderPassed[0] != 0){
            $pdf->Cell(25,5,$genderPassed[0],1);
        }else{
            $pdf->Cell(25,5,'',1);
        }

        if ($genderPassed[1] != 0){
            $pdf->Cell(28,5,$genderPassed[1],1);
        }else{
            $pdf->Cell(28,5,'',1);
        }
        
        $pdf->Cell(20,5,$percent,1);
        $pdf->Ln();
    }else{
        $pdf->Cell(15,5,'Pre-mock',1);
        $pdf->Cell(25,5,'',1);
        $pdf->Cell(25,5,'',1);
        $pdf->Cell(25,5,'',1);
        $pdf->Cell(10,5,'',1);
        $pdf->Cell(15,5,'',1);
        $pdf->Cell(25,5,'',1);
        $pdf->Cell(28,5,'',1);
        $pdf->Cell(20,5,'',1);
        $pdf->Ln();
    }

    if(!empty($mock)){
        $sat = count($Model->GetStudentsSatForExam($year_id, $class_id, $mock[0]['id']));
        $passed = $Model->GetStudentsPassedExam($year_id, $class_id, $mock[0]['id'], $subject);
        $percent = '';
        if($sat != 0){
            $percent = round(($passed/$sat)*100, 2);
        }
        $pdf->Cell(15,5,'T2',1);
        $pdf->Cell(25,5,$Model->HighestMark($year_id, $class_id, $mock[0]['id'], $subject),1);
        $pdf->Cell(25,5,$Model->LowestMark($year_id, $class_id, $mock[0]['id'], $subject),1);
        $avMark = $Model->AverageMark($year_id, $class_id, $mock[0]['id'], $subject);
        if(is_numeric($avMark)){
            $pdf->Cell(25,5,round($Model->AverageMark($year_id, $class_id, $mock[0]['id'], $subject), 2),1);
        }else{
            $pdf->Cell(25,5,' ',1);
        }
        
        if($sat != 0){
            $pdf->Cell(10,5,$sat,1);
        }else{
            $pdf->Cell(10,5,'',1);
        }

        if($passed != 0){
            
        }else{
            $pdf->Cell(15,5,'',1);
        }

        $genderPassed = $Model->GetGenderPassedExam($year_id, $class_id, $mock[0]['id'], $subject);
        if ($genderPassed[0] != 0){
            $pdf->Cell(25,5,$genderPassed[0],1);
        }else{
            $pdf->Cell(25,5,'',1);
        }

        if ($genderPassed[1] != 0){
            $pdf->Cell(28,5,$genderPassed[1],1);
        }else{
            $pdf->Cell(28,5,'',1);
        }
        $pdf->Cell(20,5,$percent,1);
        $pdf->Ln();
    }else{
        $pdf->Cell(15,5,'Mock',1);
        $pdf->Cell(25,5,'',1);
        $pdf->Cell(25,5,'',1);
        $pdf->Cell(25,5,'',1);
        $pdf->Cell(10,5,'',1);
        $pdf->Cell(15,5,'',1);
        $pdf->Cell(25,5,'',1);
        $pdf->Cell(28,5,'',1);
        $pdf->Cell(20,5,'',1);
        $pdf->Ln();
    }
    $pdf->Output();
    }else{
        echo '<h3>This class does not write Pre-mock/Mock</h3>';
    }
}