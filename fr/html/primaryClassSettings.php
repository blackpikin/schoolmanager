<?php 
    $genName = "";
    $subName = ""; $result = ""; $cycle=""; $err = false;
    $mockable = "0";
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $genName = $_POST['className'];
        $subName = $Primodel->test_input($_POST['subName']);
        $mockable = $_POST['mockable'];
        if (empty($subName)){
            $err=  true;
            $result = "Enter the sub name";
        }

        if(!$err){
            $data = [$genName, $subName, $cycle, $mockable, $section];
            $result = $Model->RegisterNewClass($data);
        }
    }
?>
<br>
<p style="color:red;"><?= $result ?></p>
<br>
<div class="row">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]['ClassSettings'] ?></label>
        </p>
        <form action="" method="post">
        <p>
            <label><?= $lang[$_SESSION['lang']]['GeneralClassName'] ?></label>
            <select required="required" class="form-control" name="className">
                <option value=""><?= $lang[$_SESSION['lang']]['Select the class'] ?></option>
                    <?php 
                    if($section == 1){
                        //Add content here if French section exists
                    }else{
                        ?>
                            <option value="DAY-CARE">DAY-CARE</option>
                            <option value="PRE-NURSERY">PRE-NURSERY</option>
                            <option value="NURSERY ONE">NURSERY ONE</option>
                            <option value="NURSERY TWO">NURSERY TWO</option>
                            <option value="CLASS ONE">CLASS ONE</option>
                            <option value="CLASS TWO">CLASS TWO</option>
                            <option value="CLASS THREE">CLASS THREE</option>
                            <option value="CLASS FOUR">CLASS FOUR</option>
                            <option value="CLASS FIVE">CLASS FIVE</option>
                            <option value="CLASS SIX">CLASS SIX</option>
                        <?php
                    }
                ?>
            </select>
        </p>
        <p>
            <label><?= $lang[$_SESSION['lang']]['SubClassName'] ?></label>
            <input type="text" required="required" class="form-control" name="subName" placeholder="e.g. A, B or C">
        </p>
        
        <label><?= $lang[$_SESSION['lang']]['Mockable'] ?></label>
            <select required="required" class="form-control" name="mockable">
                <option><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
                <option value="1"><?= $lang[$_SESSION['lang']]['Yes'] ?></option>
                <option value="0"><?= $lang[$_SESSION['lang']]['No'] ?></option>
            </select>   
        <br>
            <button type="submit" class="btn btn-primary"><?= $lang[$_SESSION['lang']]['Save'] ?></button>
        </form>
        </div>
    </div>
    <div class="col-xs-2">

    </div>
    </div>