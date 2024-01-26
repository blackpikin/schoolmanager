<?php
session_start();
include "../includes/Model.php";
include '../includes/Lang.php';
$lng = $_SESSION['lang'];
$section = 0;
if($lng == 'fr'){
    $section = 1;
}

$Model = new Model($section);

$action = $_POST['action'];

if($action == 'GetMocksForYear'){
    $year = $_POST['year_id'];
    $mocks = $Model->GetMockExamsForYear($year);
    $data = "";
    $data .= '<option value="">Select examination</option>';
    foreach($mocks as $mock){
        $data .= ' <option>'.$mock['sequence'].'</option>';
    }

    echo $data;
}

if($action == 'ChangeLang'){
    $lang = $_POST['lang'];
    $_SESSION['lang'] = $lang;
    echo "Ok Lang to ".$lang;
}

if($action == 'ComputeTerm'){
    $class_id = $_POST['klass'];
    $year_id = $_POST['Year'];
    $term_id = $_POST['Term'];
    $exam_ids = $Model->ExamsForTerm($term_id, $year_id, $section);

    if(count($exam_ids) < 2){
        echo "Not enough evaluations for this term. Please fill in the marks for at least 2 evaluations";
    }else{
        foreach ($exam_ids as $exam_id){
            $students = $Model->GetMarkSheet($year_id, $class_id, $exam_id['id']);
            $student_codes = [];
            $data = []; $students_totals = [];

            foreach($students as $student){
                if(!in_array($student['student_code'], $student_codes)){
                    array_push($student_codes, $student['student_code']);
                }
            }

            $class_av = 0;
            $position_array = [];
            foreach($student_codes as $student){
                $marks = $Model->GetStudentsMarks($year_id, $class_id, $exam_id['id'], $student);
                $total_coef = 0;
                $total_marks = 0;

                foreach ($marks as $mark){
                    $coef = $Model->GetCoefficient($mark['subject'], $class_id);
                    $total = $mark['mark'] * $coef;
                    $remark = "";
                    if($mark['mark'] < 10){$remark = "NA";}elseif($mark['mark'] >= 10 && $mark['mark'] <= 13){$remark = "ATBA";}elseif($mark['mark'] > 13 && $mark['mark'] <= 16){$remark = "A";}elseif($mark['mark'] > 16){$remark = "A+";}
                    $rank = $Model->SubjectRank($mark['subject'], $student, $year_id, $class_id, $exam_id['id'] );
                    $grade = '';
                    if($mark['mark'] < 8){
                        $grade = "Weak";
                    }elseif($mark['mark'] >= 8 && $mark['mark'] <= 9.99){
                        $grade = "B.Av";
                    }elseif($mark['mark'] >= 10 && $mark['mark'] <= 11.99){
                        $grade = "Average";
                    }elseif($mark['mark'] >= 12 && $mark['mark'] <= 12.99){
                        $grade = "Fair";
                    }elseif($mark['mark'] >= 13 && $mark['mark'] <= 13.99){
                        $grade = "Fairly good";
                    }elseif($mark['mark'] >= 14 && $mark['mark'] <= 15.99){
                        $grade = "Good";
                    }elseif($mark['mark'] >= 16 && $mark['mark'] <= 17.99){
                        $grade = "Very good";
                    }elseif($mark['mark'] >= 18){
                        $grade = "Excellent";
                    }
                    
                    $rep_group = $Model->GetRepGroup($mark['subject'], $class_id, 1);
                    $teacher = $Model->GetSubjectTeacher($mark['subject'], $class_id, $year_id);
                    $total_coef = $total_coef + $coef;
                    $total_marks = $total_marks + $total;

                    $mark_data = [$mark['subject'], $student, $exam_id['id'], $year_id, $total, $rank, $remark, $grade, $rep_group, $class_id ];
                    if($Model->TotalExists($mark['subject'], $student, $exam_id['id'], $year_id, $class_id)){
                        $res = $Model->UpdateStudentTotal($mark_data);
                    }else{
                        $res = $Model->NewStudentTotal($mark_data);
                    }
                } 
                
                $average = round($total_marks/$total_coef, 2);
                $class_av = $class_av + $average;
                $class_average = round($class_av/count($student_codes), 2);
                $term = $Model->GetTermName($exam_id['id']);
                $overall_remark = '';
                if($average < 10){$overall_remark = "B.Av";}elseif($average >= 10 && $average <= 13){$overall_remark = "Good";}elseif($average > 13 && $average <= 16){$overall_remark = "V.Good";}elseif($average > 16){$overall_remark = "Excellent";}
                $annual_av = 0.00;
                $position = 0;
                $position_array[$student] = $average;
                arsort($position_array);
                $dta = [$student, $exam_id['id'], $year_id, $position, $term, $average, $overall_remark, $annual_av, $class_id];

                if($Model->AverageExists($student, $exam_id['id'], $year_id, $class_id)){
                    $res = $Model->UpdateStudentAverage($dta);
                }else{
                    $res = $Model->NewStudentAverage($dta);
                }
                    }
                }
    }

    echo 'Computation complete';
}

