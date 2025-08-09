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
include "../html/eduna/includes/EdunaModel.php";
include "../includes/Lang.php";
$Eduna = new EdunaModel();
include_once('../lib/fpdf.php');
 
class PDF extends FPDF
{
// Page header
function Header()
    { 
       //bkg
        $this->Image('../img/pagebkg.png',0,25,200, 200);
        // Logo
        $this->Image('../img/letterhead.png',0,2,200, 35);
        // Line break
        $this->Ln(30);
    }
 
    // Page footer
    function Footer()
    {
        $this->Image('../img/footer-Blue.png',2,260,200);
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        
    }
}

$student_code = $_GET['ref'];
$year_id = $_GET['year'];
$class_id = $_GET['class'];
$classname = $Eduna->GetSomeWithCriteria('setup_schoolclasses', ['name'], ['id' => $class_id]);
$term_id = $_GET['term'];
$year_student_id = $_GET['year_student_id'];
$trade = $_GET['trade'];

$general_coef = 0;
$general_total = 0;
$general_av =0;
$positions =[];
$subjects_passed = 0;
$seq1 =0;
$seq2 = 0;
$seq1_avg =0;
$seq2_avg =0;
$s = $Eduna->GetAllWithCriteria('students_students', ['id' => $student_code]);
$pdf = new PDF();
//header
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,4, "", 0);
$pdf->Ln(3);
$pdf->Cell(30,4, "", 0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,4, "", 0);
if($term_id == 1){
    $pdf->Cell(10,4,$lang[$_SESSION['lang']]['FirstTermRep'],0,0);
    $eval1 = 1; $eval2 = 2;
}elseif($term_id == 2){
    $pdf->Cell(10,4,$lang[$_SESSION['lang']]['SecondTermRep'],0,0);
    $eval1 = 3; $eval2 = 4;
}else{
    $pdf->Cell(10,4,$lang[$_SESSION['lang']]['ThirdTermRep'],0,0);
    $eval1 = 5; $eval2 = 6;
}

$roll = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['total_students_in_class'],['class_trade_id'=> $class_id,'school_term_id' => $term_id,'year_id' =>$year_id,'yearly_student_id'=>$year_student_id]);

$pdf->Ln();
$pdf->Cell(110,4,$lang[$_SESSION['lang']]["Name"].': '.$s[0]['full_name'],0);
$pdf->Cell(50,4,$lang[$_SESSION['lang']]["Class"].': '.$classname[0]['name'],0);                    
$pdf->Ln();
$pdf->Cell(110,4,$lang[$_SESSION['lang']]["Gender"].': '.$s[0]['gender'],0);
if(isset($roll[0])){
    $pdf->Cell(50,4,$lang[$_SESSION['lang']]["Onroll"].': '.$roll[0]['total_students_in_class'],0);
}else{
    $pdf->Cell(50,4,$lang[$_SESSION['lang']]["Onroll"].': ',0);
}
$pdf->Ln();
$pdf->Cell(110,4,$lang[$_SESSION['lang']]["DOB"].': '.$s[0]['date_of_birth'].' at '.$s[0]['place_of_birth'],0);
$pdf->Cell(50,4,$lang[$_SESSION['lang']]["Repeater"].': No',0);
$pdf->Ln();
$pdf->Cell(90,4,$lang[$_SESSION['lang']]["AdmissionNum"].': '.$s[0]['manual_matricule'],0);
$pdf->Ln();
$pdf->Cell(90,4,$lang[$_SESSION['lang']]["Classmaster"].': ',0);
$pdf->Ln(10);
$pdf->SetFont('Arial','B',8);

$pdf->SetFillColor(0,0,128);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(54,5,$lang[$_SESSION['lang']]["Subject"],1,0,'', true);
$pdf->Cell(20,5,'Evaluation'.$eval1,1,0,'', true);
$pdf->Cell(20,5,'Evaluation'.$eval2,1,0,'', true);
$pdf->Cell(10,5,$lang[$_SESSION['lang']]["Mark"],1,0,'', true);
$pdf->Cell(10,5,'Coef',1,0,'', true);
$pdf->Cell(10,5,'Total',1,0,'', true);
//$pdf->Cell(9,5,$lang[$_SESSION['lang']]["Rank"],1,0,'', true);
$pdf->Cell(26,5,'Appreciation',1,0,'', true);
$pdf->Cell(20,5,'Grade',1,0,'', true);
$pdf->Cell(25,5,$lang[$_SESSION['lang']]["Teacher"],1,0,'', true);
$pdf->Ln();
$pdf->SetTextColor(0,0,0);

