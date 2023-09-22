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
        // Logo
        $this->Image('../img/letterhead.png',2,2,200);
        //watermark
        $this->Image('../img/map.jpg',10,40,195,160);
        // Line break
        $this->Ln(30);
    }
 
    // Page footer
    function Footer()
    {
        //$this->Image('../img/footer.png',2,260,200);
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
$term_name = $_GET['term_id'];
$exam_ids = $Model->ExamsForTerm($term_name, $year_id, $section);
$eval1 = 1; $eval2 = 2; $sequence = '';

$positions = $Model->GetPosition($year_id, $class_id, strToUpper($term_name).' TERM');
$termBest = $Model->GetTermBest($year_id, $class_id, strToUpper($term_name).' TERM');
$classAv = round($Model->ClassAverageForTerm($year_id, $class_id, strToUpper($term_name).' TERM'),2);
$averages = $Model->GetTermAverageForStudent($year_id, $class_id, strToUpper($term_name).' TERM');
$pass_av = 0;
foreach($averages as $av){
    if ($av >= 10){
        $pass_av++;
    }
}
$succ = round(($pass_av/count($averages))*100,2);
$fg = false; $sg = false; $tg = false;
 if(!empty($positions)){
    $pdf = new PDF();
    foreach($positions as $code => $pos){
        $s = $Model->GetStudent($code, $section);
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial','B',9);
        $pdf->SetFillColor(0,0,128);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(60, 5, "", 0);
        if($term_name == 'First'){
            $pdf->Cell(30,5,$lang[$_SESSION['lang']]["FirstTermRep"] ,0);
            $eval1 = 1; $eval2 = 2;
        }elseif($term_name == 'Second'){
            $pdf->Cell(30,5,$lang[$_SESSION['lang']]["SecondTermRep"] ,0);
            $eval1 = 3; $eval2 = 4; 
        }elseif($term_name == 'Third'){
            $pdf->Cell(30,5,$lang[$_SESSION['lang']]["ThirdTermRep"] ,0);
            $eval1 = 5; $eval2 = 6; ;
        }else{
            $pdf->Cell(30,5,$lang[$_SESSION['lang']]["SpecialTermRep"] ,0);
            $eval1 = 7; $eval2 = 8;
        }
        
        $pdf->Ln();
        $pdf->Cell(90,5,$lang[$_SESSION['lang']]["NameAndSurname"].': '.$s[0]['name'],0);
        $pdf->Cell(30,5,$lang[$_SESSION['lang']]["Gender"].': '.$s[0]['gender'],0);
        $pdf->Cell(60,5,$lang[$_SESSION['lang']]["DOB"].': '.$s[0]['dob'].' at '.$s[0]['pob'],0);
        $pdf->Ln();
        $pdf->Cell(90,5,$lang[$_SESSION['lang']]["AdmissionNum"].': '.$s[0]['adm_num'],0);
        $pdf->Cell(50,5,$lang[$_SESSION['lang']]["Class"].':'.$Model->GetAClassName($class_id),0);
        $pdf->Cell(70,5,$lang[$_SESSION['lang']]["Onroll"].':'.count($positions),0);
        $pdf->Ln();
        $pdf->Cell(90,5,$lang[$_SESSION['lang']]["Classmaster"].':___________________________________ ',0);
        $pdf->Cell(70,5,$lang[$_SESSION['lang']]["Repeater"].':',0);
        $pdf->Ln();
        //$pdf->Cell(60, 5, "", 0);
        $pdf->SetFont('Arial','',8);
        if(!empty($Model->GetAllWithCriteria('subjects', ['class_name' => $class_id, 'rep_group'=>1,'section' => $section]))){
            $fg = true;
            $pdf->SetTextColor(255,255,255);
            $pdf->Cell(195,5,$lang[$_SESSION['lang']]["FirstGroupSubs"],1,0,'C', true);
            $pdf->Ln();
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(35,5,utf8_decode($lang[$_SESSION['lang']]["Subject"]),1);
            $pdf->Cell(50,5,utf8_decode($lang[$_SESSION['lang']]["Competences tested"]),1);
            $pdf->Cell(10,5,'Eval'.$eval1,1);
            $pdf->Cell(10,5,'Eval'.$eval2,1);
            $pdf->Cell(10,5,$lang[$_SESSION['lang']]["Mark"],1);
            $pdf->Cell(10,5,'Coef',1);
            $pdf->Cell(10,5,'Total',1);
            $pdf->Cell(10,5,$lang[$_SESSION['lang']]["Rank"],1);
            $pdf->Cell(10,5,'Appr',1);
            $pdf->Cell(10,5,'Grade',1);
            $pdf->Cell(30,5,$lang[$_SESSION['lang']]["Teacher"],1);
            $pdf->Ln();
            $subjects = $Model->ViewClassSubjects($class_id);
            $total_coef = 0;
            $total_mark = 0;
            foreach($subjects as $subject){
                if($Model->GetRepGroup($subject['subject'], $class_id, $section) == 1){
                    $total_sub_total = 0.0;
                    $mark1 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[0]['id']]);
                    $mark2 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[1]['id']]);
                    $av_mark = round(((float)$mark1 + (float)$mark2)/2, 2);
                    $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                    $total_coef += $coef;
                    $total_sub_total += $av_mark * $coef;
                    $total_mark += $total_sub_total;
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

                    if(strlen($subject['subject']) > 18){
                        $pdf->Cell(35,5,substr($subject['subject'], 0, 18),1);
                    }else{
                        $pdf->Cell(35,5,$subject['subject'],1);
                    }

                    $pdf->Cell(50,5,$Model->GetSequenceCompetence($year_id, $class_id, $exam_ids[1]['id'], $code, $subject['subject']),1);
                    if($mark1 < 10){
                        $pdf->SetTextColor(255,0,0);
                        $pdf->Cell(10,5,$mark1,1);
                    }else{
                        $pdf->SetTextColor(0,0,0);
                        $pdf->Cell(10,5,$mark1,1);
                    }

                    if($mark2 < 10){
                        $pdf->SetTextColor(255,0,0);
                        $pdf->Cell(10,5,$mark2,1);
                    }else{
                        $pdf->SetTextColor(0,0,0);
                        $pdf->Cell(10,5,$mark2,1);
                    }

                    if($av_mark < 10){
                        $pdf->SetTextColor(255,0,0);
                        $pdf->Cell(10,5,$av_mark,1);
                    }else{
                        $pdf->SetTextColor(0,0,0);
                        $pdf->Cell(10,5,$av_mark,1);
                    }
                    $pdf->SetTextColor(0,0,0);
                    $pdf->Cell(10,5,$coef,1);
                    $pdf->Cell(10,5,$total_sub_total,1);
                    $pdf->Cell(10,5,$av_rank,1);
                    $pdf->Cell(10,5,'',1);
        
                    $remark = "";
                    if($av_mark < 10){$remark = "NA";}elseif($av_mark >= 10 && $av_mark <= 13){$remark = "ATBA";}elseif($av_mark > 13 && $av_mark <= 16){$remark = "A";}elseif($av_mark > 16){$remark = "A+";}
        
                    $pdf->Cell(10,5,$remark,1);
                    $pdf->Cell(30,5,$Model->GetStaffName($Model->GetSubjectTeacher($subject['subject'], $class_id, $year_id)),1);
                    $pdf->Ln();
        
                }
            }
            $group_av1 = '';
            if($total_coef != 0 ){
                $group_av1 = round($total_mark/$total_coef,2);
            }
            $pdf->Cell(105,5,$lang[$_SESSION['lang']]["GroupTotal"],1,0,'R', false);
            $pdf->Cell(10,5,$group_av1,1);
            $pdf->Cell(10,5,$total_coef,1);
            $pdf->Cell(10,5,$total_mark,1);
            $pdf->Cell(60,5,'Remarks: ',1);
            $pdf->Ln();
        
            $general_coef = $total_coef;
            $general_total = $total_mark;
        }
        
        if(!empty($Model->GetAllWithCriteria('subjects', ['class_name' => $class_id, 'rep_group'=>2,'section' => $section]))){
            $sg = true;
            //Group 2 subjects
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(195,5,$lang[$_SESSION['lang']]["SecondGroupSubs"],1,0,'C', true);
        $pdf->Ln();
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(35,5,utf8_decode($lang[$_SESSION['lang']]["Subject"]),1);
        $pdf->Cell(50,5,utf8_decode($lang[$_SESSION['lang']]["Competences tested"]),1);
        $pdf->Cell(10,5,'Eval'.$eval1,1);
        $pdf->Cell(10,5,'Eval'.$eval2,1);
        $pdf->Cell(10,5,$lang[$_SESSION['lang']]["Mark"],1);
        $pdf->Cell(10,5,'Coef',1);
        $pdf->Cell(10,5,'Total',1);
        $pdf->Cell(10,5,$lang[$_SESSION['lang']]["Rank"],1);
        $pdf->Cell(10,5,'Appr',1);
        $pdf->Cell(10,5,'Grade',1);
        $pdf->Cell(30,5,$lang[$_SESSION['lang']]["Teacher"],1);
        $pdf->Ln();
        $total_coef = 0;
        $total_mark = 0;
        foreach($subjects as $subject){
            if($Model->GetRepGroup($subject['subject'], $class_id, $section) == 2){
                $total_sub_total = 0.0;
                $mark1 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[0]['id']]);
                $mark2 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[1]['id']]);
                $av_mark = round(((float)$mark1 + (float)$mark2)/2, 2);
                $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                $total_coef += $coef;
                $total_sub_total += $av_mark * $coef;
                $total_mark += $total_sub_total;
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

                if(strlen($subject['subject']) > 18){
                    $pdf->Cell(35,5,substr($subject['subject'], 0, 18),1);
                }else{
                    $pdf->Cell(35,5,$subject['subject'],1);
                }
                $pdf->Cell(50,5,$Model->GetSequenceCompetence($year_id, $class_id, $exam_ids[1]['id'], $code, $subject['subject']),1);
                if($mark1 < 10){
                    $pdf->SetTextColor(255,0,0);
                    $pdf->Cell(10,5,$mark1,1);
                }else{
                    $pdf->SetTextColor(0,0,0);
                    $pdf->Cell(10,5,$mark1,1);
                }

                if($mark2 < 10){
                    $pdf->SetTextColor(255,0,0);
                    $pdf->Cell(10,5,$mark2,1);
                }else{
                    $pdf->SetTextColor(0,0,0);
                    $pdf->Cell(10,5,$mark2,1);
                }

                if($av_mark < 10){
                    $pdf->SetTextColor(255,0,0);
                    $pdf->Cell(10,5,$av_mark,1);
                }else{
                    $pdf->SetTextColor(0,0,0);
                    $pdf->Cell(10,5,$av_mark,1);
                }
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(10,5,$coef,1);
                $pdf->Cell(10,5,$total_sub_total,1);
                $pdf->Cell(10,5,$av_rank,1);
                $pdf->Cell(10,5,'',1);
    
                $remark = "";
                if($av_mark < 10){$remark = "NA";}elseif($av_mark >= 10 && $av_mark <= 13){$remark = "ATBA";}elseif($av_mark > 13 && $av_mark <= 16){$remark = "A";}elseif($av_mark > 16){$remark = "A+";}
    
                $pdf->Cell(10,5,$remark,1);
                $pdf->Cell(30,5,$Model->GetStaffName($Model->GetSubjectTeacher($subject['subject'], $class_id, $year_id)),1);
                $pdf->Ln();
    
            }
        }
        $group_av2 = '';
        if($total_coef != 0 ){
            $group_av2 = round($total_mark/$total_coef,2);
        }

        $pdf->Cell(105,5,$lang[$_SESSION['lang']]["GroupTotal"],1,0,'R', false);
        $pdf->Cell(10,5,$group_av2,1);
        $pdf->Cell(10,5,$total_coef,1);
        $pdf->Cell(10,5,$total_mark,1);
        $pdf->Cell(60,5,'Remarks: ',1);
        $pdf->Ln();
    
        $general_coef += $total_coef;
        $general_total += $total_mark;
        }
        
        if(!empty($Model->GetAllWithCriteria('subjects', ['class_name' => $class_id, 'rep_group'=>3,'section' => $section]))){
            $tg = true;
            //Group 3 subjects
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(195,5,$lang[$_SESSION['lang']]["ThirdGroupSubs"],1,0,'C', true);
        $pdf->Ln();
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(35,5,utf8_decode($lang[$_SESSION['lang']]["Subject"]),1);
        $pdf->Cell(50,5,utf8_decode($lang[$_SESSION['lang']]["Competences tested"]),1);
        $pdf->Cell(10,5,'Eval'.$eval1,1);
        $pdf->Cell(10,5,'Eval'.$eval2,1);
        $pdf->Cell(10,5,$lang[$_SESSION['lang']]["Mark"],1);
        $pdf->Cell(10,5,'Coef',1);
        $pdf->Cell(10,5,'Total',1);
        $pdf->Cell(10,5,$lang[$_SESSION['lang']]["Rank"],1);
        $pdf->Cell(10,5,'Appr',1);
        $pdf->Cell(10,5,'Grade',1);
        $pdf->Cell(30,5,$lang[$_SESSION['lang']]["Teacher"],1);
        $pdf->Ln();
        
        $total_coef = 0;
        $total_mark = 0;
        foreach($subjects as $subject){
            if($Model->GetRepGroup($subject['subject'], $class_id, $section) == 3){
                $total_sub_total = 0.0;
                $mark1 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[0]['id']]);
                $mark2 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[1]['id']]);
                $av_mark = round(((float)$mark1 + (float)$mark2)/2, 2);
                $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                $total_coef += $coef;
                $total_sub_total += $av_mark * $coef;
                $total_mark += $total_sub_total;
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

                if(strlen($subject['subject']) > 18){
                    $pdf->Cell(35,5,substr($subject['subject'], 0, 18),1);
                }else{
                    $pdf->Cell(35,5,$subject['subject'],1);
                }
                $pdf->Cell(50,5,$Model->GetSequenceCompetence($year_id, $class_id, $exam_ids[1]['id'], $code, $subject['subject']),1);
                if($mark1 < 10){
                    $pdf->SetTextColor(255,0,0);
                    $pdf->Cell(10,5,$mark1,1);
                }else{
                    $pdf->SetTextColor(0,0,0);
                    $pdf->Cell(10,5,$mark1,1);
                }

                if($mark2 < 10){
                    $pdf->SetTextColor(255,0,0);
                    $pdf->Cell(10,5,$mark2,1);
                }else{
                    $pdf->SetTextColor(0,0,0);
                    $pdf->Cell(10,5,$mark2,1);
                }

                if($av_mark < 10){
                    $pdf->SetTextColor(255,0,0);
                    $pdf->Cell(10,5,$av_mark,1);
                }else{
                    $pdf->SetTextColor(0,0,0);
                    $pdf->Cell(10,5,$av_mark,1);
                }
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(10,5,$coef,1);
                $pdf->Cell(10,5,$total_sub_total,1);
                $pdf->Cell(10,5,$av_rank,1);
                $pdf->Cell(10,5,'',1);
    
                $remark = "";
                if($av_mark < 10){$remark = "NA";}elseif($av_mark >= 10 && $av_mark <= 13){$remark = "ATBA";}elseif($av_mark > 13 && $av_mark <= 16){$remark = "A";}elseif($av_mark > 16){$remark = "A+";}
    
                $pdf->Cell(10,5,$remark,1);
                $pdf->Cell(30,5,$Model->GetStaffName($Model->GetSubjectTeacher($subject['subject'], $class_id, $year_id)),1);
                $pdf->Ln();
    
            }
        }

        $group_av3 = '';
        if($total_coef != 0 ){
            $group_av3 = round($total_mark/$total_coef,2);
        }

        $pdf->Cell(105,5,$lang[$_SESSION['lang']]["GroupTotal"],1,0,'R', false);
        $pdf->Cell(10,5,$group_av3,1);
        $pdf->Cell(10,5,$total_coef,1);
        $pdf->Cell(10,5,$total_mark,1);
        $pdf->Cell(60,5,'Remarks: ',1);
        $pdf->Ln();
    
        $general_coef += $total_coef;
        $general_total += $total_mark;
        }
    
        if($fg == false && $sg == false && $tg == false){
            $pdf->SetTextColor(255,255,255);
        $pdf->Ln();
        $pdf->Cell(35,5,utf8_decode($lang[$_SESSION['lang']]["Subject"]),1, 0, '', true);
        $pdf->Cell(50,5,utf8_decode($lang[$_SESSION['lang']]["Competences tested"]),1, 0, '', true);
        $pdf->Cell(10,5,'Eval'.$eval1,1, 0, '', true);
        $pdf->Cell(10,5,'Eval'.$eval2,1, 0, '', true);
        $pdf->Cell(10,5,$lang[$_SESSION['lang']]["Mark"],1, 0, '', true);
        $pdf->Cell(10,5,'Coef',1, 0, '', true);
        $pdf->Cell(10,5,'Total',1, 0, '', true);
        $pdf->Cell(10,5,$lang[$_SESSION['lang']]["Rank"],1, 0, '', true);
        $pdf->Cell(10,5,'Appr',1, 0, '', true);
        $pdf->Cell(10,5,'Grade',1, 0, '', true);
        $pdf->Cell(30,5,$lang[$_SESSION['lang']]["Teacher"],1, 0, '', true);
        $pdf->Ln();
        $pdf->SetTextColor(0,0,0);
        $subjects = $Model->ViewClassSubjects($class_id);
        $total_coef = 0;
        $total_mark = 0;
        foreach($subjects as $subject){
                $total_sub_total = 0.0;
                $mark1 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[0]['id']]);
                $mark2 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[1]['id']]);
                $av_mark = round(((float)$mark1 + (float)$mark2)/2, 2);
                $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                $total_coef += $coef;
                $total_sub_total += $av_mark * $coef;
                $total_mark += $total_sub_total;
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

                if(strlen($subject['subject']) > 18){
                    $pdf->Cell(35,5,substr($subject['subject'], 0, 18),1);
                }else{
                    $pdf->Cell(35,5,$subject['subject'],1);
                }
                $pdf->Cell(50,5,$Model->GetSequenceCompetence($year_id, $class_id, $exam_ids[1]['id'], $code, $subject['subject']),1);
                if($mark1 < 10){
                    $pdf->SetTextColor(255,0,0);
                    $pdf->Cell(10,5,$mark1,1);
                }else{
                    $pdf->SetTextColor(0,0,0);
                    $pdf->Cell(10,5,$mark1,1);
                }

                if($mark2 < 10){
                    $pdf->SetTextColor(255,0,0);
                    $pdf->Cell(10,5,$mark2,1);
                }else{
                    $pdf->SetTextColor(0,0,0);
                    $pdf->Cell(10,5,$mark2,1);
                }

                if($av_mark < 10){
                    $pdf->SetTextColor(255,0,0);
                    $pdf->Cell(10,5,$av_mark,1);
                }else{
                    $pdf->SetTextColor(0,0,0);
                    $pdf->Cell(10,5,$av_mark,1);
                }
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(10,5,$coef,1);
                $pdf->Cell(10,5,$total_sub_total,1);
                $pdf->Cell(10,5,$av_rank,1);
                $pdf->Cell(10,5,'',1);
    
                $remark = "";
                if($av_mark < 10){$remark = "NA";}elseif($av_mark >= 10 && $av_mark <= 13){$remark = "ATBA";}elseif($av_mark > 13 && $av_mark <= 16){$remark = "A";}elseif($av_mark > 16){$remark = "A+";}
    
                $pdf->Cell(10,5,$remark,1);
                $pdf->Cell(30,5,$Model->GetStaffName($Model->GetSubjectTeacher($subject['subject'], $class_id, $year_id)),1);
                $pdf->Ln();
        }
    
        $general_coef = $total_coef;
        $general_total = $total_mark;
        }
        
        $general_av = round($general_total/$general_coef,2);
        
        $pdf->Cell(105,5,$lang[$_SESSION['lang']]["Total"],1,0,'R', false);
        $pdf->Cell(10,5,'',1);//could fill with general average
        $pdf->Cell(10,5,$general_coef,1);
        $pdf->Cell(10,5,$general_total,1);
        $pdf->Cell(60,5,$lang[$_SESSION['lang']]["Average"].': '.$averages[$code],1);
        $pdf->Ln();

        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(65,5,$lang[$_SESSION['lang']]["WorkAppr"],1,0,'C', true);
        $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Averages"],1,0,'C', true);
        $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Rank"].': ',1,0,'C', true);
        $pdf->Ln();
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(65,5,'',1,0,'C', false);
        $pdf->Cell(21,5,isset($group_av1)?$group_av1:'',1,0,'C', false);
        $pdf->Cell(21,5,isset($group_av2)?$group_av2:'',1,0,'C', false);
        $pdf->Cell(23,5,isset($group_av3)?$group_av3:'',1,0,'C', false);
        $eff = count($positions);

        $num = $positions[$code];
        $position = $num."/".$eff;
        $pdf->Cell(65,5,$position,1,0,'C', false);
        
        $pdf->Ln();
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Conduct"].':',1,0,'C', true);
        $pdf->Cell(33,5,$lang[$_SESSION['lang']]["Discipline"].':',1,0,'C', true);
        $pdf->Cell(32,5,$lang[$_SESSION['lang']]["ClassProfile"].':',1,0,'C', true);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Principal"].':',0,0,'C', false);
        $pdf->Ln();
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(27,5,$lang[$_SESSION['lang']]["JustAbs"],1,0,'C', false);
        $pdf->Cell(5,5,$Model->CountAbsences($year_id, $class_id, $term_name, $code, 'absences'),1,0,'C', false);
        $pdf->Cell(28,5,$lang[$_SESSION['lang']]["UnJustAbs"],1,0,'C', false);
        $pdf->Cell(5,5,'0',1,0,'C', false);
        $pdf->Cell(33,5,'',1,0,'C', false);
        $pdf->Cell(32,5,$lang[$_SESSION['lang']]["ClassAv"].$classAv,1,0,'J', false);
        $pdf->Ln();
        $pdf->Cell(27,5,$lang[$_SESSION['lang']]["WarningsD"],1,0,'C', false);
        $pdf->Cell(5,5,$Model->CountAbsences($year_id, $class_id, $term_name, $code, 'warning'),1,0,'C', false);
        $pdf->Cell(28,5,$lang[$_SESSION['lang']]["WarningsH"],1,0,'C', false);
        $pdf->Cell(5,5,'0',1,0,'C', false);
        $pdf->Cell(33,5,'',1,0,'C', false);
        $pdf->Cell(32,5,$lang[$_SESSION['lang']]["SuccRate"].' '.$succ,1,0,'J', false);
        $pdf->Ln();
        $pdf->Cell(27,5,$lang[$_SESSION['lang']]["Lateness"],1,0,'C', false);
        $pdf->Cell(5,5,$Model->CountAbsences($year_id, $class_id, $term_name, $code, 'punishment'),1,0,'C', false);
        $pdf->Cell(28,5,$lang[$_SESSION['lang']]["Suspension"],1,0,'C', false);
        $pdf->Cell(5,5,$Model->CountAbsences($year_id, $class_id, $term_name, $code, 'suspension'),1,0,'C', false);
        $pdf->Cell(65,5,'',1,0,'J', false);
        $pdf->Ln();
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(65,5,$lang[$_SESSION['lang']]["ApprPP"],1,0,'C', true);
        $pdf->Cell(33,5,$lang[$_SESSION['lang']]["ParentSign"],1,0,'C', true);
        $pdf->Cell(16,5,$lang[$_SESSION['lang']]["BestAv"],1,0,'C', true);
        $pdf->Cell(16,5,$lang[$_SESSION['lang']]["WorstAv"],1,0,'C', true);
        $pdf->Cell(65,5,'',0,0,'L', false);
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln();
        $pdf->Cell(65,5,'',1,0,'C', false);
        $pdf->Cell(33,5,'',1,0,'C', false);
        $pdf->Cell(16,5,$termBest['best'],1,0,'C', false);
        $pdf->Cell(16,5,$termBest['last'],1,0,'C', false);
        $pdf->Cell(65,5,'',0,0,'L', false);
    }
       $pdf->Output();
 }
 
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}