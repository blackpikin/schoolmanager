<?php 

?>
<div class="row" style="margin-top: 10px;">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["Gender statistics"] ?></label>
        </p>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-6 col-sm-6 col-xs-6">
        <label><?= $lang[$_SESSION['lang']]["Academic year"] ?></label>
        <select id="year" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]["Choose one"] ?></option>
            <?php 
            $years = $Model->GetAcademicYears();
            if(!empty($years)){
                foreach($years as $year){
                    ?>
                    <option value="<?= $year['id'] ?>">
                        <?php 
                            echo $year['start'].'/'.$year['end'];
                        ?>
                    </option>
                    <?php
                }
            }
            ?>

        </select>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <br>
        <button class="btn btn-primary" onclick="GenderStats()"><?= $lang[$_SESSION['lang']]["View"] ?></button>
    </div>
</div>
<div class="row">
    <div class="col-md-11 col-sm-11 col-xs-11">
        <div id="class_list">
            <span><i><?= $lang[$_SESSION['lang']]["NoYearSelected"] ?></i></span>
        </div>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>
<br>
<br>
<br>
<br>