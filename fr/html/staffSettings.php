<?php 
    
    $name = ""; $role = ""; $phone = ""; $email = ""; $subjects = ""; $err = 0;
    $result = "";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $name = $Model->test_input($_POST['nameOfStaff']);
        $role = $Model->test_input($_POST['role']);
        $phone = $Model->test_input($_POST['phone']);
        $email = $Model->test_input($_POST['email']);
        $subjects = $Model->test_input($_POST['subjects']);
        $sec = $Model->test_input(($_POST['sec']));
        if(!empty($sec)){
            $err = 1;
        }

        if (empty($name) || $name == ""){
            $err = 1;
            $result = "Enter the name of the staff";
        }

        if (empty($phone) || $phone == ""){
            $err = 1;
            $result = "Enter the phone number of the staff";
        }

        if (empty($email) || $email == ""){
            $err = 1;
            $result = "Enter the email address of the staff";
        }

        if (empty($subjects) || $subjects == ""){
            $err = 1;
            $result = "Enter the subject of the staff";
        }

        if ($err == 0){
            $lng = $_SESSION['lang'];
            $section = 0;
            if($lng == 'fr'){
                $section = 1;
            }
            $data = [$name, $role, $phone, $email, $subjects, $Model->DefaultUserPassword(), $section];
            $result = $Model->RegisterNewUser($data);
            if ($result == "User registered successfully"){
                $name = ""; $role = ""; $phone = ""; $email = ""; $subjects = ""; $err = 0;
            }
        }

    }
    

?>
<br>
<p style="color:red;"><?= $result ?></p>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-xs-8 col-xs-8">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-3">

            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <h2 id="label1">Add new Staff</h2>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3">

            </div>
        </div>
        <div class="curved-box">
        <form action="" method="post">
        <p>
            <label>Name of Staff</label>
            <input type="text" required="required" class="form-control" name="nameOfStaff" value="<?= $name ?>">
        </p>
    
        <p>
            <label>Role</label>
            <select class="form-control" name="role">
                <option value="Admin">Admin (Secondary)</option>
                <option value="Admin-p">Admin (Primary)</option>
                <option value="Teacher">Teacher(Secondary)</option>
                <option value="Primary-Teacher">Teacher(Primary)</option>
                <option value="Bursar">Bursar</option>
                <option value="Manager">Manager</option>
            </select>
        </p>
        
        <p>
            <label>Phone number</label>
            <input type="number" required="required" class="form-control" name="phone" value="<?= $phone ?>">
        </p>
    
        <p>
           <label>E-mail</label>
           <input type="email" required="required" class="form-control" name="email" value="<?= $email ?>">
        </p>
        
        <p>
           <label>Subjects</label>
           <input type="text" required="required" class="form-control" name="subjects" value="<?= $subjects ?>">
        </p>
        <p>
            <label class="sec">Sec</label>
            <input type="text" class="form-control sec" name="sec" value="">
        </p> 
        <button type="submit" class="btn btn-primary" name="save">Save</button>
        </form>
        </div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    </div>