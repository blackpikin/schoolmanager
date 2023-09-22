<?php 
    $user = $Model->GetUser($_GET['ref']);
    $name = $user[0]['name']; $role = $user[0]['role'];; $phone = $user[0]['phone']; 
    $email = $user[0]['email']; $subjects = $user[0]['subjects']; $err = 0;
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
            $result = "Enter the teacher's name";
        }

        if (empty($phone) || $phone == ""){
            $err = 1;
            $result = "Enter the teacher's phone";
        }

        if (empty($email) || $email == ""){
            $err = 1;
            $result = "Enter the teacher's email";
        }

        if (empty($subjects) || $subjects == ""){
            $err = 1;
            $result = "Enter the teacher's subjects";
        }

        if ($err == 0){
            $data = [$name, $role, $phone, $email, $subjects, $_GET['ref']];
            $result = $Model->UpdateUser($data);
            if ($result == "Utilisateur mis à jour avec succès"){
               //pass
            }
        }

    }
    

?>
<br>
<p style="color:red;"><?= $result ?></p>
<br>
<div class="row">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["EditStaffInfo"] ?></label>
        </p>
        <form action="" method="post">
        <p>
            <label><?= $lang[$_SESSION['lang']]["Name"] ?></label>
            <input type="text" required="required" class="form-control" name="nameOfStaff" value="<?= $name ?>">
        </p>
    
        <p>
            <label><?= $lang[$_SESSION['lang']]["Role"] ?></label>
            <select class="form-control" name="role">
                <?php $roles=['Admin'=>'Admin','Teacher'=>'Teacher(Secondary)','Bursar'=>'Bursar','Manager'=>'Manager', 'Primary-Teacher'=>'Teacher(Primary)' ] ?>
                <option value="<?= $role ?>"><?= $roles["$role"] ?></option>
                <option value="Admin">Admin</option>
                <option value="Teacher">Teacher(Secondary)</option>
                <option value="Primary-Teacher">Teacher(Primary)</option>
                <option value="Bursar"><?= $lang[$_SESSION['lang']]["Bursar"] ?></option>
                <option value="Manager"><?= $lang[$_SESSION['lang']]["Manager"] ?></option>
            </select>
        </p>
        
        <p>
            <label><?= $lang[$_SESSION['lang']]["Phone"] ?></label>
            <input type="number" required="required" class="form-control" name="phone" value="<?= $phone ?>">
        </p>
    
        <p>
           <label>E-mail</label>
           <input type="email" required="required" class="form-control" name="email" value="<?= $email ?>">
        </p>
        
        <p>
           <label><?= $lang[$_SESSION['lang']]["Subject"] ?></label>
           <input type="text" required="required" class="form-control" name="subjects" value="<?= $subjects ?>">
        </p>
        <p>
            <label class="sec">Sec</label>
            <input type="text" class="form-control sec" name="sec" value="">
        </p>
        <br>
        <button type="submit" class="btn btn-primary" name="save"><?= $lang[$_SESSION['lang']]["Save"] ?></button>
        </form>
    </div>
    <div class="col-xs-2">

    </div>
    </div>
