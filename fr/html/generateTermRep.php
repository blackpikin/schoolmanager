<?php
$year_id = $_GET['year_id'];
$class_id = $_GET['class_id'];
$exam_id = $_GET['exam_id'];
$url = 'year_id='.$year_id.'&class_id='.$class_id.'&exam_id='.$exam_id;
$students = $Model->GetMarkSheet($year_id, $class_id, $exam_id);
$student_codes = [];
$data = []; $students_totals = [];

if(!empty($students)){
    ?>
     <br>
            <br>
<div class="row">
        <div class="col-xs-3">

        </div>
        <div class="col-xs-3">
            <p><a target="blank" href="./pdf/SequenceRepPdf.php?<?= $url ?>" title="Save as PDF" class="btn btn-primary"><i class="glyphicon glyphicon-download"></i> Save the reports as PDF</a></p>
        </div>
        <div class="col-xs-3">

        </div>
</div>
<div class="row">
    <div class="col-xs-11">
    <?php
    foreach($students as $student){
        if(!in_array($student['student_code'], $student_codes)){
            array_push($student_codes, $student['student_code']);
        }
    }

    $class_av = 0;
    $position_array = [];
    foreach($student_codes as $student){
        $marks = $Model->GetStudentsMarks($year_id, $class_id, $exam_id, $student);
        $total_coef = 0;
        $total_marks = 0;

        foreach ($marks as $mark){
            $coef = $Model->GetCoefficient($mark['subject'], $class_id);
            $total = $mark['mark'] * $coef;
            $remark = "";
            if($mark['mark'] < 10){$remark = "NA";}elseif($mark['mark'] >= 10 && $mark['mark'] <= 13){$remark = "ATBA";}elseif($mark['mark'] > 13 && $mark['mark'] <= 16){$remark = "A";}elseif($mark['mark'] > 16){$remark = "A+";}
            $teacher = $Model->GetSubjectTeacher($mark['subject'], $class_id, $year_id);
            $total_coef = $total_coef + $coef;
            $total_marks = $total_marks + $total;
            
           array_push($data, [  
            'student' => $student,   
            'subject'=>$mark['subject'],
            'mark'=>$mark['mark'], 
            'coef'=>$coef, 
            'total'=> $total,
            'rank' => $Model->SubjectRank($mark['subject'], $student, $year_id, $class_id, $exam_id ),
            'remark' => $remark,
            'teacher'=> $Model->GetStaffName($teacher)
         ]);
        }

        $average = round($total_marks/$total_coef, 2);
        $class_av = $class_av + $average;
        $class_average = round($class_av/count($student_codes), 2);

        $students_totals[$student] = [
            'marks'=>$data, 
            'total_coef'=>$total_coef, 
            'total_marks' =>$total_marks,
             'average'=>$average,
        ];
        $position_array[$student] = $average;
        arsort($position_array);
    }

   $KEYS = array_keys($position_array);

    foreach ($position_array as $student_code => $average){
       ?>
            <br>
            <br>
            <?php 
                $s = $Model->GetStudent($student_code, $section);
            ?>
             <table class="table table-bordered table-responsive" style="font-size:9pt;">
                <tr class="table-header">
                <td class="report-td"><?= $Model->GetTermName($exam_id); ?></td>
                <td class="report-td">ENROLMENT: <?= count($student_codes); ?></td>
                <td class="report-td"><?= $Model->GetSequenceName($exam_id) ?> REPORT CARD </td>
                <td class="report-td"><?= $Model->GetYearName($year_id) ?></td>
                <td colspan="5">
                <?php 
                    if($s[0]['picture'] != ""){
                        $data = base64_decode($s[0]['picture']);
                        $file = "./img/students/" . $s[0]["student_code"] . '.'.$s[0]["picture_ext"];
                        $success = file_put_contents($file, $data);
                        ?>
                            <img src="<?= $file ?>" alt="" class="student-pic">
                        <?php
                    }
                ?>
                </td>
                </tr>
                <tr class="normal-tr">
                    <td><b>NAME:</b> <?= $s[0]['name'] ?></td>
                    <td><b>GENDER:</b> <?= $s[0]['gender'] ?></td>
                    <td><b>DOB:</b> <?= $s[0]['dob'] ?></td>
                    <td><b>BORN AT:</b> <?= $s[0]['pob'] ?></td>
                    <td colspan="5"><b>CLASS:</b> <?= $Model->GetAClassName($class_id) ?></td>
                </tr>
                <tr class="normal-tr">
                    <td><b>Parent or Guardian Address:</b> </td>
                    <td><b>Admission:</b> </td>
                    <td><b>ID:</b> <?= $s[0]['student_code'] ?></td>
                    <td><b>Admission number:</b> </td>
                    <td colspan="5"></td>
                </tr>
                <tr class="normal-tr">
                    <td class="bold-header">Subject</td>
                    <td class="bold-header">Test 1 </td>
                    <td class="bold-header">Test Av.</td>
                    <td class="bold-header">Coef</td>
                    <td class="bold-header">Total</td>
                    <td class="bold-header">Rank</td>
                    <td class="bold-header">Remark</td>
                    <td class="bold-header">Teacher's name</td>
                </tr>
                <?php
                
                $d = $students_totals[$student_code]['marks'];
                asort($d);

                foreach($d as $subject){
                    if($subject['student'] == $student_code){
                    ?>
                    <tr class="normal-tr"> 
                    <td><?= $subject['subject'] ?></td>
                    <td><?= $subject['mark'] ?></td>
                    <td><?= $subject['mark'] ?></td>
                    <td><?= $subject['coef'] ?></td>
                    <td><?= $subject['total'] ?></td>
                    <td><?= $subject['rank'] ?></td>
                    <td><?= $subject['remark'] ?></td>
                    <td><?= $subject['teacher'] ?></td>                  
                    </tr>
                    <?php
                    }
        
                }
                ?>
                

                <tr class=normal-tr>
                    <td></td>
                    <td class="bold-header"><?= $average ?></td>
                    <td class="bold-header"></td>
                    <td class="bold-header"><?= $students_totals[$student_code]['total_coef'] ?></td>
                    <td class="bold-header"><?= $students_totals[$student_code]['total_marks'] ?></td>
                    <td class="bold-header"></td>
                    <td class="bold-header"></td>
                    <td class="bold-header">
                       <?php 
                            for ($i = 0; $i < count($KEYS); $i++){ 
                              if($KEYS[$i] == $student_code){
                                  $pos = $i + 1;
                                ?>
                                    Position: <?= $pos.' / '.count($position_array) ?>
                                <?php
                              }        
                            }
                       ?>
                    </td>
                    </tr>
                    <tr class="grey-tr">
                        <td colspan="2" class="bold-header"><b>Number of papers:</b> <?= $Model->NumberOfPapersSeq($student_code, $year_id, $class_id, $exam_id) ?></td>
                        <td colspan="3" class="bold-header"><b>Term Av: </b><?= $average; ?></td>
                        <td colspan="3" class="bold-header"><b>Class Av: </b><?= $class_average; ?></td>
                    </tr>
                    <tr class="normal-tr">
                        <td colspan="3" class="bold-header"><b>GENERAL CONDUCT</b></td>
                        <td colspan="5" class="bold-header"><b>CLASS COUNCIL DECISION </b></td>
                    </tr>
                    <tr class="grey-tr">
                        <td colspan="2" class="normal-tr"><b>DISCIPLINE</b></td>
                        <td colspan="3" class="normal-tr"><b>HEALTH</b></td>
                        <td colspan="3" class="normal-tr"><b>ACADEMIC WORK</b></td>
                    </tr>
                    <tr class="">
                        <td class="normal-tr"><b>Absences</b></td>
                        <td class="normal-tr"><b><?= $Model->CountAbsences($year_id, $class_id, explode(' ',$Model->GetTermName($exam_id))[0], $student_code, 'absences') ?></b></td>
                        <td class="normal-tr"><b>Good</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Could do better</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Warning</b></td>
                        <td class="normal-tr"><b></b></td>
                    </tr>
                    <tr class="">
                        <td class="normal-tr"><b>Punishment</b></td>
                        <td class="normal-tr"><b><?= $Model->CountAbsences($year_id, $class_id, explode(' ',$Model->GetTermName($exam_id))[0], $student_code, 'punishment') ?></b></td>
                        <td class="normal-tr"><b>Fair</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Honour roll</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Dismissed</b></td>
                        <td class="normal-tr"><b></b></td>
                    </tr>
                    <tr >
                        <td class="normal-tr"><b>Warning</b></td>
                        <td class="normal-tr"><b><?= $Model->CountAbsences($year_id, $class_id, explode(' ',$Model->GetTermName($exam_id))[0], $student_code, 'warning') ?></b></td>
                        <td class="normal-tr"><b>Needs attention</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Principal's list</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Passed</b></td>
                        <td class="normal-tr"><b></b></td>
                    </tr>
                    <tr >
                        <td class="normal-tr"><b>Suspension in days</b></td>
                        <td class="normal-tr"><b><?= $Model->CountAbsences($year_id, $class_id, explode(' ',$Model->GetTermName($exam_id))[0], $student_code, 'suspension') ?></b></td>
                        <td class="normal-tr"><b>Other comments</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td colspan="2" class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Failed</b></td>
                        <td class="normal-tr"><b></b></td>
                    </tr>
                    <tr >
                        <td class="normal-tr"><b>Reopening date:</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Class supervisor:</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td colspan="2" class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Principal</b></td>
                        <td class="normal-tr"><b></b></td>
                    </tr>
                    <tr>
                        <td colspan="8" class="grey-tr"> 
                        DISCLAIMER: Any cancellation on the report card is not the handiwork of the school
                        </td>
                    </tr>
                <?php
                ?>
            </table>
       <?php

    }
}
?>
    </div>
<div class="col-xs-1">

</div>
</div>
