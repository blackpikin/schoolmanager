<?php 
 $student_code = $_GET['ref'];
?>
<div>
<br>
<div class="row">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-8 ">
        <label id="label1"><?= $lang[$_SESSION['lang']]["StudentCert"] ?></label>
    </div>
    <div class="col-xs-2">

    </div>
</div>
<div class="row">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-8 curved-box">       
        <button class="btn btn-primary button-width" onclick="window.open('./pdf/attendanceCert.php?ref=<?= $student_code ?>')"><?= $lang[$_SESSION['lang']]["AttendanceCert"] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="window.open('./pdf/transferCert.php?ref=<?= $student_code ?>')"><?= $lang[$_SESSION['lang']]["TransferCert"] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('studentsCards&ref=<?= $student_code ?>')"><?= $lang[$_SESSION['lang']]["Report cards"] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="window.open('./pdf/studentsTranscript2.php?ref=<?= $student_code ?>&type=FIRST')"><?= $lang[$_SESSION['lang']]["Transcript1"] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="window.open('./pdf/studentsTranscript2.php?ref=<?= $student_code ?>&type=SECOND')"><?= $lang[$_SESSION['lang']]["Transcript2"] ?></button>
    </div>
    <div class="col-xs-2">

    </div>
</div>
</div>