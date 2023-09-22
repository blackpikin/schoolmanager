<?php 
    $r = $Model->GetDiscountReason($_GET['ref']);
    $reason = $r['reason']; $percent = $r['percent'];
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
            $result = $Model->UpdateReason($reason, $percent, $_SESSION['id'], $_GET['ref']);
        }

        if ($result == "Successful"){
            $r = $Model->GetDiscountReason($_GET['ref']);
            $reason = $r['reason']; $percent = $r['percent'];
        }
    }

?>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1">Add discount reasons</label>
        </p>
    </div>
    <div class="col-xs-4">

    </div>
</div>
<p style="color:red; font-weight:bold;"><?= $result ?></p>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-8">
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
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
    </div>
    <div class="col-xs-2">

    </div>
</div>