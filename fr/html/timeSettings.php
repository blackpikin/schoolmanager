<?php 
    $result = "";
    $startTime = "";
    $endTime = "";
    $periodLen = "";
    $breakLen = "";
    $breakAfter = "";
    $oneToThree = "";
    $fourToFive = "";
    $lssToUss = "";
    $practoLen = "";
    $practoPref = "";
    $sec = "";
    $err = false;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $startTime = $Model->test_input($_POST['startTime']);
        $endTime = $Model->test_input($_POST['endTime']);
        $periodLen = $Model->test_input($_POST['periodLen']);
        $breakLen = $Model->test_input($_POST['breakLen']);
        $breakAfter = $Model->test_input($_POST['breakAfter']);
        $oneToThree = $Model->test_input($_POST['f1to3']);
        $fourToFive = $Model->test_input($_POST['f4to5']);
        $lssToUss = $Model->test_input($_POST['LSUS']);
        $practoLen = $Model->test_input($_POST['practoLen']);
        $practoPref = $Model->test_input($_POST['practoPref']);
        $sec = $Model->test_input($_POST['sec']);

        if(!empty($sec)){
            $err = true;
        }

        if(!$err){
            $data = [
                'startTime'=>$startTime, 
                'endTime'=>$endTime,
                'periodLen'=>$periodLen, 
                'breakLen'=>$breakLen, 
                'breakAfter'=>$breakAfter, 
                'oneToThree'=>$oneToThree, 
                'fourToFive'=>$fourToFive,
                'lssToUss'=>$lssToUss, 
                'practoLen'=>$practoLen, 
                'practoPref'=>$practoPref];
            if(!empty($Model->ContentExists('time_settings', 'id', 1))){
                $result = $Model->Update('time_settings', $data, ['id'=>1]);
            }else{
                $result = $Model->Insert('time_settings', $data);
            }
        }

        
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
            <label id="label1"><?= $lang[$_SESSION['lang']]["TimeSettings"] ?></label>
        </p>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>
<div class="row" >
    <div class="col-md-3 col-sm-3 col-xs-3">

    </div>
    <div class="col-md-6 col-sm-6 col-xs-6 curved-box">
        <form action="" method="post">
        <p>
       <label>Lessons' start time</label>
       <input type="time" name="startTime" class="form-control" placeholder="Start time" required>
        </p>
        <p>
       <label>Lessons' end time</label>
       <input type="time" name="endTime" class="form-control" placeholder="End time" required>
        </p>
        <p>
        <p>
       <label>How long is one period?(in minutes)</label>
       <input type="number" name="periodLen" class="form-control" placeholder="e.g 45 minutes" required>
        </p>
       <label>How long is break?(in minutes)</label>
       <input type="number" name="breakLen" class="form-control" placeholder="e.g 30 minutes" required>
        </p>
        <p>
       <label>Break comes after how many hours/periods?</label>
       <input type="number" name="breakAfter" class="form-control" placeholder="e.g 4 periods" required>
        </p>
        <p>
       <label>How many hours/periods do Forms 1 - 3 have per day?</label>
       <input type="number" name="f1to3" class="form-control" placeholder="e.g 8 hours" required>
        </p>
        <p>
       <label>How many hours/periods do Forms 4 - 5 have per day?</label>
       <input type="number" name="f4to5" class="form-control" placeholder="e.g 8 hours" required>
        </p>
        <p>
       <label>How many hours/periods do Lower Sixth to Upper Sixth have per day?</label>
       <input type="number" name="LSUS" class="form-control" placeholder="e.g 8 hours" required>
        </p>
        <div class="curved-box">
            <label>For classes with Practicals</label>
            <br>
            <p>
                <label>How many hours/periods should practicals take?</label>
                <input type="number" name="practoLen" class="form-control" placeholder="e.g 3 hours" required>
            </p>
            <p>
                <label>When is it preferable to have practicals?</label><br>
                <input type="radio" name="practoPref"  value="morning" required><label>Morning</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="practoPref"  value="afternoon" required><label>Afternoon</label>
            </p>
            <p>
            <label class="sec">Sec</label>
            <input type="text" class="form-control sec" name="sec" value="">
            </p> 
            <p>
                <button type="submit" name="submit" class="btn btn-primary">Save</button>
            </p>
        </div>
        </form>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-3">

    </div>
</div>