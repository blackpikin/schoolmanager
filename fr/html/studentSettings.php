<?php
    $name = ""; $gender = "";$dob = ""; $pob = "";$class = "";$gName = ""; $gNum = ''; $gEmail = ""; $gAddress = ""; 
    $err = false; $result = ""; $motherName = ""; $fatherName = ""; $admNum = "";
    
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
        $sec = $Model->test_input(($_POST['sec']));
        if(!empty($sec)){
            $err = true;
        }
        
        if (empty($name)){
            $err = true;
            $result = "Enter student's full name";
        }

        if (empty($gender)){
            $err = true;
            $result = "Select the student's gender";
        }

        if (empty($class)){
            $err = true;
            $result = "Select the student's class";
        }

        if (empty($gender)){
            $err = true;
            $result = "Select the student's gender";
        }

        if (empty($gName)){
            $err = true;
            $result = "Enter the name of the student's guardian";
        }
        if (empty($gNum)){
            $err = true;
            $result = "Enter the guardian's phone number";
        }

        if (!$err){
            $code = $Model->getToken(7);
            $data = [strToUpper($name), $gender,$dob, strToUpper($pob), strToUpper($gName), $gNum, $gEmail, $gAddress, $code, strToUpper($motherName), strToUpper($fatherName), $admNum, $section];
            $result = $Model->RegisterNewStudent($data);
            if($result = "Successful"){
                $data =[$code, $Model->GetCurrentYear()[0]['id'], $class ];
                $result = $Model->RegisterStudentToClass($data);
                $result = $Model->NewFeePayment($code, $Model->GetCurrentYear()[0]['id'], $class, 0, date('Y-m-d'), date('m-Y'), date('Y'), $_SESSION['id']);
                $name = ""; $gender = "";$dob = ""; $pob = "";$class = "";$gName = ""; $gNum = ''; $gEmail = ""; $gAddress = ""; $admNum = "";
                $motherName = ""; $fatherName = "";
            }
        }
    }

?>
<br>
<p style="color:red;"><?= $result ?></p>
<br>
<div class="row">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
            <label id="label1"><?= $lang[$_SESSION['lang']]["RegisterNewStudent"] ?></label>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">    
        <form action="" method="post">
        <div class="curved-box">
        <p>
                <label><?= $lang[$_SESSION['lang']]["NameAsOnBC"] ?></label>
                <input type="text" required="required" class="form-control" name="name" value="<?= $name ?>">
        </p>
        <p>
            <div class="space2"><label><?= $lang[$_SESSION['lang']]["Gender"] ?></label></div>
            <select  class="form-control" name="gender">
                <option value=""><?= $lang[$_SESSION['lang']]["Choose one"] ?></option>
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
                    <option value=""><?= $lang[$_SESSION['lang']]["Choose one"] ?></option>
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

        <p>
            <label class="sec">Sec</label>
            <input type="text" class="form-control sec" name="sec" value="">
            </p>  
            <button type="submit" class="btn btn-primary"><?= $lang[$_SESSION['lang']]["Save"] ?></button>
        </form>
        <br>
        <br>
    <button onclick="GotoPage('importStudents')" class="btn btn-warning"><?= $lang[$_SESSION['lang']]["ImportStudents"] ?></button>
            </div>
    </div>
    
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    </div>
