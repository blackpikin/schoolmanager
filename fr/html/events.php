<?php
$event = ""; $date = ''; $duration = ""; $montYear = ""; $result = ""; $err = false;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $event = $Model->test_input($_POST['event']);
    $date = $Model->test_input($_POST['dateof']);
    $duration = $Model->test_input($_POST['duration']);
    $sec = $Model->test_input(($_POST['sec']));
    if(!empty($sec)){
        $err = true;
    }
    if(empty($event)){
        $err = true;
        $result = "Invalid event name";
    }
    if(empty($date)){
        $err = true;
        $result = "Invalid date";
    }
    if(empty($duration)){
        $err = true;
        $result = "Invalid duration";
    }
    $color = $_POST['color'];
    $arr = explode('-', $date);

    $montYear = $arr[0].'-'.$arr[1];

    if(!$err){
        $result = $Model->RegisterNewEvent($event, $date, $duration, $color, $montYear);
    }

    if($result == "Successful"){
        $event = ""; $date = ''; $duration = ""; $montYear = "";
    }

}

?>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["Add events"] ?></label>
        </p>
        <div class="curved-box">
        <p style="color:red;"><?= $result ?></p>
        <form action="" method="post">
        <p>
            <label><?= $lang[$_SESSION['lang']]["Event name"] ?></label>
            <input type="text" class="form-control" placeholder="Event name e.g. End of term" name="event" required="required" value="<?= $event ?>">
            <br>
            <label>Date</label>
            <input type="text" class="form-control" placeholder="format:YYYY-mm-dd e.g. 2020-06-03 i.e. 3rd June 2020" name="dateof" required="required" value="<?= $date ?>">
            <br>
            <label><?= $lang[$_SESSION['lang']]['Duration in days'] ?></label>
            <input type="number" class="form-control" placeholder="Whole numbers only" name="duration" required="required" value="<?= $duration ?>">
            <br>
            <label><?= $lang[$_SESSION['lang']]["Colour"] ?></label>
            <select name="color" class="form-control">
                <option value="red"><?= $lang[$_SESSION['lang']]["Red"] ?></option>
                <option value="green"><?= $lang[$_SESSION['lang']]["Green"] ?></option>
                <option value="blue"><?= $lang[$_SESSION['lang']]["Blue"] ?></option>
            </select>
        </p>
        <p>
                <label class="sec">Sec</label>
               <input type="text" class="form-control sec" name="sec" value="">
            </p>
        <button type="submit" class="btn btn-primary"><?= $lang[$_SESSION['lang']]["Save"] ?></button>
        </form>
        </div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    </div>