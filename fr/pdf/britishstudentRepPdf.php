<?php
ini_set ( 'max_execution_time' , '-1' );
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
        
        //$this->Image('../img/footer-Blue.png',0,240,220);
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        //$this->SetFont('Arial','I',8);
        // Page number
        //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        $this->SetTextColor(0,0,0);
        $this->SetFont('Arial','B',6);
        $this->Cell(10,4,"Any alteration on the report card is not the handiwork of Quality International School, Yaounde",0);
    
        
    }
}
///////////////////////////////////////////////////////////////////////////////
$student_code = $_GET["ref"];
$year_id = $_GET['year_id'];
$class_id = $_GET['class_id'];
$term_name = $_GET['term_id'];
$exam_ids = $Model->ExamsForTerm($term_name, $year_id, $section);
$eval1 = 1; $eval2 = 2; $sequence = '';

$positions = $Model->GetPosition($year_id, $class_id, strToUpper($term_name).' TERM');
$termBest = $Model->GetTermBest($year_id, $class_id, strToUpper($term_name).' TERM');
$classAv = round($Model->ClassAverageForTerm($year_id, $class_id, strToUpper($term_name).' TERM'),2);
$averages = $Model->GetTermAverageForStudent($year_id, $class_id, strToUpper($term_name).' TERM');

if($term_name == 'Third'){
    $annual_averages = $Model->GetAnnualAverage($year_id, $class_id);
    $students = $Model->GetAnnualPosition($year_id, $class_id);
    $term1 = $Model->GetTermAverageForStudent($year_id, $class_id, 'FIRST TERM');
    $term2 = $Model->GetTermAverageForStudent($year_id, $class_id, 'SECOND TERM');
    $term3 = $Model->GetTermAverageForStudent($year_id, $class_id, 'THIRD TERM');
}

$pass_av = 0;
$number_of_papers = 0;
foreach($averages as $av){
    if ($av >= 10){
        $pass_av++;
    }
}
$general_coef = 0;
$general_total = 0;
$t1_total=0;
$t2_total=0;
$total_subs_pass = 0;
$succ = round(($pass_av/count($averages))*100,2);
$langs = $Model->Languages();
$science = $Model->Sciences();
$art = $Model->Arts();
$others = $Model->OtherSubjects();

