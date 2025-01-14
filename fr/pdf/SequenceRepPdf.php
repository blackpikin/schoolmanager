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
$report_style = $Model->GetAllWithCriteria('report_templates', ['selected' => 1])[0]['template'];
class PDF extends FPDF
{

// Page header
function Header()
    {
        
        //watermark
        $this->Image('../img/pagebkg.png',-10,-10,250,250);
        // Logo
        $this->Image('../img/letterhead.png',2,2,200);
        // Line break
        $this->Ln(30);
    }
 
    // Page footer
    function Footer()
    {
        
        $this->Image('../img/footer-Blue.png',0,240,220);
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        //$this->SetFont('Arial','I',8);
        // Page number
        //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        
    }
}
///////////////////////////////////////////////////////////////////////////////
$year_id = $_GET['year_id'];
$class_id = $_GET['class_id'];
$exam_id = $_GET['exam_id'];
$eval1 = 1; $eval2 = 2; $sequence = '';
//Get averages
$means = $Model->GetAverages($exam_id, $class_id, $year_id);
$pass_means = $Model->GetAllWithCriteria('computed_averages', ['exam_id' => $exam_id, 'class_id' => $class_id, 'year_id' => $year_id], 'AND average >= 10');
$succ = round((count($pass_means)/count($means))* 100, 2);
$general_coef = 0;
$general_total = 0;
$subject_groups = [];

$langs = $Model->Languages();
$science = $Model->Sciences();
$art = $Model->Arts();
$others = $Model->OtherSubjects();

$hasSci = false;
$hasArt = false;
$hasLang = false;
$hasOther = false;
$class_subjects = $Model->GetAllWithCriteria('subjects',['class_name' => $class_id]);

foreach ($class_subjects as $s){
    if(in_array($s['subject'], $langs)){
        $hasLang = true;
    }
}

foreach ($class_subjects as $s){
    if(in_array($s['subject'], $science)){
        $hasSci = true;
    }
}

foreach ($class_subjects as $s){
    if(in_array($s['subject'], $art)){
        $hasArt = true;
    }
}

foreach ($class_subjects as $s){
    if(in_array($s['subject'], $others)){
        $hasOther = true;
    }
}

$group_index = ['ZeroGroupSubs'=>0,'FirstGroupSubs'=>1, 'SecondGroupSubs'=>2, 'ThirdGroupSubs'=>3];
//Get subject groups
    $first_group = $Model->GetAllWithCriteria('subjects', ['class_name' => $class_id, 'rep_group'=>1,'section' => $section]);
    $second_group = $Model->GetAllWithCriteria('subjects', ['class_name' => $class_id, 'rep_group'=>2,'section' => $section]);
    $third_group = $Model->GetAllWithCriteria('subjects', ['class_name' => $class_id, 'rep_group'=>3,'section' => $section]);
    $zero_group = $Model->GetAllWithCriteria('subjects', ['class_name' => $class_id, 'rep_group'=>0,'section' => $section]);

    $subject_groups['ZeroGroupSubs'] = $zero_group;
    $subject_groups['FirstGroupSubs'] = $first_group;
    $subject_groups['SecondGroupSubs'] = $second_group;
    $subject_groups['ThirdGroupSubs'] = $third_group;


