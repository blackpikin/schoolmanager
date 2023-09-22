<?php
/**
 * Created by PhpStorm.
 * User: Halsey
 * Date: 28/03/2020
 * Time: 18:24
 */

if (isset($_SESSION['username']) && $_SESSION['username'] != ""){
    echo '<script>window.location="index.php?p=home"</script>';
}

////////////////////////
///
$username = "";
$password = "";
$result = "";

if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $username = $Model->test_input($_POST['username']);
    $password = $Model->test_input($_POST['password']);

    if ($Model->LoginUser($username, $password) == true){
        $user = $Model->StartUserSession($username);

        $_SESSION['id'] = $user[0]['id'];
        $_SESSION['username'] = $user[0]['name'];
        $_SESSION['role'] =$user[0]['role'];
        $_SESSION['timer'] = time();
        $username = "";
        $password = "";
        echo '<script>window.location="./?p=home"</script>';
    }else{
        $result = "Invalid username or password";
    }


}
?>
<div id="firstline">
    <label style="color:red"><?= isset($_GET['sub']) ? $_GET['sub'] : "" ?></label>
    <br>
    <div class="row">
        <div class="col-md-2 col-sm-2 hidden-xs">

        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h4 style="color: red;"><?php echo $result ?></h4>
            <form action="" method="post">
                <h4 id="label1" style="text-align:center;"><?= $lang[$_SESSION['lang']]["Login"] ?></h4>
                <div class="curved-box">
                <label style="color: #74b3d1;font-weight: bold"><?= $lang[$_SESSION['lang']]["Username"] ?></label>
                <input type="text" value="<?php echo $username ?>" name="username" class="form-control" required="required" placeholder="<?= $lang[$_SESSION['lang']]["UsernamePlaceHolder"] ?>" />
                <br>
                <label style="color: #74b3d1;font-weight: bold"><?= $lang[$_SESSION['lang']]["Password"] ?></label>
                <input type="password" value="<?php echo $password ?>" name="password" required="required" class="form-control" placeholder="<?= $lang[$_SESSION['lang']]["PasswordPlaceHolder"] ?>" />
                <br>
                <button class="btn btn-primary"><?= $lang[$_SESSION['lang']]["Log in"] ?></button>
                </div>
                
            </form>
            <br>
            <a href="./?p=forgottenPw" ><?= $lang[$_SESSION['lang']]["I forgot my password"] ?></a>
            &nbsp;&nbsp;||&nbsp;&nbsp;
            <a style="color:white; background-color:orangered;padding:5px;border-radius:8px;" href="../" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["ChangeSubsystem"] ?></a>
            <hr>
            <span style="color:white; background-color:blue;padding:5px;border-radius:8px;" onclick="GotoPage('viewchild')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["ForParents"] ?></span>
        </div>
        <div class="col-md-4 col-sm-4 hidden-xs">

        </div>
    </div>
</div>

