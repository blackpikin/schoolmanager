<?php
    $srch =''; $results = []; $amount= 0; $resultat = '';
    $reg = 0; $pta = 0; $first = 0; $second = 0; $left = 0; $amount = 0;
    $reg = 0; $pta = 0; $first = 0; $second = 0;
    $remain1 = 0;  $remain2 = 0; $remain3 = 0; $remain4 = 0; $payment = 0; $discount=false;
    $s = []; $err = false;

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST['srch'])){
            $srch = $Model->test_input($_POST['srch']);
            if (!empty($srch)){
                $results = $Primodel->SearchPupil($srch);
            }
        }elseif(isset($_POST['payment'])){
            $student_code = $_SESSION['payfees_student_code'];
            $class_id = $_SESSION['payfees_student_class'];
            $year_id = $Model->GetCurrentYear()[0]['id'];
            $payment = $_POST['payment'];
            $feeType = $Model->FeeType($student_code);
            $class_fees = $Model->GetFeeStructure($class_id, $feeType);
            $amount = $Model->GetStudentsFees($student_code, $class_id, $year_id);
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
            $s = $Primodel->GetPupil($student_code);
            $results = $Primodel->SearchPupil($s[0]['name']);

            if($payment > $class_fees){
                $err = true;
                $resultat = 'The amount you typed is higher than fees for the class';
            }

            if($payment > $left){
                $err = true;
                $resultat = 'The amount you typed is higher than the fees left for the student';
            }

            if(!$err){
                $resultat = $Model->NewFeePayment($student_code, $year_id, $class_id, $payment, date('Y-m-d'), date('m-Y'), date('Y'), $_SESSION['id']);
            }
            
            if($resultat == 'Successful'){
                //TODO: Print PDF receipt

                $payment = 0;
                $amount = $Model->GetStudentsFees($student_code, $class_id, $year_id);
                $feeType = $Model->FeeType($student_code);
                $class_fees = $Model->GetFeeStructure($class_id, $feeType);
                $s = $Primodel->GetPupil($student_code);
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

            }
        }
    }
 }
?>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1">Receive fees (Primary)</label>
        </p>
    </div>
    <div class="col-xs-4">
        
    </div>
</div>
<div id="recFee">
<p style="color:red; font-weight:bold;"><?= $resultat ?></p>
    <div class="row">
        <div class="col-xs-3">
            <div id="fee_bkd" class="curved-box">
                <h4><u>Fees</u></h4>
                <label>Student's name</label>
                <input type="text" required="required" readonly class="form-control fees_input_small" id="name" value="<?= isset($s[0]['name'])? $s[0]['name']:"" ?>">
                <br>
                <label>Total paid</label>
                <input type="text" required="required" readonly class="form-control fees_input" id="total" value="<?= $amount ?>">
                <br>
                <label>First Installment</label>
                <input type="text" required="required" readonly  class="form-control fees_input" id="first" value="<?= $first ?>">
                <br>
                <label>Second Installment</label>
                <input type="text" required="required" readonly  class="form-control fees_input" id="second" value="<?= $second ?>">
                <br>
                <label>Registration</label>
                <input type="text" required="required" readonly  class="form-control fees_input" id="reg" value="<?= $reg ?>">
                <label>PTA</label>
                <input type="text" required="required" readonly  class="form-control fees_input" id="PTA" value="<?= $pta ?>">
                <br>
                <?php
                    if ($discount != false){
                        $dis_id = $discount['discount_id'];
                        $reason = $Model->GetDiscountReason($dis_id)['reason'];
                        $percent = $Model->GetDiscountReason($dis_id)['percent'];
                        $installments = $class_fees['first_ins'] + $class_fees['second_ins'];
                        $dis_amt = round(($percent/100)*$installments, 0);
                        ?>
                        <label><?= $reason?> discount</label>
                    <input type="text" required="required" readonly  class="form-control fees_input" id="disc" value="-<?= $dis_amt ?>">
                    <br>
                    <?php
                    }

                    ?>
                <label>Total left</label>
                <input type="text" required="required" readonly  class="form-control fees_input" id="left" value="<?= $left ?>">
                <br>
                <br>
            </div>
        </div>
        <div class="col-xs-7">
        <label>Search student</label>
            <form id="search" method="post" action="">
                <input type="text" required="required" class="form-control" name="srch" id="name" value="<?= $srch ?>">
            </form>
            <br>
            <div class="row">
        <div class="col-xs-12">
            <div class="curved-box">
            <h4><u>Search results</u></h4>
            <?php 
                if (!empty($results)){
                    ?>
                    <table class="table table-responsive table-bordered">
                        <tr class="table-header">
                            <td>Name</td>
                            <td>Gender</td>
                            <td>Current class</td>
                        </tr>
                    <?php
                    foreach ($results as $result){
                        $year = $Model->GetCurrentYear()[0]['id'];
                        $studentCode = $result['student_code'];
                        $class_id = $Model->GetClassId($year, $studentCode);
                        $className = $Model->GetAClassName($class_id);
    
                        ?>
                        <tr class="normal-tr">
                        <td><span onmouseover="SetPointer(this)" onclick="LoadPrimaryFees('<?= $studentCode ?>','<?= $class_id ?>')"><?= $result['name'] ?></span></td>
                        <td><?= $result['gender'] ?></td>
                        <td><?= $className ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                     </table>
                <?php
                }else{
                    ?>
                    <label style="color:red;font-size:11pt;font-style:italic">No student found</label>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="col-xs-12">
        <div class="curved-box">
        <h4><u>Fees</u></h4>
            <form id="payment" action="" method="post">
            <label>Enter amount received</label>
            <input name="payment" type="number" placeholder="amount in francs CFA" required="required" class="form-control fees_input" id="amount" value="<?= $payment ?>">
            <br>
            <button type="submit" class="btn btn-danger">Receive</button>
            </form>
        </div>
        </div>
    </div>
        </div>
        <div class="col-xs-2">
            <br>
        <button onclick="SubmitSearch()" class="btn btn-warning fa fa-search"></button>
        </div>
    </div>
</div>