<?php 
    $amount = 0; $receiver = ''; $reason = ''; $source = 'Select the head of the expense';
    $err = false; $result = '';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $amount = $_POST['amount'];
        $receiver = $Model->test_input($_POST['receiver']);
        $reason = $Model->test_input($_POST['reason']);
        $head = $Model->test_input($_POST['head']);

        if(empty($amount) || empty($receiver) || empty($reason)){
            $err = true;
            $result = 'Please fill in the amount, receiver and reason';
        }

        if (empty($head)){
            $err = true;
            $result = 'Please select the expense head';
        }

        if(!$err){
            $result = $Model->NewPayout($amount, $receiver, $head, $reason, $_SESSION['id'], date('Y-m-d'), date('m-Y'), date('Y'));
        }

        if($result == 'Successful'){
            //pdf receipt in two copies
            $amount = 0; $receiver = ''; $reason = '';
        }
    }

?>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1">Cash Payout</label>
        </p>
    </div>
    <div class="col-xs-4">

    </div>
</div>
<p style="color:red; font-weight:bold;"><?= $result ?></p>
<div class="row">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-8">
        <div class="curved-box">
            <form action="" method="post">
        <label>Enter amount</label>
        <input type="number" placeholder="amount in francs CFA" required="required" class="form-control fees_input" name="amount" value="<?= $amount ?>">
        <br>
        <label>Receiver</label>
        <input type="text"  required="required" class="form-control fees_input" name="receiver" value="<?= $receiver ?>">
        <br>
        <label>Cash out under</label>
        <select name="head" class="form-control fees_input">
        <option value=""><?= $source ?></option>
        <?php
            $sources = $Model->GetExpenseSources();
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
        <label>Reason</label>
        <input type="text"  required="required" class="form-control fees_input" name="reason" value="<?= $reason ?>">
        <br>
        <button type="submit" class="btn btn-danger">Pay out</button>
    </form>
</div>
    </div>
    <div class="col-xs-2">

    </div>
</div>
