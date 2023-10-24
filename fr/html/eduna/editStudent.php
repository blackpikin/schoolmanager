<?php 
    include "includes/EdunaModel.php";
    $Eduna = new EdunaModel();
    $result = ''; $err = false;
    $student = $Eduna->GetSomeWithCriteria('students_students',['full_name', 'gender', 'date_of_birth', 'place_of_birth', 'guard_phone', 'guard_email', 'manual_matricule'],['id'=>$_GET['ref']]);
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $name = $Model->test_input($_POST['name']);
        $gender = $Model->test_input($_POST['gender']);
        $dob = $Model->test_input($_POST['dob']);
        $pob = $Model->test_input($_POST['pob']);
        $phone = $Model->test_input($_POST['phone']);
        $email = $Model->test_input($_POST['email']);	
        $adm = $Model->test_input($_POST['adm']);

        if (empty($name)){
            $err = true;
            $result = 'Please enter a name';
        }

        if (empty($gender)){
            $err = true;
            $result = 'Please select a gender';
        }

        if (empty($dob)){
            $err = true;
            $result = 'Please enter the date of birth';
        }

        if (empty($pob)){
            $err = true;
            $result = 'Please enter the place of birth';
        }

        if (empty($adm)){
            $err = true;
            $result = 'Please enter the Admission number';
        }

        if (!$err){
            $result = $Eduna->Update('students_students', 
            [
                'full_name' => $name, 'gender' => $gender, 'date_of_birth' => $dob, 'place_of_birth'=>$pob, 
                'guard_phone' => $phone, 'guard_email' => $email, 'manual_matricule' => $adm
            ],
            ['id' => $_GET['ref']
        ]);
        $student = $Eduna->GetSomeWithCriteria('students_students',['full_name', 'gender', 'date_of_birth', 'place_of_birth', 'guard_phone', 'guard_email', 'manual_matricule'],['id'=>$_GET['ref']]);
        }
    }
?>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-3">

    </div>
    <div class="col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]['EditStudent'] ?><label>
        </p>
    </div>
    <div class="col-xs-1">

    </div>
</div>
<label style="color:red;"><?=  $result ?></label>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-7 curved-box">
        <form action="" method="post">
            <label>Name:<sup>*</sup></label>
            <input type="text" value="<?= $student[0]['full_name'] ?>" name="name" class="form-control" placeholder="Enter the name of the student" required/>
            <br>
            <label>Gender:<sup>*</sup></label>
            <select name="gender" class="form-control" required>
                <option value="<?= $student[0]['gender'] ?>"><?= $student[0]['gender'] == 'M'? 'Male':'Female' ?></option>
                <option value="M">Male</option>
                <option value="F">Female</option>
            </select>
            <br>
            <label>Date of birth:<sup>*</sup></label>
            <input type="date" value="<?= $student[0]['date_of_birth'] ?>" name="dob" class="form-control" required/>
            <br>
            <label>Place of birth:<sup>*</sup></label>
            <input type="text" value="<?= $student[0]['place_of_birth'] ?>" name="pob" class="form-control" placeholder="e.g. Mamfe" required/>
            <br>
            <label>Phone number:</label>
            <input type="text" value="<?= $student[0]['guard_phone'] ?>" name="phone" class="form-control" placeholder="Enter the guardian's phone number"/>
            <br>
            <label>Email address:</label>
            <input type="email" value="<?= $student[0]['guard_email'] ?>" name="email" class="form-control" placeholder="Enter the guardian's email address"/>
            <br>
            <label>Admission number:</label>
            <input required type="text" value="<?= $student[0]['manual_matricule'] ?>" name="adm" class="form-control" placeholder="Enter the Admission number from the black log book"/>
            <br>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
    <div class="col-xs-3">

    </div>
</div>