if($action == 'ComputeSequence'){
    $class_id = $_POST['klass'];
    $year_id = $_POST['Year'];
    $exam_id = $_POST['Exam'];

    $students = $Model->GetMarkSheet($year_id, $class_id, $exam_id);
    $student_codes = [];
    $data = []; $students_totals = [];

    foreach($students as $student){
        if(!in_array($student['student_code'], $student_codes)){
            array_push($student_codes, $student['student_code']);
        }
    }

    $class_av = 0;
    $position_array = [];
    foreach($student_codes as $student){
        $marks = $Model->GetStudentsMarks($year_id, $class_id, $exam_id, $student);
        $total_coef = 0;
        $total_marks = 0;

        foreach ($marks as $mark){
            $coef = $Model->GetCoefficient($mark['subject'], $class_id);
            $total = $mark['mark'] * $coef;
            $remark = "";
            if($mark['mark'] < 10){$remark = "NA";}elseif($mark['mark'] >= 10 && $mark['mark'] <= 13){$remark = "ATBA";}elseif($mark['mark'] > 13 && $mark['mark'] <= 16){$remark = "A";}elseif($mark['mark'] > 16){$remark = "A+";}
            $rank = $Model->SubjectRank($mark['subject'], $student, $year_id, $class_id, $exam_id );
            $grade = '';
            if($mark['mark'] < 8){
                $grade = "Weak";
            }elseif($mark['mark'] >= 8 && $mark['mark'] <= 9.99){
                $grade = "B.Av";
            }elseif($mark['mark'] >= 10 && $mark['mark'] <= 11.99){
                $grade = "Average";
            }elseif($mark['mark'] >= 12 && $mark['mark'] <= 12.99){
                $grade = "Fair";
            }elseif($mark['mark'] >= 13 && $mark['mark'] <= 13.99){
                $grade = "Fairly good";
            }elseif($mark['mark'] >= 14 && $mark['mark'] <= 15.99){
                $grade = "Good";
            }elseif($mark['mark'] >= 16 && $mark['mark'] <= 17.99){
                $grade = "Very good";
            }elseif($mark['mark'] >= 18){
                $grade = "Excellent";
            }
            $rep_group = $Model->GetRepGroup($mark['subject'], $class_id, 0);
            $teacher = $Model->GetSubjectTeacher($mark['subject'], $class_id, $year_id);
            $total_coef = $total_coef + $coef;
            $total_marks = $total_marks + $total;

            $mark_data = [$mark['subject'], $student, $exam_id, $year_id, $total, $rank, $remark, $grade, $rep_group, $class_id ];
            if($Model->TotalExists($mark['subject'], $student, $exam_id, $year_id, $class_id)){
                $res = $Model->UpdateStudentTotal($mark_data);
            }else{
                $res = $Model->NewStudentTotal($mark_data);
            }
        }

        $average = round($total_marks/$total_coef, 2);
        $class_av = $class_av + $average;
        $class_average = round($class_av/count($student_codes), 2);
        $term = $Model->GetTermName($exam_id);
        $overall_remark = '';
        if($average < 10){$overall_remark = "B.Av";}elseif($average >= 10 && $average <= 13){$overall_remark = "Good";}elseif($average > 13 && $average <= 16){$overall_remark = "V.Good";}elseif($average > 16){$overall_remark = "Excellent";}
        $annual_av = 0.0;
        $position = 0;
        $position_array[$student] = $average;
        arsort($position_array);
        $dta = [$student, $exam_id, $year_id, $position, $term, $average, $overall_remark, $annual_av, $class_id];

        if($Model->AverageExists($student, $exam_id, $year_id, $class_id) == true){
            $res = $Model->UpdateStudentAverage($dta);
        }else{
            $res = $Model->NewStudentAverage($dta);
        }

    }
    echo "Computation complete";

}