$class_subjects = [];
//Get subjects done in the class
$subjects = $Eduna->GetSomeWithCriteria('setup_classtradesubjects', ['school_subject_id'], ['school_class_id'=> $class_id]);
foreach ($subjects as $k => $v) {
    if (!in_array($v['school_subject_id'], $class_subjects)) {
        array_push($class_subjects, $v['school_subject_id']);
    }
}
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
foreach ($class_subjects as $id){
    $pdf->SetFont('Arial','B',7);
    $sudentDid = $Eduna->GetTranscriptMark($term_id, $id, $year_student_id, $year_id, $class_id, $eval1, $eval2);
    if($sudentDid != ''){
        $sub = $Eduna->GetSomeWithCriteria('setup_schoolsubjects', ['name'], ['id'=> $id])[0]['name'];
        if(strlen($sub) > 34){
            $pdf->Cell(54,5,substr($sub, 0, 34),1,0,'', false);
        }else{
            $pdf->Cell(54,5,$sub,1,0,'', false);
        }
        

        $teacher_id = $Eduna->GetSomeWithCriteria('user_mgt_yearlyusersubjects', ['user_id'], ['school_subject_id'=> $id, 'year_id'=>$year_id])[0]['user_id'];
        $teacher_full = $Eduna->GetSomeWithCriteria('user_mgt_userprofile', ['full_name'], ['id'=> $teacher_id])[0]['full_name'];
        $teacher = explode(' ', $teacher_full)[0];
        $mark1 = $Eduna->SequenceMark($term_id, $id, $year_student_id, $year_id, $class_id, $eval1);
        $mark2 = $Eduna->SequenceMark($term_id, $id, $year_student_id, $year_id, $class_id, $eval2);
        
        if($mark1 != 0 && $mark2 != 0){
            if(!is_numeric($mark1)){
                $mark1 = 11;
                //$av_mark = $mark2;
                $av_mark = round(($mark1 + $mark2) /2, 2);
                $seq1 += $mark1;
                $seq2 += $mark2;
            }elseif(!is_numeric($mark2)){
                $mark2 = 11;
                //$av_mark = $mark1;
                $av_mark = round(($mark1 + $mark2) /2, 2);
                $seq1 += $mark1;
                $seq2 += $mark2;
            }else{
                $av_mark = round(($mark1 + $mark2) /2, 2);
                $seq1 += $mark1;
                $seq2 += $mark2;
            }
        }elseif($mark1 != 0 && $mark2 == 0){
            $av_mark = $mark1;
            $seq1 += $mark1;
        }elseif($mark1 == 0 && $mark2 != 0){
            $av_mark = $mark2;
            $seq1 += $mark1;
            $seq2 += $mark2;
        }

        if($mark1 < 10){
            $pdf->SetTextColor(255,0,0);
            $pdf->Cell(20,5,$mark1,1,0,'C', false);
        }else{
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(20,5,$mark1,1,0,'C', false);
        }
        $pdf->SetTextColor(0,0,0);
        if($mark2 < 10 ){
            $pdf->SetTextColor(255,0,0);
            $pdf->Cell(20,5,$mark2,1,0,'C', false);
        }else{
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(20,5,$mark2,1,0,'C', false);
        }
        $pdf->SetTextColor(0,0,0);

        
        

        if($av_mark < 10){
            $pdf->SetTextColor(255,0,0);
            $pdf->Cell(10,5,$av_mark,1,0,'', false);
        }else{
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(10,5,$av_mark,1,0,'', false);
            $subjects_passed++;
        }
        $pdf->SetTextColor(0,0,0);
        if($trade == 1 ){
            if($sub == 'MATHEMATICS' || $sub == 'ENGLISH LANGUAGE' || $sub == 'FRENCH'){
                $coef = 5;
                $general_coef += 5;
            }else if($sub == 'PHYSICAL EDUCATION'){
                $coef = 1;
                $general_coef += 1;
            }else{
                $coef = 3;
                $general_coef += 3;
            }
        }else{
            if($sub == 'PHYSICAL EDUCATION' || $sub == 'SPORTS'){
                $coef = 2;
                $general_coef += 2;
            }else{
                $coef = 5;
                $general_coef += 5;
            }
        }
        

        $pdf->Cell(10,5,$coef,1,0,'', false);
        
        $total = $av_mark*$coef;
        $general_total += $total;

        $pdf->Cell(10,5,$total,1,0,'', false);
        //$pdf->Cell(9,5,$lang[$_SESSION['lang']]["Rank"],1,0,'', true);
        $appr = '';
        if($av_mark < 8){
            $appr = "Weak";
        }elseif($av_mark >= 8 && $av_mark <= 9.99){
            $appr = "B.Av";
        }elseif($av_mark >= 10 && $av_mark <= 11.99){
            $appr = "Average";
        }elseif($av_mark >= 12 && $av_mark <= 12.99){
            $appr = "Fair";
        }elseif($av_mark >= 13 && $av_mark <= 13.99){
            $appr = "Fairly good";
        }elseif($av_mark >= 14 && $av_mark <= 15.99){
            $appr = "Good";
        }elseif($av_mark >= 16 && $av_mark <= 17.99){
            $appr = "Very good";
        }elseif($av_mark >= 18){
            $appr = "Excellent";
        }
        $pdf->Cell(26,5,$appr,1,0,'', false);$remark = "";
        if($av_mark < 10){$remark = "NA";}elseif($av_mark >= 10 && $av_mark <= 13){$remark = "ATBA";}elseif($av_mark > 13 && $av_mark <= 16){$remark = "A";}elseif($av_mark > 16){$remark = "A+";}


        $remark = "";
        if($av_mark < 10){$remark = "NA";}elseif($av_mark >= 10 && $av_mark <= 13){$remark = "ATBA";}elseif($av_mark > 13 && $av_mark <= 16){$remark = "A";}elseif($av_mark > 16){$remark = "A+";}

        $pdf->Cell(20,5,$remark,1,0,'', false);
        $pdf->Cell(25,5,$teacher,1,0,'', false);
        $pdf->Ln();
        
    }
}
$seq1_avg = round($seq1/$coef, 2);
$seq2_avg = round($seq2/$coef, 2);
//Display number of papers and sequence averages
$pdf->SetFillColor(128,128,128);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(54,5,'Subjects passed: '.$subjects_passed,1,0,'', true);
$pdf->Cell(20,5,'Eval'.$eval1.': '.$seq1_avg,1,0,'', true);
$pdf->Cell(20,5,'Eval'.$eval2.': '.$seq2_avg,1, 0,'', true);
$pdf->Cell(10,5,'',1, 0,'', true);
$pdf->Cell(10,5,$general_coef,1, 0,'', true);
$pdf->Cell(10,5,$general_total,1, 0,'', true);
$pdf->Cell(71,5,'',1, 0,'', true);
$pdf->Ln();

