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
        $this->Image('../img/pagebkg.png',50,25,200, 200);
        // Logo
        $this->Image('../img/letterhead.png',50,2,200);
        // Line break
        $this->Ln(30);
    }
 
    // Page footer
    function Footer()
    {
        $this->Image('../img/footer.png',2,260,200);
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        
    }
}
///////////////////////////////////////////////////////////////////////////////
//Get all the First cycle classes
//For each class get the subjects for that class
//For each subject get the Totals of the said student
$student_code = $_GET['ref'];
$type = $_GET['type'];
$s = $Eduna->GetAllWithCriteria('students_students', ['id' => $student_code]);
$pdf = new PDF();
//header
$pdf->AddPage('O');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,4, "", 0);
$pdf->Ln();
$pdf->Cell(30,4, "", 0);
$pdf->SetFont('Times','B',18);
$pdf->Cell(70,4, "", 0);
if($type == 1){
    $pdf->Cell(10,4,"SECONDARY SCHOOL TRANSCRIPT",0,0);
}else{
    $pdf->Cell(10,4,"HIGH SCHOOL TRANSCRIPT",0,0);
}

$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(130,4,'NAME: '.strToUpper($s[0]['full_name']),0);
$pdf->Cell(30,4,'GENDER: '.$s[0]['gender'],0);
$pdf->Cell(40,4,'DOB: '.$s[0]['date_of_birth'],0);
$pdf->Cell(40,4,'POB: '.$s[0]['place_of_birth'],0);
$pdf->Cell(10,4,'ADMISSION #: '.$s[0]['manual_matricule'],0);
$pdf->Ln();
$pdf->SetFont('Arial','B',9);
$pdf->SetFillColor(0,0,128);
$pdf->SetTextColor(255, 255, 255);
if($type == 1){
    $pdf->Cell(40,4,'SUBJECT',1 , 0,'', true);
}else{
    $pdf->Cell(70,4,'SUBJECT',1, 0,'', true);
}

///////////////////////////////////////////////////////////////////////////////
//Get all the First cycle classes                                            //
//For each class get the subjects for that class                             //
//For each subject get the Totals of the said student                        //
///////////////////////////////////////////////////////////////////////////////

//1. Get all the First Cycle Classes//
$students_classes = $Eduna->GetAllWithCriteria('students_yearlystudents', ['student_id' => $student_code, 'school_trade_id'=> $type]);
$class_subjects = [];
foreach ($students_classes as $key => $value) {
    //write transcript table headers
    $classname = $Eduna->GetSomeWithCriteria('setup_schoolclasses', ['name'], ['id' => $value['school_class_id']]);
    $yearname = $Eduna->GetSomeWithCriteria('setup_academicyears', ['short_name'], ['id' => $value['year_id']]);
    $pdf->SetFillColor(0,0,128);
    $pdf->SetTextColor(255, 255, 255);
    if($type == 1){
        $pdf->Cell(47,4,$classname[0]['name'].' - '.$yearname[0]['short_name'],1, 0,'', true);
    }else{
        $pdf->Cell(70,4,$classname[0]['name'].' - '.$yearname[0]['short_name'],1, 0,'', true);
    }
    

    //Get subjects done in the class
    $subjects = $Eduna->GetSomeWithCriteria('setup_classtradesubjects', ['school_subject_id'], ['school_class_id'=> $value['school_class_id']]);
    foreach ($subjects as $k => $v) {
        if (!in_array($v['school_subject_id'], $class_subjects)) {
            array_push($class_subjects, $v['school_subject_id']);
        }
    }
}

$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->SetFillColor(128,128,128);
$pdf->SetTextColor(255, 255, 255);
if($type == 1){
    $pdf->Cell(40,4,'',1, 0,'', true);
}else{
    $pdf->Cell(70,4,'',1, 0,'', true);
}

for($i=0; $i<count($students_classes); $i++){
    if($type == 1){
        $pdf->Cell(15,4,'First Term',1, 0,'', true);
        $pdf->Cell(17,4,'Second Term',1, 0,'', true);
        $pdf->Cell(15,4,'Third Term',1, 0,'', true);
    }else{
        $pdf->Cell(22.5,4,'First Term',1, 0,'', true);
        $pdf->Cell(25,4,'Second Term',1, 0,'', true);
        $pdf->Cell(22.5,4,'Third Term',1, 0,'', true);
    }
    
}
$pdf->SetTextColor(0, 0, 0);


