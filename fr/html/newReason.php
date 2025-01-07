<?php 
    $reason = ""; $percent = 0;
    $result = ""; $err = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $reason = $Model->test_input($_POST['reason']);
        $percent = $_POST['percent'];


        if (empty($reason)){
            $err = true;
            $result = 'Please enter the reason';
        }

        if (empty($percent)){
            $err = true;
            $result = 'Please enter the percentage discount';
        }

        if(!$err){
            $result = $Model->NewReason($reason, $percent, $_SESSION['id']);
        }

        if ($result == "Successful"){
            $reason = ""; $percent = 0;
        }
    }

?>
<div class="row" style="margin-top: 10px;">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <p>
            <label id="label1">Add discount reasons</label>
        </p>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
</div>
<p style="color:red; font-weight:bold;"><?= $result ?></p>
<div class="row" style="margin-top: 10px;">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <div class="curved-box">
    <form action="" method="post">
        <p>
            <label>Reason</label>
            <input type="text" value="<?= $reason ?>" class="form-control" name="reason" required >
        </p>
        <p>
            <label>Percentage</label>
            <input type="number" value="<?= $percent ?>" class="form-control" name="percent" required >
        </p>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
</div>