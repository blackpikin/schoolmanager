<?php 
    $start = ""; $end = ""; $err = false;
    $result = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $start = $Model->test_input($_POST['start']);
        $end = $Model->test_input($_POST['end']);
        $sec = $Model->test_input(($_POST['sec']));
        if(!empty($sec)){
            $err = true;
        }

        $start = (int) $start;
        $end = (int) $end;

        if (empty($start) || !is_int($start) || strlen($start) != 4){
            $err = true;
            $result = "Invalid start value for academic year";
        }

        if (empty($end) || !is_int($end) || strlen($end) != 4){
            $err = true;
            $result = "Invalid end value for academic year";
        }

        if(!$err){
            $data = [$start, $end];
            $result = $Model->EndAcademicYear();
            if($result == "Successful"){
                $result = $Model->StartNewAcademicYear($data);

                if ($result == "New academic year started"){
                    $start = ""; $end = "";
                }
            }
            
        }


    }


?>
<br>
<p style="color:red;"><?= $result ?></p>
<br>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
    <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]['AcademicYear'] ?></label>
        </p>
       <div class="curved-box">
        <form action="" method="post">
        <p>
            <label><?= $lang[$_SESSION['lang']]['Start'] ?></label>
            <input type="number" class="form-control" name="start" required="required" value="<?= $start ?>">
            <br>
            <label><?= $lang[$_SESSION['lang']]['End'] ?></label>
            <input type="number" class="form-control" name="end" required="required" value="<?= $end ?>">
        </p>
        <br>
        <p>
            <label class="sec">Sec</label>
            <input type="text" class="form-control sec" name="sec" value="">
        </p> 
        <button type="submit" class="btn btn-primary"><?= $lang[$_SESSION['lang']]['Save'] ?></button>
        </form>
       </div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    </div>