if($action == 'LoadFees'){
    $student_code = $_POST['student'];
    $class_id = $_POST['klass'];
    $year_id = $Model->GetCurrentYear()[0]['id'];
    $amount = $Model->GetStudentsFees($student_code, $class_id, $year_id);
    $data = '';

    $_SESSION['payfees_student_code'] = $student_code;
    $_SESSION['payfees_student_class'] = $class_id;

    $feeType = $Model->FeeType($student_code);
    $class_fees = $Model->GetFeeStructure($class_id, $feeType);
        $s = $Model->GetStudent($student_code, $section);
        $reg = 0; $pta = 0; $first = 0; $second = 0;
        $remain1 = 0;  $remain2 = 0; $remain3 = 0; $remain4 = 0; 
        $discount = $Model->GetDiscount($student_code);
        if ($discount != false){
            $dis_id = $discount['discount_id'];
            $percent = $Model->GetDiscountReason($dis_id)['percent'];
            $installments = $class_fees['first_ins'] + $class_fees['second_ins'];
            $dis_amt = round(($percent/100)*$installments, 0);
            $left = (int)$class_fees['totalfee'] - $dis_amt - (int)$amount;
        }else{
            $left = (int)$class_fees['totalfee'] - (int)$amount;
        }
        
    if (!empty($amount)){
        if($amount > $class_fees['registration']){
            if($class_fees['registration'] > 0){
                $remain1 = (int)$amount - (int)$class_fees['registration'];
                $reg = $class_fees['registration'];
            }else{
                $reg = 0;
            }
        }elseif($amount == $class_fees['registration']){
            $reg = $amount;
        }else{
            $reg = $amount;
        }

        if($remain1 > $class_fees['pta']){
            if($class_fees['pta'] > 0){
                $remain2 = $remain1 - $class_fees['pta'];
                $pta = $class_fees['pta'];
            }else{
                $pta = 0;
            }
        }elseif($remain1 == $class_fees['pta']){
            $pta = $remain1;
        }else{
            $pta = $remain1;
        }

        if($remain2 > $class_fees['first_ins']){
            if($class_fees['first_ins'] > 0){
                $remain3 = $remain2 - $class_fees['first_ins'];
                $first = $class_fees['first_ins'];
            }else{
                $first = 0;
            }
        }elseif($remain2 == $class_fees['first_ins']){
            $first = $remain2;
        }else{
            $first = $remain2;
        }

        if($remain3 > $class_fees['second_ins']){
            if($class_fees['second_ins'] > 0){
                $remain4 = $remain3 - $class_fees['second_ins'];
                $second = $class_fees['second_ins'];
            }else{
                $second = 0;
            }
        }elseif($remain3 == $class_fees['second_ins']){
            $second = $remain3;
        }else{
            $second = $remain3;
        }

        $data = '<h4><u>Fees</u></h4>
        <label>Student\'s name</label>
        <input type="text" required="required" readonly class="form-control fees_input_small" id="name" value="'.$s[0]['name'].'">
        <br>
        <label>Total paid</label>
        <input type="text" required="required" readonly class="form-control fees_input" id="total" value="'.$amount.'">
        <br>
        <label>First Installment</label>
        <input type="text" required="required" readonly  class="form-control fees_input" id="first" value="'.$first.'">
        <br>
        <label>Second Installment</label>
        <input type="text" required="required" readonly  class="form-control fees_input" id="second" value="'.$second.'">
        <br>
        <label>Registration</label>
        <input type="text" required="required" readonly  class="form-control fees_input" id="reg" value="'.$reg.'">
        <label>PTA</label>
        <input type="text" required="required" readonly  class="form-control fees_input" id="PTA" value="'.$pta.'">
        <br>';
        if ($discount != false){
            $dis_id = $discount['discount_id'];
            $reason = $Model->GetDiscountReason($dis_id)['reason'];
            $percent = $Model->GetDiscountReason($dis_id)['percent'];
            $installments = $class_fees['first_ins'] + $class_fees['second_ins'];
            $dis_amt = round(($percent/100)*$installments, 0);
            $data .= '<label>'.$reason.' discount</label>
        <input type="text" required="required" readonly  class="form-control fees_input" id="disc" value="-'.$dis_amt.'">
        <br>';
        }
        $data .= '<label>Total left</label>
        <input type="text" required="required" readonly  class="form-control fees_input" id="left" value="'.$left.'">
        <br>
        <br>';
        echo $data;
    }else{
        $data = '<h4><u>Fees</u></h4>
        <label>Student\'s name</label>
        <input type="text" required="required" readonly class="form-control fees_input_small" id="name" value="'.$s[0]['name'].'">
        <br>
        <label>Total paid</label>
        <input type="text" required="required" readonly class="form-control fees_input" id="total" value="'.$amount.'">
        <br>
        <label>First Installment</label>
        <input type="text" required="required" readonly  class="form-control fees_input" id="first" value="'.$first.'">
        <br>
        <label>Second Installment</label>
        <input type="text" required="required" readonly  class="form-control fees_input" id="second" value="'.$second.'">
        <br>
        <label>Registration</label>
        <input type="text" required="required" readonly  class="form-control fees_input" id="reg" value="'.$reg.'">
        <label>PTA</label>
        <input type="text" required="required" readonly  class="form-control fees_input" id="PTA" value="'.$pta.'">
        <br>';
        if ($discount != false){
            $dis_id = $discount['discount_id'];
            $reason = $Model->GetDiscountReason($dis_id)['reason'];
            $percent = $Model->GetDiscountReason($dis_id)['percent'];
            $installments = $class_fees['first_ins'] + $class_fees['second_ins'];
            $dis_amt = round(($percent/100)*$installments, 0);
            $data .= '<label>'.$reason.' discount</label>
        <input type="text" required="required" readonly  class="form-control fees_input" id="disc" value="-'.$dis_amt.'">
        <br>';
        }
        $data .= '<label>Total left</label>
        <label>Total left</label>
        <input type="text" required="required" readonly  class="form-control fees_input" id="left" value="'.$left.'">
        <br>
        <br>';
        echo $data;
    }

}

