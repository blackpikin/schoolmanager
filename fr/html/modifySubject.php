<?php
$class_id = $_GET['ref'];
$subject = $_GET['title'];
$result = ""; $err = false;
$data = $Model->GetAllWithCriteria('subjects', ['class_name' => $class_id, 'subject' => $subject]);
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $coef = $Model->test_input($_POST['coefficient']);
    $group = $Model->test_input($_POST['Sgroup']);
    $hours = $Model->test_input($_POST['hours']);

    if(empty($hours) || $hours <= 0 ){
        $result = "Invalid number of hours";
        $err = true;
    }

    if(!$err){
        $result = $Model->Update('subjects', ['coef' => $coef, 'rep_group' => $group, 'hours' => $hours], ['subject' => $subject, 'class_name' => $class_id]);
    }
    $data = $Model->GetAllWithCriteria('subjects', ['class_name' => $class_id, 'subject' => $subject]);
}
?>
<p style="color:red;"><?= $result ?></p>
<div class="row" >
    <div class="col-xs-3">

    </div>
    <div class="col-xs-8">
        <p>
            <label id="label1"><?= 'Modify '.$subject.' for '.$Model->GetAClassName($class_id) ?></label>
        </p>
    </div>
    <div class="col-xs-1">

    </div>
</div>
<div class="row" >
    <div class="col-xs-3">

    </div>
    <div class="col-xs-6 curved-box">
        <form action="" method="post">
            <label>Coefficient</label>
            <select class="form-control" name="coefficient" id="coefficient" required>
                <option value="<?= $data[0]['coef'] ?>"><?= $data[0]['coef'] ?></option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
            </select>
            <br>
            <label>Group</label>
            <div class="curved-box">
                <input type="radio" 
                <?php if($data[0]['rep_group'] == 1){ echo 'checked="checked"'; } ?> name="Sgroup" value="1">&nbsp;<?= $lang[$_SESSION['lang']]['Group'] ?> 1 (General)
                &nbsp;&nbsp;&nbsp;
                <input type="radio" <?php if($data[0]['rep_group'] == 2){ echo 'checked="checked"';} ?> name="Sgroup" value="2">&nbsp;<?= $lang[$_SESSION['lang']]['Group'] ?> 2 (Science)
                &nbsp;&nbsp;&nbsp;
                <input type="radio"  <?php if($data[0]['rep_group'] == 3){ echo 'checked="checked"'; } ?>name="Sgroup" value="3">&nbsp;<?= $lang[$_SESSION['lang']]['Group'] ?> 3 (Arts)
                &nbsp;&nbsp;&nbsp;
                <input type="radio"  <?php if($data[0]['rep_group'] == 0){ echo 'checked="checked"'; } ?>name="Sgroup" value="0">&nbsp;No <?= $lang[$_SESSION['lang']]['Group'] ?>
            </div>
            <br>
            <label>Hours per week</label>
            <input type="number" name="hours" value="<?= $data[0]['hours'] ?>" id="hours" class="form-control" required>
            <br>
            <button class="btn btn-primary" type="submit"><?= $lang[$_SESSION['lang']]['Save'] ?></button>
        </form>
    </div>
    <div class="col-xs-3">

    </div>
</div>


