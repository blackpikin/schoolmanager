<?php
    $template = $Model->GetAllWithCriteria('report_templates', ['selected' => 1]);
    $result = "";
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $t = $_POST['template'];
        $result = $Model->Update('report_templates', ['template' => $t, 'selected' => 1], ['id' => 1]);
        $template = $Model->GetAllWithCriteria('report_templates', ['selected' => 1]);
    }
?>
<br>
<div class="row" >
    <div class="col-md-3 col-sm-3 col-xs-3">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["cardTemplate"] ?></label>
        </p>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>
<div class="row" >
<div class="col-md-3 col-sm-3 col-xs-3">

</div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <span style="color:red"><?= $result ?></span>
        <form action="" method="post">
        <select name="template" class="form-control">
        <option value="<?= $template[0]['template'] ?>"><?= $lang[$_SESSION['lang']][$template[0]['template']] ?></option>
            <option value="default"><?= $lang[$_SESSION['lang']]["default"] ?></option>
            <option value="british"><?= $lang[$_SESSION['lang']]["british"] ?></option>
        </select>
    </div>
    <div class="col-md-5 col-sm-5 col-xs-5">
        <button type="submit" class="btn btn-primary"><?= $lang[$_SESSION['lang']]["Save"] ?></button>
</form>
    </div>
    </div>
</div>