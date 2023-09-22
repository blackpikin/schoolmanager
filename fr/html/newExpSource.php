<?php 
    $reason = ""; 
    $result = ""; $err = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $reason = $Model->test_input($_POST['reason']);


        if (empty($reason)){
            $err = true;
            $result = 'Please enter the source';
        }

        if(!$err){
            $result = $Model->NewExpSource($reason, $_SESSION['id']);
        }

        if ($result == "Successful"){
            $reason = "";
        }
    }

?>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1">Add expense source</label>
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
            <label>Source</label>
            <input type="text" value="<?= $reason ?>" class="form-control" name="reason" required >
        </p>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
    </div>
    <div class="col-xs-2">

    </div>
</div>