foreach ($class_subjects as $id){
    $pdf->Ln();
    $pdf->SetFont('Arial','',7);
    $sub = $Eduna->GetSomeWithCriteria('setup_schoolsubjects', ['name'], ['id'=> $id])[0]['name'];
    if($type == 1){
        if(strlen($sub) > 22){
            $pdf->Cell(40,4,substr($sub, 0, 22),1);
        }else{
            $pdf->Cell(40,4,$sub,1);
        }
    }else{
        $pdf->Cell(70,4,$sub,1);
    }
   
    foreach($students_classes as $class){
        $t1 = $Eduna->GetTranscriptMark(1, $id, $class['id'], $class['year_id'], $class['school_class_id'], 1, 2);
        $t2 = $Eduna->GetTranscriptMark(2, $id, $class['id'], $class['year_id'], $class['school_class_id'], 3, 4);
        $t3 = $Eduna->GetTranscriptMark(3, $id, $class['id'], $class['year_id'], $class['school_class_id'], 5, 6);
        if($type == 1){
            if($t1 != '' && $t1 < 10){
                $pdf->SetTextColor(255,0,0);
                $pdf->Cell(15,4,$t1,1);
            }else{
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(15,4,$t1,1);
            }
            $pdf->SetTextColor(0,0,0);
            if($t2 != '' && $t2 < 10){
                $pdf->SetTextColor(255,0,0);
                $pdf->Cell(17,4,$t2,1);
            }else{
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(17,4,$t2,1);
            }
            $pdf->SetTextColor(0,0,0);
            if($t3 != '' && $t3 < 10){
                $pdf->SetTextColor(255,0,0);
                $pdf->Cell(15,4,$t3,1);
            }else{
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(15,4,$t3,1);
            }
            $pdf->SetTextColor(0,0,0);
        }else{
            if($t1 != '' && $t1 < 10){
                $pdf->SetTextColor(255,0,0);
                $pdf->Cell(22.5,4,$t1,1);
            }else{
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(22.5,4,$t1,1);
            }
            $pdf->SetTextColor(0,0,0);
            if($t2 != '' && $t2 < 10){
                $pdf->SetTextColor(255,0,0);
                $pdf->Cell(25,4,$t2,1);
            }else{
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(25,4,$t2,1);
            }
            $pdf->SetTextColor(0,0,0);
            if($t3 != '' && $t3 < 10){
                $pdf->SetTextColor(255,0,0);
                $pdf->Cell(22.5,4,$t3,1);
            }else{
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(22.5,4,$t3,1);
            }
            $pdf->SetTextColor(0,0,0);
        }
        
    }
}

$pdf->Ln();
$pdf->SetFont('Arial','B',8);
if($type == 1){
    $pdf->Cell(40,4,"AVERAGE",1);
}else{
    $pdf->Cell(70,4,"AVERAGE",1);
}



foreach($students_classes as $class){
    if(!empty($Eduna->GetSomeWithCriteria('user_mgt_reportcards',['term_average_str'],['class_trade_id'=> $class['school_class_id'],'school_term_id' => 1,'year_id' =>$class['year_id'],'yearly_student_id'=>$class['id']]))){
        $term1_av = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['term_average_str'],['class_trade_id'=> $class['school_class_id'],'school_term_id' => 1,'year_id' =>$class['year_id'],'yearly_student_id'=>$class['id']]);
    }else{
        $term1_av = '';
    }
    if(!empty($Eduna->GetSomeWithCriteria('user_mgt_reportcards',['term_average_str'],['class_trade_id'=> $class['school_class_id'],'school_term_id' => 2,'year_id' =>$class['year_id'],'yearly_student_id'=>$class['id']]))){
        $term2_av = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['term_average_str'],['class_trade_id'=> $class['school_class_id'],'school_term_id' => 2,'year_id' =>$class['year_id'],'yearly_student_id'=>$class['id']]);
    }else{
        $term2_av = '';
    }
    if(!empty($Eduna->GetSomeWithCriteria('user_mgt_reportcards',['term_average_str'],['class_trade_id'=> $class['school_class_id'],'school_term_id' => 3,'year_id' =>$class['year_id'],'yearly_student_id'=>$class['id']]))){
        $term3_av = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['term_average_str'],['class_trade_id'=> $class['school_class_id'],'school_term_id' => 3,'year_id' =>$class['year_id'],'yearly_student_id'=>$class['id']]);
    }else{
        $term3_av = '';
    }
    if($type == 1){
        $pdf->Cell(15,4,$term1_av == ''? '':$term1_av[0]['term_average_str'],1);
        $pdf->Cell(17,4,$term2_av == ''? '':$term2_av[0]['term_average_str'],1);
        $pdf->Cell(15,4,$term3_av == ''? '':$term3_av[0]['term_average_str'],1);
    }else{
        $pdf->Cell(22.5,4,$term1_av == ''? '':$term1_av[0]['term_average_str'],1);
        $pdf->Cell(25,4,$term2_av == ''? '':$term2_av[0]['term_average_str'],1);
        $pdf->Cell(22.5,4,$term3_av == ''? '':$term3_av[0]['term_average_str'],1);
    }
    
}

