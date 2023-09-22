<div class="row" style="margin-top: 10px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["Sequence statistics"] ?></label>
        </p>
    </div>
    <div class="col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-xs-12">
       <div class="row curved-box">
       <h4><?= $lang[$_SESSION['lang']]["SelectCriteria"] ?></h4>
       <div class="col-xs-2">
            <label><?= $lang[$_SESSION['lang']]["AcademicYear"] ?></label>
            <br>
        <select id="year" class="form-control" onchange="LoadSequences('exam')">
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
       <div class="col-xs-2">
       <label><?= $lang[$_SESSION['lang']]["Select the subject"] ?></label>
        <select id="c_name" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]["Choose one"] ?></option>
            <?php 
            $classes = $Model->GetAllSubjects();
            if(!empty($classes)){
                foreach($classes as $class){
                    ?>
                    <option value="<?= $class['subject'] ?>">
                        <?php 
                             echo $class['subject'];
                        ?>
                    </option>
                    <?php
                }
            }
            ?>

        </select>
       </div>
       <div class="col-xs-3">
           <label value=""><?= $lang[$_SESSION['lang']]["Select examination"] ?></label>
           <select id="exam" class="form-control">
           <option value=""><?= $lang[$_SESSION['lang']]["Choose one"] ?></option>
           </select>
       </div>
       <div class="col-xs-2">
           <br>
            <button class="btn btn-primary button-width" onclick="SequenceStatPDF()"><?= $lang[$_SESSION['lang']]["Generate"] ?></button>
        </div>
        <div class="col-xs-2">
           <br>            
            <button class="btn btn-primary button-width" onclick="SequenceStatAnnualPDF()"><?= $lang[$_SESSION['lang']]["GenerateAnnual"] ?></button>
        </div>
       </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-xs-11">
        <div id="class_list">
            <span><i></i></span>
        </div>
    </div>
    <div class="col-xs-1">

    </div>
</div>