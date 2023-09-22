<?php 

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
}

?>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-8">
        <p>
        <h3 id="label1"><?= $lang[$_SESSION['lang']]["ImportStudents"] ?></h3>
        <br>
        <br>
        <form action="" method="post" name="upload_excel" enctype="multipart/form-data">
            <div class="control-group">
                <div class="control-label">
                    <label><?= $lang[$_SESSION['lang']]["SelectCSV"] ?></label>
                </div>
                <div class="controls">
                    <input type="file" name="file" id="file" class="form-control">
                </div>
                <br>
            </div>
            <div class="control-group">
                <div class="controls">
                <button type="submit" id="submit" name="Import" class="btn btn-primary" data-loading-text="Loading..."><?= $lang[$_SESSION['lang']]["Upload"] ?></button>
                </div>
            </div>
        </form>
        </p>
    </div>
    <div class="col-xs-2">

    </div>
</div>

			