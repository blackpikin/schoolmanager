<?php
$user_id = $_SESSION['id'];
$result = ""; $err = false;
$oldp = ""; $newp = ""; $cnewp = "";
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $oldp = $_POST['oldp'];
    $newp = $_POST['newp'];
    $cnewp = $_POST['cnewp'];
    $sec = $Model->test_input(($_POST['sec']));
        if(!empty($sec)){
            $err = true;
        }

    if($newp !== $cnewp){
        $err = true;
        $result = "New password must match with the confirmation";
    }

    if ($Model->GetUserPassword($user_id) != $Model->HashPassword($oldp)){
        $err = true;
        $result = "Invalid old password";
    }


    if(!$err){
        $result = $Model->UpdateUserPassword($user_id, $Model->HashPassword($newp));

        if($result == "Password updated successfully"){
            session_destroy();
            echo '<script>window.location="index.php?p=logout"</script>';
        }
    }

}


?>
<div class="row" style="margin-top: 10px;">
    <div class="col-md-3 col-sm-3 col-xs-3">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["Change your password"] ?></label>
        </p>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>

<div class="row" style="margin-top: 10px;">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-7 col-sm-7 col-xs-7">
    <div class="curved-box">
        <span style="color:red; font-weight:bold;"><?= $result ?></span>
        <form action="" method="post">
            <label><?= $lang[$_SESSION['lang']]["Current password"] ?></label>
            <input type="password" class="form-control" required="required" name="oldp" value="<?= $oldp ?>" >
                <br>
            <label><?= $lang[$_SESSION['lang']]["New password"] ?></label>
            <input type="password" class="form-control" required="required" name="newp" value="<?= $newp ?>" >
            <br>
            <label><?= $lang[$_SESSION['lang']]["Confirm new password"] ?></label>
            <input type="password" class="form-control" required="required" name="cnewp" value="<?= $cnewp ?>" >
            <br>
            <p>
                <label class="sec">Sec</label>
               <input type="text" class="form-control sec" name="sec" value="">
            </p> 
            <button type="submit" class="btn btn-primary"><?= $lang[$_SESSION['lang']]["Change the password"] ?></button>
            <br>
            <br>
            <span><?= $lang[$_SESSION['lang']]['password change msg'] ?></span>
        </form>
    </div>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-3">

    </div>
</div>
