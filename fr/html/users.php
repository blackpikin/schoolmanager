<?php
    $srch =""; 
    $results = "";
    $users = [];
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $srch = $Model->test_input($_POST['srch']);
    if (!empty($srch)){
        $users = $Model->SearchStaff($srch);
    }

    }else{
         $users = $Model->GetAllUsers();
    }
?>
<div class="row" style="margin-top: 10px;">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
            <h2 id="label1"><?= $lang[$_SESSION['lang']]["StaffSettings"] ?></h2>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-md-7 col-sm-7 col-xs-7">
        <button class="btn btn-primary button-width" onclick="GotoPage('staffSettings')"><?= $lang[$_SESSION['lang']]["NewStaff"] ?></button>   
    </div>
    <div class="col-md-5 col-sm-5 col-xs-5">

    </div>
</div>
<br>
<form action="" method="post">
    <div class="row">
    <div class="col-md-8 col-sm-8 col-xs-8">
    <input name="srch" style="height:55px;" type="text" value="<?= $srch ?>" placeholder="<?= $lang[$_SESSION['lang']]["SearchStaffPlaceHolder"] ?>" class="form-control" >
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <button type="submit" style="height:55px;width:55px;" class="btn btn-outline-warning fa fa-search"></button>
    </div>
    </div>
</form>
    <br>
<div class="row">
<div class="col-md-11 col-sm-11 col-xs-11">
<table class="table table-responsive table-bordered table-hover">
    <tr class="table-header">
        <td>#</td>
        <td><?= $lang[$_SESSION['lang']]["Name"] ?></td>
        <td><?= $lang[$_SESSION['lang']]["Role"] ?></td>
        <td><?= $lang[$_SESSION['lang']]["Phone"] ?></td>
        <td>E-mail</td>
        <td><?= $lang[$_SESSION['lang']]["Subject"] ?></td>
        <td><?= $lang[$_SESSION['lang']]["RegisteredOn"] ?></td>
        <td>Actions</td>
    </tr>
    <?php 
    if(!empty($users)){
        foreach ($users as $key => $user){
            ?>
            <tr class="normal-tr">
                <td><?= ++$key ?></td>
                <td><?= strToUpper($user['name']) ?></td>
                <td><?php 
                if($user['role'] == 'Admin'){
                    echo 'Admin';
                }elseif($user['role'] == 'Teacher'){
                    echo $lang[$_SESSION['lang']]["Teacher"];
                }elseif($user['role'] == 'Bursar'){
                    echo $lang[$_SESSION['lang']]["Bursar"];
                }elseif($user['role'] == 'Manager'){
                    echo $lang[$_SESSION['lang']]["Manager"];
                }
                 ?>
                 </td>
                <td><?= $user['phone'] ?></td>
                <td><?= strToLower($user['email']) ?></td>
                <td><?= strToUpper($user['subjects']) ?></td>
                <td>
                    <?php
                        $date = new DateTime($user['dateof']);

                    ?>
                    <?= date_format($date, "d-m-Y") ?>
                </td>
                <td>
                    <button title="<?= $lang[$_SESSION['lang']]["EditStaffInfo"] ?>" class="btn btn-outline-secondary fa fa-edit" onclick="GotoPage('modifyStaff&ref=<?= $user['id']?>')"></button>
                    <button title="<?= $lang[$_SESSION['lang']]["ViewStaffFiles"] ?>" class="btn btn-outline-secondary fa fa-file" onclick="GotoPage('staffFiles&ref=<?= $user['id']?>')"></button>
                    <button title="<?= $lang[$_SESSION['lang']]["AssignSubjects"] ?>" class="btn btn-outline-secondary fa fa-user" onclick="GotoPage('staffSubjects&ref=<?= $user['id']?>')"></button>
                    <button title="<?= $lang[$_SESSION['lang']]["StaffDays"] ?>" class="btn btn-outline-secondary fa fa-calendar" onclick="GotoPage('staffDays&ref=<?= $user['id']?>')"></button> 
                    <button title="<?= $lang[$_SESSION['lang']]["FillInMarks"] ?>" class="btn btn-outline-secondary fa fa-check" onclick="GotoPage('registermarks&ref=<?= $user['id']?>')"></button> 
                    <button title="<?= $lang[$_SESSION['lang']]["ResetPassword"] ?>" class="btn btn-outline-secondary fa fa-refresh" onclick="ResetUserPassword('<?= $user['id']?>')"></button> 
                </td>
            </tr>
            <?php
        }
    }   
    ?>
</table>
</div>
<div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>