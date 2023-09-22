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
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["StaffSettings"] ?></label>
        </p>
    </div>
    <div class="col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-xs-7">
        <button class="btn btn-primary button-width" onclick="GotoPage('staffSettings')"><?= $lang[$_SESSION['lang']]["NewStaff"] ?></button>   
    </div>
    <div class="col-xs-5">

    </div>
</div>
<br>
<form action="" method="post">
    <div class="row">
    <div class="col-xs-8">
    <input name="srch" style="height:55px;" type="text" value="<?= $srch ?>" placeholder="<?= $lang[$_SESSION['lang']]["SearchStaffPlaceHolder"] ?>" class="form-control" >
    </div>
    <div class="col-xs-4">
        <button type="submit" style="height:55px;width:55px;" class="btn btn-warning glyphicon glyphicon-search"></button>
    </div>
    </div>
    </form>
    <br>

<table class="table tabel-responsive table-bordered">
    <tr class="table-header">
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
        foreach ($users as $user){
            ?>
            <tr class="normal-tr">
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
                    <button title="<?= $lang[$_SESSION['lang']]["EditStaffInfo"] ?>" class="glyphicon glyphicon-edit" onclick="GotoPage('modifyStaff&ref=<?= $user['id']?>')"></button>
                    <button title="<?= $lang[$_SESSION['lang']]["ViewStaffFiles"] ?>" class="glyphicon glyphicon-file" onclick="GotoPage('staffFiles&ref=<?= $user['id']?>')"></button>
                    <button title="<?= $lang[$_SESSION['lang']]["AssignSubjects"] ?>" class="glyphicon glyphicon-user" onclick="GotoPage('staffSubjects&ref=<?= $user['id']?>')"></button>
                    <button title="<?= $lang[$_SESSION['lang']]["StaffDays"] ?>" class="glyphicon glyphicon-calendar" onclick="GotoPage('staffDays&ref=<?= $user['id']?>')"></button> 
                    <button title="<?= $lang[$_SESSION['lang']]["FillInMarks"] ?>" class="glyphicon glyphicon-check" onclick="GotoPage('registermarks&ref=<?= $user['id']?>')"></button> 
                    <button title="<?= $lang[$_SESSION['lang']]["ResetPassword"] ?>" class="glyphicon glyphicon-refresh" onclick="ResetUserPassword('<?= $user['id']?>')"></button> 
                </td>
            </tr>
            <?php
        }
    }   
    ?>
</table>