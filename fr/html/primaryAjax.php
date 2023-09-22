<?php 
session_start();
include "../includes/Model.php";
include "../includes/PrimaryModel.php";
include '../includes/Lang.php';
$lng = $_SESSION['lang'];
$section = 0;
if($lng == 'fr'){
    $section = 1;
}

$Model = new Model($section);
$Primodel = new PrimaryModel($section);

$action = $_POST['action'];

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
        $s = $Primodel->GetPupil($student_code);
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

if ($action == 'PrintableClassList'){
    $class_id = $_POST['classID'];
    $students = $Model->StudentCodesPerYear($class_id, $Model->GetCurrentYear()[0]['id']);
    $data = "";
    $mixed_stds = [];
    if(!empty($students)){
        $data .= '<a target="blank" href="./pdf/primaryClassListPdf.php?ref='.$class_id.'" title="Save as PDF" class="btn btn-primary glyphicon glyphicon-download">&nbsp;Save as PDF</a></p>';
        $data .= '<br><table class="table table-bordered table-responsive">';
        $data .= '<tr class="table-header"><td>SN</td><td>'.$lang[$_SESSION['lang']]["Name"].'</td><td>'.$lang[$_SESSION['lang']]["Gender"].'</td> <td>T1</td><td>T2</td> <td>T3</td><td>T4</td> <td>T5</td><td>T6</td></tr>';
        foreach($students as $student){
            $std = $Primodel->GetPupil($student['student_code']);
            $mixed_stds[$student['student_code']] = $std[0]['name'];
        }

        asort($mixed_stds);

        $sn = 1;

        foreach($mixed_stds as $code => $student){
            $data .= '<tr class="normal-tr"><td>'.$sn++.'</td><td>'.$student.'</td><td>'.$Primodel->GetPupil($code)[0]['gender'].'</td><td></td><td></td> <td></td><td></td> <td></td><td></td></tr>';
        }

        $data .= '</table><p>';
        echo $data;
    }else{
        echo "No one in this class this year";
    }
   

}