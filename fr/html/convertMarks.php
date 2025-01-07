<div class="row">
    <?php 
        if(isset($Model->GetCurrentYear()['0']['id'])){
            ?>
            <h4 id="label1" style="text-align:center;"><?= $lang[$_SESSION['lang']]['ConvertMarks'] ?> - <?= $lang[$_SESSION['lang']]['Academic year'].' '.$Model->YearNameDigits($Model->GetCurrentYear()['0']['id']); ?></h4>
        <?php
        }else{
            ?>
            <h4 id="label1" style="text-align:center;"><?= $lang[$_SESSION['lang']]['ConvertMarks'] ?></h4>
            <?php
        }
    ?>
    
    <div class="col-md-12 col-sm-12 col-xs-12">
        <label>Copy from</label>
       <div class="row curved-box">
       <div class="col-xs-4">
       <?php 
            $lng = $_SESSION['lang'];
            $section = 0;
            if($lng == 'fr'){
                $section = 1;
            }
                 $exams = $Model->GetAllExams($Model->GetCurrentYear()[0]['id']);
           ?>
           <label value=""><?= $lang[$_SESSION['lang']]['Select examination'] ?></label>
           <select id="exam" class="form-control">
           <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
           <?php 
                if (!empty($exams)){
                    
                    foreach ($exams as $exam){
                        ?>
                        <option value="<?= $exam['id'] ?>">
                            <?php
                                if($exam['sequence'] == "ONE" || $exam['sequence'] == "TWO"){
                                    echo strToUpper($lang[$_SESSION['lang']][strToUpper($exam['term'])].' '.strToUpper($lang[$_SESSION['lang']]['TERM']).'  SEQUENCE '.$lang[$_SESSION['lang']][$exam['sequence']]);
                                }else{
                                    echo strToUpper($exam['term'].' '.strToUpper($lang[$_SESSION['lang']]['TERM']).' '.$exam['sequence']);
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
       <label><?= $lang[$_SESSION['lang']]['Select the class'] ?></label>
        <select id="c_name" class="form-control" onchange="SetClassId(this)">
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
       <div class="col-xs-3">
       <label><?= $lang[$_SESSION['lang']]['Select the subject'] ?></label>
       <select id="subjects" class="form-control">
       <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
       </select>
       </div>
       <div class="col-xs-2">
           <br>
            <button class="btn btn-primary" onclick="ShowBlankExam()"><?= $lang[$_SESSION['lang']]['View'] ?></button>
        </div>
       </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <label>To</label>
       <div class="row curved-box">
       <div class="col-xs-4">
       <?php 
            $lng = $_SESSION['lang'];
            $section = 0;
            if($lng == 'fr'){
                $section = 1;
            }
                 $exams = $Model->GetAllExams($Model->GetCurrentYear()[0]['id']);
           ?>
           <label value=""><?= $lang[$_SESSION['lang']]['Select examination'] ?></label>
           <select id="exam2" class="form-control">
           <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
           <?php 
                if (!empty($exams)){
                    
                    foreach ($exams as $exam){
                        ?>
                        <option value="<?= $exam['id'] ?>">
                            <?php
                                if($exam['sequence'] == "ONE" || $exam['sequence'] == "TWO"){
                                    echo strToUpper($lang[$_SESSION['lang']][strToUpper($exam['term'])].' '.strToUpper($lang[$_SESSION['lang']]['TERM']).'  SEQUENCE '.$lang[$_SESSION['lang']][$exam['sequence']]);
                                }else{
                                    echo strToUpper($exam['term'].' '.strToUpper($lang[$_SESSION['lang']]['TERM']).' '.$exam['sequence']);
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
       <label><?= $lang[$_SESSION['lang']]['Select the class'] ?></label>
        <select id="c_name2" class="form-control" onchange="SetClassId(this, 2)">
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
       <div class="col-xs-3">
       <label><?= $lang[$_SESSION['lang']]['Select the subject'] ?></label>
       <select id="subjects2" class="form-control">
       <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
       </select>
       </div>
       <div class="col-xs-2">
           <br>
            <button class="btn btn-primary" onclick="ShowClosedExamList('copy')"><?= $lang[$_SESSION['lang']]['View'] ?></button>
            <button class="btn btn-danger" onclick="CopyMarks()"><?= $lang[$_SESSION['lang']]['copy'] ?></button>
        </div>
       </div>
    </div>
</div>
<div  class="row curved-box">
<div id="closed_exam_list" class="col-md-5 col-sm-5 col-xs-5">
    
</div>
<div class="col-md-2 col-sm-2 col-xs-2">
    
</div>
<div id="closed_exam_list_copy" class="col-md-5 col-sm-5 col-xs-5">
    
</div>
</div>