if ($action == 'FeeSettings'){
    $class_id = $_POST['classID'];
    $type = $_POST['Type'];
    $_SESSION['fee_class_id'] = $class_id;
    $_SESSION['fee_class_type'] = $type;
    $fees = $Model->GetFeeStructure($class_id, $type);
    $data = '<form action="" method="post">
    <h4><u>Fees</u></h4>
    <label>Total fees</label>
    <input type="number" required="required" readonly="readonly" class="form-control fees_input" name="total" id="total" value="'.$fees['totalfee'].'">
    <br>
    <label>Registration</label>
    <input type="number" required="required" onfocusout="ComputeTotalFee()" name="reg" class="form-control fees_input" id="reg" value="'.$fees['registration'].'">
    <br>
    <label>PTA</label>
    <input type="number" required="required" onfocusout="ComputeTotalFee()" class="form-control fees_input" id="pta" value="'.$fees['pta'].'" name="pta">
    <br>
    <label>First Installment</label>
    <input type="number" required="required" onfocusout="ComputeTotalFee()" class="form-control fees_input" id="first" value="'.$fees['first_ins'].'" name="first">
    <br>
    <label>Second installment</label>
    <input  type="number" required="required" onfocusout="ComputeTotalFee()" class="form-control fees_input" id="second" value="'.$fees['second_ins'].'" name="second">
    <br>
    <button class="btn btn-primary" type="submit">Update</button>';
    echo $data;

}

if ($action == 'CodeList'){
    $class_id = $_POST['classID'];
    $students = $Model->StudentCodesPerYear($class_id, $Model->GetCurrentYear()[0]['id']);
    $data = "";
    $mixed_stds = [];
    if(!empty($students)){
        $data .= '<br><table class="table table-bordered table-responsive">';
        $data .= '<tr class="table-header"><td>Code</td><td>'.$lang[$_SESSION['lang']]['Name'].'</td><td>'.$lang[$_SESSION['lang']]['Gender'].'</td> <td>T1</td><td>T2</td> <td>T3</td><td>T4</td> <td>T5</td><td>T6</td></tr>';
        foreach($students as $student){
            $std = $Model->GetStudent($student['student_code'], $section);
            $mixed_stds[$student['student_code']] = $std[0]['name'];
        }

        asort($mixed_stds);

        $sn = 1;

        foreach($mixed_stds as $code => $student){
            $data .= '<tr class="normal-tr"><td>'.$code.'</td><td>'.$student.'</td><td>'.$Model->GetStudent($code, $section)[0]['gender'].'</td><td></td><td></td> <td></td><td></td> <td></td><td></td></tr>';
        }

        $data .= '</table><p>';
        $data .= '<a target="blank" href="./pdf/codelistPdf.php?ref='.$class_id.'" title="'.$lang[$_SESSION['lang']]['Save list as PDF'].'" class="btn btn-primary glyphicon glyphicon-download">'.$lang[$_SESSION['lang']]['Save list as PDF'].'</a></p>';
        echo $data;
    }else{
        echo "No students in this class for this year";
    }
   
}

if ($action == "LoadTerms"){
    $year_id = $_POST['year'];
    $exams = $Model->GetAllTerms($year_id);
    $data = '<option value="">'.$lang[$_SESSION['lang']]['Choose one'].'</option>';
    if (!empty($exams)){
        foreach ($exams as $exam){
            $data .= '<option value="'.$exam['term'].'">';
            $data .= $lang[$_SESSION['lang']][strToUpper($exam['term'])].' '.$lang[$_SESSION['lang']]['TERM'];
            $data .= '</option>';
        }
    }

    echo $data;
}

