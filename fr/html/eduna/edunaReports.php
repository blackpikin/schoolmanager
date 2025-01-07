<?php
include "includes/EdunaModel.php";
$Eduna = new EdunaModel();
//1. Get the students information
$student_code = $_GET['ref'];
$s = $Eduna->GetAllWithCriteria('students_students', ['id' => $student_code]);
//2. Get the students classes
$students_classes = $Eduna->GetAllWithCriteria('students_yearlystudents', ['student_id' => $student_code, 'school_trade_id'=> 1]);
$class_subjects = [];
?>
<div class="row" style="margin-top: 10px;">
    <div class="col-md-3 col-sm-3 col-xs-3">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]['TermReps'] ?> - <?= $s[0]['full_name'] ?></label>
        </p>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>
<table class="table table-bordered table-hover">
    <thead class="table-header">
        <td><?= $lang[$_SESSION['lang']]['Academic year'] ?></td>
        <td><?= $lang[$_SESSION['lang']]['Class'] ?></td>
        <td><?= $lang[$_SESSION['lang']]['Report cards'] ?></td>
    </thead>
    <?php
        foreach ($students_classes as $key => $value) {
            $classname = $Eduna->GetSomeWithCriteria('setup_schoolclasses', ['name'], ['id' => $value['school_class_id']]);
            $yearname = $Eduna->GetSomeWithCriteria('setup_academicyears', ['short_name'], ['id' => $value['year_id']]);
            ?>
                <tr class="normal-tr">
                    <td>
                        <?= $yearname[0]['short_name'] ?>
                    </td>
                    <td>
                        <?= $classname[0]['name'] ?>
                    </td>
                    <td>
                        <button onclick="window.open('./pdf/EdunaReportPDF.php?ref=<?= $student_code ?>&year=<?= $value['year_id'] ?>&class=<?= $value['school_class_id'] ?>&term=1&year_student_id=<?= $value['id'] ?>&trade=1')" class="btn btn-primary" type="button"><?= $lang[$_SESSION['lang']]['FIRST TERM'] ?> </button>
                        <button onclick="window.open('./pdf/EdunaReportPDF.php?ref=<?= $student_code ?>&year=<?= $value['year_id'] ?>&class=<?= $value['school_class_id'] ?>&term=2&year_student_id=<?= $value['id'] ?>&trade=1')" class="btn btn-success" type="button"><?= $lang[$_SESSION['lang']]['SECOND TERM'] ?> </button>
                        <button onclick="window.open('./pdf/EdunaReportPDF.php?ref=<?= $student_code ?>&year=<?= $value['year_id'] ?>&class=<?= $value['school_class_id'] ?>&term=3&year_student_id=<?= $value['id'] ?>&trade=1')" class="btn btn-danger" type="button"><?= $lang[$_SESSION['lang']]['THIRD TERM'] ?> </button>
                    </td>
                </tr>
            <?php
        }

        //Get second cycle
        $students_classes = $Eduna->GetAllWithCriteria('students_yearlystudents', ['student_id' => $student_code, 'school_trade_id'=> 2]);
        
        foreach ($students_classes as $key => $value) {
            $classname = $Eduna->GetSomeWithCriteria('setup_schoolclasses', ['name'], ['id' => $value['school_class_id']]);
            $yearname = $Eduna->GetSomeWithCriteria('setup_academicyears', ['short_name'], ['id' => $value['year_id']]);
            ?>
                <tr class="normal-tr">
                    <td>
                        <?= $yearname[0]['short_name'] ?>
                    </td>
                    <td>
                        <?= $classname[0]['name'] ?>
                    </td>
                    <td>
                        <button onclick="window.open('./pdf/EdunaReportPDF.php?ref=<?= $student_code ?>&year=<?= $value['year_id'] ?>&class=<?= $value['school_class_id'] ?>&term=1&year_student_id=<?= $value['id'] ?>&trade=2')" class="btn btn-primary" type="button"><?= $lang[$_SESSION['lang']]['FIRST TERM'] ?> </button>
                        <button onclick="window.open('./pdf/EdunaReportPDF.php?ref=<?= $student_code ?>&year=<?= $value['year_id'] ?>&class=<?= $value['school_class_id'] ?>&term=2&year_student_id=<?= $value['id'] ?>&trade=2')" class="btn btn-success" type="button"><?= $lang[$_SESSION['lang']]['SECOND TERM'] ?> </button>
                        <button onclick="window.open('./pdf/EdunaReportPDF.php?ref=<?= $student_code ?>&year=<?= $value['year_id'] ?>&class=<?= $value['school_class_id'] ?>&term=3&year_student_id=<?= $value['id'] ?>&trade=2')" class="btn btn-danger" type="button"><?= $lang[$_SESSION['lang']]['THIRD TERM'] ?> </button>
                    </td>
                </tr>
            <?php
        }
    ?>
    
</table>