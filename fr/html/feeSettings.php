<?php
    $result = ""; $err = false;
    $classes = [];
    $total = 0; $reg = 0; $pta = 0; $first = 0; $second = 0;
    $type = '';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $classes = $_POST['classes'];
        $total = $_POST['total'];
        $reg = $_POST['reg'];
        $pta = $_POST['pta'];
        $first = $_POST['first'];
        $second = $_POST['second'];
        $type = $_POST['newold'];

        if(empty($classes)){
            $err = true;
            $result = "Select a class that has this fee structure";
        }

        if(empty($total)){
            $err = true;
            $result = "Enter the fee structure";
        }

        if(empty($reg)){
            //$err = true;
            //$result = "Enter the Registration fee";
        }

        if(empty($pta)){
            //$err = true;
            //$result = "Enter the PTA";
        }

        if(empty($first)){
            //$err = true;
            //$result = "Enter the First Installment";
        }

        if(empty($second)){
            //$err = true;
            //$result = "Enter the Second Installment";
        }

        if(!$err){
            foreach($classes as $class){
                $result = $Model->NewFees($class, $total, $reg, $pta, $first, $second, $type, $_SESSION['id'] );
            }
        }

        if($result == 'Successful'){
            $total = 0; $reg = 0; $pta = 0; $first = 0; $second = 0;
        }
    }


?>
<div class="row" style="margin-top: 10px;">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <p>
            <label id="label1">Fee settings</label>
        </p>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
</div>
<p style="color:red; font-weight:bold;"><?= $result ?></p>
<div class="row">
    <div class="col-md-4 col-sm-4 col-xs-4">
        <h3>Primary</h3>
        <div class="curved-box">
            <h4><u>Classes</u></h4>
            <form action="" method="post">
            <?php 
                $cycle1 = $Primodel->GetAllPrimaryClasses();
                if (!empty($cycle1)){
                    foreach($cycle1 as $class){
                        ?>
                        <input type="checkbox" name="classes[]" value="<?= $class['id'] ?>" >
                        <?php
                            echo $Model->GetAClassName($class['id']);
                        ?>
                        <br>
                        <?php
                    }
                }
            ?>
        </div>
        <h3>Secondary</h3>
        <div class="curved-box">
            <h4><u>Classes</u></h4>
            <?php 
                $cycle1 = $Model->GetAllClasses($section);
                if (!empty($cycle1)){
                    foreach($cycle1 as $class){
                        ?>
                        <input type="checkbox" name="classes[]" value="<?= $class['id'] ?>" >
                        <?php
                            echo $Model->GetAClassName($class['id']);
                        ?>
                        <br>
                        <?php
                    }
                }
            ?>
        </div>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
    <div class="curved-box">
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
    </div>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <div class="curved-box">
        <h4><u>Student type</u></h4>
        <input id="new" type="radio" name="newold" value="new" required="required"> New
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input id="old" type="radio" name="newold" value="old" required="required"> Old
        <br>
        <br>
        <button class="btn btn-primary" type="submit">Save</button>
        </form>
        </div>
        <br>
        <div class="curved-box">
            <button class="btn btn-primary" onclick="GotoPage('modifyFees')">Modify Fees</button>
        </div>
    </div>
</div>