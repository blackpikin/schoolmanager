<?php
    $srch =""; $results = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $srch = $Model->test_input($_POST['srch']);
        if (!empty($srch)){
            $student = $Model->GetStudent($srch, $section);
            if (!empty($student)){
                $code = $student[0]['student_code'];
                echo '<script>window.open("./pdf/profilePdf.php?ref='.$code.'")</script>';
            }else{

            }
        }

    }
?>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["View your child's performance"] ?></label>
        </p>
    </div>
    <div class="col-xs-2">

    </div>
</div>
<div class="row">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-8">
        <form action="" method="post">
        <div class="row">
        <div class="col-xs-8">
        <label><?= $lang[$_SESSION['lang']]["Enter the student's code"] ?>:</label>
        <input name="srch" style="height:55px;" type="text" value="<?= $srch ?>" placeholder="<?= $lang[$_SESSION['lang']]["Code provided by the school"] ?>" class="form-control" >
        </div>
        <div class="col-xs-4">
            <br>
            <button type="submit" style="height:55px;width:55px;" class="btn btn-warning glyphicon glyphicon-search"></button>
        </div>
        </div>
        </form>
        <br>
        <button class="btn btn-primary" onclick="GotoPage('login')"><?= $lang[$_SESSION['lang']]["back"] ?></button>
    </div>
    <div class="col-xs-2">

    </div>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
