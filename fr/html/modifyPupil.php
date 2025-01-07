<?php
    $student = $Primodel->GetPupil($_GET['ref']);
    $name = $student[0]['name']; $gender = $student[0]['gender']; 
    $dob = $student[0]['dob']; $pob = $student[0]['pob'];
    $gName = $student[0]['guardian'];
     $gNum = $student[0]['guardian_number'];
     $class= $Model->GetAClass($Model->GetClassId($Model->GetCurrentYear()[0]['id'], $_GET['ref']));
      $gEmail = $student[0]['guardian_email']; $gAddress = $student[0]['guardian_address']; 
      $motherName = $student[0]['mother_name']; $fatherName = $student[0]['father_name'];
      $admNum = $student[0]['adm_num'];
    $err = false; $result = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $name = $Model->test_input($_POST['name']);
        $gender = $Model->test_input($_POST['gender']);
        $dob = $Model->test_input($_POST['dateOfBirth']);
        $pob = $Model->test_input($_POST['placeOfBirth']);
        $class = $Model->test_input($_POST['class']);
        $gName = $Model->test_input($_POST['guardiansName']);
        $gNum = $Model->test_input($_POST['guardiansNumber']);
        $gEmail = $Model->test_input($_POST['guardiansEmail']);
        $gAddress = $Model->test_input($_POST['guardiansAddress']);
        $motherName = $Model->test_input($_POST['mother']);
        $fatherName = $Model->test_input($_POST['father']);
        $admNum = $Model->test_input($_POST['admNum']);
        
        if (empty($name)){
            $err = true;
            $result = "Enter students full name";
        }

        if (empty($gender)){
            $err = true;
            $result = "Select gender";
        }

        if (empty($class)){
            $err = true;
            $result = "Select class";
        }

        if (empty($gName)){
            $err = true;
            $result = "Enter the guardian's name";
        }
        if (empty($gNum)){
            $err = true;
            $result = "Enter the guardian's phone number";
        }

        if (!$err){
            $code = $_GET['ref'];
            $pupdata = [strToUpper($name), $gender,$dob, strToUpper($pob), StrToUpper($gName), $gNum, $gEmail, $gAddress,strToUpper($motherName), strToUpper($fatherName), $admNum, $code];
            $result = $Primodel->UpdatePupil($pupdata);
            if($result = "Successful"){
                $data = [$class, $Model->GetCurrentYear()[0]['id'], $code];
                $result = $Model->UpdateStudentToClass($data);
                $curr_class_id = $Model->GetStudentCurrentClass($student_code);
                $result = $Model->UpdateFeesClassChange($code, $Model->GetCurrentYear()[0]['id'], $curr_class_id);
                echo '<script>window.location="index.php?p=pupils"</script>';
            }
        }
    }

?>
<br>
<p style="color:red;"><?= $result ?></p>
<br>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <label id="label1"><?= $lang[$_SESSION['lang']]["ModifyPupilInfo"] ?></label>
        <form action="" method="post">
        <div class="curved-box">
        <p>
                <label><?= $lang[$_SESSION['lang']]["NameAsOnBC"] ?></label>
                <input type="text" required="required" class="form-control" name="name" value="<?= $name ?>">
        </p>
        <p>
            <div class="space2"><label><?= $lang[$_SESSION['lang']]["Gender"] ?></label></div>
            <select  class="form-control" name="gender">
                <option value="<?= $gender ?>"><?= $gender == "M" ?  $lang[$_SESSION['lang']]["Male"]  : $lang[$_SESSION['lang']]["Female"] ?></option>
                <option value="M"><?= $lang[$_SESSION['lang']]["Male"] ?></option>
                <option value="F"><?= $lang[$_SESSION['lang']]["Female"] ?></option>
            </select>
        </p>
        <p>
            <label><?= $lang[$_SESSION['lang']]["DOB"] ?></label>
            <input type="date" required="required"  class="form-control" name="dateOfBirth" value="<?= $dob ?>">
        <p>
            <label><?= $lang[$_SESSION['lang']]["POB"] ?></label>
            <input type="text" required="required"  class="form-control" name="placeOfBirth" value="<?= $pob ?>">
        </p>
        <p>
                <label><?= $lang[$_SESSION['lang']]["Class"] ?></label>
                <select  class="form-control" name="class">
                    <option value="<?= $class[0]['id'] ?>"><?= $class[0]['general_name'] ?> <?= $class[0]['sub_name'] ?></option>
                    <?php
                        $classes = $Model->GetAllClasses($section);
                        foreach($classes as $class){
                        ?>
                            <option value="<?= $class['id'] ?>"><?= $class['general_name'] ?> <?= $class['sub_name'] ?></option>
                        <?php
                        }
                    ?>
                </select>
        </p>
        </div>
        <div class="curved-box">
        <p>
            <label><?= $lang[$_SESSION['lang']]["GuardianName"] ?></label>
            <input type="text" required="required"  class="form-control" name="guardiansName" value="<?= $gName ?>">
        </p>
        <p>
            <label><?= $lang[$_SESSION['lang']]["GuardianPhone"] ?></label>
            <input type="number" required="required" maxlength="9" value="<?= $gNum ?>"  class="form-control" name="guardiansNumber">
        </p>
        </p>
        <p>
            <label><?= $lang[$_SESSION['lang']]["GuardianEmail"] ?></label>
            <input type="email" required="required" value="<?= $gEmail ?>"  class="form-control" name="guardiansEmail">
        </p>
        <p>
            <label><?= $lang[$_SESSION['lang']]["GuardianAddress"] ?></label>
            <input type="text" required="required" value="<?= $gAddress ?>"  class="form-control" name="guardiansAddress">
        </p>
        </div>

        <div class="curved-box">
        <p>
            <label><?= $lang[$_SESSION['lang']]["MotherName"] ?></label>
            <input type="text" required="required" value="<?= $motherName ?>"  class="form-control" name="mother">
        </p>
        <p>
            <label><?= $lang[$_SESSION['lang']]["FatherName"] ?></label>
            <input type="text" required="required" value="<?= $fatherName ?>"  class="form-control" name="father">
        </p>
        <p>
            <label><?= $lang[$_SESSION['lang']]["AdmissionNum"] ?></label>
            <input type="text" required="required" value="<?= $admNum ?>"  class="form-control" name="admNum">
        </p>
        </div>
        
        <br>
            <button type="submit" class="btn btn-primary"><?= $lang[$_SESSION['lang']]["Save"] ?></button>
        </form>
            </div>
    </div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    </div>