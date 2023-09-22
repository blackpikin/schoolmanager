<?php 
    $srch = ''; $discount = ''; $results =''; $students = []; $err = false; $resultats='';
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST['srch'])){
            $srch = $Model->test_input($_POST['srch']);
            if (!empty($srch)){
                $results = $Model->SearchStudent($srch);
            }
        }elseif(isset($_POST['discount'])){
            $students = $_POST['students'];
            $discount = $_POST['discount'];
            if(empty($students) || empty($discount)){
                $err = true;
                $resultats = 'Please select the student(s) and the discount';
            }

            if (!$err){
                if(is_array($students)){
                    foreach($students as $student_code){
                        $resultats = $Model->NewDiscount($student_code, $discount, $_SESSION['id'], date('Y-m-d'), date('m-Y'), date('Y'));
                    }
                }else{
                    $resultats = $Model->NewDiscount($students, $discount, $_SESSION['id'], date('Y-m-d'), date('m-Y'), date('Y'));
                }
            }
            
        }
    }

?>
<p style="color:red; font-weight:bold;"><?= $resultats ?></p>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1">Discount</label>
        </p>
    </div>
    <div class="col-xs-4">

    </div>
</div>

<div class="row" style="margin-top: 10px;">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-8">
    <div class="row">
    <div class="col-xs-1">

    </div>
    <div class="col-xs-8">
    <label>Search student</label>
            <form id="search" method="post" action="">
                <input type="text" required="required" class="form-control" name="srch" id="name" value="<?= $srch ?>">
            </form>
            <br>
    </div>
    <div class="col-xs-3">
        <br>
        <button onclick="SubmitSearch()" class="btn btn-warning glyphicon glyphicon-search"></button>
    </div>
</div>
    
            <div class="row">
        <div class="col-xs-12">
            <div class="curved-box">
            <h4><u>Search results</u></h4>
            <form id="payment" action="" method="post">
            <?php 
                if (!empty($results)){
                    ?>
                    <table class="table table-responsive table-bordered">
                        <tr class="table-header">
                            <td>Select</td>
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
                        <td><input type="checkbox" name="students[]" value="<?= $studentCode ?>"></td>
                        <td><span onmouseover="SetPointer(this)"><?= $result['name'] ?></span></td>
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
            <label>Select discount type</label>
            <select name="discount" class="form-control">
            <option value="">Choose one</option>
            <?php 
                $discounts = $Model->GetDiscountReasons();
                if(!empty($discounts)){
                    foreach($discounts as $d){
                    ?>
                        <option value="<?= $d['id'] ?>"><?= $d['reason'] ?></option>
                    <?php
                    }
                }
            ?>
            </select>
            <br>
            <button type="submit" class="btn btn-danger">Receive</button>
            </form>
        </div>
        </div>
    </div>
            </div>
    <div class="col-xs-2">

    </div>
</div>
