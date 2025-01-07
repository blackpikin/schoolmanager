<?php 
$year_id = $_GET['year_id'];
$class_id = $_GET['class_id'];
$students = $Model->GetStudentsInClass($class_id, $year_id);
$url = 'year_id='.$year_id.'&class_id='.$class_id;
?>
<div class="row" style="margin-top: 10px;">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <p>
            <label id="label1">ANNUAL RESULT SUMMARY - <?= $Model->GetAClassName($class_id) ?> - <?= $Model->YearNameDigits($year_id) ?></label>
        </p>
    </div>
</div>
<div class="row">
        <div class="col-md-3 col-sm-3 col-xs-3">

        </div>
        <div class="col-md-3 col-sm-3 col-xs-3">
            <p><a target="blank" href="./pdf/annualSummaryPDF.php?<?= $url ?>" title="Save as PDF" class="btn btn-primary"><i class="fa fa-download"></i> Save as PDF</a></p>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-3">

        </div>
</div>
<br>
<table class="table table-bordered table-responsive">
 <tr class="table-header">
     <td>Name of student</td>   
     <td>Gender</td> 
     <td>First Term</td>
     <td>Second Term</td>
     <td>Third Term</td>
     <td>Annual Average</td> 
</tr>             
<?php
$ranked_students = [];
foreach ($students as $stud){
    $first_term_av = $Model->TermAverage($stud['student_code'], 'First', $year_id, $class_id);
    $second_term_av = $Model->TermAverage($stud['student_code'], 'Second', $year_id, $class_id);
    $third_term_av = $Model->TermAverage($stud['student_code'], 'Third', $year_id, $class_id);
    $ann = round(($first_term_av + $second_term_av + $third_term_av)/3, 2);
    $ranked_students[$stud['student_code']] = $ann;
    arsort($ranked_students);
}
foreach ($ranked_students as $code => $annual){
    $s = $Model->GetStudent($code, $section);
    ?>
<tr class="normal-tr">
     <td><?= $s[0]['name']?></td>   
     <td><?= $s[0]['gender']?></td> 
     <td><?= $Model->TermAverage($code, 'First', $year_id, $class_id) ?></td>
     <td><?= $Model->TermAverage($code, 'Second', $year_id, $class_id) ?></td>
     <td><?= $Model->TermAverage($code, 'Third', $year_id, $class_id) ?></td>
     <td><?= $annual ?></td> 
</tr>
    <?php
}
?>
</table>