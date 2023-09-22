<?php

$lng = $_SESSION['lang'];
$section = 0;
if($lng == 'fr'){
    $section = 1;
}


$result = ""; $err = false;
$classes = [];
$total = 0; $reg = 0; $pta = 0; $first = 0; $second = 0;
$type = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $class = $_SESSION['fee_class_id'];
    $total = $_POST['total'];
    $reg = $_POST['reg'];
    $pta = $_POST['pta'];
    $first = $_POST['first'];
    $second = $_POST['second'];
    $type = $_SESSION['fee_class_type'];

    if(empty($class)){
        $err = true;
        $result = "Select a class that has this fee structure";
    }

    if(empty($total)){
        $err = true;
        $result = "Enter the fee structure";
    }

    if(!$err){
        $result = $Model->UpdateFees($class, $total, $reg, $pta, $first, $second, $type, $_SESSION['id'] );
    }

    if($result == 'Successful'){
        $total = 0; $reg = 0; $pta = 0; $first = 0; $second = 0;
        $_SESSION['fee_class_id'] = '';
        $_SESSION['fee_class_type'] = '';
    }
}

?>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1">Modify fee settings</label>
        </p>
    </div>
    <div class="col-xs-4">

    </div>
</div>
<p style="color:red; font-weight:bold;"><?= $result ?></p>
<div class="row">
    <div class="col-xs-4">
    <label>Select the class</label>
        <select id="c_name" class="form-control">
            <option value="">Select a class</option>
            <optgroup label="Primary">Primary</optgroup>
            <?php 
            $classes = $Primodel->GetAllPrimaryClasses();
            if(!empty($classes)){
                foreach($classes as $class){
                    ?>
                    <option value="<?= $class['id'] ?>">
                        <?= $Model->GetAClassName($class['id'])  ?>
                    </option>
                    <?php
                }
            }
            ?>
            <optgroup label="Secondary">Secondary</optgroup>
            <?php 
            $classes = $Model->GetAllClasses($section);
            if(!empty($classes)){
                foreach($classes as $class){
                    ?>
                    <option value="<?= $class['id'] ?>">
                        <?= $Model->GetAClassName($class['id'])  ?>
                    </option>
                    <?php
                }
            }
            ?>

        </select>
        <br>
        <div class="curved-box">
            <h4><u>Student type</u></h4>
            <input id="new" type="radio" name="newold" value="new" required="required"> New
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input id="old" type="radio" name="newold" value="old" required="required"> Old
            <br>
            <br>
            <button class="btn btn-primary" onclick="LoadFeeSettings()">View</button>
        </div>
    </div>
    <div class="col-xs-4">
    <div id="fee_list" class="curved-box">
    <form action="" method="post">
        <h4><u>Fees</u></h4>
        <label>Total fees</label>
        <input type="number" required="required" readonly="readonly" class="form-control fees_input" name="total" id="total" value="<?= $total ?>">
        <br>
        <label>Registration</label>
        <input type="number" required="required" onfocusout="ComputeTotalFee()" name="reg" class="form-control fees_input" id="reg" value="<?= $reg ?>">
        <br>
        <label>PTA</label>
        <input type="number" required="required" onfocusout="ComputeTotalFee()" class="form-control fees_input" id="pta" value="<?= $pta ?>" name="pta">
        <br>
        <label>First Installment</label>
        <input type="number" required="required" onfocusout="ComputeTotalFee()" class="form-control fees_input" id="first" value="<?= $first ?>" name="first">
        <br>
        <label>Second installment</label>
        <input  type="number" required="required" onfocusout="ComputeTotalFee()" class="form-control fees_input" id="second" value="<?= $second ?>" name="second">
        <br>
        <button class="btn btn-primary" type="submit">Update</button>
    </form>
    </div>
    </div>
    <div class="col-xs-4">
    
    </div>
</div>