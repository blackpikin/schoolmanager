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
            array_push($result, ['sn'=>$sn++,'name'=>$student, 'gender'=>$Model->GetStudent($code, $section)[0]['gender'],'t1'=>' ', 't2'=>' ', 't3'=>' ', 't4'=>' ', 't5'=>' ', 't6'=>' ', 'code'=>$code ]);
        }
    }

    $className = $Model->GetAClassName($class_id);
    $exams = $Model->GetExamsForYear($year_id, $class_id, $section);
    
//Generate PDF file//
///////////////////
$pdf = new PDF();
//header
    $pdf->AddPage();
//foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(10,5,$subject.' '.$lang[$_SESSION['lang']]["MarksheetFor"].' '.$className." - ".$Model->YearNameDigits($year_id),0);
    $pdf->Ln();
    $pdf->Cell(10,5,'SN',1);
    $pdf->Cell(90,5,$lang[$_SESSION['lang']]["Name"],1);
    $pdf->Cell(20,5,$lang[$_SESSION['lang']]["Gender"],1);
    $pdf->Cell(13,5,'T1',1);
    $pdf->Cell(13,5,'T2',1);
    $pdf->Cell(13,5,'T3',1);
    $pdf->Cell(13,5,'T4',1);
    $pdf->Cell(13,5,'T5',1);
    $pdf->Cell(13,5,'T6',1);
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
            }elseif($key == 't1'){
                if(isset($exams[0])){
                    $pdf->Cell(13,5,$Model->GetStudentsMarksForSubject($year_id, $class_id, $exams[0], $row['code'], $subject),1);
                }else{
                    $pdf->Cell(13,5,$column,1);
                }
            }elseif($key == 't2'){
                if(isset($exams[1])){
                    $pdf->Cell(13,5,$Model->GetStudentsMarksForSubject($year_id, $class_id, $exams[1], $row['code'], $subject),1);
                }else{
                    $pdf->Cell(13,5,$column,1);
                }
            }elseif($key == 't3'){
                if(isset($exams[2])){
                    $pdf->Cell(13,5,$Model->GetStudentsMarksForSubject($year_id, $class_id, $exams[2], $row['code'], $subject),1);
                }else{
                    $pdf->Cell(13,5,$column,1);
                }
            }elseif($key == 't4'){
                if(isset($exams[3])){
                    $pdf->Cell(13,5,$Model->GetStudentsMarksForSubject($year_id, $class_id, $exams[3], $row['code'], $subject),1);
                }else{
                    $pdf->Cell(13,5,$column,1);
                }
            }elseif($key == 't5'){
                if(isset($exams[4])){
                    $pdf->Cell(13,5,$Model->GetStudentsMarksForSubject($year_id, $class_id, $exams[4], $row['code'], $subject),1);
                }else{
                    $pdf->Cell(13,5,$column,1);
                }
            }elseif($key == 't6'){
                if(isset($exams[5])){
                    $pdf->Cell(13,5,$Model->GetStudentsMarksForSubject($year_id, $class_id, $exams[5], $row['code'], $subject),1);
                }else{
                    $pdf->Cell(13,5,$column,1);
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
    if(isset($exams[0])){
        $sat = count($Model->GetStudentsSatForExam($year_id, $class_id, $exams[0]));
        $passed = $Model->GetStudentsPassedExam($year_id, $class_id, $exams[0], $subject);
        $percent = '';
        if($sat != 0){
            $percent = round(($passed/$sat)*100, 2);
        }
        
        $pdf->Cell(15,5,'T1',1);
        $pdf->Cell(25,5,$Model->HighestMark($year_id, $class_id, $exams[0], $subject),1);
        $pdf->Cell(25,5,$Model->LowestMark($year_id, $class_id, $exams[0], $subject),1);
        $avMark = $Model->AverageMark($year_id, $class_id, $exams[0], $subject);
        if(is_numeric($avMark)){
            $pdf->Cell(25,5,round($Model->AverageMark($year_id, $class_id, $exams[0], $subject), 2),1);
        }else{
            $pdf->Cell(25,5,' ',1);
        }
        
        if($sat != 0){
            $pdf->Cell(10,5,$sat,1);
        }else{
            $pdf->Cell(10,5,'',1);
        }

        if($passed != 0){
            $pdf->Cell(15,5,$passed,1);
        }else{
            $pdf->Cell(15,5,'',1);
        }

        $genderPassed = $Model->GetGenderPassedExam($year_id, $class_id, $exams[0], $subject);
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
        $pdf->Cell(15,5,'T1',1);
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

    if(isset($exams[1])){
        $sat = count($Model->GetStudentsSatForExam($year_id, $class_id, $exams[1]));
        $passed = $Model->GetStudentsPassedExam($year_id, $class_id, $exams[1], $subject);
        $percent = '';
        if($sat != 0){
            $percent = round(($passed/$sat)*100, 2);
        }
        $pdf->Cell(15,5,'T2',1);
        $pdf->Cell(25,5,$Model->HighestMark($year_id, $class_id, $exams[1], $subject),1);
        $pdf->Cell(25,5,$Model->LowestMark($year_id, $class_id, $exams[1], $subject),1);
        $avMark = $Model->AverageMark($year_id, $class_id, $exams[1], $subject);
        if(is_numeric($avMark)){
            $pdf->Cell(25,5,round($Model->AverageMark($year_id, $class_id, $exams[1], $subject), 2),1);
        }else{
            $pdf->Cell(25,5,' ',1);
        }
        
        if($sat != 0){
            $pdf->Cell(10,5,$sat,1);
        }else{
            $pdf->Cell(10,5,'',1);
        }

        if($passed != 0){
            $pdf->Cell(15,5,$passed,1);
        }else{
            $pdf->Cell(15,5,'',1);
        }

        $genderPassed = $Model->GetGenderPassedExam($year_id, $class_id, $exams[1], $subject);
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
        $pdf->Cell(15,5,'T2',1);
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

    if(isset($exams[2])){
        $sat = count($Model->GetStudentsSatForExam($year_id, $class_id, $exams[2]));
        $passed = $Model->GetStudentsPassedExam($year_id, $class_id, $exams[2], $subject);
        $percent = '';
        if($sat != 0){
            $percent = round(($passed/$sat)*100, 2);
        }
        $pdf->Cell(15,5,'T3',1);
        $pdf->Cell(25,5,$Model->HighestMark($year_id, $class_id, $exams[2], $subject),1);
        $pdf->Cell(25,5,$Model->LowestMark($year_id, $class_id, $exams[2], $subject),1);
        $avMark = $Model->AverageMark($year_id, $class_id, $exams[2], $subject);
        if(is_numeric($avMark)){
            $pdf->Cell(25,5,round($Model->AverageMark($year_id, $class_id, $exams[2], $subject), 2),1);
        }else{
            $pdf->Cell(25,5,' ',1);
        }
        
        if($sat != 0){
            $pdf->Cell(10,5,$sat,1);
        }else{
            $pdf->Cell(10,5,'',1);
        }

        if($passed != 0){
            $pdf->Cell(15,5,$passed,1);
        }else{
            $pdf->Cell(15,5,'',1);
        }

        $genderPassed = $Model->GetGenderPassedExam($year_id, $class_id, $exams[2], $subject);
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
        $pdf->Cell(15,5,'T3',1);
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

    if(isset($exams[3])){
        $sat = count($Model->GetStudentsSatForExam($year_id, $class_id, $exams[3]));
        $passed = $Model->GetStudentsPassedExam($year_id, $class_id, $exams[3], $subject);
        $percent = '';
        if($sat != 0){
            $percent = round(($passed/$sat)*100, 2);
        }
        $pdf->Cell(15,5,'T4',1);
        $pdf->Cell(25,5,$Model->HighestMark($year_id, $class_id, $exams[3], $subject),1);
        $pdf->Cell(25,5,$Model->LowestMark($year_id, $class_id, $exams[3], $subject),1);
        $avMark = $Model->AverageMark($year_id, $class_id, $exams[3], $subject);
        if(is_numeric($avMark)){
            $pdf->Cell(25,5,round($Model->AverageMark($year_id, $class_id, $exams[3], $subject), 2),1);
        }else{
            $pdf->Cell(25,5,' ',1);
        }
        
        if($sat != 0){
            $pdf->Cell(10,5,$sat,1);
        }else{
            $pdf->Cell(10,5,'',1);
        }

        if($passed != 0){
            $pdf->Cell(15,5,$passed,1);
        }else{
            $pdf->Cell(15,5,'',1);
        }

        $genderPassed = $Model->GetGenderPassedExam($year_id, $class_id, $exams[3], $subject);
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
        $pdf->Cell(15,5,'T4',1);
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

    if(isset($exams[4])){
        $sat = count($Model->GetStudentsSatForExam($year_id, $class_id, $exams[4]));
        $passed = $Model->GetStudentsPassedExam($year_id, $class_id, $exams[4], $subject);
        $percent = '';
        if($sat != 0){
            $percent = round(($passed/$sat)*100, 2);
        }
        $pdf->Cell(15,5,'T5',1);
        $pdf->Cell(25,5,$Model->HighestMark($year_id, $class_id, $exams[4], $subject),1);
        $pdf->Cell(25,5,$Model->LowestMark($year_id, $class_id, $exams[4], $subject),1);
        $avMark = $Model->AverageMark($year_id, $class_id, $exams[4], $subject);
        if(is_numeric($avMark)){
            $pdf->Cell(25,5,round($Model->AverageMark($year_id, $class_id, $exams[4], $subject), 2),1);
        }else{
            $pdf->Cell(25,5,' ',1);
        }
        
        if($sat != 0){
            $pdf->Cell(10,5,$sat,1);
        }else{
            $pdf->Cell(10,5,'',1);
        }

        if($passed != 0){
            $pdf->Cell(15,5,$passed,1);
        }else{
            $pdf->Cell(15,5,'',1);
        }

        $genderPassed = $Model->GetGenderPassedExam($year_id, $class_id, $exams[4], $subject);
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
        $pdf->Cell(15,5,'T5',1);
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

    if(isset($exams[5])){
        $sat = count($Model->GetStudentsSatForExam($year_id, $class_id, $exams[5]));
        $passed = $Model->GetStudentsPassedExam($year_id, $class_id, $exams[5], $subject);
        $percent = '';
        if($sat != 0){
            $percent = round(($passed/$sat)*100, 2);
        }
        $pdf->Cell(15,5,'T6',1);
        $pdf->Cell(25,5,$Model->HighestMark($year_id, $class_id, $exams[5], $subject),1);
        $pdf->Cell(25,5,$Model->LowestMark($year_id, $class_id, $exams[5], $subject),1);
        $avMark = $Model->AverageMark($year_id, $class_id, $exams[5], $subject);
        if(is_numeric($avMark)){
            $pdf->Cell(25,5,round($Model->AverageMark($year_id, $class_id, $exams[5], $subject), 2),1);
        }else{
            $pdf->Cell(25,5,' ',1);
        }
        
        if($sat != 0){
            $pdf->Cell(10,5,$sat,1);
        }else{
            $pdf->Cell(10,5,'',1);
        }

        if($passed != 0){
            $pdf->Cell(15,5,$passed,1);
        }else{
            $pdf->Cell(15,5,'',1);
        }

        $genderPassed = $Model->GetGenderPassedExam($year_id, $class_id, $exams[5], $subject);
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
        $pdf->Cell(15,5,'T6',1);
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
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}

?>
