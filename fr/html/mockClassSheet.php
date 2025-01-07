<?php
$lng = $_SESSION['lang'];
$section = 0;
if($lng == 'fr'){
    $section = 1;
}

$year_id = $_GET['year_id'];
$class_id = $_GET['class_id'];
$exam_name = $_GET['exam_id'];
$exam_id = $Model->GetMockExam($year_id, $exam_name)[0]['id'];
$url = 'year_id='.$year_id.'&class_id='.$class_id.'&exam_name='.$exam_name;
?>
<br>
<h5 id="label1" style="text-align:center;"><?= $exam_name ?>  MASTER SHEET - <?= $Model->GetAClassName($class_id) ?> - <?= $Model->GetYearName($year_id) ?></h5>
<br>
<div class="row">
        <div class="col-md-1 col-sm-1 col-xs-1">

        </div>
        <div class="col-md-8 col-sm-8 col-xs-8">
            <p><a target="blank" href="./pdf/MockClassMasterPdf.php?<?= $url ?>" title="Save as PDF" class="btn btn-primary"><i class="fa fa-download"></i> Save the Sheet as PDF</a></p>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-3">

        </div>
</div>
<br>
<table class="table table-responsive table-bordered">
    <tr class="tiny-table-header">
    <td>STUDENT</td>
<?php
/*
    Get subjects in the class


*/
$subjects = $Model->ViewClassSubjects($class_id);
foreach($subjects as $subject){
    ?>
        <td><?= $subject['subject'] ?></td>
    <?php
}
?>
        <td>PAPERS</td>
        <td>POINTS</td>
    </tr>

    <?php
/*
    Get students in the class


*/
$class_cycle = $Model->GetAClass($class_id)[0]['cycle'];
$students = $Model->GetStudentsSatForExam($year_id, $class_id, $exam_id);
foreach($students as $student){
    $papers = 0; $points = 0;
    ?>
    <tr class="normal-tr">
    <td><?= $Model->GetStudent($student['student_code'], $section)[0]['name'] ?></td>
    <?php
    foreach($subjects as $subject){
        ?>
        <td>
        <?php
            if($class_cycle == 'FIRST'){
                $grade = $Model->OLGrade($student['student_code'], $year_id, $class_id, $exam_id, $subject['subject'] );
                if($grade == "A"){
                    $papers++;
                    $points = $points + 3;
                }elseif($grade == "B"){
                    $papers++;
                    $points = $points + 2;
                }elseif($grade == "C"){
                    $papers++;
                    $points = $points + 1;
                }
                echo $grade;
            }else{
                $grade = $Model->ALGrade($student['student_code'], $year_id, $class_id, $exam_id, $subject['subject'] );
                if($grade == "A"){
                    $papers++;
                    $points = $points + 5;
                }elseif($grade == "B"){
                    $papers++;
                    $points = $points + 4;
                }elseif($grade == "C"){
                    $papers++;
                    $points = $points + 3;
                }elseif($grade == "D"){
                    $papers++;
                    $points = $points + 2;
                }elseif($grade == "E"){
                    $papers++;
                    $points = $points + 1;
                }
                echo $grade;
            }
         ?>
         </td>
    <?php
    }
    ?>
    <td><?= $papers ?> </td>
    <td><?= $points ?> </td>
    <?php
}
?>
</table>