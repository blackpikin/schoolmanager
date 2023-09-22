<?php
    $lng = $_SESSION['lang'];
    $section = 0;
    if($lng == 'fr'){
        $section = 1;
    }
?>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["ClassSettings"] ?></label>
        </p>
    </div>
    <div class="col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-8">
        <button class="btn btn-primary button-width" onclick="GotoPage('primaryClassSettings')"><?= $lang[$_SESSION['lang']]["AddNewClass"] ?></button>   
    </div>
    <div class="col-xs-2">

    </div>
</div>
<br>
<br>
<?php $classes = $Primodel->GetAllPrimaryClasses(); ?>
<table class="table tabel-responsive table-bordered">
    <tr class="table-header">
        <td><?= $lang[$_SESSION['lang']]["Name"] ?></td>
        <td><?= $lang[$_SESSION['lang']]["subName"] ?></td>
        <td>Actions</td>
    </tr>
    <?php 
        foreach ($classes as $class){
            ?>
            <tr class="normal-tr">
                <td><?= $class['general_name'] ?></td>
                <td><?= $class['sub_name'] ?></td>
                <td>
                    <button title="<?= $lang[$_SESSION['lang']]["EditClass"] ?>" class="glyphicon glyphicon-edit" onclick="GotoPage('modifyClass&ref=<?= $class['id']?>')"></button>
                    <button title="<?= $lang[$_SESSION['lang']]["viewClassSubjects"] ?>" class="glyphicon glyphicon-list" onclick="GotoPage('viewClassSubjects&ref=<?= $class['id'] ?>')"></button>
                </td>
            </tr>
            <?php
        }
    ?>
</table>