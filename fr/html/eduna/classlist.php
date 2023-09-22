<?php 
include "includes/EdunaModel.php";
$Eduna = new EdunaModel();
$academic_years = $Eduna->Get('setup_academicyears');
?>

<div class="row" style="margin-top: 10px;">
    <div class="col-xs-3">

    </div>
    <div class="col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]['Class lists'] ?><label>
        </p>
    </div>
    <div class="col-xs-1">

    </div>
</div>
<div class="row">
    <div class="col-xs-4">
        <h4><?= $lang[$_SESSION['lang']]['Academic year'] ?></h4>
        <select class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
            <?php 
                foreach ($academic_years as $year) {
                    ?>
                         <option value="<?= $year['id'] ?>"><?= $year['short_name'] ?></option>
                    <?php
                }
            ?>
        </select>
    </div>
    <div class="col-xs-4">
        
    </div>
    <div class="col-xs-4">

    </div>
</div>
