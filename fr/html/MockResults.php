<?php 
/*
- Get the students in the class
-For each student in the class
  ..Get his marks from the mark_sheet
  ..calculate the subjects he passed
  ..Calculate the grades per subject
  ..Display the slip
*/

$lng = $_SESSION['lang'];
$section = 0;
if($lng == 'fr'){
    $section = 1;
}

$year_id = $_GET['year_id'];
$class_id = $_GET['class_id'];
$exam_name = $_GET['exam_id'];
$url = 'year_id='.$year_id.'&class_id='.$class_id.'&exam_name='.$exam_name;
$limits = $Model->Grade();
?>
<br>
<h4 id="label1" style="text-align:center;"><?= $exam_name ?>  RESULTS - <?= $Model->GetAClassName($class_id) ?> - <?= $Model->GetYearName($year_id) ?></h4>
<br>
<div class="row">
        <div class="col-md-3 col-sm-3 col-xs-3">

        </div>
        <div class="col-md-3 col-sm-3 col-xs-3">
            <p><a target="blank" href="./pdf/MockResultPdf.php?<?= $url ?>" title="Save as PDF" class="btn btn-primary"><i class="fa fa-download"></i> Save the Slips as PDF</a></p>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-3">

        </div>
</div>
<?php

$class_cycle = $Model->GetAClass($class_id)[0]['cycle'];
$exam_id = $Model->GetMockExam($year_id, $exam_name)[0]['id'];
$student_codes = $Model->GetStudentsInClass($class_id, $year_id);
foreach($student_codes as $student){
    $marks = $Model-> GetStudentsMarks($year_id, $class_id, $exam_id, $student['student_code']);
    $sat = count($marks);
    $passed = 0;
    foreach($marks as $mark){
        if($class_cycle == "FIRST"){
            if($mark['mark'] >= $limits['OL']['OLCmin']){
                $passed++;
            }
        }else{
            if($mark['mark'] >= $limits['AL']['ALEmin']){
                $passed++;
            }
        }
    }

    ?>
        <table class="table table-bordered table-responsive">
            <tr class="table-header">
                <th>Name: <?= $Model->GetStudent($student['student_code'], $section)[0]['name']; ?></th>
                <th>Date of Birth: <?= $Model->GetStudent($student['student_code'], $section)[0]['dob']; ?></th>
                <th>Gender: <?= $Model->GetStudent($student['student_code'], $section)[0]['gender']; ?></th>
                <th>Class: <?= $Model->GetAClassName($class_id) ?></th>
            </tr>
            <tr class="table-header">
                <td>Registered: <?= $sat; ?></td>
                <td>Sat: <?= $sat ?></td>
                <td>Passed: <?= $passed ?></td>
                <td> </td>
            </tr>
            <tr class="grey-tr">
                <td><b>Subject</b></td>
                <td><b>Grade</b></td>
                <td><b>Remark</b></td>
                <td> </td>
            </tr>
    <?php
       
    foreach($marks as $mark){
        $remark = ""; $decision = "";
        if($class_cycle == "FIRST"){
            if($mark['mark'] <= $limits['OL']['OLUmax']){
                $remark = "U";
                $decision = "FAILED";
            }elseif($mark['mark'] >= $limits['OL']['OLEmin'] && $mark['mark'] <= $limits['OL']['OLEmax']){
                $remark = "E";
                $decision = "FAILED";
            }elseif($mark['mark'] >= $limits['OL']['OLDmin'] && $mark['mark'] <= $limits['OL']['OLDmax']){
                $remark = "D";
                $decision = "FAILED";
            }elseif($mark['mark'] >= $limits['OL']['OLCmin'] && $mark['mark'] <= $limits['OL']['OLCmax']){
                $remark = "C";
                $decision = "PASSED";
            }elseif($mark['mark'] >= $limits['OL']['OLBmin'] && $mark['mark'] <= $limits['OL']['OLBmax']){
                $remark = "B";
                $decision = "PASSED";
            }elseif($mark['mark'] >= $limits['OL']['OLAmin']){
                $remark = "A";
                $decision = "PASSED";
            }
        }else{
            if($mark['mark'] <= $limits['AL']['ALFmax']){
                $remark = "F";
                $decision = "FAILED";
            }elseif($mark['mark'] >= $limits['AL']['ALOmin'] && $mark['mark'] <= $limits['AL']['ALOmax']){
                $remark = "O";
                $decision = "FAILED";
            }elseif($mark['mark'] >= $limits['AL']['ALEmin'] && $mark['mark'] <= $limits['AL']['ALEmax']){
                $remark = "E";
                $decision = "PASSED";
            }elseif($mark['mark'] >= $limits['AL']['ALDmin'] && $mark['mark'] <= $limits['AL']['ALDmax']){
                $remark = "D";
                $decision = "PASSED";
            }elseif($mark['mark'] >= $limits['AL']['ALCmin'] && $mark['mark'] <= $limits['AL']['ALCmax']){
                $remark = "C";
                $decision = "PASSED";
            }elseif($mark['mark'] >= $limits['AL']['ALBmin'] && $mark['mark'] <= $limits['AL']['ALBmax']){
                $remark = "B";
                $decision = "PASSED";
            }elseif($mark['mark'] >= $limits['AL']['ALAmin']){
                $remark = "A";
                $decision = "PASSED";
            }
        }
        ?>
            <tr class="normal-tr">
                <td><?= $mark['subject']; ?></td>
                <td><?= $remark ?></td>
                <td><?= $decision ?></td>
                <td></td>
            </tr>
        
        <?php
    }
    ?>
    </table>
    <br><br>
    <?php
}