if ($action == "LoadSequences"){
    $year_id = $_POST['year'];
    $exams = $Model->GetAllExams($year_id);
    $sequences = ['ONE'=>'Un', 'TWO'=>'Deux', 'THREE'=>'Trois', 'FOUR'=>'Quartre', 'FIVE'=>'Cinq', 'SIX'=>'Six','MOCK'=>'MOCK', 'PRE-MOCK'=>'PRE-MOCK'];
    $data = '<option value="">'.$lang[$_SESSION['lang']]["Choose one"].'</option>';
    if (!empty($exams)){
        foreach ($exams as $exam){
            $data .= '<option value="'.$exam['id'].'">';
            if($exam['sequence'] == "ONE" || $exam['sequence'] == "TWO"){
                $data .= $lang[$_SESSION['lang']][strToUpper($exam['term'])].' '.strToUpper($lang[$_SESSION['lang']]["TERM"]).' SEQUENCE '.$lang[$_SESSION['lang']][strToUpper($exam['sequence'])];
            }else{
                $data .= strToUpper($exam['term'].' '.$lang[$_SESSION['lang']]["TERM"].' '.$sequences[$exam['sequence']]);
            }
            $data .= '</option>';
        }
    }

    echo $data;
}

if($action == "ShowPromotionList"){
    $class_id = $_POST['klass'];
    $year_id = $_POST['year'];

    $results = $Model->GetPromotionList($class_id, $year_id);
    $data = '<br><div class="curved-box"><form action="" method="post">';
    foreach($results as $student_code => $student_name){
        $data .= $student_name.'&nbsp;&nbsp;<input type="checkbox" name="student[]" value="'.$student_code.'"><br><hr>';
    }
    $data .= '<label>'.$lang[$_SESSION['lang']]['SelectNewClass'].'</label>
    <select name="new_class" class="form-control">
    <option value="">'.$lang[$_SESSION['lang']]['Choose one'].'</option>';
    
    $classes = $Model->GetAllClasses($section);
    if(!empty($classes)){
        foreach($classes as $class){
            
            $data .= '<option value="'.$class['id'].'">';
                    if($class['general_name'] != $class['sub_name']){
                        $data .= $class['general_name'].' '.$class['sub_name'];
                    }else{
                        $data .= $class['general_name'];
                    }
                
            $data .= '</option>';
        }
    }
    
$data .= '</select>
<br>
<label>'.$lang[$_SESSION['lang']]['AcademicYear'].'</label>
<br>
<select name="new_year" class="form-control">
<option value="">'.$lang[$_SESSION['lang']]['Choose one'].'</option>';

$years = $Model->GetCurrentYear();
if(!empty($years)){
foreach($years as $year){
    $data .= '<option value="'.$year['id'].'">';
    $data .= $year['start'].'/'.$year['end'];
    $data .= '</option>';
}
}
$data .= '</select><br>';
$data .= '<button type="submit" class="btn btn-primary">'.$lang[$_SESSION['lang']]['Promote'].'</button>';
$data .= '</form></div><br>';
echo $data;

}

if($action == "ResetPw"){
    $userid = $_POST['user'];
    $result = $Model->ResetUserPassword($userid);
    echo $result;
}

if ($action == "SetClassId"){
    $class_id = $_POST['classID'];
    $subjects = $Model->ViewClassSubjects($class_id);
    $data = '<option value="">'.$lang[$_SESSION['lang']]["Choose one"].'</option>';
    foreach($subjects as $sub){
        $data .= '<option value="'.$sub['subject'].'">'.$sub['subject'].'</option>';
    }
    echo $data;
}


if ($action == 'SaveAbsent'){
    $student_id = $_POST['studentID'];
    $abs = $_POST['Abs'];
    $class_id = $_POST['classID'];
    $term = $_POST['Term'];
    $year = $_POST['Year'];
    $type = $_POST['Type'];
    $result = $Model->SaveAbsence($student_id, $year, $class_id, $term, $abs, $type);
    if ($result == "Successful"){
        echo "Conduct saved";
    }else{
        echo "Conduct not saved";
    }
}

if ($action == 'AbsencesList'){
    $year_id = $_POST['Year']; //The academic year Id
    $class_id = $_POST['klass']; // The name of the subject
    $term = $_POST['Term']; 

    $students_id = $Model->GetStudentsInClass($class_id, $year_id);
    $data = '<br><div class="curved-box"><span id="result" style="color:green;"></span><h4>'.$lang[$_SESSION['lang']]["Class list for"].' <span id="c_name">'.$Model->GetAClassName($class_id).'</span></h4><hr>';
    $id_array = [];
    
    if(!empty($students_id)){
        foreach ($students_id as $id){
            $student = $Model->GetStudent($id['student_code'], $section);
            $id_array[$student[0]['student_code']] = $student[0]['name'];
            asort($id_array);
        }
    }

    foreach ($id_array as $student_code => $student){
        $student_data = [$student_code, $class_id, $term, $year_id ];
        $data .= '<div class="row"><div class="col-xs-7"><label>'.$student.'</label></div>  <div class="col-xs-5"><input data-term="'.$term.'" data-year="'.$year_id.'" data-klass="'.$class_id.'" id="'.$student_code.'" type="text" class="form-control" onfocusout="SaveAbsence(this)" ></div></div><hr>';
    }
    $data .= '</div>';
   echo $data;

}