$final_av = round($general_total/$general_coef, 2);
//Display general total and final average
$pdf->SetTextColor(0,0,0);
$pdf->Cell(94,10,$lang[$_SESSION['lang']]["GeneralTotal"],1,0,'R', false);
$pdf->Cell(10,10,'',1);//could fill with general average
$pdf->Cell(10,10,$general_coef,1, 0,'C');
$pdf->Cell(10,10,$general_total,1);
$pdf->SetTextColor(0,120,0);
$t1 = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['term_average'],['class_trade_id'=> $class_id,'school_term_id' => 1,'year_id' =>$year_id,'yearly_student_id'=>$year_student_id]);
if(!empty($t1)){
    $t1 = $t1[0]['term_average'];
}else{
    $t1 = '';
}
$t2 = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['term_average'],['class_trade_id'=> $class_id,'school_term_id' => 2,'year_id' =>$year_id,'yearly_student_id'=>$year_student_id]);
if(!empty($t2)){
    $t2 = $t2[0]['term_average'];
}else{
    $t2 = '';
}
$t3 = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['term_average'],['class_trade_id'=> $class_id,'school_term_id' => 3,'year_id' =>$year_id,'yearly_student_id'=>$year_student_id]);
if(!empty($t3)){
    $t3 = $t3[0]['term_average'];
}else{
    $t3 = '';
}
if(is_numeric($t1) && is_numeric($t2) && is_numeric($t3)){
    $ann_av = round(($t1+$t2+$t3)/3, 2);
}else{
    $ann_av = '';
}

