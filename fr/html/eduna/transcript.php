<?php 
include "includes/EdunaModel.php";
$Eduna = new EdunaModel();
$srch =''; $results = [];

if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_POST['srch'])){
        $srch = $Model->test_input($_POST['srch']);
        if (!empty($srch)){
            $students = $Eduna->SearchWithCriteria('students_students', ['full_name' => $srch]);
        }
    }
}else{
    $students = $Eduna->Get('students_students');
}

?>

<div class="row" style="margin-top: 10px;">
    <div class="col-md-3 col-sm-3 col-xs-3">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]['EdunaStudents'] ?></label>
        </p>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>
<div class="row">
    <div class="col-md-1 col-sm-1 col-xs-1">
       
    </div>
    <div class="col-md-7 col-sm-7 col-xs-7">
        <form action="" method="post">
    <input name="srch" style="height:55px;" type="text" value="<?= $srch ?>" placeholder="<?= $lang[$_SESSION['lang']]["SearchStudentPlaceholder"] ?>" class="form-control" >
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <button type="submit" style="height:55px;width:55px;" class="btn btn-warning fa fa-search"></button>
        </form>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <table class="table table-striped table-responsive table-bordered table-hover">
            <thead class="table-header">
                <td>SN</td>
                <td>Name</td>
                <td>Gender</td>
                <td>Date of Birth</td>
                <td>Place of Birth</td>
                <td>Phone Number</td>
                <td>Email</td>
                <td>Admission number</td>
                <td>Actions</td>
            </thead>
            <?php
                foreach ($students as $key => $student) {
                    ?>
                    <tr class="normal-tr">
                        <td><?= ++$key ?></td>
                        <td><?= $student['full_name'] ?></td>
                        <td><?= $student['gender'] ?></td>
                        <td><?= $student['date_of_birth'] ?></td>
                        <td><?= $student['place_of_birth'] ?></td>
                        <td><?= $student['guard_phone'] ?></td>
                        <td><?= $student['guard_email'] ?></td>
                        <td><?= $student['manual_matricule'] ?></td>
                        <td>
                            <button onclick="window.open('./pdf/PDFTranscript.php?ref=<?= $student['id'] ?>&type=1')" title="Secondary school transcript" class="btn btn-primary fa fa-folder-open"></button>
                            <button onclick="window.open('./pdf/PDFTranscript.php?ref=<?= $student['id'] ?>&type=2')" title="High school transcript" class="btn btn-success fa fa-folder-open"></button>
                            <button onclick="GotoPage('eduna/editStudent&ref=<?= $student['id'] ?>')" title="<?= $lang[$_SESSION['lang']]['EditStudent'] ?>" class="btn btn-warning fa fa-edit"></button>
                            <button onclick="GotoPage('eduna/edunaReports&ref=<?= $student['id'] ?>')" title="<?= $lang[$_SESSION['lang']]['TermReps'] ?>" class="btn btn-warning fa fa-list"></button>
                        </td>
                    </tr>
                    <?php
                }
            ?>
        </table>
    </div>
</div>
