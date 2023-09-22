<?php
$student_code = $_GET['ref'];
$data = $Model->GetStudent($student_code, $section);
?>
<h5 id="label1" style="text-align:center;"><?= $lang[$_SESSION['lang']]["studentProfile"] ?> - <?= $data[0]['name'] ?></h5>
<br>
<div class="row">
        <div class="col-xs-1">

        </div>
        <div class="col-xs-8">
            <p><a target="blank" href="./pdf/profilePdf.php?ref=<?= $student_code ?>" title="Enregistrer etant PDF" class="btn btn-primary"><i class="glyphicon glyphicon-download"></i> <?= $lang[$_SESSION['lang']]["Save list as PDF"] ?></a></p>
        </div>
        <div class="col-xs-3">

        </div>
</div>
<div class="row">
        <div class="col-xs-1">

        </div>
        <div class="col-xs-8">
        <table class="table table-responsive table-bordered">
    <tr class="grey-tr">
        <td><?= $lang[$_SESSION['lang']]["Name"] ?>:</td>
        <td><?= $data[0]['name'] ?></td>
        <td>
        <?php 
                if($data[0]['picture'] != ""){
                    $d = base64_decode($data[0]['picture']);
                    $file = "./img/students/" . $data[0]["student_code"] . '.'.$data[0]["picture_ext"];
                    $success = file_put_contents($file, $d);
                    ?>
                        <img src="<?= $file ?>" alt="" class="student-pic">
                    <?php
                }
            ?>
        </td>
    </tr>
    <tr class="normal-tr">
        <td><?= $lang[$_SESSION['lang']]["Gender"] ?>:</td>
        <td><?= $data[0]['gender'] ?></td>
    </tr>
    <tr class="grey-tr">
        <td><?= $lang[$_SESSION['lang']]["DOB"] ?>:</td>
        <td><?= $data[0]['dob'] ?></td>
    </tr>
    <tr class="normal-tr">
        <td><?= $lang[$_SESSION['lang']]["POB"] ?>:</td>
        <td><?= $data[0]['pob'] ?></td>
    </tr>
    <tr class="grey-tr">
        <td><?= $lang[$_SESSION['lang']]["FatherName"] ?>:</td>
        <td><?= $data[0]['father_name'] ?></td>
    </tr>
    <tr class="normal-tr">
        <td><?= $lang[$_SESSION['lang']]["MotherName"] ?>:</td>
        <td><?= $data[0]['mother_name'] ?></td>
    </tr>
    <tr class="grey-tr">
        <td><?= $lang[$_SESSION['lang']]["AdmissionNum"] ?>:</td>
        <td><?= $data[0]['adm_num'] ?></td>
    </tr>
    <tr class="normal-tr">
        <td><?= $lang[$_SESSION['lang']]["GuardianName"] ?>:</td>
        <td><?= $data[0]['guardian'] ?></td>
    </tr>
    <tr class="grey-tr">
        <td><?= $lang[$_SESSION['lang']]["GuardianPhone"] ?>:</td>
        <td><?= $data[0]['guardian_number'] ?></td>
    </tr>
    <tr class="normal-tr">
        <td><?= $lang[$_SESSION['lang']]["GuardianEmail"] ?>:</td>
        <td><?= $data[0]['guardian_email'] ?></td>
    </tr>
    <tr class="grey-tr">
        <td><?= $lang[$_SESSION['lang']]["GuardianAddress"] ?>:</td>
        <td><?= $data[0]['guardian_address'] ?></td>
    </tr>
    <tr class="normal-tr">
        <td>Code:</td>
        <td><?= $data[0]['student_code'] ?></td>
    </tr>
</table>
        </div>
        <div class="col-xs-3">

        </div>
</div>
<div class="row">
<div class="col-xs-1">

</div>
<div class="col-xs-8">
    <table class="table table-responsive table-bordered">
        <tr class="table-header">
            <td><?= $lang[$_SESSION['lang']]["Class"] ?></td>
            <td><?= $lang[$_SESSION['lang']]["TERM"] ?> 1</td>
            <td><?= $lang[$_SESSION['lang']]["TERM"] ?> 2</td>
            <td><?= $lang[$_SESSION['lang']]["TERM"] ?> 3</td>
        </tr>
        <?php 
            $classes = $Model->TranscriptClasses($student_code, 'FIRST');
            foreach($classes as $class){
                ?>
                <tr class="normal-tr">
                    <td><?= $Model->GetAClassName($class['class_id']); ?></td>
                    <td><?= $Model->TermAverage($student_code, 'First', $class['academic_year_id'], $class['class_id']); ?></td>
                    <td><?= $Model->TermAverage($student_code, 'Second', $class['academic_year_id'], $class['class_id']); ?></td>
                    <td><?= $Model->TermAverage($student_code, 'Third', $class['academic_year_id'], $class['class_id']); ?></td>
                </tr>
                <?php
            }
            $classes = $Model->TranscriptClasses($student_code, 'SECOND');
            foreach($classes as $class){
                ?>
                <tr class="normal-tr">
                    <td><?= $Model->GetAClassName($class['class_id']); ?></td>
                    <td><?= $Model->TermAverage($student_code, 'First', $class['academic_year_id'], $class['class_id']); ?></td>
                    <td><?= $Model->TermAverage($student_code, 'Second', $class['academic_year_id'], $class['class_id']); ?></td>
                    <td><?= $Model->TermAverage($student_code, 'Third', $class['academic_year_id'], $class['class_id']); ?></td>
                </tr>
                <?php
            }
        ?>
    </table>
</div>
<div class="col-xs-3">

</div>
</div>
<div class="row">
<div class="col-xs-1">

</div>
<div class="col-xs-8">
    <table class="table table-responsive table-bordered">
        <tr class="table-header">
            <td>Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td>Description</td>
            <td>Photo</td>
        </tr>
        <?php 
            $conducts = $Model->StudentConducts($student_code);
            foreach($conducts as $conduct){
                ?>
                <tr class="normal-tr">
                    <td>
                    <?php  $date = New DateTime($conduct['date']); ?>
                    <?= date_format($date, "d-m-Y")  ?>
                    </td>
                    <td><?= $conduct['tittle'].'<br>'.$conduct['description'] ?></td>
                    <td>
                        <?php
                            if($conduct['photo'] != ""){
                                $d = base64_decode($conduct['photo']);
                                $file = "./img/students/" . $conduct["student_code"] . '1.'.$conduct["photo_ext"];
                                $success = file_put_contents($file, $d);
                                ?>
                                    <img src="<?= $file ?>" alt="" class="conduct-pic">
                                <?php
                            }else{
                                ?>
                                    <div class="conduct-pic">
                                        No photo available.
                                    </div>
                                <?php
                            }
                        ?>
                    </td>
                </tr>
                <?php
            }
           ?>
    </table>
</div>
<div class="col-xs-3">

</div>
</div>

