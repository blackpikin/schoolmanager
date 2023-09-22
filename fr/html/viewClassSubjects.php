<?php
$subjects = $Model->ViewClassSubjects($_GET['ref']);
?>
<br>
<label id="label1"><?= $lang[$_SESSION['lang']]['SubjectsFor']. ' '.$Model->GetAClass($_GET['ref'])[0]['general_name'].' '.$Model->GetAClass($_GET['ref'])[0]['sub_name']?></label>
<br>
<div class="row">
    <div class="col-xs-1">

    </div>
    <div class="col-xs-10">
        <table class="table tabel-responsive table-bordered">
        <tr class="table-header">
            <td><?= $lang[$_SESSION['lang']]['Subject'] ?></td>
            <td>Coefficient</td>
            <td>Actions</td>
        </tr>
        <?php 
            foreach ($subjects as $subject){
                ?>
                <tr class="normal-tr">
                    <td><?= $subject['subject'] ?></td>
                    <td><?= $subject['coef'] ?></td>
                    <td>
                        <button title="Add a subject" class="glyphicon glyphicon-plus" onclick="GotoPage('subjectSettings')"></button>
                        <button title="Modify this subject" class="glyphicon glyphicon-pencil" onclick="GotoPage('modifySubject&ref=<?= $_GET['ref'] ?>&title=<?= $subject['subject'] ?>')"></button>
                    </td>
                </tr>
                <?php
            }
        ?>
        </table>
    </div>
    <div class="col-xs-1">

    </div>
</div>