if($action == 'SequenceStats'){
    $year_id = $_POST['Year']; //The academic year Id
    $subject = $_POST['klass']; // The name of the subject
    $exam_id = $_POST['Exam']; //The exam id
    $html_data = '<h3>SUBJECT STATISTICS</h3>';
    $html_data .= '<h3>'.$Model->GetYearName($year_id).' &mdash;';
    $html_data .= ' '.$Model->GetTermName($exam_id).' &mdash;';
    $html_data .= ' '.$Model->GetSequenceName($exam_id).' &mdash;';
    $html_data .= ' '.$subject.'</h3>';
    $html_data .= '<br><p><button class="btn btn-primary" onclick="SequenceStatPDF()">Save as PDF</button><br><table class="table table-responsive table-bordered">';
    $html_data .= '<tr class="table-header">';
    $html_data .= '<td class="table-header">Teacher</td>';
    $html_data .= '<td class="table-header">Class</td>';
    $html_data .= '<td class="table-header">On roll</td>';
    $html_data .= '<td class="table-header">Males</td>';
    $html_data .= '<td class="table-header">Females</td>';
    $html_data .= '<td class="table-header">Sat</td>';
    $html_data .= '<td class="table-header">Males passed</td>';
    $html_data .= '<td class="table-header">Females passed</td>';
    $html_data .= '<td class="table-header">Total Passed</td>';
    $html_data .= '<td class="table-header">% Passed</td>';
    $html_data .= '</tr>';
    //Get all classes
    $classes = $Model->GetAllClasses($section);

    //For each class 
    /*
        - get the number of students in the class
        - Get the number of males and females
        - Get the number who passed the subject
        - Calculate the percentage of those who passed
        Hence calculate the number who failed
        and the percentage who failed

    */

    foreach ($classes as $class){
        $students = $Model->StudentCodesPerYear($class['id'], $year_id);
        $onroll = count($students); //Students in the class
        $males = 0; $females = 0; 

        //Get males and females
        foreach($students as $student){
            if($Model->GetStudent($student['student_code'], $section)[0]['gender'] == 'M'){
                $males++;
            }else{
                $females++;
            }
        }

        //Get those who passed
        $passed = 0; $failed = 0;
        $students_in_subject = $Model->GetSubjectInMarkSheet($year_id, $class['id'], $exam_id, $subject);
        $sat = count($students_in_subject);
        $male_pass = 0; $female_pass = 0;
        foreach ($students_in_subject as $student){
            if ($student['mark']> 10){
                $passed++;
                if($Model->GetStudent($student['student_code'], $section)[0]['gender'] == 'M'){
                    $male_pass++;
                }else{
                    $female_pass++;
                }
            }else{
                $failed++;
            }
        }

        if ($sat != 0){
            $percentage_pass = round(($passed/$sat)*100, 2);
            $percentage_fail = round(($failed/$sat)*100, 2);
        }else{
            $percentage_pass = 0;
            $percentage_fail = 0;
        }

        $teacher_id = $Model->GetSubjectTeacher($subject, $class['id'], $year_id);


        $html_data .= '<tr class="normal-tr">';
        $html_data .= '<td>'.$Model->GetStaffName($teacher_id).'</td>';
        $html_data .= '<td>'.$Model->GetAClassName($class['id']).'</td>';
        $html_data .= '<td>'.$onroll.'</td>';
        $html_data .= '<td>'.$males.'</td>';
        $html_data .= '<td>'.$females.'</td>';
        $html_data .= '<td>'.$sat.'</td>';
        $html_data .= '<td>'.$male_pass.'</td>';
        $html_data .= '<td>'.$female_pass.'</td>';
        $html_data .= '<td>'.$passed.'</td>';
        $html_data .= '<td>'.$percentage_pass.'</td>';
        $html_data .= '</tr>';
        
    }
    $html_data .= '</table>';
    echo $html_data;

}

