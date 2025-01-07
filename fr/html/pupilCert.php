<?php 
 $student_code = $_GET['ref'];
?>
<div>
<br>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8 ">
        <label id="label1"><?= $lang[$_SESSION['lang']]["StudentCert"] ?></label>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
</div>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8 curved-box">       
        <button class="btn btn-primary button-width" onclick="window.open('./pdf/primaryAttendanceCert.php?ref=<?= $student_code ?>')"><?= $lang[$_SESSION['lang']]["AttendanceCert"] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="window.open('./pdf/primaryTransferCert.php?ref=<?= $student_code ?>')"><?= $lang[$_SESSION['lang']]["TransferCert"] ?></button>
        <br>
        <br>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
</div>
</div>