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
        //watermark
        $this->Image('../img/map.jpg',10,40,195,160);
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
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        
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
$fg = false; $sg = false; $tg = false;
$general_coef = 0;
$general_total = 0;

if(!empty($means)){
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
        $pdf->Cell(30,5,$lang[$_SESSION['lang']]["FirstSeqEval"] ,0);
        $eval1 = 1; $eval2 = 2; $sequence = '1';
    }elseif($stud_av['term'] == 'FIRST TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE TWO'){
        $pdf->Cell(30,5,$lang[$_SESSION['lang']]["SecondSeqEval"] ,0);
        $eval1 = 1; $eval2 = 2; $sequence = '2';
    }elseif($stud_av['term'] == 'SECOND TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE ONE'){
        $pdf->Cell(30,5,$lang[$_SESSION['lang']]["ThirdSeqEval"] ,0);
        $eval1 = 3; $eval2 = 4; $sequence = '3';
    }elseif($stud_av['term'] == 'SECOND TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE TWO'){
        $pdf->Cell(30,5,$lang[$_SESSION['lang']]["FourthSeqEval"] ,0);
        $eval1 = 3; $eval2 = 4;  $sequence = '4';
    }elseif($stud_av['term'] == 'THIRD TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE ONE'){
        $pdf->Cell(30,5,$lang[$_SESSION['lang']]["FifthSeqEval"] ,0);
        $eval1 = 5; $eval2 = 6; $sequence = '5';
    }elseif($stud_av['term'] == 'THIRD TERM' && $Model->GetSequenceName($exam_id) == 'SEQUENCE TWO'){
        $pdf->Cell(30,5,$lang[$_SESSION['lang']]["SixthSeqEval"] ,0);
        $eval1 = 5; $eval2 = 6; $sequence = '6';
    }
    
    $pdf->Ln();
    $pdf->Cell(90,5,$lang[$_SESSION['lang']]["NameAndSurname"].': '.$s[0]['name'],0);
    $pdf->Cell(30,5,$lang[$_SESSION['lang']]["Gender"].': '.$s[0]['gender'],0);
    $pdf->Cell(60,5,$lang[$_SESSION['lang']]["DOB"].': '.$s[0]['dob'].' at '.$s[0]['pob'],0);
    $pdf->Ln();
    $pdf->Cell(90,5,$lang[$_SESSION['lang']]["AdmissionNum"].': '.$s[0]['adm_num'],0);
    $pdf->Cell(50,5,$lang[$_SESSION['lang']]["Class"].':'.$Model->GetAClassName($class_id),0);
    $pdf->Cell(70,5,$lang[$_SESSION['lang']]["Onroll"].':'.count($means),0);
    $pdf->Ln();
    $pdf->Cell(90,5,$lang[$_SESSION['lang']]["Classmaster"].':___________________________________ ',0);
    $pdf->Cell(70,5,$lang[$_SESSION['lang']]["Repeater"].':',0);
    $pdf->Ln();
    //$pdf->Cell(60, 5, "", 0);
    $pdf->SetFont('Arial','',8);

    //Check if the first group exists
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
        $marks = $Model->GetStudentsMarks($year_id, $class_id, $exam_id, $stud_av['student']);
        $total_coef = 0;
        $total_mark = 0;
        foreach($marks as $mark){
            if($Model->GetRepGroup($mark['subject'], $class_id, $section) == 1){
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

                $pdf->Cell(50,5,$mark['competence'],1);
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
                $pdf->Cell(10,5,$sub_total['rank'],1);
                $pdf->Cell(10,5,$sub_total['grade'],1);
                $pdf->Cell(10,5,$sub_total['remark'],1);
                $pdf->Cell(30,5,$Model->GetStaffName($Model->GetSubjectTeacher($mark['subject'], $class_id, $year_id)),1);
                $pdf->Ln();
                }
            }
        }
        $group_av1 = '';
        if ($total_coef != 0){
            $group_av1 = round($total_mark/$total_coef,2);
        }

        $pdf->Cell(105,5,$lang[$_SESSION['lang']]["GroupTotal"],1,0,'R', false);
        $pdf->Cell(10,5,$group_av1,1);
        $pdf->Cell(10,5,$total_coef,1);
        $pdf->Cell(10,5,$total_mark,1);
        $pdf->Cell(60,5,'Remarks: ',1);
        $pdf->Ln();

        $general_coef += $total_coef; $general_total += $total_mark; //End check for first group
    }
    
    if(!empty($Model->GetAllWithCriteria('subjects', ['class_name' => $class_id, 'rep_group'=>2,'section' => $section]))){
//check if Group 2 subjects exist
    $sg = true;
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(195,5,$lang[$_SESSION['lang']]["SecondGroupSubs"] ,1,0,'C', true);
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
    foreach($marks as $mark){
        if($Model->GetRepGroup($mark['subject'], $class_id, $section) == 2){
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
                $pdf->Cell(50,5,$mark['competence'],1);
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
                $pdf->Cell(10,5,$sub_total['rank'],1);
                $pdf->Cell(10,5,$sub_total['grade'],1);
                $pdf->Cell(10,5,$sub_total['remark'],1);
                $pdf->Cell(30,5,strToUpper($Model->GetStaffName($Model->GetSubjectTeacher($mark['subject'], $class_id, $year_id))),1);
                $pdf->Ln();
            }
        }
    }

    $group_av2 = '';
    if ($total_coef != 0){
        $group_av2 = round($total_mark/$total_coef,2);
    }
    
    $pdf->Cell(105,5,$lang[$_SESSION['lang']]["GroupTotal"],1,0,'R', false);
    $pdf->Cell(10,5,$group_av2,1);
    $pdf->Cell(10,5,$total_coef,1);
    $pdf->Cell(10,5,$total_mark,1);
    $pdf->Cell(60,5,'Remarks: ',1);
    $pdf->Ln();
    $general_coef += $total_coef; $general_total += $total_mark;
    //end check group 2 subjects
}

