<?php
$lng = $_SESSION['lang'];
$section = 0;
if($lng == 'fr'){
    $section = 1;
}

?>
<?php $type = $_GET['type']; ?>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-3">

    </div>
    <div class="col-xs-8">
        <p>
            <label id="label1">Generate Report summary</label>
        </p>
    </div>
    <div class="col-xs-1">

    </div>
</div>

<?php
if($type == 'seq'){
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
            <div class="col-xs-6">
                <p><a target="blank" href="./pdf/SequenceSummaryPdf.php?<?= $url ?>" title="Save as PDF" class="btn btn-primary"><i class="glyphicon glyphicon-download"></i> Save the summary as PDF</a></p>
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
    ?>
    <table class="table table-responsive table-bordered">
        <tr class="table-header">
            <td>Position</td>
            <td>Name of student</td>
            <td>Gender</td>
            <td>Average</td>
            <td>Number of subjects passed</td>
        </tr>
        <?php 
            foreach($position_array as $student_code => $average){
                $s = $Model->GetStudent($student_code, $section);
                ?>
                <tr class="normal-tr">
                    <td><?php 
                            for ($i = 0; $i < count($KEYS); $i++){ 
                              if($KEYS[$i] == $student_code){
                                  $pos = $i + 1;
                                 echo $pos;
                              }        
                            }
                       ?></td>
                    <td><?= $s[0]['name'] ?> </td>
                    <td><?= $s[0]['gender'] ?></td>
                    <td><?= $average ?></td>
                    <td><?= $Model->NumberOfPapersSeq($student_code, $year_id, $class_id, $exam_id) ?></td>
                </tr>
                <?php
            }
        ?>
    </table>
    </div>
    </div>
    <?php
    }
}

if($type == 'term'){
$year_id = $_GET['year_id'];
$class_id = $_GET['class_id'];
$term_id = $_GET['term_id'];
$url = 'year_id='.$year_id.'&class_id='.$class_id.'&exam_id='.$term_id;

//Get exam ids for ther term
$term_exams_ids = $Model->ExamsForTerm($term_id, $year_id, $section);

//Calculate mean marks for the two terms
$exam1 = $Model->GetMarkSheet($year_id, $class_id, $term_exams_ids[0]['id']);
$exam2 = $Model->GetMarkSheet($year_id, $class_id, $term_exams_ids[1]['id']);

$student_codes1 = []; $student_codes2 = [];
$data1 = []; $data2 = [];
 $students_totals1 = []; $students_totals2 = [];

 $term_student_codes = []; 

foreach($exam1 as $student){
    if(!in_array($student['student_code'], $student_codes1)){
        array_push($student_codes1, $student['student_code']);
    }
    if(!in_array($student['student_code'], $term_student_codes)){
        array_push($term_student_codes, $student['student_code']);
    }
}

foreach($exam2 as $student){
    if(!in_array($student['student_code'], $student_codes2)){
        array_push($student_codes2, $student['student_code']);
    }
    if(!in_array($student['student_code'], $term_student_codes)){
        array_push($term_student_codes, $student['student_code']);
    }
}

$class_av = 0;  $position_array = [];
$class_average = 0 ;
foreach($student_codes1 as $student){
    $marks = $Model->GetStudentsMarks($year_id, $class_id, $term_exams_ids[0]['id'], $student);
    $total_coef = 0;
    $total_marks = 0;

    foreach ($marks as $mark){
        $coef = $Model->GetCoefficient($mark['subject'], $class_id);
        $total = $mark['mark'] * $coef;
        $total_coef = $total_coef + $coef;
        $total_marks = $total_marks + $total;
        
       array_push($data1, [  
        'student' => $student,   
        'subject'=>$mark['subject'],
        'mark'=>$mark['mark'], 
        'coef'=>$coef, 
        'total'=> $total,
     ]);
    }
    
    $average = round($total_marks/$total_coef, 2);
    $students_totals1[$student] = [
        'marks'=>$data1, 
        'total_coef'=>$total_coef, 
        'total_marks' =>$total_marks,
         'average'=>$average,
    ];
    
}

foreach($student_codes2 as $student){
    $marks = $Model->GetStudentsMarks($year_id, $class_id, $term_exams_ids[1]['id'], $student);
    $total_coef = 0;
    $total_marks = 0;

    foreach ($marks as $mark){
        $coef = $Model->GetCoefficient($mark['subject'], $class_id);
        $total = $mark['mark'] * $coef;
        $total_coef = $total_coef + $coef;
        $total_marks = $total_marks + $total;
        
       array_push($data2, [  
        'student' => $student,   
        'subject'=>$mark['subject'],
        'mark'=>$mark['mark'], 
        'coef'=>$coef, 
        'total'=> $total,
     ]);
    }
    
    $average = round($total_marks/$total_coef, 2);
    $students_totals2[$student] = [
        'marks'=>$data2, 
        'total_coef'=>$total_coef, 
        'total_marks' =>$total_marks,
         'average'=>$average,
    ];
    
}


foreach ($term_student_codes as $student){
    $av1 =  $students_totals1[$student]['average'];
    $av2 =  $students_totals2[$student]['average'];
    $average = round(($av1 + $av2)/2, 2);
    $position_array[$student] = $average;

    $class_av = $class_av + $average;
    $class_average = round($class_av/count($term_student_codes), 2);

}
arsort($position_array);

$KEYS = array_keys($position_array);


if(!empty($position_array)){
    ?>
     <br>
    <br>
<div class="row">
        <div class="col-xs-3">

        </div>
        <div class="col-xs-3">
            <p><a target="blank" href="./pdf/TermSummaryPdf.php?year_id=<?= $year_id ?>&class_id=<?= $class_id ?>&term_id=<?= $term_id ?>" title="Save as PDF" class="btn btn-primary"><i class="glyphicon glyphicon-download"></i> Save the summary as PDF</a></p>
        </div>
        <div class="col-xs-3">

        </div>
</div>
<table class="table table-responsive table-bordered">
        <tr class="table-header">
            <td>Position</td>
            <td>Name of student</td>
            <td>Gender</td>
            <td>Average</td>
        </tr>
<?php
 foreach ($position_array as $student_code => $average){
    $s = $Model->GetStudent($student_code, $section);
    ?>
    <tr class="normal-tr">
        <td>
            <?php 
                for ($i = 0; $i < count($KEYS); $i++){ 
                    if($KEYS[$i] == $student_code){
                        $pos = $i + 1;
                        echo $pos;
                    }        
                }
            ?>
        </td>
        <td><?= $s[0]['name'] ?> </td>
        <td><?= $s[0]['gender'] ?></td>
        <td><?= $average ?></td>
    </tr>
    <?php
 }
?>
 </table>
 <?php
}
}