if($term_id == '3'){
    $pdf->Cell(71,10,$lang[$_SESSION['lang']]["AnnualAverage"].': '.$ann_av,1, 0, 'C');
}else{
    if($term_id == '1') {
        if(is_numeric($t1)){
            $pdf->Cell(71,10,$lang[$_SESSION['lang']]["FinAverage"].': '.$t1,1, 0, 'C');
        }else{
            $pdf->Cell(71,10,$lang[$_SESSION['lang']]["FinAverage"].': '.round($general_total/$general_coef,2),1, 0, 'C');
        }
    }elseif($term_id == '2') {
        if(is_numeric($t2)){
            $pdf->Cell(71,10,$lang[$_SESSION['lang']]["FinAverage"].': '.$t2,1, 0, 'C');
        }else{
            $pdf->Cell(71,10,$lang[$_SESSION['lang']]["FinAverage"].': '.round($general_total/$general_coef,2),1, 0, 'C');
        }
    }
    
}
$pdf->Ln();
$pdf->SetFillColor(0,0,128);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(65,5,$lang[$_SESSION['lang']]["WorkAppr"],1,0,'C', true);
$pdf->Cell(65,5,$lang[$_SESSION['lang']]["TermAverages"],1,0,'C', true);
$pos = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['position'],['class_trade_id'=> $class_id,'school_term_id' => $term_id,'year_id' =>$year_id,'yearly_student_id'=>$year_student_id]);
if($term_id == '3') {
    if(!empty($pos)){
        
        $pdf->Cell(20,5,$lang[$_SESSION['lang']]["Rank"].' ',1,0,'C', true);
        $pdf->Cell(45,5,$lang[$_SESSION['lang']]["AnnualRank"].' ',1,0,'C', true);
    }else{
        $pdf->Cell(20,5,$lang[$_SESSION['lang']]["Rank"].' !#@',1,0,'C', true);
        $pdf->Cell(45,5,$lang[$_SESSION['lang']]["AnnualRank"].' ',1,0,'C', true);
    }
}else{
    if(!empty($pos)){
        $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Rank"].' ',1,0,'C', true);
    }else{
        $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Rank"],1,0,'C', true);
    }
    
}
$pdf->Ln();
$pdf->SetTextColor(0,0,0);
$work ='';
if($general_av < 10){$work = "Could do better";}elseif($general_av >= 10 && $general_av <= 13){$work = "Satisfactory";}elseif($general_av > 13 && $general_av <= 16){$work = "Keep up the good work";}elseif($general_av > 16){$work = "Excellent work";}
$pdf->Cell(65,5,$work,1,0,'C', false);
if($term_id == 1){
    $pdf->Cell(21,5,'Term1: '.isset($t1)?$t1:'',1,0,'C', false);
    $pdf->Cell(21,5,'',1,0,'C', false);
    $pdf->Cell(23,5,'',1,0,'C', false);
}elseif($term_id == 2 ){
    $pdf->Cell(21,5,'Term2: '.isset($t1)?$t1:'',1,0,'C', false);
    $pdf->Cell(21,5,'Term1: '.isset($t2)?$t2:'',1,0,'C', false);
    $pdf->Cell(23,5,'',1,0,'C', false);
}else{
    $pdf->Cell(21,5,'Term2: '.isset($t1)?$t1:'',1,0,'C', false);
    $pdf->Cell(21,5,'Term1: '.isset($t2)?$t2:'',1,0,'C', false);
    $pdf->Cell(23,5,'Term3: '.isset($t3)?$t3:'',1,0,'C', false);
}