if(!empty($Model->GetAllWithCriteria('subjects', ['class_name' => $class_id, 'rep_group'=>3,'section' => $section]))){
    $tg = true;
//check group 3 subjects
    //Group 3 subjects
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(195,5,$lang[$_SESSION['lang']]["ThirdGroupSubs"] ,1,0,'C', true);
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
    foreach($marks as $mark){
        if($Model->GetRepGroup($mark['subject'], $class_id, $section) == 3){
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
                $pdf->Cell(50,5,$mark['competence'],1);
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
                $pdf->Cell(10,5,$sub_total['rank'],1);
                $pdf->Cell(10,5,$sub_total['grade'],1);
                $pdf->Cell(10,5,$sub_total['remark'],1);
                $pdf->Cell(30,5,$Model->GetStaffName($Model->GetSubjectTeacher($mark['subject'], $class_id, $year_id)),1);
                $pdf->Ln();
            }
        }
    }
    
    $group_av3 = '';
    if ($total_coef != 0){
        $group_av3 = round($total_mark/$total_coef,2);
    }

    $pdf->Cell(105,5,$lang[$_SESSION['lang']]["GroupTotal"],1,0,'R', false);
    $pdf->Cell(10,5,$group_av3,1);
    $pdf->Cell(10,5,$total_coef,1);
    $pdf->Cell(10,5,$total_mark,1);
    $pdf->Cell(60,5,'Remarks: ',1);
    $pdf->Ln();
    $general_coef += $total_coef; $general_total += $total_mark;
//End check for group 3 subjects
}

