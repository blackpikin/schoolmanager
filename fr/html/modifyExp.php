<?php 
    $r = $Model->ExpenseSource($_GET['ref']);
    $reason = $r['source']; 
    $result = ""; $err = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $reason = $Model->test_input($_POST['reason']);

        if (empty($reason)){
            $err = true;
            $result = 'Please enter the reason';
        }

        if(!$err){
            $result = $Model->UpdateExpenseSource($reason, $_SESSION['id'], $_GET['ref']);
        }

        if ($result == "Successful"){
            $r = $Model->ExpenseSource($_GET['ref']);
            $reason = $r['source']; 
        }
    }

?>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1">Modify expense source</label>
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
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
    </div>
    <div class="col-xs-2">

    </div>
</div>