if ($action == 'PrintableEnrolment'){
    $year_id = $_POST['Year'];
    //Get all classes available for the selected year
    $classes = $Model->GetAllClasses($section);
    
    $data = '<br> <br> <table class="table  table-responsive table-bordered">';
    $data .= '<tr class="table-header"><td>'.$lang[$_SESSION['lang']]["Class"].'</td><td>'.$lang[$_SESSION['lang']]["Male"].'s</td><td>'.$lang[$_SESSION['lang']]["Female"].'s</td><td>Total</td></tr>';

    $students = [];

    //Get all students enrolled in each of the classes
    $Gmales = 0; $Gfemales = 0; $Gtotal = 0;

    foreach ($classes as $class){
        $students = $Model->GetStudentsInClass($class['id'], $year_id);
        $males = 0; $females = 0;

         //count Males and females and total
        foreach($students as $student){
            if($Model->GetStudent($student['student_code'], $section)[0]['gender'] == 'M'){
                $males++;
            }else{
                $females++;
            }
        }
        $total = $males + $females;
        $data .= '<tr class="normal-tr"><td>'.$Model->GetAClassName($class['id']).'</td><td>'.$males.'</td><td>'.$females.'</td><td>'.$total.'</td></tr>';
        $Gmales += $males;
        $Gfemales += $females;
        $Gtotal += $total;
    }

    //calculate grand total
    $data .= '<tr class="normal-tr"><td><b>Total</b></td><td>'.$Gmales.'</td><td>'.$Gfemales.'</td><td>'.$Gtotal.'</td></tr>';
    $data .= '</table><p>';
    $data .= '<a target="blank" href="./pdf/enrolmentListPdf.php?ref='.$year_id.'" title="Save as PDF" class="btn btn-primary glyphicon glyphicon-download"></a></p>';


    echo $data;
    
}

if ($action == 'PrintableClassList'){
    $class_id = $_POST['classID'];
    $students = $Model->StudentCodesPerYear($class_id, $Model->GetCurrentYear()[0]['id']);
    $data = "";
    $mixed_stds = [];
    if(!empty($students)){
        $data .= '<a target="blank" href="./pdf/classlistPdf.php?ref='.$class_id.'" title="Save as PDF" class="btn btn-primary glyphicon glyphicon-download">&nbsp;Save as PDF</a></p>';
        $data .= '<br><table class="table table-bordered table-responsive">';
        $data .= '<tr class="table-header"><td>SN</td><td>'.$lang[$_SESSION['lang']]["Name"].'</td><td>'.$lang[$_SESSION['lang']]["Gender"].'</td> <td>T1</td><td>T2</td> <td>T3</td><td>T4</td> <td>T5</td><td>T6</td></tr>';
        foreach($students as $student){
            $std = $Model->GetStudent($student['student_code'], $section);
            $mixed_stds[$student['student_code']] = $std[0]['name'];
        }

        asort($mixed_stds);

        $sn = 1;

        foreach($mixed_stds as $code => $student){
            $data .= '<tr class="normal-tr"><td>'.$sn++.'</td><td>'.$student.'</td><td>'.$Model->GetStudent($code, $section)[0]['gender'].'</td><td></td><td></td> <td></td><td></td> <td></td><td></td></tr>';
        }

        $data .= '</table><p>';
        echo $data;
    }else{
        echo "No one in this class this year";
    }
   

}

if ($action == 'SaveMark'){
    $student_code = $_POST['studentID'];
    $mark = (float) $_POST['mark'];
    $class_id = $_POST['classID'];
    $exam_id = $_POST['examID'];
    $subject = $_POST['Subject'];
    $competence = $_POST['competence'];
    $err = false;
    $result = "";
    if (is_numeric($_POST['mark'])){       
        if($mark > 20){
            $result = "Mark must not be more than 20";
            $err = true;
        }

        if($mark < 0){
            $result = "Mark must not be less than zero";
            $err = true;
        }

        if (!$err){
            $data = [$student_code, $class_id, $Model->GetCurrentYear()[0]['id'], $subject, $exam_id, $mark, $competence];
    
            if ($Model->EntryExists($data)){
                $result = $Model->UpdateMark($data);
            }else{
                $result = $Model->SaveMark($data);
            }
    
            
        }
    }else{
        
        $result = $Model->DeleteMark([$student_code, $class_id, $Model->GetCurrentYear()[0]['id'], $subject, $exam_id, $mark, $competence]);
        $err = true;
    }

    if ($result == "Mark Saved"){
        echo '<span style="color:green;">'.$result.'</span>';
    }else{
        echo '<span style="color:red;">'.$result.'</span>';
    }

}