if($fg == false && $sg == false && $tg == false){
    $pdf->SetTextColor(255,255,255);
    $pdf->Ln();
    $pdf->Cell(35,5,utf8_decode($lang[$_SESSION['lang']]["Subject"]),1, 0,'', true);
    $pdf->Cell(50,5,utf8_decode($lang[$_SESSION['lang']]["Competences tested"]),1, 0,'', true);
    $pdf->Cell(10,5,'Eval'.$eval1,1, 0,'', true);
    $pdf->Cell(10,5,'Eval'.$eval2,1, 0,'', true);
    $pdf->Cell(10,5,$lang[$_SESSION['lang']]["Mark"],1, 0,'', true);
    $pdf->Cell(10,5,'Coef',1, 0,'', true);
    $pdf->Cell(10,5,'Total',1, 0,'', true);
    $pdf->Cell(10,5,$lang[$_SESSION['lang']]["Rank"],1, 0,'', true);
    $pdf->Cell(10,5,'Appr',1, 0,'', true);
    $pdf->Cell(10,5,'Grade',1, 0,'', true);
    $pdf->Cell(30,5,$lang[$_SESSION['lang']]["Teacher"],1, 0,'', true);
    $pdf->Ln();
    $pdf->SetTextColor(0,0,0);
    $marks = $Model->GetStudentsMarks($year_id, $class_id, $exam_id, $stud_av['student']);
    $total_coef = 0;
    $total_mark = 0;
    foreach($marks as $mark){
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
            $pdf->Cell(50,5,$mark['competence'],1);
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
            $pdf->Cell(10,5,$sub_total['rank'],1);
            $pdf->Cell(10,5,$sub_total['grade'],1);
            $pdf->Cell(10,5,$sub_total['remark'],1);
            $pdf->Cell(30,5,$Model->GetStaffName($Model->GetSubjectTeacher($mark['subject'], $class_id, $year_id)),1);
            $pdf->Ln();
        }
    }
    $general_coef = $total_coef; $general_total = $total_mark;
}
    $general_av = round($general_total/$general_coef,2);

    $pdf->Cell(105,5,$lang[$_SESSION['lang']]["GeneralTotal"],1,0,'R', false);
    $pdf->Cell(10,5,$general_av,1);
    $pdf->Cell(10,5,$general_coef,1);
    $pdf->Cell(10,5,$general_total,1);
    $pdf->Cell(60,5,$lang[$_SESSION['lang']]["FinAverage"].$stud_av['average'],1);
    $pdf->Ln();

    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(65,5,$lang[$_SESSION['lang']]["WorkAppr"],1,0,'C', true);
    $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Averages"],1,0,'C', true);
    $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Rank"].': ',1,0,'C', true);
    $pdf->Ln();
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
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Conduct"].':',1,0,'C', true);
    $pdf->Cell(33,5,$lang[$_SESSION['lang']]["Discipline"].':',1,0,'C', true);
    $pdf->Cell(32,5,$lang[$_SESSION['lang']]["ClassProfile"].':',1,0,'C', true);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Principal"].':',0,0,'C', false);
    $pdf->Ln();
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(27,5,$lang[$_SESSION['lang']]["JustAbs"],1,0,'C', false);
    $pdf->Cell(5,5,$Model->CountAbsences($year_id, $class_id, explode(' ',$Model->GetTermName($exam_id))[0], $stud_av['student'], 'absences'),1,0,'C', false);
    $pdf->Cell(28,5,$lang[$_SESSION['lang']]["UnJustAbs"],1,0,'C', false);
    $pdf->Cell(5,5,'0',1,0,'C', false);
    $pdf->Cell(33,5,'',1,0,'C', false);
    $pdf->Cell(32,5,$lang[$_SESSION['lang']]["ClassAv"].round($Model->GetClassAverage($exam_id, $class_id, $year_id),2),1,0,'J', false);
    $pdf->Ln();
    $pdf->Cell(27,5,$lang[$_SESSION['lang']]["WarningsD"],1,0,'C', false);
    $pdf->Cell(5,5,$Model->CountAbsences($year_id, $class_id, explode(' ',$Model->GetTermName($exam_id))[0], $stud_av['student'], 'warning'),1,0,'C', false);
    $pdf->Cell(28,5,$lang[$_SESSION['lang']]["WarningsH"],1,0,'C', false);
    $pdf->Cell(5,5,'0',1,0,'C', false);
    $pdf->Cell(33,5,'',1,0,'C', false);
    $pdf->Cell(32,5,$lang[$_SESSION['lang']]["SuccRate"].' '.$succ,1,0,'J', false);
    $pdf->Ln();
    $pdf->Cell(27,5,$lang[$_SESSION['lang']]["Lateness"],1,0,'C', false);
    $pdf->Cell(5,5,$Model->CountAbsences($year_id, $class_id, explode(' ',$Model->GetTermName($exam_id))[0], $stud_av['student'], 'punishment'),1,0,'C', false);
    $pdf->Cell(28,5,$lang[$_SESSION['lang']]["Suspension"],1,0,'C', false);
    $pdf->Cell(5,5,$Model->CountAbsences($year_id, $class_id, explode(' ',$Model->GetTermName($exam_id))[0], $stud_av['student'], 'suspension'),1,0,'C', false);
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
    $pdf->Cell(16,5,$Model->GetClassBest($exam_id, $class_id, $year_id),1,0,'C', false);
    $pdf->Cell(16,5,$Model->GetClassLast($exam_id, $class_id, $year_id),1,0,'C', false);
    $pdf->Cell(65,5,'',0,0,'L', false);
   }
   $pdf->Output();
}
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}