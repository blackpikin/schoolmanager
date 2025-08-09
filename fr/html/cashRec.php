<?php
    $amount = 0; $source = 'Select the source of the revenue'; $err = false; $result = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $amount = $_POST['amount'];
        $source = $_POST['source'];

        if(empty($amount) || empty($source)){
            $err = true;
            $result = "Please Enter the amount and the source";
        }

        if (!$err){
            $result = $Model->NewCash($amount, $source, $_SESSION['id'], date('Y-m-d'), date('m-Y'), date('Y'));

            if($result == 'Successful'){
                //Pdf reciept in two copies
                $amount = 0; $source = 'Select the source of the revenue';
            }
        }
    }

?>
<div class="row" style="margin-top: 10px;">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <p>
            <label id="label1">Receive cash</label>
        </p>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
</div>
<p style="color:red; font-weight:bold;"><?= $result ?></p>
<div class="row" id="recSub">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <form action="" method="post">
        <div class="curved-box">
        <h4><u>Other cash sources</u></h4>
        <label>Enter amount</label>
        <input type="number" placeholder="amount in francs CFA" required="required" class="form-control fees_input" name="amount" value="<?= $amount ?>">
        <br>
        <label>Source</label>
        <select name="source" class="form-control fees_input">
        <option value=""><?= $source ?></option>
        <?php
            $sources = $Model->GetRevenueSources();
            if(!empty($sources)){
                foreach($sources as $source){
                    ?>
                    <option value="<?= $source['id'] ?>"><?= $source['source'] ?></option>
                    <?php
                }
            }
        ?>
        </select>
        <br>
        <button type="submit" class="btn btn-danger">Receive</button>
        </form>
</div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
</div>

