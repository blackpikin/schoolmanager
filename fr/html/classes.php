<?php
    $lng = $_SESSION['lang'];
    $section = 0;
    if($lng == 'fr'){
        $section = 1;
    }
?>
<div class="row" style="margin-top: 10px;">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["ClassSettings"] ?></label>
        </p>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <button class="btn btn-primary button-width" onclick="GotoPage('classSettings')"><?= $lang[$_SESSION['lang']]["AddNewClass"] ?></button>   
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
</div>
<br>
<br>
<?php $classes = $Model->GetAllClasses($section); ?>
<table class="table tabel-responsive table-bordered">
    <tr class="table-header">
        <td><?= $lang[$_SESSION['lang']]["Name"] ?></td>
        <td><?= $lang[$_SESSION['lang']]["subName"] ?></td>
        <td>Cycle</td>
        <td>Actions</td>
    </tr>
    <?php 
        foreach ($classes as $class){
            ?>
            <tr class="normal-tr">
                <td><?= $class['general_name'] ?></td>
                <td><?= $class['sub_name'] ?></td>
                <td>
                    <?php
                        if($class['cycle'] == 'FIRST'){
                            echo $lang[$_SESSION['lang']]['FIRST'].' CYCLE';
                        }else{
                            echo $lang[$_SESSION['lang']]['SECOND'].' CYCLE';
                        }
                     ?>
                </td>
                <td>
                    <button title="<?= $lang[$_SESSION['lang']]["EditClass"] ?>" class="btn btn-outline-primary fa fa-edit" onclick="GotoPage('modifyClass&ref=<?= $class['id']?>')"></button>
                    <button title="<?= $lang[$_SESSION['lang']]["viewClassSubjects"] ?>" class="btn btn-outline-primary fa fa-list" onclick="GotoPage('viewClassSubjects&ref=<?= $class['id'] ?>')"></button>
                </td>
            </tr>
            <?php
        }
    ?>
</table>