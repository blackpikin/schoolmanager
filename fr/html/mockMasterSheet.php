<?php
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
            <p><a target="blank" href="./pdf/MockMasterPdf.php?<?= $url ?>" title="Save as PDF" class="btn btn-primary"><i class="fa fa-download"></i> Save the Sheet as PDF</a></p>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-3">

        </div>
</div>
<?php
/*
 Get Subjects in the said class
 Decide whether class is First or second cycle
 For each subject determine the number of A B C D E grades

*/

$class_cycle = $Model->GetAClass($class_id)[0]['cycle'];
if($class_cycle == 'FIRST'){
    ?>
    <table class="table table-responsive table-bordered">
        <tr class="table-header">
            <td>Subject</td>
            <td>A</td>
            <td>B</td>
            <td>C</td>
            <td><span style="color:red">D</span></td>
            <td><span style="color:red">E</span></td>
            <td><span style="color:red">U</span></td>
        </tr>
        <?php 
            $subjects = $Model->ViewClassSubjects($class_id);
            foreach($subjects as $subject){
                ?>
                <tr class="normal-tr">
            <td><?= $subject['subject'] ?></td>
            <td><?= $Model->CountOLevelGrade('A', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']) ?></td>
            <td><?= $Model->CountOLevelGrade('B', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']) ?></td>
            <td><?= $Model->CountOLevelGrade('C', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']) ?></td>
            <td><?= $Model->CountOLevelGrade('D', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']) ?></td>
            <td><?= $Model->CountOLevelGrade('E', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']) ?></td>
            <td><?= $Model->CountOLevelGrade('U', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']) ?></td>
        </tr> 
                <?php
            }
        ?>
    </table>
    <?php
}else{
    ?>
    <table class="table table-responsive table-bordered">
        <tr class="table-header">
            <td>Subject</td>
            <td>A</td>
            <td>B</td>
            <td>C</td>
            <td>D</td>
            <td>E</td>
            <td><span style="color:red">O</span></td>
            <td><span style="color:red">F</span></td>
        </tr>
        <?php 
            $subjects = $Model->ViewClassSubjects($class_id);
            foreach($subjects as $subject){
                ?>
                <tr class="normal-tr">
            <td><?= $subject['subject'] ?></td>
            <td><?= $Model->CountALevelGrade('A', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']) ?></td>
            <td><?= $Model->CountALevelGrade('B', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']) ?></td>
            <td><?= $Model->CountALevelGrade('C', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']) ?></td>
            <td><?= $Model->CountALevelGrade('D', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']) ?></td>
            <td><?= $Model->CountALevelGrade('E', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']) ?></td>
            <td><?= $Model->CountALevelGrade('O', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']) ?></td>
            <td><?= $Model->CountALevelGrade('F', $year_id, $class_id, $exam_id, $exam_name, $subject['subject']) ?></td>

        </tr>
                <?php
            }
        ?>
    </table>
    <?php
}
