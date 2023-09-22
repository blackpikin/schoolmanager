<?php
    $lng = $_SESSION['lang'];
    $section = 0;
    if($lng == 'fr'){
        $section = 1;
    }
    
    $staff_id = $_GET['ref'];
    $academic_year = $Model->GetCurrentYear()[0]['id']; 
    $staff_subjects = $Model->GetStaffSubjects($staff_id, $academic_year);
?>
<br>
<div class="row">
    <div class="col-xs-3">

    </div>
    <div class="col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["FillMarksFor"] ?> <?= $Model->GetStaffName($staff_id) ?></label>
        </p>
    </div>
    <div class="col-xs-1">

    </div>
</div>
<div class="row">
    <div class="col-xs-11">
    <div class="row">
        <div class="col-xs-5">
        <div class="curved-box">
       <h4><?= $lang[$_SESSION['lang']]["ExistingSubjects"] ?></h4>
                <table class="table table-bordered table-responsive">
                    <tr>
                        <td><?= $lang[$_SESSION['lang']]["Subject"] ?>s</td>
                        <td><?= $lang[$_SESSION['lang']]["Class"] ?></td>
                        <td><?= $lang[$_SESSION['lang']]["Exam"] ?></td>
                        <td>Actions</td>
                    </tr>
                    <?php 
                    if(!empty($staff_subjects)){
                        foreach($staff_subjects as $subject){
                            $classes = explode(",", $subject['class_id']);
                            if(isset($Model->GetCurrentExam()[0]['sequence'])){
                                $sequence = strToUpper($Model->GetCurrentExam()[0]['sequence']);
                            }else{
                                $sequence = '';
                            }

                            if($sequence !== ''){
                                foreach($classes as $class){
                                    if($sequence == 'ONE' || $sequence == 'TWO'){
                                       ?>
                                        <tr class="normal-tr">
                                        <td><?= $subject['subject'] ?></td>
                                        <td><?php
                                            $cl = $Model->GetAClass($class); 
                                            echo $cl[0]['general_name'].' '.$cl[0]['sub_name'];
                                        ?></td>
                                        <td>
                                            <?php 
                                                $term = strToUpper($Model->GetCurrentExam()[0]['term']);
                                                $sequence = strToUpper($Model->GetCurrentExam()[0]['sequence']);
                                                echo $lang[$_SESSION['lang']][strToUpper($term)].' '.$lang[$_SESSION['lang']]['TERM'].' SEQUENCE '.$lang[$_SESSION['lang']][$sequence];
                                            ?>
                                        </td>
                                        <td><button onclick="ShowClassList('<?= $cl[0]['id'] ?>','<?= $subject['subject'] ?>')" title="<?= $lang[$_SESSION['lang']]["FillMarks"] ?>" class="btn btn-success glyphicon glyphicon-check"></button></td>
                                    </tr>
                                       <?php
                                    }else{
                                        $cl = $Model->GetAClass($class);
                                        if($cl[0]['mockable'] == '1'){
                                        ?>
                                        <tr class="normal-tr">
                                        <td><?= $subject['subject'] ?></td>
                                        <td><?php
                                            $cl = $Model->GetAClass($class); 
                                            echo $cl[0]['general_name'].' '.$cl[0]['sub_name'];
                                        ?></td>
                                        <td>
                                            <?php 
                                                $term = strToUpper($Model->GetCurrentExam()[0]['term']);
                                                $sequence = strToUpper($Model->GetCurrentExam()[0]['sequence']);
                                                echo $lang[$_SESSION['lang']][strToUpper($term)].' '.strtoupper($lang[$_SESSION['lang']]['TERM']).' '.$lang[$_SESSION['lang']][$sequence];
                                            ?>
                                        </td>
                                        <td><button onclick="ShowClassList('<?= $cl[0]['id'] ?>','<?= $subject['subject'] ?>')" title="<?= $lang[$_SESSION['lang']]["FillMarks"] ?>" class="btn btn-success glyphicon glyphicon-check"></button></td>
                                    </tr>
                                       <?php
                                        }
                                    }
                                                                   
                                }
                            }else{
                                //pass
                            }
                        }
                    }
                    ?>
                </table>    
       </div>
        </div>
        <div class="col-xs-7">
            <div class="curved-box">
                <label id="result"></label>
                <div id="c_list">
                    <span id="loading" style="font-style:italic;display:none;"><?= $lang[$_SESSION['lang']]["Loading"] ?></span>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
<br>
<br>
