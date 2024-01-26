<div class="row" style="margin-top: 10px;">
    <div class="col-xs-3">

    </div>
    <div class="col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]['Print Marksheet']?></label>
        </p>
    </div>
    <div class="col-xs-1">

    </div>
</div>
<div class="row">
    <div class="col-xs-12">
       <div class="row curved-box">
       <h4><?= $lang[$_SESSION['lang']]['Marksheet']?></h4>
       <div class="col-xs-3">
            <label><?= $lang[$_SESSION['lang']]['AcademicYear']?></label>
            <br>
        <select id="year" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]['Choose one']?></option>
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
       <div class="col-xs-3">
       <label><?= $lang[$_SESSION['lang']]['Select the class']?></label>
        <select id="c_name" class="form-control" onchange="SetClassId(this)">
            <option value=""><?= $lang[$_SESSION['lang']]['Choose one']?></option>
            <?php 
            $lng = $_SESSION['lang'];
            $section = 0;
            if($lng == 'fr'){
                $section = 1;
            }
            $classes = $Model->GetAllClasses($section);
            if(!empty($classes)){
                foreach($classes as $class){
                    ?>
                    <option value="<?= $class['id'] ?>">
                        <?php 
                             if($class['general_name'] != $class['sub_name']){
                                echo $class['general_name'].' '.$class['sub_name'];
                             }else{
                                 echo $class['general_name'];
                             }
                        ?>
                    </option>
                    <?php
                }
            }
            ?>

        </select>
       </div>
       <div class="col-xs-3">
       <label><?= $lang[$_SESSION['lang']]['Select the subject']?></label>
        <select id="subjects" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]['Choose one']?></option>
        </select>
       </div>
       <div class="col-xs-3">
           <br>
            <button class="btn btn-primary button-width" onclick="MarksheetPrint()"><i class="glyphicon glyphicon-print"></i> <?= $lang[$_SESSION['lang']]['Print']?></button>
            <br>
            <br>
            <button class="btn btn-primary button-width" onclick="MarksheetPremock()"><i class="glyphicon glyphicon-print"></i> <?= $lang[$_SESSION['lang']]['Pre-mock/Mock']?></button>
            <br>
        </div>
       </div>
    </div>
    
</div>