<div class="row" style="margin-top: 10px;">
    <div class="col-md-3 col-sm-3 col-xs-3">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]['GenerateReportCards'] ?></label>
        </p>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
       <div class="row curved-box">
       <h4><?= $lang[$_SESSION['lang']]['SequenceReps'] ?></h4>
       <div class="col-md-3 col-sm-3 col-xs-3">
            <label><?= $lang[$_SESSION['lang']]['AcademicYear'] ?></label>
            <br>
        <select id="year" class="form-control" onchange="LoadSequences('exam')">
            <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
            <?php 
            $lng = $_SESSION['lang'];
            $section = 0;
            if($lng == 'fr'){
                $section = 1;
            }
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
       <div class="col-md-3 col-sm-3 col-xs-3">
       <label><?= $lang[$_SESSION['lang']]['Select the class'] ?></label>
        <select id="c_name" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
            <?php 
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
           <label value=""><?= $lang[$_SESSION['lang']]['Select examination'] ?></label>
           <select id="exam" class="form-control">
           <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
           </select>
       </div>
       <div class="col-md-3 col-sm-3 col-xs-3">
           <br>
            <button class="btn btn-primary button-width" onclick="SequenceReport()"><?= $lang[$_SESSION['lang']]['SequenceReps'] ?></button>
            <br>
            <br>
            <button class="btn btn-primary button-width" onclick="SequenceSummary()"><?= $lang[$_SESSION['lang']]['SequenceSumm'] ?></button>

        </div>
       </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
       <div class="row curved-box">
       <h4><?= $lang[$_SESSION['lang']]['TermReps'] ?></h4>
       <div class="col-md-3 col-sm-3 col-xs-3">
            <label><?= $lang[$_SESSION['lang']]['AcademicYear'] ?></label>
            <br>
        <select id="term_year" class="form-control" onchange="LoadTerms('term_term')">
            <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
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
       <div class="col-md-3 col-sm-3 col-xs-3">
       <label><?= $lang[$_SESSION['lang']]['Select the class'] ?></label>
        <select id="term_class" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
            <?php 
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
                            <?= $lang[$_SESSION['lang']][strToUpper($exam['term'])].$lang[$_SESSION['lang']]['TERM'] ?>
                        </option>
                        <?php
                    }
                }
           ?>
           </select>
       </div>
       <div class="col-md-3 col-sm-3 col-xs-3">
           <br>
            <button class="btn btn-primary button-width" onclick="TermReport()"><?= $lang[$_SESSION['lang']]['TermReps'] ?></button>
            <br><br>
            <button class="btn btn-primary button-width" onclick="TermSummary()"><?= $lang[$_SESSION['lang']]['TermSumm'] ?></button>
            <br><br>
            <button class="btn btn-primary button-width" onclick="AnnualSummary()"><?= $lang[$_SESSION['lang']]['AnnSumm'] ?></button>

       </div>
       </div>
    </div>
</div>
<br>
<br>