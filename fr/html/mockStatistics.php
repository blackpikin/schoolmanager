<?php
$year_id = $_GET['year_id'];
$class_id = $_GET['class_id'];
$exam_name = $_GET['exam_id'];
$exam_id = $Model->GetMockExam($year_id, $exam_name)[0]['id'];
$url = 'year_id='.$year_id.'&class_id='.$class_id.'&exam_name='.$exam_name;
?>
<br>
<h4 id="label1" style="text-align:center;"><?= $exam_name ?>  STATISTICS - <?= $Model->GetYearName($year_id) ?></h4>
<br>
<div class="row">
        <div class="col-md-1 col-sm-1 col-xs-1">

        </div>
        <div class="col-md-8 col-sm-8 col-xs-8">
            <p><a target="blank" href="./pdf/MockStatPdf.php?<?= $url ?>" title="Save as PDF" class="btn btn-primary"><i class="fa fa-download"></i> Save the Stats as PDF</a></p>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-3">

        </div>
</div>
<?php
/*
    -Get Mockable classes
    Foreach class
    ..count students on roll
    ..count students who sat
    ..Count those who passed more than 4 or two(for A/Level)
    .. count percentage passed
    Count number of each grade

*/

//Get Mockable classes
$classes = $Model->GetMockableClasses($section);

if(!empty($classes)){
    ?>
    <table class="table table-responsive table-bordered">
        <tr class="table-header">
            <td>Class</td>
            <td>On roll</td>
            <td>Sat</td>
            <td>Passed</td>
            <td>Percentage pass</td>
        </tr>
    <?php
    foreach($classes as $class){
        ?>
        <tr class="normal-tr">
            <td><?= $Model->GetAClassName($class['id']) ?></td>
            <td><?= count($Model->GetStudentsInClass($class['id'], $year_id)) ?></td>
            <td>
            <?php 
                $sat = 0;
                $sat = count($Model->GetStudentsSatForExam($year_id, $class['id'], $exam_id));
                echo $sat;
             ?>
            </td>
            <td>
                <?php 
                $pass = 0;
                if ($Model->GetAClass($class['id'])[0]['cycle'] == "FIRST"){
                    $students = $Model->GetStudentsSatForExam($year_id, $class['id'], $exam_id);
                    foreach($students as $student){
                        $papers = $Model->GetStudentsPassPapers($year_id, $class['id'], $exam_id, $student['student_code']);
                        if($papers >= 4){
                            $pass++;
                        }
                    }
                }else{
                    $students = $Model->GetStudentsSatForExam($year_id, $class['id'], $exam_id);
                    foreach($students as $student){
                        $papers = $Model->GetStudentsPassPapers($year_id, $class['id'], $exam_id, $student['student_code']);
                        if($papers >= 2){
                            $pass++;
                        }
                    }
                }
                echo $pass;
                ?>
            </td>
            <td>
                <?php 
                    if($sat > 0){
                        $Percent_pass = round(($pass/$sat)*100, 2);
                        echo $Percent_pass;
                    }else{
                        echo '0.00';
                    }
                    
                 ?>
            </td>
            </tr>
        <?php
    }
    ?>
    </table>
    <?php
}