$pdf->Ln();
if($type == 1) {
    $pdf->Cell(40,4,"POSITION",1);
}else{
    $pdf->Cell(70,4,"POSITION",1);
}

foreach($students_classes as $class){
    if(!empty($Eduna->GetSomeWithCriteria('user_mgt_reportcards',['position'],['class_trade_id'=> $class['school_class_id'],'school_term_id' => 1,'year_id' =>$class['year_id'],'yearly_student_id'=>$class['id']]))){
        $pos1 = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['position'],['class_trade_id'=> $class['school_class_id'],'school_term_id' => 1,'year_id' =>$class['year_id'],'yearly_student_id'=>$class['id']]);
        $roll = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['total_students_in_class'],['class_trade_id'=> $class['school_class_id'],'school_term_id' => 1,'year_id' =>$class['year_id'],'yearly_student_id'=>$class['id']]);
    }else{
        $pos1 = '';
    }
    if(!empty($Eduna->GetSomeWithCriteria('user_mgt_reportcards',['position'],['class_trade_id'=> $class['school_class_id'],'school_term_id' => 2,'year_id' =>$class['year_id'],'yearly_student_id'=>$class['id']]))){
        $pos2 = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['position'],['class_trade_id'=> $class['school_class_id'],'school_term_id' => 2,'year_id' =>$class['year_id'],'yearly_student_id'=>$class['id']]);
        $roll = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['total_students_in_class'],['class_trade_id'=> $class['school_class_id'],'school_term_id' => 2,'year_id' =>$class['year_id'],'yearly_student_id'=>$class['id']]);
    }else{
        $pos2 = '';
    }
    if(!empty($Eduna->GetSomeWithCriteria('user_mgt_reportcards',['position'],['class_trade_id'=> $class['school_class_id'],'school_term_id' => 3,'year_id' =>$class['year_id'],'yearly_student_id'=>$class['id']]))){
        $pos3 = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['position'],['class_trade_id'=> $class['school_class_id'],'school_term_id' => 3,'year_id' =>$class['year_id'],'yearly_student_id'=>$class['id']]);
        $roll = $Eduna->GetSomeWithCriteria('user_mgt_reportcards',['total_students_in_class'],['class_trade_id'=> $class['school_class_id'],'school_term_id' => 3,'year_id' =>$class['year_id'],'yearly_student_id'=>$class['id']]);
    }else{
        $pos3 = '';
    }
    if($type == 1){
        $pdf->Cell(15,4,$pos1 == ''? '':$pos1[0]['position'].' / '.$roll[0]['total_students_in_class'],1);
        $pdf->Cell(17,4,$pos2 == ''? '':$pos2[0]['position'].' / '.$roll[0]['total_students_in_class'],1);
        $pdf->Cell(15,4,$pos3 == ''? '':$pos3[0]['position'].' / '.$roll[0]['total_students_in_class'],1);
    }else{
        $pdf->Cell(22.5,4,$pos1 == ''? '':$pos1[0]['position'].' / '.$roll[0]['total_students_in_class'],1);
        $pdf->Cell(25,4,$pos2 == ''? '':$pos2[0]['position'].' / '.$roll[0]['total_students_in_class'],1);
        $pdf->Cell(22.5,4,$pos3 == ''? '':$pos3[0]['position'].' / '.$roll[0]['total_students_in_class'],1);
    }
    
}
$pdf->Ln();
$pdf->SetFont('Arial','B',6);
$pdf->Cell(10,5,"Any alteration on the transcript is not the handiwork of Quality International School",0);
if($type == 1){
    $pdf->Ln(16);
}else{
    $pdf->Ln(40);
}

$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,5,"Done at________________, on the _______________                                                                                                                                  Principal__________________",0);
$pdf->Output();
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}