if ($action == 'ShowClassList'){
    $class_id = $_POST['classId'];
    $subject = $_POST['Subject'];

    $students_id = $Model->GetAcademicYearClass($class_id, $Model->GetCurrentYear()[0]['id']);
    $class_name = "";

    $cl = $Model->GetAClass($class_id); 
    if($cl[0]['general_name'] != $cl[0]['sub_name']){
        $class_name = $cl[0]['general_name'].' '.$cl[0]['sub_name'];
    }else{
        $class_name = $cl[0]['general_name'];
     }

    $data = '<h4>'.$lang[$_SESSION['lang']]["Mark registration list for"].'<span id="c_name">'.$class_name.' - '.$subject.'</span></h4><br><input id="competence" type="text" placeholder="'.$lang[$_SESSION['lang']]["Competences tested"].'" class="form-control" value=""><hr>';
    $id_array = [];
    
    if(!empty($students_id)){
        foreach ($students_id as $id){
            $student = $Model->GetStudent($id['student_code'], $section);
            $id_array[$student[0]['student_code']] = $student[0]['name'];
            asort($id_array);
        }
    }
    $sn = 0;
    foreach ($id_array as $student_code => $student){
        $student_data = [$student_code, $class_id, $Model->GetCurrentYear()[0]['id'], $subject, $Model->GetCurrentExam()[0]['id'] ];
        $mark = $Model->GetAMark($student_data);
        $data .= '<div class="row"><div class="col-xs-7"><label>'.++$sn.'. '.$student.'</label></div>  <div class="col-xs-5"><input data-subject="'.$subject.'" data-exam="'.$Model->GetCurrentExam()[0]['id'].'" data-klass="'.$class_id.'" id="'.$student_code.'" type="text" onfocusout="SaveMark(this)" class="form-control" value="'.$mark.'"></div></div><hr>';
    }

   echo $data;
}

if ($action == 'ShowFillMarksList'){
    $class_id = $_POST['classId'];
    $subject = $_POST['Subject'];

    $students_id = $Model->GetAcademicYearClass($class_id, $Model->GetCurrentYear()[0]['id']);
    $class_name = "";

    $cl = $Model->GetAClass($class_id); 
    if($cl[0]['general_name'] != $cl[0]['sub_name']){
        $class_name = $cl[0]['general_name'].' '.$cl[0]['sub_name'];
    }else{
        $class_name = $cl[0]['general_name'];
     }

    $data = '<h4>'.$lang[$_SESSION['lang']]['Mark registration list for'].'<span id="c_name">'.$class_name.'</span></h4><br><input id="competence" type="text" placeholder="'.$lang[$_SESSION['lang']]['Competences tested']. '" class="form-control"><hr>';
    $id_array = [];
    
    if(!empty($students_id)){
        foreach ($students_id as $id){
            $student = $Model->GetStudent($id['student_code'], $section);
            $id_array[$student[0]['student_code']] = $student[0]['name'];
            asort($id_array);
        }
    }
    $sn = 0;
    foreach ($id_array as $student_code => $student){
        $student_data = [$student_code, $class_id, $Model->GetCurrentYear()[0]['id'], $subject, $Model->GetCurrentExam()[0]['id'] ];
        $mark = $Model->GetAMark($student_data);
        if($mark == ""){
            $data .= '<div class="row"><div class="col-xs-7"><label>'.++$sn.'. '.$student.'</label></div>  <div class="col-xs-5"><input data-subject="'.$subject.'" data-exam="'.$Model->GetCurrentExam()[0]['id'].'" data-klass="'.$class_id.'" id="'.$student_code.'" type="text" onfocusout="SaveMark(this)" class="form-control" value="'.$mark.'"></div></div><hr>';

        }else{
            $data .= '<div class="row"><div class="col-xs-7"><label>'.++$sn.'. '.$student.'</label></div>  <div class="col-xs-5"><input disabled="disabled" type="text" class="form-control" value="'.$mark.'"></div></div><hr>';
        }

    }

   echo $data;
}

if ($action == "ShowClosedExamList"){
    $exam_id = $_POST['Exam'];
    $class_id = $_POST['Klass'];
    $subject = $_POST['Subject'];

    $students_id = $Model->GetAcademicYearClass($class_id, $Model->GetCurrentYear()[0]['id']);
    $class_name = "";

    $class_name = $Model->GetAClassName($class_id); 
    
    $data = '<h4>'.$lang[$_SESSION['lang']]['Mark registration list for']. '<span id="c_name">'.$class_name.' - '.$subject.'</span></h4><br><span id="result" style="color:green;"></span><input disabled id="competence" type="text" placeholder="'.$lang[$_SESSION['lang']]['Competences tested']. '" class="form-control"><hr>';
    $id_array = [];
    
    if(!empty($students_id)){
        foreach ($students_id as $id){
            $student = $Model->GetStudent($id['student_code'], $section);
            $id_array[$student[0]['student_code']] = $student[0]['name'];
            asort($id_array);
        }
    }
    $sn = 0;
    foreach ($id_array as $student_code => $student){
        $student_data = [$student_code, $class_id, $Model->GetCurrentYear()[0]['id'], $subject, $exam_id ];
        $mark = $Model->GetAMark($student_data);
        $data .= '<div class="row"><div class="col-xs-7"><label>'.++$sn.'. '.$student.'</label></div>  <div class="col-xs-5"><input data-subject="'.$subject.'" data-exam="'.$exam_id.'" data-klass="'.$class_id.'" id="'.$student_code.'" type="text" onfocusout="SaveMark(this)" class="form-control" value="'.$mark.'"></div></div><hr>';
    }

    echo $data;

}