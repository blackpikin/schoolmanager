<?php

$lng = $_SESSION['lang'];
$section = 0;
if($lng == 'fr'){
    $section = 1;
}
$year_id = $_GET['year_id'];
$class_id = $_GET['class_id'];
$term_id = $_GET['term_id'];
$url = 'year_id='.$year_id.'&class_id='.$class_id.'&term_id='.$term_id;

//Get exam ids for the term
$term_exams_ids = $Model->ExamsForTerm($term_id, $year_id, $class_id);

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
            <p><a target="blank" href="./pdf/TermRepPdf.php?<?= $url ?>" title="Save as PDF" class="btn btn-primary"><i class="glyphicon glyphicon-download"></i> Save the Reports as PDF</a></p>
        </div>
        <div class="col-xs-3">

        </div>
</div>
<div class="row">
    <div class="col-xs-11">
    <?php

    foreach ($position_array as $student_code => $average){
        ?>
        <br>
        <br>
        <?php 
            $s = $Model->GetStudent($student_code, $section);
        ?>
         <table class="table table-bordered table-responsive" style="font-size:9pt;">
            <tr class="table-header">
            <td  class="report-td"><?= strToUpper($term_id)." TERM"; ?></td>
            <td colspan="2" class="report-td">ENROLMENT: <?= count($position_array); ?></td>
            <td colspan="2" class="report-td"><?= strToUpper($term_id)." TERM"; ?> REPORT CARD </td>
            <td colspan="2" class="report-td"><?= $Model->GetYearName($year_id) ?></td>
            <td colspan="2">
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
            <tr class="table-header">
                <td class="bold-header">Subject</td>
                <td class="bold-header">Test 1 </td>
                <td class="bold-header">Test 2 </td>
                <td class="bold-header">Test Av.</td>
                <td class="bold-header">Coef</td>
                <td class="bold-header">Total</td>
                <td class="bold-header">Rank</td>
                <td class="bold-header">Remark</td>
                <td class="bold-header">Teacher's name</td>
            </tr>
            <?php
                $class_subjects = $Model->ViewClassSubjects($class_id);
                $t_coef = 0;
                $total_term_marks = 0; $number_of_papers = 0;
                foreach($class_subjects as $subject){
                    $datas = [$student_code, $class_id, $year_id, $subject['subject'], $term_exams_ids[0]['id'] ];
                    $test1 = $Model->GetAMark($datas);

                    $datas = [$student_code, $class_id, $year_id, $subject['subject'], $term_exams_ids[1]['id'] ];
                    $test2 = $Model->GetAMark($datas);

                    $coef = $Model->GetCoefficient($subject['subject'], $class_id);
                    $t_coef += $coef;

                    
                    
                ?>
                <tr class="normal-tr">
                    <td class="normal-tr"><?= $subject['subject'] ?></td>
                    <td class="normal-tr">
                        <?php 
                            echo $test1;
                        ?>
                    </td>
                    <td class="normal-tr">
                        <?php 
                             echo $test2;                       
                        ?>
                         </td>
                    <td class="normal-tr">
                        <?php
                            if (!is_numeric($test1)){
                                $test1 = 0;
                            }
                            if (!is_numeric($test2)){
                                $test2 = 0;
                            }
                            
                            $subj_total = round(($test1 + $test2)/2, 2);
                            echo $subj_total;

                            $totalofSubject = $coef * $subj_total;

                            $total_term_marks += $totalofSubject;

                            if ($subj_total >= 10){
                                $number_of_papers++;
                            }
                        ?>
                    </td>
                    <td class="normal-tr"><?= $coef ?></td>
                    <td class="normal-tr"><?= $totalofSubject ?></td>
                    <td class="normal-tr"><?= $Model->SubjectRank($subject['subject'], $student_code, $year_id, $class_id, $term_exams_ids[1]['id'] ) ?></td>
                    <td class="normal-tr">
                        <?php
                        $remark = "";
                        if($subj_total < 10){
                            $remark = "NA";
                        }elseif($subj_total >= 10 && $subj_total <= 13){
                            $remark = "ATBA";
                        }elseif($subj_total > 13 && $subj_total <= 16){
                            $remark = "A";
                        }elseif($subj_total > 16){
                            $remark = "A+";
                        }
                        echo $remark;
                        ?>
                    </td>
                    <td class="normal-tr">
                    <?php
                        $teacher = $Model->GetSubjectTeacher($subject['subject'], $class_id, $year_id);
                        echo $Model->GetStaffName($teacher)
                     ?>
                     </td>
                </tr>
                <?php
                }
            ?>
            <tr class=normal-tr>
                    <td></td>
                    <td class="bold-header"><?= $students_totals1[$student_code]['average'] ?></td>
                    <td class="bold-header"><?= $students_totals2[$student_code]['average'] ?></td>
                    <td class="bold-header"></td>
                    <td class="bold-header"><?= $t_coef ?></td>
                    <td class="bold-header"><?= $total_term_marks ?></td>
                    <td class="bold-header"></td>
                    <td class="bold-header"></td>
                    <td class="bold-header">
                       <?php 
                            for ($i = 0; $i < count($KEYS); $i++){ 
                              if($KEYS[$i] == $student_code){
                                  $pos = $i + 1;
                                ?>
                                    Position: <?= $pos.'/'.count($position_array) ?>
                                    <br>
                                <?php
                              }        
                            }
                       ?>
                    </td>
                    </tr>
                    <tr class="grey-tr">
                        <td colspan="2" class="bold-header"><b>Number of papers:</b> <?= $number_of_papers ?></td>
                        <td colspan="3" class="bold-header"><b>Term Av: </b><?= $average; ?></td>
                        <td colspan="2" class="bold-header"><b>Class Av: </b><?= $class_average; ?></td>
                        <td colspan="2" class="bold-header">
                            <?php 
                               if ($term_id == "Third"){ ?>
                               <b>Annual Av: </b>
                               <?php
                                   echo $Model->AnnualAverage($year_id, $class_id, $student_code);
                               }
                            ?>
                        </td>
                    </tr>
                    <tr class="normal-tr">
                        <td colspan="3" class="bold-header"><b>GENERAL CONDUCT</b></td>
                        <td colspan="6" class="bold-header"><b>CLASS COUNCIL DECISION </b></td>
                    </tr>
                    <tr class="grey-tr">
                        <td colspan="2" class="normal-tr"><b>DISCIPLINE</b></td>
                        <td colspan="3" class="normal-tr"><b>HEALTH</b></td>
                        <td colspan="4" class="normal-tr"><b>ACADEMIC WORK</b></td>
                    </tr>
                    <tr class="">
                        <td class="normal-tr"><b>Absences</b></td>
                        <td class="normal-tr"><b><?= $Model->CountAbsences($year_id, $class_id, $term_id, $student_code, 'absences') ?></b></td>
                        <td class="normal-tr"><b>Good</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Could do better</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Warning</b></td>
                        <td colspan="4" class="normal-tr"><b></b></td>
                    </tr>
                    <tr class="">
                        <td class="normal-tr"><b>Punishment</b></td>
                        <td class="normal-tr"><b><?= $Model->CountAbsences($year_id, $class_id, $term_id, $student_code, 'punishment') ?></b></td>
                        <td class="normal-tr"><b>Fair</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Honour roll</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Dismissed</b></td>
                        <td colspan="4" class="normal-tr"><b></b></td>
                    </tr>
                    <tr >
                        <td class="normal-tr"><b>Warning</b></td>
                        <td class="normal-tr"><b><?= $Model->CountAbsences($year_id, $class_id, $term_id, $student_code, 'warning') ?></b></td>
                        <td class="normal-tr"><b>Needs attention</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Principal's list</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Passed</b></td>
                        <td colspan="4" class="normal-tr"><b></b></td>
                    </tr>
                    <tr >
                        <td class="normal-tr"><b>Suspension in days</b></td>
                        <td class="normal-tr"><b><?= $Model->CountAbsences($year_id, $class_id, $term_id, $student_code, 'suspension') ?></b></td>
                        <td class="normal-tr"><b>Other comments</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td colspan="2" class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Failed</b></td>
                        <td colspan="4" class="normal-tr"><b></b></td>
                    </tr>
                    <tr >
                        <td class="normal-tr"><b>Reopening date:</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Class supervisor:</b></td>
                        <td class="normal-tr"><b></b></td>
                        <td colspan="2" class="normal-tr"><b></b></td>
                        <td class="normal-tr"><b>Principal</b></td>
                        <td colspan="4" class="normal-tr"><b></b></td>
                    </tr>
                    <tr>
                        <td colspan="9" class="grey-tr"> 
                        DISCLAIMER: Any cancellation on the report card is not the handiwork of the school
                        </td>
                    </tr>
         </table>
            <?php
    }
}
?>
    </div>
    <div class="col-xs-1">

    </div>
</div>