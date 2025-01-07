<?php 
    $staff_id = $_GET['ref'];
    $academic_year = $Model->GetCurrentYear()[0]['id']; 
    $result = "";

    $staff_days = $Model->ContentExists('staff_days', 'staff_id', $staff_id);

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(!isset($_POST['days'])){
            $result = "Select at least one day";
        }else{
            $days = $_POST['days'];
            $days = implode(',', $days);
            if(empty($Model->ContentExists('staff_days', 'staff_id', $staff_id))){
                $result = $Model->Insert('staff_days', ['staff_id' => $staff_id, 'dow' => $days]);
            }else{
                $result = $Model->Update('staff_days', ['dow' => $days], ['staff_id' => $staff_id]);
            }
        } 
        $staff_days = $Model->ContentExists('staff_days', 'staff_id', $staff_id);
    }
?>
<br>
<p style="color:red;"><?= $result ?></p>
<br>
<div class="row" >
    <div class="col-md-3 col-sm-3 col-xs-3">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["AvailableDaysFor"] ?> <?= $Model->GetUser($_GET['ref'])[0]['name'] ?></label>
        </p>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>
<div class="row">
<div class="col-md-3 col-sm-3 col-xs-3">

</div>
<div class="col-md-6 col-sm-6 col-xs-6 curved-box">
    <form action="" method="post">
       <p><input type="checkbox" name="days[]" value="Monday"><label> Monday</label></p>
       <p><input type="checkbox" name="days[]" value="Tuesday"><label> Tuesday</label></p>
       <p><input type="checkbox" name="days[]" value="Wednesday"><label> Wednesday</label></p>
       <p><input type="checkbox" name="days[]" value="Thursday"><label> Thursday</label></p>
       <p><input type="checkbox" name="days[]" value="Friday"><label> Friday</label></p>
       <p><input type="checkbox" name="days[]" value="Saturday"><label> Saturday</label></p>
       <button type="submit" class="btn btn-primary"><?= $lang[$_SESSION['lang']]["Save"] ?></button>
    </form>
</div>
<label>Selected days: <br><?= isset($staff_days[0]['dow']) ? $staff_days[0]['dow'] : 'None' ?></label>
<div class="col-md-3 col-sm-3 col-xs-3">
    
</div>
</div>
