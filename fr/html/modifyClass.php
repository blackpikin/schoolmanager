<?php 
$class = $Model->GetAClass($_GET['ref']);
    $genName = $class[0]['general_name'];
    $subName = $class[0]['sub_name']; $result = ""; 
    
    if($class[0]['cycle'] == 'FIRST'){
        $cycle = $lang[$_SESSION['lang']]['FIRST'].' CYCLE';
    }else if($class[0]['cycle'] == 'SECOND'){
        $cycle = $lang[$_SESSION['lang']]['SECOND'].' CYCLE';
    }else{
        $cycle = "";
    }
                    
    $mockable = $class[0]['mockable'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $genName = $_POST['className'];
        $subName = $Model->test_input($_POST['subName']);
        if(isset($_POST['cycle'])){
            $cycle = $_POST['cycle'];
        }else{
            $cycle = "";
        }

        if (empty($subName)){
            $subName = $genName;
        }

        $data = [$genName, $subName, $cycle, $mockable, $_GET['ref']];
        $result = $Model->UpdateClass($data);
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
            <label id="label1"><?= $lang[$_SESSION['lang']]['ModifyClass'] ?></label>
        </p>
        <form action="" method="post">
        <p>
            <label><?= $lang[$_SESSION['lang']]['GeneralClassName'] ?></label>
            <input type="text" value="<?= $genName ?>" class="form-control" name="className" >
        </p>
        <p>
            <label><?= $lang[$_SESSION['lang']]['SubClassName'] ?></label>
            <input type="text"  value="<?= $subName ?>" required="required" class="form-control" name="subName" placeholder="e.g. A or leave empty if sub class name is same as general name">
        </p>
        <?php 
            if ($cycle !== ""){
                ?>
                    <p>
                        <label><?= $lang[$_SESSION['lang']]['Level'] ?></label>
                        <select required="required" class="form-control" name="cycle">
                            <option value="<?= $cycle ?>"><?= $cycle ?></option>
                            <option value='FIRST'><?= $lang[$_SESSION['lang']]['FIRST'] ?> CYCLE</option>
                             <option value='SECOND'><?= $lang[$_SESSION['lang']]['SECOND'] ?> CYCLE</option>
                        </select>        
                    </p>
                <?php
            }
        ?>
        <label><?= $lang[$_SESSION['lang']]['Mockable'] ?></label>
            <select required="required" class="form-control" name="mockable">
                <option value="<?= $mockable ?>"><?= $mockable == 1 ?  $lang[$_SESSION['lang']]['Yes'] : $lang[$_SESSION['lang']]['No'] ?></option>
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