$eff = count($positions);

if($term_id == '3' && isset($pos[0])) {
    $pdf->Cell(20,5,$pos[0]['position'].' / '.$roll[0]['total_students_in_class'],1,0,'C', false);
    $pdf->Cell(45,5,$pos[0]['position']+1..' / '.$roll[0]['total_students_in_class'],1,0,'C', false);
}elseif($term_id != '3' && isset($pos[0]['position'])){
    $pdf->Cell(65,5,$pos[0]['position'].' / '.$roll[0]['total_students_in_class'],1,0,'C', false);
}else{
    if($term_id == '3' && !isset($pos[0])){
        $pdf->Cell(20,5,'',1,0,'C', false);
        $pdf->Cell(45,5,'',1,0,'C', false);
    }else{
        $pdf->Cell(65,5,'',1,0,'C', false);
    }
}
$pdf->Ln();
$pdf->SetTextColor(255,255,255);
$pdf->Cell(65,5,$lang[$_SESSION['lang']]["Conduct"].'',1,0,'C', true);
$pdf->Cell(33,5,$lang[$_SESSION['lang']]["Discipline"].'',1,0,'C', true);
$pdf->Cell(32,5,$lang[$_SESSION['lang']]["ClassProfile"].'',1,0,'C', true);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(65,5,'',0,0,'C', false);
$pdf->Ln();
$pdf->SetTextColor(0,0,0);
$pdf->Cell(27,5,$lang[$_SESSION['lang']]["JustAbs"],1,0,'C', false);
$pdf->Cell(5,5,'0',1,0,'C', false);
$pdf->Cell(28,5,$lang[$_SESSION['lang']]["UnJustAbs"],1,0,'C', false);
$pdf->Cell(5,5,'0',1,0,'C', false);
$pdf->Cell(33,5,'',1,0,'C', false);
$class_av = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['avg(term_average) AS m'],['class_trade_id'=> $class_id,'school_term_id' => $term_id,'year_id' =>$year_id]);
$pdf->Cell(32,5,$lang[$_SESSION['lang']]["ClassAv"].':   '.round($class_av[0]['m'], 2),1,0,'J', false);
$pdf->Ln();
$pdf->Cell(27,5,$lang[$_SESSION['lang']]["WarningsD"],1,0,'C', false);
$pdf->Cell(5,5,'0',1,0,'C', false);
$pdf->Cell(28,5,$lang[$_SESSION['lang']]["WarningsH"],1,0,'C', false);
$pdf->Cell(5,5,'0',1,0,'C', false);
$pdf->Cell(33,5,'',1,0,'C', false);
$pdf->Cell(32,5,$lang[$_SESSION['lang']]["SuccRate"].':  ',1,0,'J', false);
$pdf->Ln();
$pdf->Cell(27,5,$lang[$_SESSION['lang']]["Lateness"],1,0,'C', false);
$pdf->Cell(5,5,'0',1,0,'C', false);
$pdf->Cell(28,5,$lang[$_SESSION['lang']]["Suspension"],1,0,'C', false);
$pdf->Cell(5,5,'0',1,0,'C', false);
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
$termBest = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['MAX(term_average) AS m'],['class_trade_id'=> $class_id,'school_term_id' => $term_id,'year_id' =>$year_id]);
$termWorst = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['MIN(term_average) AS m'],['class_trade_id'=> $class_id,'school_term_id' => $term_id,'year_id' =>$year_id]);
$pdf->Cell(65,5,'',1,0,'C', false);
$pdf->Cell(33,5,'',1,0,'C', false);
$pdf->Cell(16,5,$termBest[0]['m'],1,0,'C', false);
$pdf->Cell(16,5,$termWorst[0]['m'],1,0,'C', false);
$pdf->Cell(65,5,$lang[$_SESSION['lang']]["Principal"].'',0,0,'C', false);
$pdf->Ln();
//Last thing: any alteration
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(10,4,"Any alteration on the report card is not the handiwork of Quality International School",0);


$pdf->Output();
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}