if(!empty($means)){
    if($report_style == 'british') {
        $pdf = new PDF();
        foreach($means as $pos => $stud_av){
            $s = $Model->GetStudent($stud_av['student'], $section);
            $general_coef = 0;
            $general_total = 0;
            $pdf->AddPage();
            $pdf->AliasNbPages();
            $pdf->SetFont('Arial','B',8);
            $pdf->SetFillColor(0,0,128);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(60, 5, "", 0);
            
            if($stud_av['term'] == 'FIRST TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE ONE'){
                $pdf->Cell(30,5,$Model->YearNameDigits($year_id)." ".$stud_av['term']." - ".$lang[$_SESSION['lang']]["FirstSeqEval"] ,0);
                $eval1 = 1; $eval2 = 2; $sequence = '1';
            }elseif($stud_av['term'] == 'FIRST TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE TWO'){
                $pdf->Cell(30,5,$Model->YearNameDigits($year_id)." ".$stud_av['term']." - ".$lang[$_SESSION['lang']]["SecondSeqEval"] ,0);
                $eval1 = 1; $eval2 = 2; $sequence = '2';
            }elseif($stud_av['term'] == 'SECOND TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE ONE'){
                $pdf->Cell(30,5,$Model->YearNameDigits($year_id)." ".$stud_av['term']." - ".$lang[$_SESSION['lang']]["ThirdSeqEval"] ,0);
                $eval1 = 3; $eval2 = 4; $sequence = '3';
            }elseif($stud_av['term'] == 'SECOND TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE TWO'){
                $pdf->Cell(30,5,$Model->YearNameDigits($year_id)." ".$stud_av['term']." - ".$lang[$_SESSION['lang']]["FourthSeqEval"] ,0);
                $eval1 = 3; $eval2 = 4;  $sequence = '4';
            }elseif($stud_av['term'] == 'THIRD TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE ONE'){
                $pdf->Cell(30,5,$Model->YearNameDigits($year_id)." ".$stud_av['term']." - ".$lang[$_SESSION['lang']]["FifthSeqEval"] ,0);
                $eval1 = 5; $eval2 = 6; $sequence = '5';
            }elseif($stud_av['term'] == 'THIRD TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE TWO'){
                $pdf->Cell(30,5,$Model->YearNameDigits($year_id)." ".$stud_av['term']." - ".$lang[$_SESSION['lang']]["SixthSeqEval"] ,0);
                $eval1 = 5; $eval2 = 6; $sequence = '6';
            }
            
            //Student information
            $pdf->Ln();
            $pdf->Cell(110,4,$lang[$_SESSION['lang']]["Name"].': '.$s[0]['name'],0);
            $pdf->Cell(50,4,$lang[$_SESSION['lang']]["Class"].': '.$Model->GetAClassName($class_id),0);
            if($s[0]['picture'] != ""){
                $data = base64_decode($s[0]['picture']);
                $file = "../img/students/" . $s[0]["student_code"] . '.'.$s[0]["picture_ext"];
                //$success = file_put_contents($file, $data);
                $pdf->Image($file,170,40,25, 25);
            }          
            $pdf->Ln();
            $pdf->Cell(110,4,$lang[$_SESSION['lang']]["Gender"].': '.$s[0]['gender'],0);
            $pdf->Cell(50,4,$lang[$_SESSION['lang']]["Onroll"].': '.count($means),0);
            $pdf->Ln();
            $pdf->Cell(110,4,$lang[$_SESSION['lang']]["DOB"].': '.$s[0]['dob'].' at '.$s[0]['pob'],0);
            $pdf->Cell(50,4,$lang[$_SESSION['lang']]["Repeater"].': No',0);
            $pdf->Ln();
            $pdf->Cell(90,4,$lang[$_SESSION['lang']]["AdmissionNum"].': '.$s[0]['adm_num'],0);
            $pdf->Ln();
            $pdf->Cell(90,4,$lang[$_SESSION['lang']]["Classmaster"].': '.$Model->GetAllWithCriteria('classes', ['id'=>$class_id])[0]['cm'],0);
            $pdf->Ln(10);
            //End student information

            //Main report table header
            $pdf->Cell(60,5,$lang[$_SESSION['lang']]["Subject"],0,0,'',false);
            $pdf->Cell(10,5,'Coef',0,0,'',false);
            $pdf->Cell(10,5,'Eval'.$eval1,0,0,'',false);
            $pdf->Cell(10,5,'Eval'.$eval2,0,0,'',false);
            $pdf->Cell(10,5,$lang[$_SESSION['lang']]["avg"],0,0,'',false);
            $pdf->Cell(10,5,'Total',0,0,'',false);
            $pdf->Cell(9,5,$lang[$_SESSION['lang']]["Rank"],0,0,'', false);
            $pdf->Cell(16,5,'Remarks',0,0,'', false);
            $pdf->Cell(60,5,'Class performance',0,0,'C', false);
            $pdf->Ln();
            //End main table header

            if($hasLang){
                //Print languages
            $pdf->Cell(195,5,'LANGUAGES',0,0,'',false);
            $pdf->Ln();
            $marks = $Model->GetStudentsMarks($year_id, $class_id, $exam_id, $stud_av['student']);
            $total_coef = 0;
            $total_mark = 0;
            foreach($marks as $mark){
                $sub_total = $Model->GetStudentTotals($exam_id, $class_id, $year_id, $stud_av['student'], $mark['subject']);
                    if(!empty($sub_total)){
                    if(in_array($mark['subject'],$langs)){
                        $coef = $Model->GetCoefficient($mark['subject'], $class_id);
                        $total_coef += $coef;
                        $total_mark += $sub_total['total'];
                        $general_coef += $coef;
                        $general_total += $sub_total['total'];
                        if(strlen($mark['subject']) > 30){
                            $pdf->Cell(60,5,substr($mark['subject'], 0, 30),1);
                        }else{
                            $pdf->Cell(60,5,$mark['subject'],1);
                        }
                        $pdf->Cell(10,5,$coef,1,0,'',false);
                        if($sequence == '1' || $sequence == '3' || $sequence == '5'){
                            if($mark['mark'] < 10){
                                $pdf->SetTextColor(128,0,0);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                                $pdf->Cell(10,5,'',1,0,'',false);
                            }elseif($mark['mark'] >= 17){
                                $pdf->SetTextColor(0,128,0);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                                $pdf->Cell(10,5,'',1,0,'',false);
                            }else{
                                $pdf->SetTextColor(0,0,0);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                                $pdf->Cell(10,5,'',1,0,'',false);
                            }   
                        }else{
                            if($mark['mark'] < 10){
                                $pdf->SetTextColor(128,0,0);
                                $pdf->Cell(10,5,'',1,0,'',false);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                            }elseif($mark['mark'] >= 17){
                                $pdf->SetTextColor(0,128,0);
                                $pdf->Cell(10,5,'',1,0,'',false);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                            }else{
                                $pdf->SetTextColor(0,0,0);
                                $pdf->Cell(10,5,'',1,0,'',false);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                            }   
                        }
                        if($mark['mark'] < 10){
                            $pdf->SetTextColor(128,0,0);
                            $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                        }elseif($mark['mark'] >= 17){
                            $pdf->SetTextColor(0,128,0);
                            $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                        }else{
                            $pdf->SetTextColor(0,0,0);
                            $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                        }
                        $pdf->SetTextColor(0,0,0);
                        $pdf->Cell(10,5,$sub_total['total'],1,0,'',false);
                        $pdf->Cell(9,5,$sub_total['rank'],1,0,'', false);
                        $pdf->Cell(16,5,$sub_total['remark'],1,0,'', false);
                        $pdf->Cell(60,5,'',1,0,'', false);
                        $pdf->Ln();
                    }  
                }
            }
            $pdf->Cell(60,5,'Summary',0,0,'C',false);
            $pdf->Cell(10,5,$total_coef,0,0,'',false);
            $pdf->Cell(10,5,'',0,0,'',false);
            $pdf->Cell(10,5,'',0,0,'',false);
            $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
            $pdf->Cell(10,5,$total_mark,0,0,'',false);
            $pdf->Cell(9,5,'',0,0,'', false);
            $pdf->Cell(16,5,'',0,0,'', false);
            $pdf->Cell(60,5,'',0,0,'C', false);
            $pdf->Ln();
            //End print languages
            }

             if($hasSci){
                 //Print Sciences
              $pdf->Cell(195,5,'SCIENCES',0,0,'',false);
              $pdf->Ln();
              $marks = $Model->GetStudentsMarks($year_id, $class_id, $exam_id, $stud_av['student']);
              $total_coef = 0;
              $total_mark = 0;
              foreach($marks as $mark){
                  $sub_total = $Model->GetStudentTotals($exam_id, $class_id, $year_id, $stud_av['student'], $mark['subject']);
                      if(!empty($sub_total)){ 
                      if(in_array($mark['subject'],$science)){
                        $coef = $Model->GetCoefficient($mark['subject'], $class_id);
                      $total_coef += $coef;
                      $total_mark += $sub_total['total'];
                      $general_coef += $coef;
                        $general_total += $sub_total['total'];
                          if(strlen($mark['subject']) > 30){
                              $pdf->Cell(60,5,substr($mark['subject'], 0, 30),1);
                          }else{
                              $pdf->Cell(60,5,$mark['subject'],1);
                          }
                          $pdf->Cell(10,5,$coef,1,0,'',false);
                          if($sequence == '1' || $sequence == '3' || $sequence == '5'){
                            if($mark['mark'] < 10){
                                $pdf->SetTextColor(128,0,0);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                                $pdf->Cell(10,5,'',1,0,'',false);
                            }elseif($mark['mark'] >= 17){
                                $pdf->SetTextColor(0,128,0);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                                $pdf->Cell(10,5,'',1,0,'',false);
                            }else{
                                $pdf->SetTextColor(0,0,0);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                                $pdf->Cell(10,5,'',1,0,'',false);
                            }   
                        }else{
                            if($mark['mark'] < 10){
                                $pdf->SetTextColor(128,0,0);
                                $pdf->Cell(10,5,'',1,0,'',false);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                            }elseif($mark['mark'] >= 17){
                                $pdf->SetTextColor(0,128,0);
                                $pdf->Cell(10,5,'',1,0,'',false);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                            }else{
                                $pdf->SetTextColor(0,0,0);
                                $pdf->Cell(10,5,'',1,0,'',false);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                            }   
                        }
                        if($mark['mark'] < 10){
                            $pdf->SetTextColor(128,0,0);
                            $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                        }elseif($mark['mark'] >= 17){
                            $pdf->SetTextColor(0,128,0);
                            $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                        }else{
                            $pdf->SetTextColor(0,0,0);
                            $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                        }
                        $pdf->SetTextColor(0,0,0);
                          $pdf->Cell(10,5,$sub_total['total'],1,0,'',false);
                          $pdf->Cell(9,5,$sub_total['rank'],1,0,'', false);
                          $pdf->Cell(16,5,$sub_total['remark'],1,0,'', false);
                          $pdf->Cell(60,5,'',1,0,'', false);
                          $pdf->Ln();
                      }  
                  }
              }
              $pdf->Cell(60,5,'Summary',0,0,'C',false);
              $pdf->Cell(10,5,$total_coef,0,0,'',false);
            $pdf->Cell(10,5,'',0,0,'',false);
            $pdf->Cell(10,5,'',0,0,'',false);
            $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
            $pdf->Cell(10,5,$total_mark,0,0,'',false);
              $pdf->Cell(9,5,'',0,0,'', false);
              $pdf->Cell(16,5,'',0,0,'', false);
              $pdf->Cell(60,5,'',0,0,'C', false);
              $pdf->Ln();
              //End Print sciences
             }

               if($hasArt){
                 //Print Arts
            $pdf->Cell(195,5,'ARTS',0,0,'',false);
            $pdf->Ln();
            $marks = $Model->GetStudentsMarks($year_id, $class_id, $exam_id, $stud_av['student']);
            $total_coef = 0;
            $total_mark = 0;
            foreach($marks as $mark){
                $sub_total = $Model->GetStudentTotals($exam_id, $class_id, $year_id, $stud_av['student'], $mark['subject']);
                    if(!empty($sub_total)){
                    if(in_array($mark['subject'],$art)){
                        $coef = $Model->GetCoefficient($mark['subject'], $class_id);
                        $total_coef += $coef;
                        $total_mark += $sub_total['total'];
                        $general_coef += $coef;
                        $general_total += $sub_total['total'];
                        if(strlen($mark['subject']) > 30){
                            $pdf->Cell(60,5,substr($mark['subject'], 0, 30),1);
                        }else{
                            $pdf->Cell(60,5,$mark['subject'],1);
                        }
                        $pdf->Cell(10,5,$coef,1,0,'',false);
                        if($sequence == '1' || $sequence == '3' || $sequence == '5'){
                            if($mark['mark'] < 10){
                                $pdf->SetTextColor(128,0,0);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                                $pdf->Cell(10,5,'',1,0,'',false);
                            }elseif($mark['mark'] >= 17){
                                $pdf->SetTextColor(0,128,0);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                                $pdf->Cell(10,5,'',1,0,'',false);
                            }else{
                                $pdf->SetTextColor(0,0,0);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                                $pdf->Cell(10,5,'',1,0,'',false);
                            }   
                        }else{
                            if($mark['mark'] < 10){
                                $pdf->SetTextColor(128,0,0);
                                $pdf->Cell(10,5,'',1,0,'',false);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                            }elseif($mark['mark'] >= 17){
                                $pdf->SetTextColor(0,128,0);
                                $pdf->Cell(10,5,'',1,0,'',false);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                            }else{
                                $pdf->SetTextColor(0,0,0);
                                $pdf->Cell(10,5,'',1,0,'',false);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                            }   
                        }
                        if($mark['mark'] < 10){
                            $pdf->SetTextColor(128,0,0);
                            $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                        }elseif($mark['mark'] >= 17){
                            $pdf->SetTextColor(0,128,0);
                            $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                        }else{
                            $pdf->SetTextColor(0,0,0);
                            $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                        }
                        $pdf->SetTextColor(0,0,0);
                          $pdf->Cell(10,5,$sub_total['total'],1,0,'',false);
                        $pdf->Cell(9,5,$sub_total['rank'],1,0,'', false);
                        $pdf->Cell(16,5,$sub_total['remark'],1,0,'', false);
                        $pdf->Cell(60,5,'',1,0,'', false);
                        $pdf->Ln();
                    }  
                }
            }
            $pdf->Cell(60,5,'Summary',0,0,'C',false);
            $pdf->Cell(10,5,$total_coef,0,0,'',false);
            $pdf->Cell(10,5,'',0,0,'',false);
            $pdf->Cell(10,5,'',0,0,'',false);
            $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
            $pdf->Cell(10,5,$total_mark,0,0,'',false);
            $pdf->Cell(9,5,'',0,0,'', false);
            $pdf->Cell(16,5,'',0,0,'', false);
            $pdf->Cell(60,5,'',0,0,'C', false);
            $pdf->Ln();
            // End print Arts
               }

              if($hasOther){
                //Print Others
              $pdf->Cell(195,5,'OTHERS',0,0,'',false);
              $pdf->Ln();
              $marks = $Model->GetStudentsMarks($year_id, $class_id, $exam_id, $stud_av['student']);
              $total_coef = 0;
              $total_mark = 0;
              foreach($marks as $mark){
                  $sub_total = $Model->GetStudentTotals($exam_id, $class_id, $year_id, $stud_av['student'], $mark['subject']);
                      if(!empty($sub_total)){
                      if(in_array($mark['subject'],$others)){
                        $coef = $Model->GetCoefficient($mark['subject'], $class_id);
                        $total_coef += $coef;
                        $total_mark += $sub_total['total'];
                        $general_coef += $coef;
                        $general_total += $sub_total['total'];
                          if(strlen($mark['subject']) > 30){
                              $pdf->Cell(60,5,substr($mark['subject'], 0, 30),1);
                          }else{
                              $pdf->Cell(60,5,$mark['subject'],1);
                          }
                          $pdf->Cell(10,5,$coef,1,0,'',false);
                          if($sequence == '1' || $sequence == '3' || $sequence == '5'){
                            if($mark['mark'] < 10){
                                $pdf->SetTextColor(128,0,0);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                                $pdf->Cell(10,5,'',1,0,'',false);
                            }elseif($mark['mark'] >= 17){
                                $pdf->SetTextColor(0,128,0);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                                $pdf->Cell(10,5,'',1,0,'',false);
                            }else{
                                $pdf->SetTextColor(0,0,0);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                                $pdf->Cell(10,5,'',1,0,'',false);
                            }   
                        }else{
                            if($mark['mark'] < 10){
                                $pdf->SetTextColor(128,0,0);
                                $pdf->Cell(10,5,'',1,0,'',false);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                            }elseif($mark['mark'] >= 17){
                                $pdf->SetTextColor(0,128,0);
                                $pdf->Cell(10,5,'',1,0,'',false);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                            }else{
                                $pdf->SetTextColor(0,0,0);
                                $pdf->Cell(10,5,'',1,0,'',false);
                                $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                            }   
                        }
                        if($mark['mark'] < 10){
                            $pdf->SetTextColor(128,0,0);
                            $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                        }elseif($mark['mark'] >= 17){
                            $pdf->SetTextColor(0,128,0);
                            $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                        }else{
                            $pdf->SetTextColor(0,0,0);
                            $pdf->Cell(10,5,$mark['mark'],1,0,'',false);
                        }
                        $pdf->SetTextColor(0,0,0);
                          $pdf->Cell(10,5,$sub_total['total'],1,0,'',false);
                          $pdf->Cell(9,5,$sub_total['rank'],1,0,'', false);
                          $pdf->Cell(16,5,$sub_total['remark'],1,0,'', false);
                          $pdf->Cell(60,5,'',1,0,'', false);
                          $pdf->Ln();
                      }  
                  }
              }
              $pdf->Cell(60,5,'Summary',0,0,'C',false);
              $pdf->Cell(10,5,$total_coef,0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
              $pdf->Cell(10,5,$total_mark,0,0,'',false);
              $pdf->Cell(9,5,'',0,0,'', false);
              $pdf->Cell(16,5,'',0,0,'', false);
              $pdf->Cell(60,5,'',0,0,'C', false);
              $pdf->Ln();
              //End print Others
              }

              $pdf->Cell(60,5,'TOTAL',0,0,'C',false);
              $pdf->Cell(10,5,$general_coef,0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,round($general_total/$general_coef, 2),0,0,'',false);
              $pdf->Cell(10,5,$general_total,0,0,'',false);
              $pdf->Cell(9,5,'',0,0,'', false);
              $pdf->Cell(16,5,'',0,0,'', false);
              $pdf->Cell(60,5,'',0,0,'C', false);
              $pdf->Ln();
        }

    }else{
    $pdf = new PDF();
    foreach($means as $pos => $stud_av){
    $s = $Model->GetStudent($stud_av['student'], $section);
    $general_coef = 0;
    $general_total = 0;
    $pdf->AddPage();
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',9);
    $pdf->SetFillColor(0,0,128);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(60, 5, "", 0);
    
    if($stud_av['term'] == 'FIRST TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE ONE'){
        $pdf->Cell(30,5,$Model->YearNameDigits($year_id)." ".$stud_av['term']." - ".$lang[$_SESSION['lang']]["FirstSeqEval"] ,0);
        $eval1 = 1; $eval2 = 2; $sequence = '1';
    }elseif($stud_av['term'] == 'FIRST TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE TWO'){
        $pdf->Cell(30,5,$Model->YearNameDigits($year_id)." ".$stud_av['term']." - ".$lang[$_SESSION['lang']]["SecondSeqEval"] ,0);
        $eval1 = 1; $eval2 = 2; $sequence = '2';
    }elseif($stud_av['term'] == 'SECOND TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE ONE'){
        $pdf->Cell(30,5,$Model->YearNameDigits($year_id)." ".$stud_av['term']." - ".$lang[$_SESSION['lang']]["ThirdSeqEval"] ,0);
        $eval1 = 3; $eval2 = 4; $sequence = '3';
    }elseif($stud_av['term'] == 'SECOND TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE TWO'){
        $pdf->Cell(30,5,$Model->YearNameDigits($year_id)." ".$stud_av['term']." - ".$lang[$_SESSION['lang']]["FourthSeqEval"] ,0);
        $eval1 = 3; $eval2 = 4;  $sequence = '4';
    }elseif($stud_av['term'] == 'THIRD TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE ONE'){
        $pdf->Cell(30,5,$Model->YearNameDigits($year_id)." ".$stud_av['term']." - ".$lang[$_SESSION['lang']]["FifthSeqEval"] ,0);
        $eval1 = 5; $eval2 = 6; $sequence = '5';
    }elseif($stud_av['term'] == 'THIRD TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE TWO'){
        $pdf->Cell(30,5,$Model->YearNameDigits($year_id)." ".$stud_av['term']." - ".$lang[$_SESSION['lang']]["SixthSeqEval"] ,0);
        $eval1 = 5; $eval2 = 6; $sequence = '6';
    }
    
    //Student information
    $pdf->Ln();
    $pdf->Cell(110,4,$lang[$_SESSION['lang']]["Name"].': '.$s[0]['name'],0);
    $pdf->Cell(50,4,$lang[$_SESSION['lang']]["Class"].': '.$Model->GetAClassName($class_id),0);
    if($s[0]['picture'] != ""){
        $data = base64_decode($s[0]['picture']);
        $file = "../img/students/" . $s[0]["student_code"] . '.'.$s[0]["picture_ext"];
        //$success = file_put_contents($file, $data);
        $pdf->Image($file,170,40,30, 30);
    }          
    $pdf->Ln();
    $pdf->Cell(110,4,$lang[$_SESSION['lang']]["Gender"].': '.$s[0]['gender'],0);
    $pdf->Cell(50,4,$lang[$_SESSION['lang']]["Onroll"].': '.count($means),0);
    $pdf->Ln();
    $pdf->Cell(110,4,$lang[$_SESSION['lang']]["DOB"].': '.$s[0]['dob'].' at '.$s[0]['pob'],0);
    $pdf->Cell(50,4,$lang[$_SESSION['lang']]["Repeater"].': No',0);
    $pdf->Ln();
    $pdf->Cell(90,4,$lang[$_SESSION['lang']]["AdmissionNum"].': '.$s[0]['adm_num'],0);
    $pdf->Ln();
    $pdf->Cell(90,4,$lang[$_SESSION['lang']]["Classmaster"].': '.$Model->GetAllWithCriteria('classes', ['id'=>$class_id])[0]['cm'],0);
    $pdf->Ln(10);
    //End student information
    
    $pdf->SetFont('Arial','',8);

    //Check for the groups that exist
    $group_av1 = ''; $group_av2 = ''; $group_av3 = '';
    foreach($subject_groups as $group_name => $group){
        if(!empty($group)){
            $pdf->SetFillColor(0,0,128);
            $pdf->SetTextColor(255,255,255);
            $pdf->Cell(195,5,$lang[$_SESSION['lang']]["$group_name"],1,0,'C', true);
            $pdf->Ln();
            $pdf->SetTextColor(255,255,255);
            $pdf->SetFillColor(128,128,128);
            $pdf->Cell(35,5,$lang[$_SESSION['lang']]["Subject"],1,0,'',true);
            $pdf->Cell(50,5,$lang[$_SESSION['lang']]["Competences tested"],1,0,'',true);
            $pdf->Cell(10,5,'Eval'.$eval1,1,0,'',true);
            $pdf->Cell(10,5,'Eval'.$eval2,1,0,'',true);
            $pdf->Cell(10,5,$lang[$_SESSION['lang']]["Mark"],1,0,'',true);
            $pdf->Cell(10,5,'Coef',1,0,'',true);
            $pdf->Cell(10,5,'Total',1,0,'',true);
            $pdf->Cell(9,5,$lang[$_SESSION['lang']]["Rank"],1,0,'', true);
            $pdf->Cell(16,5,'Appreciation',1,0,'', true);
            $pdf->Cell(10,5,'Grade',1,0,'', true);
            $pdf->Cell(25,5,$lang[$_SESSION['lang']]["Teacher"],1,0,'', true);
            $pdf->Ln();
            $marks = $Model->GetStudentsMarks($year_id, $class_id, $exam_id, $stud_av['student']);
            $total_coef = 0;
            $total_mark = 0;
            $pdf->SetTextColor(0,0,0);
            foreach($marks as $mark){
                if($Model->GetRepGroup($mark['subject'], $class_id, $section) == $group_index[$group_name]){
                    $pdf->SetTextColor(0,0,0);
                    $sub_total = $Model->GetStudentTotals($exam_id, $class_id, $year_id, $stud_av['student'], $mark['subject']);
                    if(!empty($sub_total)){
                    $coef = $Model->GetCoefficient($mark['subject'], $class_id);
                    $total_coef += $coef;
                    $total_mark += $sub_total['total'];
    
                    if(strlen($mark['subject']) > 18){
                        $pdf->Cell(35,5,substr($mark['subject'], 0, 18),1);
                    }else{
                        $pdf->Cell(35,5,$mark['subject'],1);
                    }
    
                    $pdf->Cell(50,5,substr($mark['competence'],0, 38),1);
                    if($sequence == '1' || $sequence == '3' || $sequence == '5'){
                        if($mark['mark'] < 10){
                            $pdf->SetTextColor(255,0,0);
                            $pdf->Cell(10,5,$mark['mark'],1);
                            $pdf->Cell(10,5,'',1);
                        }else{
                            $pdf->SetTextColor(0,0,0);
                            $pdf->Cell(10,5,$mark['mark'],1);
                            $pdf->Cell(10,5,'',1);
                        }
                    }else{
                        if($mark['mark'] < 10){
                            $pdf->SetTextColor(255,0,0);
                            $pdf->Cell(10,5,'',1);
                            $pdf->Cell(10,5,$mark['mark'],1);
                        }else{
                            $pdf->SetTextColor(0,0,0);
                            $pdf->Cell(10,5,'',1);
                            $pdf->Cell(10,5,$mark['mark'],1);
                        }
                    }
                    if($mark['mark'] < 10){
                        $pdf->SetTextColor(255,0,0);
                        $pdf->Cell(10,5,$mark['mark'],1);
                    }else{
                        $pdf->SetTextColor(0,0,0);
                        $pdf->Cell(10,5,$mark['mark'],1);
                    }
                    $pdf->SetTextColor(0,0,0);
                    $pdf->Cell(10,5,$coef,1);
                    $pdf->Cell(10,5,$sub_total['total'],1);
                    $pdf->Cell(9,5,$sub_total['rank'],1);
                    $pdf->Cell(16,5,$sub_total['grade'],1);
                    $pdf->Cell(10,5,$sub_total['remark'],1);
                    $pdf->Cell(25,5,strToUpper($Model->GetStaffName($Model->GetSubjectTeacher($mark['subject'], $class_id, $year_id))),1);
                    $pdf->Ln();
                    }
                }
            }
            
            if ($total_coef != 0){
                if($group_index[$group_name] == 1){
                    $group_av1 = round($total_mark/$total_coef,2);
                }elseif($group_index[$group_name] == 2){
                    $group_av2 = round($total_mark/$total_coef,2);
                }elseif($group_index[$group_name] == 3){
                    $group_av3 = round($total_mark/$total_coef,2);
                }else{
                    $group_av1 = round($total_mark/$total_coef,2);
                }
                
            }
    
            $pdf->Cell(105,5,$lang[$_SESSION['lang']]["GroupTotal"],1,0,'R', false);
            if($group_index[$group_name] == 1){
                $pdf->Cell(10,5,$group_av1,1);
            }elseif($group_index[$group_name] == 2){
                $pdf->Cell(10,5,$group_av2,1);
            }elseif($group_index[$group_name] == 3){
                $pdf->Cell(10,5,$group_av3,1);
            }else{
                $pdf->Cell(10,5,$group_av1,1);
            }
            
            $pdf->Cell(10,5,$total_coef,1);
            $pdf->Cell(10,5,$total_mark,1);
            $pdf->Cell(60,5,'Remarks: ',1);
            $pdf->Ln();
    
            $general_coef += $total_coef; $general_total += $total_mark;
        }
    }
    //Calculate General average
    $general_av = round($general_total/$general_coef,2);
    //Display general avaerage
    $pdf->Cell(105,10,$lang[$_SESSION['lang']]["GeneralTotal"],1,0,'R', false);
    $pdf->Cell(10,10,$general_av,1);
    $pdf->Cell(10,10,$general_coef,1);
    $pdf->Cell(10,10,$general_total,1);
    $pdf->Cell(60,10,$lang[$_SESSION['lang']]["FinAverage"].$stud_av['average'],1,0,'C');
    $pdf->Ln();

    //Work appreciation header
    $pdf->SetFillColor(0,0,128);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(65,5,$lang[$_SESSION['lang']]["WorkAppr"],1,0,'C', true);
    $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Averages"],1,0,'C', true);
    $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Rank"].'',1,0,'C', true);
    $pdf->Ln();

    //Display work appreciation
    $pdf->SetTextColor(0,0,0);
    $work ='';
    if($general_av < 10){$work = "Could do better";}elseif($general_av >= 10 && $general_av <= 13){$work = "Satisfactory";}elseif($general_av > 13 && $general_av <= 16){$work = "Keep up the good work";}elseif($general_av > 16){$work = "Excellent work";}
    $pdf->Cell(65,5,$work,1,0,'C', false);
    $pdf->Cell(21,5,isset($group_av1)?$group_av1:'',1,0,'C', false);
    $pdf->Cell(21,5,isset($group_av2)?$group_av2:'',1,0,'C', false);
    $pdf->Cell(23,5,isset($group_av3)?$group_av3:'',1,0,'C', false);
    $eff = count($means);
    $num =$pos+1;
    $position = $num."/".$eff;
    $pdf->Cell(65,5,$position,1,0,'C', false);
    $pdf->Ln();

    //Display discipline headers
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Conduct"].'',1,0,'C', true);
    $pdf->Cell(33,5,$lang[$_SESSION['lang']]["Discipline"].'',1,0,'C', true);
    $pdf->Cell(32,5,$lang[$_SESSION['lang']]["ClassProfile"].'',1,0,'C', true);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(65,5,'',0,0,'C', false);
    $pdf->Ln();

    //Display discipline information
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(27,5,$lang[$_SESSION['lang']]["JustAbs"],1,0,'C', false);
    $pdf->Cell(5,5,$Model->CountAbsences($year_id, $class_id, explode(' ',$Model->GetTermName($exam_id))[0], $stud_av['student'], 'justabs'),1,0,'C', false);
    $pdf->Cell(28,5,$lang[$_SESSION['lang']]["UnJustAbs"],1,0,'C', false);
    $pdf->Cell(5,5,$Model->CountAbsences($year_id, $class_id, explode(' ',$Model->GetTermName($exam_id))[0], $stud_av['student'], 'absences'),1,0,'C', false);
    $pdf->Cell(33,5,'',1,0,'C', false);
    $pdf->Cell(32,5,$lang[$_SESSION['lang']]["ClassAv"].round($Model->GetClassAverage($exam_id, $class_id, $year_id),2),1,0,'J', false);
    $pdf->Ln();

    //Display absences headers
    $pdf->Cell(27,5,$lang[$_SESSION['lang']]["WarningsD"],1,0,'C', false);
    $pdf->Cell(5,5,$Model->CountAbsences($year_id, $class_id, explode(' ',$Model->GetTermName($exam_id))[0], $stud_av['student'], 'warning'),1,0,'C', false);
    $pdf->Cell(28,5,$lang[$_SESSION['lang']]["WarningsH"],1,0,'C', false);
    $pdf->Cell(5,5,'0',1,0,'C', false);
    $pdf->Cell(33,5,'',1,0,'C', false);
    $pdf->Cell(32,5,$lang[$_SESSION['lang']]["SuccRate"].' '.$succ,1,0,'J', false);
    $pdf->Ln();

    //Display absences
    $pdf->Cell(27,5,$lang[$_SESSION['lang']]["Lateness"],1,0,'C', false);
    $pdf->Cell(5,5,$Model->CountAbsences($year_id, $class_id, explode(' ',$Model->GetTermName($exam_id))[0], $stud_av['student'], 'punishment'),1,0,'C', false);
    $pdf->Cell(28,5,$lang[$_SESSION['lang']]["Suspension"],1,0,'C', false);
    $pdf->Cell(5,5,$Model->CountAbsences($year_id, $class_id, explode(' ',$Model->GetTermName($exam_id))[0], $stud_av['student'], 'suspension'),1,0,'C', false);
    $pdf->Cell(65,5,'',1,0,'J', false);
    $pdf->Ln();

    //Display Classmaster's appreciation headers
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(65,5,$lang[$_SESSION['lang']]["ApprPP"],1,0,'C', true);
    $pdf->Cell(33,5,$lang[$_SESSION['lang']]["ParentSign"],1,0,'C', true);
    $pdf->Cell(16,5,$lang[$_SESSION['lang']]["BestAv"],1,0,'C', true);
    $pdf->Cell(16,5,$lang[$_SESSION['lang']]["WorstAv"],1,0,'C', true);
    $pdf->Cell(65,5,'',0,0,'L', false);
    $pdf->SetTextColor(0,0,0);
    $pdf->Ln();

    //Display Classmaster appreciation
    $pdf->Cell(65,5,'',1,0,'C', false);
    $pdf->Cell(33,5,'',1,0,'C', false);
    $pdf->Cell(16,5,$Model->GetClassBest($exam_id, $class_id, $year_id),1,0,'C', false);
    $pdf->Cell(16,5,$Model->GetClassLast($exam_id, $class_id, $year_id),1,0,'C', false);
    $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Principal"].'',0,0,'C', false);
    $pdf->Ln();

    //Last thing: any alteration
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','B',6);
    $pdf->Cell(10,4,"Any alteration on the report card is not the handiwork of ".$Model->GetSchoolInfo(1)[0]['name'],0);
    } 
    }
}
$pdf->Output();
}