$hasSci = false;
$hasArt = false;
$hasLang = false;
$hasOther = false;

 if(!empty($positions)){
    $pdf = new PDF();
    foreach($positions as $code => $pos){
        if($code == $student_code){
            $s = $Model->GetStudent($code, $section);
            $general_coef = 0;
            $general_total = 0;
            $total_subs_pass = 0;
            $t1_total = 0;
            $t2_total = 0;
            $pdf->AddPage();
            $pdf->AliasNbPages();
            $pdf->SetFont('Arial','B',8);
            $pdf->SetFillColor(0,0,128);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(60, 5, "", 0);
            $year_name = $Model->GetYearName($year_id);
            if($term_name == 'First'){
                $pdf->Cell(30,5,$lang[$_SESSION['lang']]["FirstTermRep"].' '.$year_name ,0);
                $eval1 = 1; $eval2 = 2;
            }elseif($term_name == 'Second'){
                $pdf->Cell(30,5,$lang[$_SESSION['lang']]["SecondTermRep"].' '.$year_name ,0);
                $eval1 = 3; $eval2 = 4; 
            }elseif($term_name == 'Third'){
                $pdf->Cell(30,5,$lang[$_SESSION['lang']]["AnnualReport"].' '.$year_name ,0);
                $eval1 = 5; $eval2 = 6; ;
            }else{
                $pdf->Cell(30,5,$lang[$_SESSION['lang']]["SpecialTermRep"].' '.$year_name ,0);
                $eval1 = 7; $eval2 = 8;
            }
            
           //Student information
        $pdf->Ln();
        $pdf->Cell(110,4,$lang[$_SESSION['lang']]["Name"].': '.$s[0]['name'],0);
        $pdf->Cell(50,4,$lang[$_SESSION['lang']]["Class"].': '.$Model->GetAClassName($class_id),0);
        if($s[0]['picture'] != ""){
            $data = base64_decode($s[0]['picture']);
            $file = "../img/students/" . $s[0]["student_code"] . '.'.$s[0]["picture_ext"];
            $success = file_put_contents($file, $data);
            $pdf->Image($file,170,40,30, 30);
        }                
        $pdf->Ln();
        $pdf->Cell(110,4,$lang[$_SESSION['lang']]["Gender"].': '.$s[0]['gender'],0);
        $pdf->Cell(50,4,$lang[$_SESSION['lang']]["Onroll"].': '.count($positions),0);
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
            $pdf->Cell(80,5,$lang[$_SESSION['lang']]["Subject"],0,0,'',false);
            $pdf->Cell(10,5,'Coef',0,0,'',false);
            $pdf->Cell(10,5,'Eval'.$eval1,0,0,'',false);
            $pdf->Cell(10,5,'Eval'.$eval2,0,0,'',false);
            $pdf->Cell(10,5,$lang[$_SESSION['lang']]["avg"],0,0,'',false);
            //$pdf->Cell(10,5,'Total',0,0,'',false);
            $pdf->Cell(9,5,$lang[$_SESSION['lang']]["Rank"],0,0,'', false);
            $pdf->Cell(16,5,'Remarks',0,0,'', false);
            $pdf->Cell(55,5,'Class performance',0,0,'C', false);
            $pdf->Ln();
            //End main table header
            $pdf->SetFont('Arial','',8);
            //Get class subjects
            $subjects = $Model->DoneSubjects($code, $year_id, $class_id, $term_name);
            foreach ($subjects as $s){
              if(in_array($s['subject'], $langs)){
                  $hasLang = true;
              }
          }
          
          foreach ($subjects as $s){
              if(in_array($s['subject'], $science)){
                  $hasSci = true;
              }
          }
          
          foreach ($subjects as $s){
              if(in_array($s['subject'], $art)){
                  $hasArt = true;
              }
          }
          
          foreach ($subjects as $s){
              if(in_array($s['subject'], $others)){
                  $hasOther = true;
              }
          }

            if($hasLang){
                //Print languages
                $pdf->Cell(60,5,'LANGUAGES',0,0,'',false);
                $pdf->Cell(90,5,'',0,0,'',false);
                $pdf->Cell(10,5,'Min',0,0,'',false);
                $pdf->Cell(12,5,'Avg',0,0,'',false);
                $pdf->Cell(12,5,'Max',0,0,'',false);
                $pdf->Cell(12,5,'S.R.',0,0,'',false);
                $pdf->Ln();
                $total_coef = 0;
                $total_mark = 0;
                $t1_group_total = 0;
                $t2_group_total = 0;
            foreach($subjects as $subject){
              $total_sub_total = 0.0;
              $mark1 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[0]['id']]);
              $mark2 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[1]['id']]);
              if(in_array($subject['subject'],$langs)){
                if($mark1 != "" || $mark2 != ""){
                  $av_mark = round(((float)$mark1 + (float)$mark2)/2, 2);
                  $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                  $total_coef += $coef;
                  $total_sub_total += $av_mark * $coef;
                  $total_mark += $total_sub_total;
                  $general_coef += $coef;
                  $general_total += $av_mark * $coef;
                  $rank1 = $Model->GetStudentTotals($exam_ids[0]['id'], $class_id, $year_id, $code, $subject['subject']);
                  $rank2 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                  $rnk1 = 0;
                  $rnk2 = 0;
                  $av_rank = '';
                  //Get total for each sequence if both marks available
                  $t1_group_total += (float)$mark1 * $coef;
                  $t2_group_total += (float)$mark2 * $coef;
                  if(!empty($rank1) && !empty($rank2)){
                      $av_rank = round(($rank1['rank'] + $rank2['rank'] )/2, 0);
                  }elseif(empty($rank1) && !empty($rank2)){
                      $av_rank = $rank2['rank'];
                  }elseif(!empty($rank1) && empty($rank2)){
                      $av_rank = $rank1['rank'];
                  }else{
                    $av_rank = '';
                  }
                }elseif($mark1 != "" || $mark2 == ""){
                  $av_mark = round((float)$mark1/2, 2);
                  $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                  $total_coef += $coef;
                  $total_sub_total += $av_mark * $coef;
                  $general_coef += $coef;
                  $total_mark += $total_sub_total;
                  $general_total += $av_mark * $coef;
                  $rank1 = $Model->GetStudentTotals($exam_ids[0]['id'], $class_id, $year_id, $code, $subject['subject']);
                  //$rank2 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                  $rnk1 = 0;
                  $rnk2 = 0;
                  $av_rank = '';
                  if(!empty($rank1)){
                    $av_rank = round($rank1['rank'], 0);
                  }else{
                    $av_rank = '';
                  }
                  
                  //Get total for each sequence if only first marks available
                  $t1_group_total += (float)$mark1 * $coef;
                  $t2_group_total += 0;
                }elseif($mark1 == "" || $mark2 != ""){
                  $av_mark = round((float)$mark2/2, 2);
                  $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                  $total_coef += $coef;
                  $total_sub_total += $av_mark * $coef;
                  $general_coef += $coef;
                  $total_mark += $total_sub_total;
                  $general_total += $av_mark * $coef;
                  $rank1 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                  //$rank2 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                  $rnk1 = 0;
                  $rnk2 = 0;
                  $av_rank = '';
                  if(!empty($rank1)){
                    $av_rank = round($rank1['rank'], 0);
                  }else{
                    $av_rank = '';
                  }
                  //Get total for each sequence if both marks available
                  $t1_group_total += 0;
                  $t2_group_total += (float)$mark2 * $coef;
                }else{
                  $av_rank = '';
                }
            if($mark1 == '' && $mark2 == ''){

            }else{
              //Print subject only if at least one mark is present
              if(strlen($subject['subject']) > 34){
                if($subject['subject'] == "PURE MATHEMATICS" ||  $subject['subject'] == 'PURE MATHS WITH MECHS' || $subject['subject'] == 'PURE MATHS WITH STATS'){
                  $subjectTitle ='MATHEMATICS';
                }else{
                  $subjectTitle = substr($subject['subject'], 0, 34);
                }
                $teacher = ucfirst($Model->GetStaffName($Model->GetSubjectTeacher($subject['subject'], $class_id, $year_id)));
                $pdf->Cell(80,5,$subjectTitle."  ($teacher)",1);
            }else{
              if($subject['subject'] == "PURE MATHEMATICS" ||  $subject['subject'] == 'PURE MATHS WITH MECHS' || $subject['subject'] == 'PURE MATHS WITH STATS'){
                $subjectTitle ='MATHEMATICS';
              }else{
                $subjectTitle = $subject['subject'];
              }
                $teacher = ucfirst($Model->GetStaffName($Model->GetSubjectTeacher($subject['subject'], $class_id, $year_id)));
                $pdf->Cell(80,5,$subjectTitle."  ($teacher)",1);
            }
            $pdf->Cell(10,5,$coef,1,0,'',false);
            if($mark1 < 10){
              $pdf->SetTextColor(128,0,0);
              $pdf->Cell(10,5,$mark1,1,0,'',false);
          }elseif($mark1 >= 17){
              $pdf->SetTextColor(0,128,0);
              $pdf->Cell(10,5,$mark1,1,0,'',false);
          }else{
              $pdf->SetTextColor(0,0,0);
              $pdf->Cell(10,5,$mark1,1,0,'',false);
          }   
          if($mark2 < 10){
            $pdf->SetTextColor(128,0,0);
            $pdf->Cell(10,5,$mark2,1,0,'',false);
        }elseif($mark2 >= 17){
            $pdf->SetTextColor(0,128,0);
            $pdf->Cell(10,5,$mark2,1,0,'',false);
        }else{
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(10,5,$mark2,1,0,'',false);
        }   
            if($av_mark < 10){
                $pdf->SetTextColor(128,0,0);
                $pdf->Cell(10,5,$av_mark,1,0,'',false);
            }elseif($av_mark >= 17){
                $pdf->SetTextColor(0,128,0);
                $pdf->Cell(10,5,$av_mark,1,0,'',false);
            }else{
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(10,5,$av_mark,1,0,'',false);
            }
            $pdf->SetTextColor(0,0,0);
            
            if($av_mark >= 10){
              $total_subs_pass++;
            }
            
            if(substr($av_rank,-1,1) == '1' && $av_rank != '11'){
                $pdf->Cell(9,5,$av_rank.'st',1,0,'', false);
              }elseif(substr($av_rank,-1,1) == '2' && $av_rank != '12'){
                $pdf->Cell(9,5,$av_rank.'nd',1,0,'', false);
              }elseif(substr($av_rank,-1,1) == '3' && $av_rank != '13'){
                $pdf->Cell(9,5,$av_rank.'rd',1,0,'', false);
              }else{ 
                $pdf->Cell(9,5,$av_rank.'th',1,0,'', false);
              }
            $pdf->Cell(16,5,$Model->GradeRemark(mark: $av_mark),1,0,'', false);
            $min1 = $Model->AvMarks($exam_ids[0]['id'],$class_id, $year_id, $subject['subject'], 'MIN');
            $min2 = $Model->AvMarks($exam_ids[1]['id'],$class_id, $year_id, $subject['subject'], 'MIN');
            $avg1 = $Model->AvMarks($exam_ids[0]['id'],$class_id, $year_id, $subject['subject'], 'AVG');
            $avg2 = $Model->AvMarks($exam_ids[1]['id'],$class_id, $year_id, $subject['subject'], 'AVG');
            $max1 = $Model->AvMarks($exam_ids[0]['id'],$class_id, $year_id, $subject['subject'], 'MAX');
            $max2 = $Model->AvMarks($exam_ids[1]['id'],$class_id, $year_id, $subject['subject'], 'MAX');
            $pdf->Cell(13,5,round(($min1+$min2)/2,2),1,0,'C',false);
            $pdf->Cell(13,5,round(($avg1+$avg2)/2,2),1,0,'C',false);
            $pdf->Cell(12,5,round(($max1+$max2)/2,2),1,0,'C',false);
            $succ1 = $Model->SuccessRate($exam_ids[0]['id'], $class_id, $year_id,  $subject['subject']);
            $succ2 = $Model->SuccessRate($exam_ids[1]['id'], $class_id, $year_id,  $subject['subject']);
            $pdf->Cell(12,5,round(($succ1+$succ2)/2,2).'%',1,0,'C',false);
            $pdf->Ln();
            }
            }    

            }
            if($total_coef != 0 && $t1_group_total !=0 && $t2_group_total != 0){
             $pdf->Cell(80,5,'Summary',0,0,'C',false);
            $pdf->Cell(10,5,$total_coef,0,0,'',false);
            $pdf->Cell(10,5,round($t1_group_total/$total_coef, 2),0,0,'',false);
            $pdf->Cell(10,5,round($t2_group_total/$total_coef, 2),0,0,'',false);       
            $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
            $pdf->Cell(10,5,'',0,0,'',false);
            $pdf->Cell(9,5,'',0,0,'', false);
            $pdf->Cell(16,5,'',0,0,'', false);
            $pdf->Cell(60,5,'',0,0,'C', false);
            $pdf->Ln();
            }else if($total_coef != 0 && $t1_group_total !=0 && $t2_group_total == 0){
              $pdf->Cell(80,5,'Summary',0,0,'C',false);
              $pdf->Cell(10,5,$total_coef,0,0,'',false);
              $pdf->Cell(10,5,round($t1_group_total/$total_coef, 2),0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(9,5,'',0,0,'', false);
              $pdf->Cell(16,5,'',0,0,'', false);
              $pdf->Cell(60,5,'',0,0,'C', false);
              $pdf->Ln();
            }else if($total_coef != 0 && $t1_group_total ==0 && $t2_group_total != 0){
              $pdf->Cell(80,5,'Summary',0,0,'C',false);
              $pdf->Cell(10,5,$total_coef,0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,round($t2_group_total/$total_coef, 2),0,0,'',false);
              $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(9,5,'',0,0,'', false);
              $pdf->Cell(16,5,'',0,0,'', false);
              $pdf->Cell(60,5,'',0,0,'C', false);
              $pdf->Ln();
            }else if($total_coef != 0 && $t1_group_total ==0 && $t2_group_total == 0){
              $pdf->Cell(80,5,'',0,0,'C',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(9,5,'',0,0,'', false);
              $pdf->Cell(16,5,'',0,0,'', false);
              $pdf->Cell(60,5,'',0,0,'C', false);
              $pdf->Ln();
            }
            //Get Sequence totals 
            $t1_total += $t1_group_total;
            $t2_total += $t2_group_total;
            //End print languages
            }

             if($hasSci){
                 //Print Sciences
              $pdf->Cell(60,5,'SCIENCES',0,0,'',false);
              $pdf->Cell(90,5,'',0,0,'',false);
              $pdf->Cell(10,5,'Min',0,0,'',false);
              $pdf->Cell(12,5,'Avg',0,0,'',false);
              $pdf->Cell(12,5,'Max',0,0,'',false);
              $pdf->Cell(12,5,'S.R.',0,0,'',false);
              $pdf->Ln();
              $total_coef = 0;
              $total_mark = 0;
              $t1_group_total = 0;
              $t2_group_total = 0;
              foreach($subjects as $subject){
                $total_sub_total = 0.0;
                $mark1 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[0]['id']]);
                $mark2 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[1]['id']]);
                if(in_array($subject['subject'],$science)){
                  if($mark1 != "" || $mark2 != ""){
                    $av_mark = round(((float)$mark1 + (float)$mark2)/2, 2);
                    $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                    $total_coef += $coef;
                    $total_sub_total += $av_mark * $coef;
                    $total_mark += $total_sub_total;
                    $general_coef += $coef;
                    $general_total += $av_mark * $coef;
                    $rank1 = $Model->GetStudentTotals($exam_ids[0]['id'], $class_id, $year_id, $code, $subject['subject']);
                    $rank2 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                    $rnk1 = 0;
                    $rnk2 = 0;
                    $av_rank = '';
                    //Get total for each sequence if both marks available
                    $t1_group_total += (float)$mark1 * $coef;
                    $t2_group_total += (float)$mark2 * $coef;
                    if(!empty($rank1) && !empty($rank2)){
                        $av_rank = round(($rank1['rank'] + $rank2['rank'] )/2, 0);
                    }elseif(empty($rank1) && !empty($rank2)){
                        $av_rank = $rank2['rank'];
                    }elseif(!empty($rank1) && empty($rank2)){
                        $av_rank = $rank1['rank'];
                    }else{
                      $av_rank = '';
                    }
                  }elseif($mark1 != "" || $mark2 == ""){
                    $av_mark = round((float)$mark1/2, 2);
                    $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                    $total_coef += $coef;
                    $total_sub_total += $av_mark * $coef;
                    $general_coef += $coef;
                    $total_mark += $total_sub_total;
                    $general_total += $av_mark * $coef;
                    $rank1 = $Model->GetStudentTotals($exam_ids[0]['id'], $class_id, $year_id, $code, $subject['subject']);
                    //$rank2 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                    $rnk1 = 0;
                    $rnk2 = 0;
                    $av_rank = '';
                    if(!empty($rank1)){
                      $av_rank = round($rank1['rank'], 0);
                    }else{
                      $av_rank = '';
                    }
                    //Get total for each sequence if both marks available
                    $t1_group_total += (float)$mark1 * $coef;
                    $t2_group_total += 0;
                  }elseif($mark1 == "" || $mark2 != ""){
                    $av_mark = round((float)$mark2/2, 2);
                    $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                    $total_coef += $coef;
                    $total_sub_total += $av_mark * $coef;
                    $general_coef += $coef;
                    $total_mark += $total_sub_total;
                    $general_total += $av_mark * $coef;
                    $rank1 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                    //$rank2 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                    $rnk1 = 0;
                    $rnk2 = 0;
                    $av_rank = '';
                    if(!empty($rank1)){
                      $av_rank = round($rank1['rank'], 0);
                    }else{
                      $av_rank = '';
                    }
                    //Get total for each sequence if both marks available
                    $t1_group_total += 0;
                    $t2_group_total += (float)$mark2 * $coef;
                  }else{
                    $av_rank = '';
                  }
                  if($mark1 == '' && $mark2 == ''){

                  }else{
                    //Print subject only if at least one mark is present
                    if(strlen($subject['subject']) > 34){
                      if($subject['subject'] == "PURE MATHEMATICS" ||  $subject['subject'] == 'PURE MATHS WITH MECHS' || $subject['subject'] == 'PURE MATHS WITH STATS'){
                        $subjectTitle ='MATHEMATICS';
                      }else{
                        $subjectTitle = substr($subject['subject'], 0, 34);
                      }
                      $teacher = ucfirst($Model->GetStaffName($Model->GetSubjectTeacher($subject['subject'], $class_id, $year_id)));
                      $pdf->Cell(80,5,$subjectTitle."  ($teacher)",1);
                  }else{
                    if($subject['subject'] == "PURE MATHEMATICS" ||  $subject['subject'] == 'PURE MATHS WITH MECHS' || $subject['subject'] == 'PURE MATHS WITH STATS'){
                      $subjectTitle ='MATHEMATICS';
                    }else{
                      $subjectTitle = $subject['subject'];
                    }
                      $teacher = ucfirst($Model->GetStaffName($Model->GetSubjectTeacher($subject['subject'], $class_id, $year_id)));
                      $pdf->Cell(80,5,$subjectTitle."  ($teacher)",1);
                  }
                  $pdf->Cell(10,5,$coef,1,0,'',false);
                  if($mark1 < 10){
                    $pdf->SetTextColor(128,0,0);
                    $pdf->Cell(10,5,$mark1,1,0,'',false);
                }elseif($mark1 >= 17){
                    $pdf->SetTextColor(0,128,0);
                    $pdf->Cell(10,5,$mark1,1,0,'',false);
                }else{
                    $pdf->SetTextColor(0,0,0);
                    $pdf->Cell(10,5,$mark1,1,0,'',false);
                }   
                if($mark2 < 10){
                  $pdf->SetTextColor(128,0,0);
                  $pdf->Cell(10,5,$mark2,1,0,'',false);
              }elseif($mark2 >= 17){
                  $pdf->SetTextColor(0,128,0);
                  $pdf->Cell(10,5,$mark2,1,0,'',false);
              }else{
                  $pdf->SetTextColor(0,0,0);
                  $pdf->Cell(10,5,$mark2,1,0,'',false);
              }   
                  if($av_mark < 10){
                      $pdf->SetTextColor(128,0,0);
                      $pdf->Cell(10,5,$av_mark,1,0,'',false);
                  }elseif($av_mark >= 17){
                      $pdf->SetTextColor(0,128,0);
                      $pdf->Cell(10,5,$av_mark,1,0,'',false);
                  }else{
                      $pdf->SetTextColor(0,0,0);
                      $pdf->Cell(10,5,$av_mark,1,0,'',false);
                  }
                  $pdf->SetTextColor(0,0,0);
                  
                  if($av_mark >= 10){
                    $total_subs_pass++;
                  }
                  
                  if(substr($av_rank,-1,1) == '1' && $av_rank != '11'){
                      $pdf->Cell(9,5,$av_rank.'st',1,0,'', false);
                    }elseif(substr($av_rank,-1,1) == '2' && $av_rank != '12'){
                      $pdf->Cell(9,5,$av_rank.'nd',1,0,'', false);
                    }elseif(substr($av_rank,-1,1) == '3' && $av_rank != '13'){
                      $pdf->Cell(9,5,$av_rank.'rd',1,0,'', false);
                    }else{ 
                      $pdf->Cell(9,5,$av_rank.'th',1,0,'', false);
                    }
                  $pdf->Cell(16,5,$Model->GradeRemark(mark: $av_mark),1,0,'', false);
                  $min1 = $Model->AvMarks($exam_ids[0]['id'],$class_id, $year_id, $subject['subject'], 'MIN');
                  $min2 = $Model->AvMarks($exam_ids[1]['id'],$class_id, $year_id, $subject['subject'], 'MIN');
                  $avg1 = $Model->AvMarks($exam_ids[0]['id'],$class_id, $year_id, $subject['subject'], 'AVG');
                  $avg2 = $Model->AvMarks($exam_ids[1]['id'],$class_id, $year_id, $subject['subject'], 'AVG');
                  $max1 = $Model->AvMarks($exam_ids[0]['id'],$class_id, $year_id, $subject['subject'], 'MAX');
                  $max2 = $Model->AvMarks($exam_ids[1]['id'],$class_id, $year_id, $subject['subject'], 'MAX');
                  $pdf->Cell(13,5,round(($min1+$min2)/2,2),1,0,'C',false);
                  $pdf->Cell(13,5,round(($avg1+$avg2)/2,2),1,0,'C',false);
                  $pdf->Cell(12,5,round(($max1+$max2)/2,2),1,0,'C',false);
                  $succ1 = $Model->SuccessRate($exam_ids[0]['id'], $class_id, $year_id,  $subject['subject']);
                  $succ2 = $Model->SuccessRate($exam_ids[1]['id'], $class_id, $year_id,  $subject['subject']);
                  $pdf->Cell(12,5,round(($succ1+$succ2)/2,2).'%',1,0,'C',false);
                  $pdf->Ln();
                  }
              }  
              }
              if($total_coef != 0 && $t1_group_total !=0 && $t2_group_total != 0){
                $pdf->Cell(80,5,'Summary',0,0,'C',false);
                $pdf->Cell(10,5,$total_coef,0,0,'',false);
                $pdf->Cell(10,5,round($t1_group_total/$total_coef, 2),0,0,'',false);
                $pdf->Cell(10,5,round($t2_group_total/$total_coef, 2),0,0,'',false);
                $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(9,5,'',0,0,'', false);
                $pdf->Cell(16,5,'',0,0,'', false);
                $pdf->Cell(60,5,'',0,0,'C', false);
                $pdf->Ln();
              }else if($total_coef != 0 && $t1_group_total !=0 && $t2_group_total == 0){
                $pdf->Cell(80,5,'Summary',0,0,'C',false);
                $pdf->Cell(10,5,$total_coef,0,0,'',false);
                $pdf->Cell(10,5,round($t1_group_total/$total_coef, 2),0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(9,5,'',0,0,'', false);
                $pdf->Cell(16,5,'',0,0,'', false);
                $pdf->Cell(60,5,'',0,0,'C', false);
                $pdf->Ln();
              }else if($total_coef != 0 && $t1_group_total ==0 && $t2_group_total != 0){
                $pdf->Cell(80,5,'Summary',0,0,'C',false);
                $pdf->Cell(10,5,$total_coef,0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(10,5,round($t2_group_total/$total_coef, 2),0,0,'',false);
                $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(9,5,'',0,0,'', false);
                $pdf->Cell(16,5,'',0,0,'', false);
                $pdf->Cell(60,5,'',0,0,'C', false);
                $pdf->Ln();
              }else if($total_coef != 0 && $t1_group_total ==0 && $t2_group_total == 0){
                $pdf->Cell(80,5,'',0,0,'C',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(9,5,'',0,0,'', false);
                $pdf->Cell(16,5,'',0,0,'', false);
                $pdf->Cell(60,5,'',0,0,'C', false);
                $pdf->Ln();
              }
              //Get Sequence totals 
              $t1_total += $t1_group_total;
              $t2_total += $t2_group_total;
              //End Print sciences
              
             }

            if($hasArt){
              //Print Arts
              $pdf->Cell(60,5,'ARTS',0,0,'',false);
              $pdf->Cell(90,5,'',0,0,'',false);
              $pdf->Cell(10,5,'Min',0,0,'',false);
              $pdf->Cell(12,5,'Avg',0,0,'',false);
              $pdf->Cell(12,5,'Max',0,0,'',false);
              $pdf->Cell(12,5,'S.R.',0,0,'',false);
              $pdf->Ln();
              $total_coef = 0;
              $total_mark = 0;
              $t1_group_total = 0;
              $t2_group_total = 0;
            foreach($subjects as $subject){
              $total_sub_total = 0.0;
              $mark1 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[0]['id']]);
              $mark2 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[1]['id']]);
              if(in_array($subject['subject'],$art)){
                if($mark1 != "" || $mark2 != ""){
                  $av_mark = round(((float)$mark1 + (float)$mark2)/2, 2);
                  $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                  $total_coef += $coef;
                  $total_sub_total += $av_mark * $coef;
                  $total_mark += $total_sub_total;
                  $general_coef += $coef;
                  $general_total += $av_mark * $coef;
                  $rank1 = $Model->GetStudentTotals($exam_ids[0]['id'], $class_id, $year_id, $code, $subject['subject']);
                  $rank2 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                  $rnk1 = 0;
                  $rnk2 = 0;
                  $av_rank = '';
                  //Get total for each sequence if both marks available
                  $t1_group_total += (float)$mark1 * $coef;
                  $t2_group_total += (float)$mark2 * $coef;
                  if(!empty($rank1) && !empty($rank2)){
                      $av_rank = round(($rank1['rank'] + $rank2['rank'] )/2, 0);
                  }elseif(empty($rank1) && !empty($rank2)){
                      $av_rank = $rank2['rank'];
                  }elseif(!empty($rank1) && empty($rank2)){
                      $av_rank = $rank1['rank'];
                  }else{
                      $av_rank = '';
                  }
                }elseif($mark1 != "" || $mark2 == ""){
                  $av_mark = round((float)$mark1/2, 2);
                  $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                  $total_coef += $coef;
                  $total_sub_total += $av_mark * $coef;
                  $general_coef += $coef;
                  $total_mark += $total_sub_total;
                  $general_total += $av_mark * $coef;
                  $rank1 = $Model->GetStudentTotals($exam_ids[0]['id'], $class_id, $year_id, $code, $subject['subject']);
                  //$rank2 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                  $rnk1 = 0;
                  $rnk2 = 0;
                  $av_rank = '';
                  if(!empty($rank1)){
                    $av_rank = round($rank1['rank'], 0);
                  }else{
                    $av_rank = '';
                  }
                  //Get total for each sequence if both marks available
                  $t1_group_total += (float)$mark1 * $coef;
                  $t2_group_total += 0;
                }elseif($mark1 == "" || $mark2 != ""){
                  $av_mark = round((float)$mark2/2, 2);
                  $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                  $total_coef += $coef;
                  $total_sub_total += $av_mark * $coef;
                  $general_coef += $coef;
                  $total_mark += $total_sub_total;
                  $general_total += $av_mark * $coef;
                  //Get total for each sequence if both marks available
                  $t1_group_total += 0;
                  $t2_group_total += (float)$mark2 * $coef;
                  $rank1 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                  //$rank2 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                  $rnk1 = 0;
                  $rnk2 = 0;
                  $av_rank = '';
                  if(!empty($rank1)){
                    $av_rank = round($rank1['rank'], 0);
                  }else{
                    $av_rank = '';
                  }
                }else{
                  $av_rank = '';
                }
                if($mark1 == '' && $mark2 == ''){

                }else{
                  //Print subject only if at least one mark is present
                  if(strlen($subject['subject']) > 34){
                    $teacher = ucfirst($Model->GetStaffName($Model->GetSubjectTeacher($subject['subject'], $class_id, $year_id)));
                    $subjectTitle = substr($subject['subject'], 0, 34);
                    $pdf->Cell(80,5,$subjectTitle."  ($teacher)",1);
                }else{
                    $teacher = ucfirst($Model->GetStaffName($Model->GetSubjectTeacher($subject['subject'], $class_id, $year_id)));
                    $subjectTitle = $subject['subject'];
                    $pdf->Cell(80,5,$subjectTitle."  ($teacher)",1);
                }
                $pdf->Cell(10,5,$coef,1,0,'',false);
                if($mark1 < 10){
                  $pdf->SetTextColor(128,0,0);
                  $pdf->Cell(10,5,$mark1,1,0,'',false);
              }elseif($mark1 >= 17){
                  $pdf->SetTextColor(0,128,0);
                  $pdf->Cell(10,5,$mark1,1,0,'',false);
              }else{
                  $pdf->SetTextColor(0,0,0);
                  $pdf->Cell(10,5,$mark1,1,0,'',false);
              }   
              if($mark2 < 10){
                $pdf->SetTextColor(128,0,0);
                $pdf->Cell(10,5,$mark2,1,0,'',false);
            }elseif($mark2 >= 17){
                $pdf->SetTextColor(0,128,0);
                $pdf->Cell(10,5,$mark2,1,0,'',false);
            }else{
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(10,5,$mark2,1,0,'',false);
            }   
                if($av_mark < 10){
                    $pdf->SetTextColor(128,0,0);
                    $pdf->Cell(10,5,$av_mark,1,0,'',false);
                }elseif($av_mark >= 17){
                    $pdf->SetTextColor(0,128,0);
                    $pdf->Cell(10,5,$av_mark,1,0,'',false);
                }else{
                    $pdf->SetTextColor(0,0,0);
                    $pdf->Cell(10,5,$av_mark,1,0,'',false);
                }
                $pdf->SetTextColor(0,0,0);
                
                if($av_mark >= 10){
                  $total_subs_pass++;
                }
                
                if(substr($av_rank,-1,1) == '1' && $av_rank != '11'){
                    $pdf->Cell(9,5,$av_rank.'st',1,0,'', false);
                  }elseif(substr($av_rank,-1,1) == '2' && $av_rank != '12'){
                    $pdf->Cell(9,5,$av_rank.'nd',1,0,'', false);
                  }elseif(substr($av_rank,-1,1) == '3' && $av_rank != '13'){
                    $pdf->Cell(9,5,$av_rank.'rd',1,0,'', false);
                  }else{ 
                    $pdf->Cell(9,5,$av_rank.'th',1,0,'', false);
                  }
                $pdf->Cell(16,5,$Model->GradeRemark(mark: $av_mark),1,0,'', false);
                $min1 = $Model->AvMarks($exam_ids[0]['id'],$class_id, $year_id, $subject['subject'], 'MIN');
                $min2 = $Model->AvMarks($exam_ids[1]['id'],$class_id, $year_id, $subject['subject'], 'MIN');
                $avg1 = $Model->AvMarks($exam_ids[0]['id'],$class_id, $year_id, $subject['subject'], 'AVG');
                $avg2 = $Model->AvMarks($exam_ids[1]['id'],$class_id, $year_id, $subject['subject'], 'AVG');
                $max1 = $Model->AvMarks($exam_ids[0]['id'],$class_id, $year_id, $subject['subject'], 'MAX');
                $max2 = $Model->AvMarks($exam_ids[1]['id'],$class_id, $year_id, $subject['subject'], 'MAX');
                $pdf->Cell(13,5,round(($min1+$min2)/2,2),1,0,'C',false);
                $pdf->Cell(13,5,round(($avg1+$avg2)/2,2),1,0,'C',false);
                $pdf->Cell(12,5,round(($max1+$max2)/2,2),1,0,'C',false);
                $succ1 = $Model->SuccessRate($exam_ids[0]['id'], $class_id, $year_id,  $subject['subject']);
                $succ2 = $Model->SuccessRate($exam_ids[1]['id'], $class_id, $year_id,  $subject['subject']);
                $pdf->Cell(12,5,round(($succ1+$succ2)/2,2).'%',1,0,'C',false);
                $pdf->Ln();
                }
            }  
            }
            if($total_coef != 0 && $t1_group_total !=0 && $t2_group_total != 0){
            $pdf->Cell(80,5,'Summary',0,0,'C',false);
            $pdf->Cell(10,5,$total_coef,0,0,'',false);
            $pdf->Cell(10,5,round($t1_group_total/$total_coef, 2),0,0,'',false);
            $pdf->Cell(10,5,round($t2_group_total/$total_coef,2),0,0,'',false);
            $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
            $pdf->Cell(10,5,'',0,0,'',false);
            $pdf->Cell(9,5,'',0,0,'', false);
            $pdf->Cell(16,5,'',0,0,'', false);
            $pdf->Cell(60,5,'',0,0,'C', false);
            $pdf->Ln();
            }else if($total_coef != 0 && $t1_group_total !=0 && $t2_group_total == 0){
              $pdf->Cell(80,5,'Summary',0,0,'C',false);
              $pdf->Cell(10,5,$total_coef,0,0,'',false);
              $pdf->Cell(10,5,round($t1_group_total/$total_coef, 2),0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(9,5,'',0,0,'', false);
              $pdf->Cell(16,5,'',0,0,'', false);
              $pdf->Cell(60,5,'',0,0,'C', false);
              $pdf->Ln();
            }else if($total_coef != 0 && $t1_group_total ==0 && $t2_group_total != 0){
              $pdf->Cell(80,5,'Summary',0,0,'C',false);
              $pdf->Cell(10,5,$total_coef,0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,round($t2_group_total/$total_coef, 2),0,0,'',false);
              $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(9,5,'',0,0,'', false);
              $pdf->Cell(16,5,'',0,0,'', false);
              $pdf->Cell(60,5,'',0,0,'C', false);
              $pdf->Ln();
            }else if($total_coef != 0 && $t1_group_total ==0 && $t2_group_total == 0){
              $pdf->Cell(80,5,'',0,0,'C',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(10,5,'',0,0,'',false);
              $pdf->Cell(9,5,'',0,0,'', false);
              $pdf->Cell(16,5,'',0,0,'', false);
              $pdf->Cell(60,5,'',0,0,'C', false);
              $pdf->Ln();
            }
            //Get Sequence totals 
            $t1_total += $t1_group_total;
            $t2_total += $t2_group_total;
           // End print Arts
               }

              if($hasOther){
                //Print Others
              $pdf->Cell(60,5,'OTHERS',0,0,'',false);
              $pdf->Cell(90,5,'',0,0,'',false);
              $pdf->Cell(10,5,'Min',0,0,'',false);
              $pdf->Cell(12,5,'Avg',0,0,'',false);
              $pdf->Cell(12,5,'Max',0,0,'',false);
              $pdf->Cell(12,5,'S.R.',0,0,'',false);
              $pdf->Ln();
              $total_coef = 0;
              $total_mark = 0;
              $t1_group_total = 0;
              $t2_group_total = 0;
              foreach($subjects as $subject){
                $total_sub_total = 0.0;
                $mark1 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[0]['id']]);
                $mark2 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[1]['id']]);
                if(in_array($subject['subject'],$others)){
                  if($mark1 != "" || $mark2 != ""){
                    $av_mark = round(((float)$mark1 + (float)$mark2)/2, 2);
                    $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                    $total_coef += $coef;
                    $total_sub_total += $av_mark * $coef;
                    $total_mark += $total_sub_total;
                    $general_coef += $coef;
                    $general_total += $av_mark * $coef;
                    //Get total for each sequence if both marks available
                    $t1_group_total += (float)$mark1 * $coef;
                    $t2_group_total += (float)$mark2 * $coef;
                    $rank1 = $Model->GetStudentTotals($exam_ids[0]['id'], $class_id, $year_id, $code, $subject['subject']);
                    $rank2 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                    $rnk1 = 0;
                    $rnk2 = 0;
                    $av_rank = '';
  
                    if(!empty($rank1) && !empty($rank2)){
                        $av_rank = round(($rank1['rank'] + $rank2['rank'] )/2, 0);
                    }elseif(empty($rank1) && !empty($rank2)){
                        $av_rank = $rank2['rank'];
                    }elseif(!empty($rank1) && empty($rank2)){
                        $av_rank = $rank1['rank'];
                    }else{
                        $av_rank = '';
                    }
                  }elseif($mark1 != "" || $mark2 == ""){
                    $av_mark = round((float)$mark1/2, 2);
                    $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                    $total_coef += $coef;
                    $total_sub_total += $av_mark * $coef;
                    $general_coef += $coef;
                    $total_mark += $total_sub_total;
                    $general_total += $av_mark * $coef;
                    //Get total for each sequence if both marks available
                    $t1_group_total += (float)$mark1 * $coef;
                    $t2_group_total += 0;
                    $rank1 = $Model->GetStudentTotals($exam_ids[0]['id'], $class_id, $year_id, $code, $subject['subject']);
                    //$rank2 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                    $rnk1 = 0;
                    $rnk2 = 0;
                    $av_rank = '';
                    if(!empty($rank1)){
                      $av_rank = round($rank1['rank'], 0);
                    }else{
                      $av_rank = '';
                    }
                  }elseif($mark1 == "" || $mark2 != ""){
                    $av_mark = round((float)$mark2/2, 2);
                    $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                    $total_coef += $coef;
                    $total_sub_total += $av_mark * $coef;
                    $general_coef += $coef;
                    $total_mark += $total_sub_total;
                    $general_total += $av_mark * $coef;
                    //Get total for each sequence if both marks available
                    $t1_group_total += 0;
                    $t2_group_total += (float)$mark2 * $coef;
                    $rank1 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                    //$rank2 = $Model->GetStudentTotals($exam_ids[1]['id'], $class_id, $year_id, $code, $subject['subject']);
                    $rnk1 = 0;
                    $rnk2 = 0;
                    $av_rank = '';
                    if(!empty($rank1)){
                      $av_rank = round($rank1['rank'], 0);
                    }else{
                      $av_rank = '';
                    }
                  }else{
                    $av_rank = '';
                  }
                  if($mark1 == '' && $mark2 == ''){

                  }else{
                    //Print subject only if at least one mark is present
                    if(strlen($subject['subject']) > 34){
                      $teacher = ucfirst($Model->GetStaffName($Model->GetSubjectTeacher($subject['subject'], $class_id, $year_id)));
                      $subjectTitle = substr($subject['subject'], 0, 34);
                      $pdf->Cell(80,5,$subjectTitle."  ($teacher)",1);
                  }else{
                      $teacher = ucfirst($Model->GetStaffName($Model->GetSubjectTeacher($subject['subject'], $class_id, $year_id)));
                      $subjectTitle = $subject['subject'];
                      $pdf->Cell(80,5,$subjectTitle."  ($teacher)",1);
                  }
                  $pdf->Cell(10,5,$coef,1,0,'',false);
                  if($mark1 < 10){
                    $pdf->SetTextColor(128,0,0);
                    $pdf->Cell(10,5,$mark1,1,0,'',false);
                }elseif($mark1 >= 17){
                    $pdf->SetTextColor(0,128,0);
                    $pdf->Cell(10,5,$mark1,1,0,'',false);
                }else{
                    $pdf->SetTextColor(0,0,0);
                    $pdf->Cell(10,5,$mark1,1,0,'',false);
                }   
                if($mark2 < 10){
                  $pdf->SetTextColor(128,0,0);
                  $pdf->Cell(10,5,$mark2,1,0,'',false);
              }elseif($mark2 >= 17){
                  $pdf->SetTextColor(0,128,0);
                  $pdf->Cell(10,5,$mark2,1,0,'',false);
              }else{
                  $pdf->SetTextColor(0,0,0);
                  $pdf->Cell(10,5,$mark2,1,0,'',false);
              }   
                  if($av_mark < 10){
                      $pdf->SetTextColor(128,0,0);
                      $pdf->Cell(10,5,$av_mark,1,0,'',false);
                  }elseif($av_mark >= 17){
                      $pdf->SetTextColor(0,128,0);
                      $pdf->Cell(10,5,$av_mark,1,0,'',false);
                  }else{
                      $pdf->SetTextColor(0,0,0);
                      $pdf->Cell(10,5,$av_mark,1,0,'',false);
                  }
                  $pdf->SetTextColor(0,0,0);
                  
                  if($av_mark >= 10){
                    $total_subs_pass++;
                  }
                  
                  if(substr($av_rank,-1,1) == '1' && $av_rank != '11'){
                      $pdf->Cell(9,5,$av_rank.'st',1,0,'', false);
                    }elseif(substr($av_rank,-1,1) == '2' && $av_rank != '12'){
                      $pdf->Cell(9,5,$av_rank.'nd',1,0,'', false);
                    }elseif(substr($av_rank,-1,1) == '3' && $av_rank != '13'){
                      $pdf->Cell(9,5,$av_rank.'rd',1,0,'', false);
                    }else{ 
                      $pdf->Cell(9,5,$av_rank.'th',1,0,'', false);
                    }
                  $pdf->Cell(16,5,$Model->GradeRemark(mark: $av_mark),1,0,'', false);
                  $min1 = $Model->AvMarks($exam_ids[0]['id'],$class_id, $year_id, $subject['subject'], 'MIN');
                  $min2 = $Model->AvMarks($exam_ids[1]['id'],$class_id, $year_id, $subject['subject'], 'MIN');
                  $avg1 = $Model->AvMarks($exam_ids[0]['id'],$class_id, $year_id, $subject['subject'], 'AVG');
                  $avg2 = $Model->AvMarks($exam_ids[1]['id'],$class_id, $year_id, $subject['subject'], 'AVG');
                  $max1 = $Model->AvMarks($exam_ids[0]['id'],$class_id, $year_id, $subject['subject'], 'MAX');
                  $max2 = $Model->AvMarks($exam_ids[1]['id'],$class_id, $year_id, $subject['subject'], 'MAX');
                  $pdf->Cell(13,5,round(($min1+$min2)/2,2),1,0,'C',false);
                  $pdf->Cell(13,5,round(($avg1+$avg2)/2,2),1,0,'C',false);
                  $pdf->Cell(12,5,round(($max1+$max2)/2,2),1,0,'C',false);
                  $succ1 = $Model->SuccessRate($exam_ids[0]['id'], $class_id, $year_id,  $subject['subject']);
                  $succ2 = $Model->SuccessRate($exam_ids[1]['id'], $class_id, $year_id,  $subject['subject']);
                  $pdf->Cell(12,5,round(($succ1+$succ2)/2,2).'%',1,0,'C',false);
                  $pdf->Ln();
                  }
              }  
              }
              if($total_coef != 0 && $t1_group_total !=0 && $t2_group_total != 0){
                $pdf->Cell(80,5,'Summary',0,0,'C',false);
                $pdf->Cell(10,5,$total_coef,0,0,'',false);
                $pdf->Cell(10,5,round($t1_group_total/$total_coef, 2),0,0,'',false);
                $pdf->Cell(10,5,round($t2_group_total/$total_coef, 2),0,0,'',false);
                $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(9,5,'',0,0,'', false);
                $pdf->Cell(16,5,'',0,0,'', false);
                $pdf->Cell(60,5,'',0,0,'C', false);
                $pdf->Ln();
              }else if($total_coef != 0 && $t1_group_total !=0 && $t2_group_total == 0){
                $pdf->Cell(80,5,'Summary',0,0,'C',false);
                $pdf->Cell(10,5,$total_coef,0,0,'',false);
                $pdf->Cell(10,5,round($t1_group_total/$total_coef, 2),0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(9,5,'',0,0,'', false);
                $pdf->Cell(16,5,'',0,0,'', false);
                $pdf->Cell(60,5,'',0,0,'C', false);
                $pdf->Ln();
              }else if($total_coef != 0 && $t1_group_total ==0 && $t2_group_total != 0){
                $pdf->Cell(80,5,'Summary',0,0,'C',false);
                $pdf->Cell(10,5,$total_coef,0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(10,5,round($t2_group_total/$total_coef, 2),0,0,'',false);
                $pdf->Cell(10,5,round($total_mark/$total_coef, 2),0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(9,5,'',0,0,'', false);
                $pdf->Cell(16,5,'',0,0,'', false);
                $pdf->Cell(60,5,'',0,0,'C', false);
                $pdf->Ln();
              }else if($total_coef != 0 && $t1_group_total ==0 && $t2_group_total == 0){
                $pdf->Cell(80,5,'',0,0,'C',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(10,5,'',0,0,'',false);
                $pdf->Cell(9,5,'',0,0,'', false);
                $pdf->Cell(16,5,'',0,0,'', false);
                $pdf->Cell(60,5,'',0,0,'C', false);
                $pdf->Ln();
              }
              
               //Get Sequence totals 
              $t1_total += $t1_group_total;
              $t2_total += $t2_group_total;
              //End print Others
              }
              if($general_coef != 0){
                  $pdf->Cell(80,5,'TOTAL',0,0,'C',false);
                  $pdf->Cell(10,5,$general_coef,0,0,'',false);
                  $pdf->Cell(10,5,round($t1_total/$general_coef, 2),0,0,'',false);
                  $pdf->Cell(10,5,round($t2_total/$general_coef, 2),0,0,'',false);
                  $pdf->Cell(10,5,round($general_total/$general_coef, 2),0,0,'',false);
                  $pdf->Cell(10,5,$general_total,0,0,'',false);
                  $pdf->Cell(9,5,'',0,0,'', false);
                  $pdf->Cell(16,5,'',0,0,'', false);
                  $pdf->Cell(60,5,'',0,0,'C', false);
              }else{
                  $pdf->Cell(80,5,'TOTAL',0,0,'C',false);
                  $pdf->Cell(10,5,'',0,0,'',false);
                  $pdf->Cell(10,5,'',0,0,'',false);
                  $pdf->Cell(10,5,'',0,0,'',false);
                  $pdf->Cell(10,5,'',0,0,'',false);
                  $pdf->Cell(10,5,'',0,0,'',false);
                  $pdf->Cell(9,5,'',0,0,'', false);
                  $pdf->Cell(16,5,'',0,0,'', false);
                  $pdf->Cell(60,5,'',0,0,'C', false);  
              }
              
              $pdf->Ln();
              $pdf->Cell(40,5,'Grading System',0,0,'C',false);
              $pdf->Cell(75,5,'Student',0,0,'C',false);
              $pdf->Cell(40,5,'Discipline',0,0,'C',false);
              $pdf->Cell(40,5,'Work',0,0,'C',false);
              $pdf->Ln();
              $pdf->Cell(20,5,'N >= 75.0%',1,0,'C',false);
              $pdf->SetTextColor(0,128,0);
              $pdf->Cell(5,5,'A',1,0,'C',false);
              $pdf->SetTextColor(0,0,0);
              $pdf->Cell(15,5,'Passed',1,0,'C',false);
              $pdf->Cell(1,5,'',0,0,'C',false);
              $pdf->Cell(78,25,'',1,0,'C',false);
              $pdf->Cell(1,5,'',0,0,'C',false);
              $just_abs = $Model->CountAbsences($year_id, $class_id, $term_name, $code, 'justabs');
              $pdf->Cell(40,5,'Justified absences(h): '.$just_abs,1,0,'L',false);
              $pdf->Cell(1,5,'',0,0,'C',false);
              $pdf->Cell(35,5,'Honour roll',1,0,'L',false);
              $pdf->Ln();
              $pdf->Cell(20,5,'N >= 60.0%',1,0,'C',false);
              $pdf->SetTextColor(0,128,0);
              $pdf->Cell(5,5,'B',1,0,'C',false);
              $pdf->SetTextColor(0,0,0);
              $pdf->Cell(15,5,'Passed',1,0,'C',false);
              $pdf->Cell(1,5,'',0,0,'C',false);
              $pdf->SetX(129);
              $pdf->Cell(1,5,'',0,0,'C',false);
              $unJust_abs = $Model->CountAbsences($year_id, $class_id, $term_name, $code, 'absences');
              $pdf->Cell(40,5,'Unjustified absences(h): '.$unJust_abs,1,0,'L',false);
              $pdf->Cell(1,5,'',0,0,'C',false);
              $pdf->Cell(35,5,'Encouragments',1,0,'L',false);
              $pdf->Ln();
              $pdf->Cell(20,5,'N >= 50.0%',1,0,'C',false);
              $pdf->SetTextColor(128,0,0);
              $pdf->Cell(5,5,'C',1,0,'C',false);
              $pdf->SetTextColor(0,0,0);
              $pdf->Cell(15,5,'Passed',1,0,'C',false);
              $pdf->Cell(1,5,'',0,0,'C',false);
              $pdf->SetX(129);
              $pdf->Cell(1,5,'',0,0,'C',false);
              $pdf->Cell(40,5,'Justified lateness(h)',1,0,'L',false);
              $pdf->Cell(1,5,'',0,0,'C',false);
              $pdf->Cell(35,5,'Congratulations',1,0,'L',false);
              $pdf->Ln();
              $pdf->Cell(20,5,'N >= 40.0%',1,0,'C',false);
              $pdf->SetTextColor(128,0,0);
              $pdf->Cell(5,5,'D',1,0,'C',false);
              $pdf->SetTextColor(0,0,0);
              $pdf->Cell(15,5,'Failed',1,0,'C',false);
              $pdf->Cell(1,5,'',0,0,'C',false);
              $pdf->SetX(129);
              $pdf->Cell(1,5,'',0,0,'C',false);
              $unjust_late = $Model->CountAbsences($year_id, $class_id, $term_name, $code, 'punishment');
              $pdf->Cell(40,5,'Unjustified lateness(h): '.$unjust_late,1,0,'L',false);
              $pdf->Cell(1,5,'',0,0,'C',false);
              
              $pdf->Cell(35,5,'Warnings',1,0,'L',false);
              $pdf->Ln();
              $pdf->Cell(20,5,'N >= 0.0%',1,0,'C',false);
              $pdf->SetTextColor(128,0,0);
              $pdf->Cell(5,5,'F',1,0,'C',false);
              $pdf->SetTextColor(0,0,0);
              $pdf->Cell(15,5,'Failed',1,0,'C',false);
              $pdf->Cell(1,5,'',0,0,'C',false);
              $pdf->SetX(129);
              $pdf->Cell(1,5,'',0,0,'C',false);
              $pdf->Cell(40,5,'Detention(h):',1,0,'L',false);
              $pdf->Cell(1,5,'',0,0,'C',false);
              $pdf->Cell(35,5,'Serious warning',1,0,'L',false);
              $pdf->SetXY(50,$pdf->GetY()+5);
              $pdf->Cell(75,5,'Class',0,0,'C',false);
              $pdf->Ln();
              $pdf->SetXY(51,$pdf->GetY());
              $pdf->Cell(78,25,'',1,0,'C',false);
              $pdf->Ln();
              $pdf->SetXY(130,$pdf->GetY()-30);
              $warn = $Model->CountAbsences($year_id, $class_id, $term_name, $code, 'warning');
              $pdf->Cell(40,5,'Warnings: '.$warn,1,0,'L',false);
              $pdf->SetXY(130,$pdf->GetY()+5);
              $pdf->Cell(40,5,'Serious Warnings:',1,0,'L',false);
              $pdf->SetXY(130,$pdf->GetY()+5);
              $susp = $Model->CountAbsences($year_id, $class_id, $term_name, $code, 'suspension');
              $pdf->Cell(40,5,'Exclusion(d): '.$susp,1,0,'L',false);
              $pdf->SetXY(53, $pdf->GetY()-35);
              $pdf->Cell(60,5,'Subjects passed',0,0,'L',false);
              $passed_subjects1 = $Model->SubjectsPassed($code, $exam_ids[0]['id'], $class_id, $year_id);
              $passed_subjects2 = $Model->SubjectsPassed($code, $exam_ids[1]['id'], $class_id, $year_id);
              $pdf->SetXY(100, $pdf->GetY());
                $pdf->Cell(10,5,$passed_subjects1,0,0,'C',false);
                $pdf->SetXY(110, $pdf->GetY());
                $pdf->Cell(10,5,$passed_subjects2,0,0,'C',false);
                $pdf->SetXY(120, $pdf->GetY());
                $pdf->Cell(10,5,$total_subs_pass,0,0,'C',false);
                $pdf->SetXY(53, $pdf->GetY()+5);
                $pdf->Cell(53,5,'Overall average (/20)',0,0,'L',false);
                $Seq_av1 = $Model->SequenceAverage($code, $exam_ids[0]['id'], $class_id, $year_id);
                $Seq_av2 = $Model->SequenceAverage($code, $exam_ids[1]['id'], $class_id, $year_id);
                $pdf->SetXY(100, $pdf->GetY());
                $pdf->Cell(10,5,$Seq_av1,0,0,'C',false);
                $pdf->SetXY(110, $pdf->GetY());
                $pdf->Cell(10,5,$Seq_av2,0,0,'C',false);
                $pdf->SetXY(120, $pdf->GetY());
                $pdf->Cell(10,5,round(($Seq_av1+$Seq_av2)/2,2),0,0,'C',false);
              $seq_rank1= $Model->RankAverage($exam_ids[0]['id'], $class_id, $year_id);
              $seq_rank2= $Model->RankAverage($exam_ids[1]['id'], $class_id, $year_id);
              $pdf->SetXY(53, $pdf->GetY()+5);
              $pdf->Cell(60,5,'Rank',0,0,'L',false);
              $pdf->SetXY(100, $pdf->GetY());
              $pdf->Cell(10,5,$seq_rank1[$code],0,0,'C',false);
              $pdf->SetXY(110, $pdf->GetY());
              $pdf->Cell(10,5,$seq_rank2[$code],0,0,'C',false);
              $pdf->SetXY(120, $pdf->GetY());
              $pdf->Cell(10,5,$pos,0,0,'C',false);
              $seq_worst1 = $Model->GetClassLast($exam_ids[0]['id'], $class_id, $year_id);
              $seq_worst2 = $Model->GetClassLast($exam_ids[1]['id'], $class_id, $year_id);
              if($term_name == 'Third') {
                $pdf->SetXY(58, $pdf->GetY()+5);
                $pdf->Cell(10,5,'Term1 Av: '.$term1[$code],0,0,'C',false);
                $pdf->SetXY(85, $pdf->GetY());
                $pdf->Cell(10,5,'Term2 Av: '.$term2[$code],0,0,'C',false);
                $pdf->SetXY(110, $pdf->GetY());
                $pdf->Cell(10,5,'Term3 Av: '.$averages[$code],0,0,'C',false);
                $pdf->SetXY(65, $pdf->GetY()+5);
                $pdf->SetTextColor(0,120,0);
                $pdf->Cell(10,5,'Annual Average: '.$annual_averages[$code],0,0,'C',false);
                $pdf->SetXY(53, $pdf->GetY()+10);
                $pdf->SetTextColor(0,0,0);
              }else{
                $pdf->SetXY(53, $pdf->GetY()+20);
              }
              $pdf->Cell(60,5,'Last student\'s average',0,0,'L',false);
              $pdf->SetXY(100, $pdf->GetY());
              $pdf->Cell(10,5,$seq_worst1,0,0,'C',false);
              $pdf->SetXY(110, $pdf->GetY());
              $pdf->Cell(10,5,$seq_worst2,0,0,'C',false);
              $pdf->SetXY(120, $pdf->GetY());
              $pdf->Cell(10,5,$termBest['last'],0,0,'C',false);
              $pdf->SetXY(53, $pdf->GetY()+5);
              $pdf->Cell(60,5,'Best student\'s average',0,0,'L',false);
              $seq_best1 = $Model->GetClassBest($exam_ids[0]['id'], $class_id, $year_id);
              $seq_best2 = $Model->GetClassBest($exam_ids[1]['id'], $class_id, $year_id);
              $pdf->SetXY(100, $pdf->GetY());
              $pdf->Cell(10,5,$seq_best1,0,0,'C',false);
              $pdf->SetXY(110, $pdf->GetY());
              $pdf->Cell(10,5,$seq_best2,0,0,'C',false);
              $pdf->SetXY(120, $pdf->GetY());
              $pdf->Cell(10,5,$termBest['best'],0,0,'C',false);
              $pdf->SetXY(53, $pdf->GetY()+5);
              $pdf->Cell(60,5,'Success rate (%)',0,0,'L',false);
              $sr1 = $Model->SuccessRateClass($exam_ids[0]['id'], $class_id, $year_id);
              $sr2 = $Model->SuccessRateClass($exam_ids[1]['id'], $class_id, $year_id);
              $pdf->SetXY(100, $pdf->GetY());
              $pdf->Cell(10,5,$sr1,0,0,'C',false);
              $pdf->SetXY(110, $pdf->GetY());
              $pdf->Cell(10,5,$sr2,0,0,'C',false);
              $pdf->SetXY(120, $pdf->GetY());
              $pdf->Cell(10,5,$succ,0,0,'C',false);
              $pdf->SetXY(53, $pdf->GetY()+5);
              $pdf->Cell(60,5,'Standard deviation',0,0,'L',false);
              $sdev1 = round($Model->StandardDeviation($exam_ids[0]['id'], $class_id, $year_id),2);
              $sdev2 = round($Model->StandardDeviation($exam_ids[1]['id'], $class_id, $year_id),2);
              $pdf->SetXY(100, $pdf->GetY());
              $pdf->Cell(10,5,$sdev1,0,0,'C',false);
              $pdf->SetXY(110, $pdf->GetY());
              $pdf->Cell(10,5,$sdev2,0,0,'C',false);
              $pdf->SetXY(120, $pdf->GetY());
              $pdf->Cell(10,5,round(($sdev1 + $sdev2)/2, 2),0,0,'C',false);
              $pdf->SetXY(53, $pdf->GetY()+5);
              $pdf->Cell(60,5,'Class average',0,0,'L',false);
              $class_av1 = round($Model->GetClassAverage($exam_ids[0]['id'], $class_id, $year_id),2);
              $class_av2 = round($Model->GetClassAverage($exam_ids[1]['id'], $class_id, $year_id),2);
              $pdf->SetXY(100, $pdf->GetY());
              $pdf->Cell(10,5,$class_av1,0,0,'C',false);
              $pdf->SetXY(110, $pdf->GetY());
              $pdf->Cell(10,5,$class_av2,0,0,'C',false);
              $pdf->SetXY(120, $pdf->GetY());
              $pdf->Cell(10,5,$classAv,0,0,'C',false);
              $pdf->SetXY(0, $pdf->GetY()+20);
              $pdf->Cell(40,5,'Parent',0,0,'C',false);
              $pdf->Cell(50,5,'Class master',0,0,'C',false);
              $pdf->Cell(80,5,'Supervisor',0,0,'C',false);
              $pdf->Cell(20,5,'Principal',0,0,'C',false);
              $pdf->Output();
              break;
            }
        } 
    }
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}
