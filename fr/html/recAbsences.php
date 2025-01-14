    <div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8 ">
        <label id="label1"><?= $lang[$_SESSION['lang']]["Students' Conduct"] ?></label>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
       <div class="row curved-box">
       <div class="col-md-3 col-sm-3 col-xs-3">
            <label><?= $lang[$_SESSION['lang']]['Academic year'] ?></label>
            <br>
        <select id="year" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
            <?php 
            $years = $Model->GetCurrentYear();
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
       <div class="col-md-3 col-sm-3 col-xs-3">
       <label><?= $lang[$_SESSION['lang']]['Select the class'] ?></label>
        <select id="c_name" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
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
       <div class="col-md-3 col-sm-3 col-xs-3">
       <?php 
        if(isset($Model->GetCurrentYear()[0]['id'])){
            $exams = $Model->GetAllTerms($Model->GetCurrentYear()[0]['id']);
        }else{
            $exams = [];
        }   
        ?>
           <label value=""><?= $lang[$_SESSION['lang']]['Select the term'] ?></label>
           <select id="term_term" class="form-control">
           <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
           <?php 
                if (!empty($exams)){
                    foreach ($exams as $exam){
                        ?>
                        <option value="<?= $exam['term'] ?>">
                            <?= $lang[$_SESSION['lang']][strToUpper($exam['term'])].' '.$lang[$_SESSION['lang']]['TERM'] ?>
                        </option>
                        <?php
                    }
                }
           ?>
           </select>
       </div>
       <div class="col-md-3 col-sm-3 col-xs-3">
           <br>
            <button class="btn btn-primary button-width" onclick="AbsencesList()"><?= $lang[$_SESSION['lang']]['Show list'] ?></button>
       </div>
       </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 ">
    <div class="row curved-box">
        <div>
        <div class="col-md-3 col-sm-3 col-xs-3">
           <br>
            <input id="abs" type="radio" value="absent" name="conduct"> <?= $lang[$_SESSION['lang']]['UnJustAbsence'] ?>
       </div>
       <div class="col-md-2 col-sm-2 col-xs-2">
           <br>
            <input id="justabs" type="radio" value="justabsent" name="conduct"> <?= $lang[$_SESSION['lang']]['JustAbsence'] ?>
       </div>
       <div class="col-md-2 col-sm-2 col-xs-2">
           <br>
            <input id="pun" type="radio" value="punishment" name="conduct"> <?= $lang[$_SESSION['lang']]['Latenessb'] ?>
       </div>
        <div class="col-md-2 col-sm-2 col-xs-2">
        <br>
            <input id="war" type="radio" value="warning" name="conduct"> <?= $lang[$_SESSION['lang']]['Warning'] ?>
       </div>
       <div class="col-md-3 col-sm-3 col-xs-3">
           <br>
            <input id="sus" type="radio" value="suspension" name="conduct"> Suspension <?= $lang[$_SESSION['lang']]['in days'] ?>
       </div>
        </div>
    </div>
    </div>
</div>
<div class="row">
    <div class="col-md-11 col-sm-11 col-xs-11">
        <div id="class_list">
            <span><i><?= $lang[$_SESSION['lang']]['No class selected'] ?></i></span>
        </div>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
