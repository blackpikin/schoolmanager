<?php
$staff_id = $_SESSION['id'];
$academic_year = $Model->GetCurrentYear()[0]['id']; 
$staff_subjects = $Model->GetStaffSubjects($staff_id, $academic_year);
?>
<br>
<div class="row">
    <div class="col-md-3 col-sm-3 col-xs-3">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <p>
            <label id="label1">Register Marks For <?= $Model->GetStaffName($staff_id) ?></label>
        </p>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>
<div class="row">
    <div class="col-md-11 col-sm-11 col-xs-11">
    <div class="row">
        <div class="col-md-5 col-sm-5 col-xs-5">
        <div class="curved-box">
       <h4>Existing subjects</h4>
                <table class="table table-bordered table-responsive">
                    <tr>
                        <td>Subject</td>
                        <td>Class</td>
                        <td>Exam</td>
                        <td>Actions</td>
                    </tr>
                    <?php 
                   
                    if(!empty($staff_subjects)){
                        foreach($staff_subjects as $subject){
                            $classes = explode(",", $subject['class_id']);
                            $sequence = strToUpper($Model->GetCurrentExam()[0]['sequence']);
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
                                            echo $term.' TERM'.' SEQUENCE '.$sequence;
                                        ?>
                                    </td>
                                    <td><button onclick="ShowFillMarksList('<?= $cl[0]['id'] ?>','<?= $subject['subject'] ?>')" title="Fill marks for this subject" class="btn btn-success fa fa-check"></button></td>
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
                                            echo $term.' TERM'.' '.$sequence;
                                        ?>
                                    </td>
                                    <td><button onclick="ShowFillMarksList('<?= $cl[0]['id'] ?>','<?= $subject['subject'] ?>')" title="Fill marks for this subject" class="btn btn-success fa fa-check"></button></td>
                                </tr>
                                   <?php
                                    }
                                }
                                                               
                            }
                        }
                    }
                    ?>
                </table>    
       </div>
        </div>
        <div class="col-md-7 col-sm-7 col-xs-7">
            <div class="curved-box">
                <label id="result"></label>
                <div id="c_list">

                </div>
            </div>
        </div>
    </div>
    </div>
</div>
<br>
<br>