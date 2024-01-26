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
$term_name = $_GET['term_id'];
$exam_ids = $Model->ExamsForTerm($term_name, $year_id, $section);
$eval1 = 1; $eval2 = 2; $sequence = '';

$positions = $Model->GetPosition($year_id, $class_id, strToUpper($term_name).' TERM');
$termBest = $Model->GetTermBest($year_id, $class_id, strToUpper($term_name).' TERM');
$classAv = round($Model->ClassAverageForTerm($year_id, $class_id, strToUpper($term_name).' TERM'),2);
$averages = $Model->GetTermAverageForStudent($year_id, $class_id, strToUpper($term_name).' TERM');
$pass_av = 0;
$number_of_papers = 0;
foreach($averages as $av){
    if ($av >= 10){
        $pass_av++;
    }
}
$general_coef = 0;
$general_total = 0;
$succ = round(($pass_av/count($averages))*100,2);
$group_index = ['ZeroGroupSubs'=>0,'FirstGroupSubs'=>1, 'SecondGroupSubs'=>2, 'ThirdGroupSubs'=>3];
    $first_group = $Model->GetAllWithCriteria('subjects', ['class_name' => $class_id, 'rep_group'=>1,'section' => $section]);
    $second_group = $Model->GetAllWithCriteria('subjects', ['class_name' => $class_id, 'rep_group'=>2,'section' => $section]);
    $third_group = $Model->GetAllWithCriteria('subjects', ['class_name' => $class_id, 'rep_group'=>3,'section' => $section]);
    $zero_group = $Model->GetAllWithCriteria('subjects', ['class_name' => $class_id, 'rep_group'=>0,'section' => $section]);

    $subject_groups['ZeroGroupSubs'] = $zero_group;
    $subject_groups['FirstGroupSubs'] = $first_group;
    $subject_groups['SecondGroupSubs'] = $second_group;
    $subject_groups['ThirdGroupSubs'] = $third_group;

 if(!empty($positions)){
    $pdf = new PDF();
    foreach($positions as $code => $pos){
        $number_of_papers = 0;
        $general_coef = 0;
        $general_total = 0;
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
        $pdf->SetFont('Arial','',8);
        //End student information
        
        $group_av1 = ''; $group_av2 = ''; $group_av3 = ''; //Group averages

        foreach($subject_groups as $group_name => $group){
            if(!empty($group)){
                $pdf->SetFillColor(0,0,128);
                $pdf->SetTextColor(255,255,255);
                $pdf->Cell(195,5,$lang[$_SESSION['lang']]["$group_name"],1,0,'C', true);
                $pdf->Ln();
                $pdf->SetFillColor(128,128,128);
                $pdf->SetTextColor(255,255,255);
                $pdf->Cell(35,5,$lang[$_SESSION['lang']]["Subject"],1,0,'', true);
                $pdf->Cell(50,5,$lang[$_SESSION['lang']]["Competences tested"],1,0,'', true);
                $pdf->Cell(10,5,'Eval'.$eval1,1,0,'', true);
                $pdf->Cell(10,5,'Eval'.$eval2,1,0,'', true);
                $pdf->Cell(10,5,$lang[$_SESSION['lang']]["Mark"],1,0,'', true);
                $pdf->Cell(10,5,'Coef',1,0,'', true);
                $pdf->Cell(10,5,'Total',1,0,'', true);
                $pdf->Cell(9,5,$lang[$_SESSION['lang']]["Rank"],1,0,'', true);
                $pdf->Cell(16,5,'Appreciation',1,0,'', true);
                $pdf->Cell(10,5,'Grade',1,0,'', true);
                $pdf->Cell(25,5,$lang[$_SESSION['lang']]["Teacher"],1,0,'', true);
                $pdf->Ln();
                $pdf->SetTextColor(0,0,0);
                $subjects = $Model->ViewClassSubjects($class_id);
                $total_coef = 0;
                $total_mark = 0;
                foreach($subjects as $subject){
                    if($Model->GetRepGroup($subject['subject'], $class_id, $section) == $group_index[$group_name]){
                        $total_sub_total = 0.0;
                        $mark1 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[0]['id']]);
                        $mark2 = $Model->GetAMark([$code,$class_id, $year_id, $subject['subject'], $exam_ids[1]['id']]);
                        //Check if marks exist
                        if($mark1 != '' || $mark2 != ''){
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
                                //$pdf->Cell(35,5,$subject['subject'],1);
                                $pdf->Cell(35,5,substr($subject['subject'], 0, 18),1);
                            }else{
                                $pdf->Cell(35,5,$subject['subject'],1);
                            }

                            $pdf->Cell(50,5,substr($Model->GetSequenceCompetence($year_id, $class_id, $exam_ids[1]['id'], $code, $subject['subject']),0,38),1);
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
                                $number_of_papers++;
                            }
                            $pdf->SetTextColor(0,0,0);
                            $pdf->Cell(10,5,$coef,1);
                            $pdf->Cell(10,5,$total_sub_total,1);
                            $pdf->Cell(9,5,$av_rank,1);
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
                            $pdf->Cell(16,5,$appr,1);
                
                            $remark = "";
                            if($av_mark < 10){$remark = "NA";}elseif($av_mark >= 10 && $av_mark <= 13){$remark = "ATBA";}elseif($av_mark > 13 && $av_mark <= 16){$remark = "A";}elseif($av_mark > 16){$remark = "A+";}
                
                            $pdf->Cell(10,5,$remark,1);
                            $pdf->Cell(25,5,strToUpper($Model->GetStaffName($Model->GetSubjectTeacher($subject['subject'], $class_id, $year_id))),1);
                            $pdf->Ln();
                        }//End test
                    }
                }
                
                if($total_coef != 0 ){
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
                $pdf->Cell(60,5,'Remarks ',1);
                $pdf->Ln();
            
                $general_coef += $total_coef;
                $general_total += $total_mark;
                }
        }
        
        $general_av = round($general_total/$general_coef,2);

        //Display number of papers and sequence averages
        $pdf->SetFillColor(128,128,128);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(50,5,'Subjects passed: '.$number_of_papers,1,0,'C', true);
        $seq1 = $Model->GetAllWithCriteria('computed_averages', [
            'student'=>$code, 
            'exam_id'=>$exam_ids[0]['id'], 
            'year_id'=>$year_id,
            'term'=>strToUpper($term_name).' TERM',
            'class_id'=>$class_id
        ])[0]['average'];

        $seq2 = $Model->GetAllWithCriteria('computed_averages', [
            'student'=>$code, 
            'exam_id'=>$exam_ids[1]['id'], 
            'year_id'=>$year_id,
            'term'=>strToUpper($term_name).' TERM',
            'class_id'=>$class_id
        ])[0]['average'];
        
        $pdf->Cell(50,5,'Evaluation'.$eval1.' Average: '.$seq1,1,0,'', true);
        $pdf->Cell(50,5,'Evaluation'.$eval2.' Average: '.$seq2,1, 0,'', true);
        if(round($seq1,0) > round($seq2,0)){
            $pdf->Cell(45,5,'Decline',1, 0,'', true);
        }else if(round($seq1,0) < round($seq2,0)){
            $pdf->Cell(45,5,'Improvement',1, 0,'', true);
        }else{
            $pdf->Cell(45,5,'No Improvement',1, 0,'', true);
        }
        
        $pdf->Ln();

        
        //Display general total and final average
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(105,10,$lang[$_SESSION['lang']]["GeneralTotal"],1,0,'R', false);
        $pdf->Cell(10,10,'',1);//could fill with general average
        $pdf->Cell(10,10,$general_coef,1, 0,'C');
        $pdf->Cell(10,10,$general_total,1);
        $pdf->SetTextColor(0,128,0);
        $pdf->Cell(60,10,$lang[$_SESSION['lang']]["FinAverage"].': '.$averages[$code],1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFillColor(0,0,128);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(65,5,$lang[$_SESSION['lang']]["WorkAppr"],1,0,'C', true);
        $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Averages"],1,0,'C', true);
        $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Rank"].' ',1,0,'C', true);
        $pdf->Ln();
        $pdf->SetTextColor(0,0,0);
        $work ='';
        if($general_av < 10){$work = "Could do better";}elseif($general_av >= 10 && $general_av <= 13){$work = "Satisfactory";}elseif($general_av > 13 && $general_av <= 16){$work = "Keep up the good work";}elseif($general_av > 16){$work = "Excellent work";}
        $pdf->Cell(65,5,$work,1,0,'C', false);
        $pdf->Cell(21,5,isset($group_av1)?$group_av1:'',1,0,'C', false);
        $pdf->Cell(21,5,isset($group_av2)?$group_av2:'',1,0,'C', false);
        $pdf->Cell(23,5,isset($group_av3)?$group_av3:'',1,0,'C', false);
        $eff = count($positions);

        $num = $positions[$code];
        $position = $num."/".$eff;
        $pdf->Cell(65,5,$position,1,0,'C', false);
        
        $pdf->Ln();
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Conduct"].'',1,0,'C', true);
        $pdf->Cell(33,5,$lang[$_SESSION['lang']]["Discipline"].'',1,0,'C', true);
        $pdf->Cell(32,5,$lang[$_SESSION['lang']]["ClassProfile"].'',1,0,'C', true);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(65,5,$lang[$_SESSION['lang']]["Principal"].'',0,0,'C', false);
        $pdf->Ln();
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(27,5,$lang[$_SESSION['lang']]["JustAbs"],1,0,'C', false);
        $pdf->Cell(5,5,$Model->CountAbsences($year_id, $class_id, $term_name, $code, 'justabs'),1,0,'C', false);
        $pdf->Cell(28,5,$lang[$_SESSION['lang']]["UnJustAbs"],1,0,'C', false);
        $pdf->Cell(5,5,$Model->CountAbsences($year_id, $class_id, $term_name, $code, 'absences'),1,0,'C', false);
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
        $pdf->Ln();
        //Last thing: any alteration
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',6);
        $pdf->Cell(10,4,"Any alteration on the report card is not the handiwork of ".$Model->GetSchoolInfo(1)[0]['name'],0);
    }
       $pdf->Output();
 }
 
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}
