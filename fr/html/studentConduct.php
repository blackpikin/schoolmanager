<?php
$student_code = $_GET['ref'];
$data = $Model->GetStudent($student_code, $section);

if (empty($data)){
    $data = $Primodel->GetPupil($student_code);
}

$type = ""; $date = ""; $title = ""; $desc = ""; $poster = ""; $pictureErr = ""; $uploadOk = 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    /*
    if (!empty($_FILES["picture"])){
        $target_dir = "./img/students/";
        $poster = $target_dir . basename($_FILES["picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($poster,PATHINFO_EXTENSION);

        $check = getimagesize($_FILES["picture"]["tmp_name"]);

        if($check === false) {
            $uploadOk = 0;
            $pictureErr = "File is not an image or is too large";
        }

        if ($_FILES["picture"]["size"] > 8000000) {
            $pictureErr = "The picture file is too large. It must be less or equal to 8 Mb";
            $uploadOk = 0;
        }

        if($imageFileType != "GIF" && $imageFileType != "PNG" && $imageFileType != "JPG" && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $pictureErr = "Only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        $imageContent = file_get_contents($_FILES["picture"]["tmp_name"]);
        $imageString = base64_encode($imageContent);
        */
        if ($uploadOk == 1){
            $type = $_POST['type'];
            $date = $_POST['date']; 
            $title = $Model->test_input($_POST['title']); 
            $desc = $Model->test_input($_POST['desc']);
            $pictureErr = $Model->NewConduct($type, $date, $title, $desc, '', '', $_GET['ref']);
        }
    //}
}

?>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-1">

    </div>
    <div class="col-xs-10">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["Conduct"] ?>/<?= $lang[$_SESSION['lang']]["Achifment"] ?>  - <?= $data[0]['name'] ?></label>
        </p>
    </div>
    <div class="col-xs-1">

    </div>
</div>

<div class="row" style="margin-top: 10px;">
    <div class="col-xs-1">

    </div>
    <div class="col-xs-10">
    <div class="curved-box">
        <span style="color:red;"><?= $pictureErr ?></span>
    <form method="post" action="">
        <label>Type</label><br>
        <input type="radio" name="type" value="conduct"> <?= $lang[$_SESSION['lang']]["Conduct"] ?>
        &nbsp;&nbsp;
        <input type="radio" name="type" value="achieve"> <?= $lang[$_SESSION['lang']]["Achifment"] ?>
        <br>
        <label>Date</label>
        <input type="date" value="" name="date" placeholder="Enter the date" class="form-control" required>
        <br>
        <label><?= $lang[$_SESSION['lang']]["Title"] ?></label>
        <input type="text" value="" name="title" placeholder="Enter the title" class="form-control" required>
        <br>
        <label>Description</label>
        <textarea  rows="8" name="desc" placeholder="Description" class="form-control" required> </textarea>
        <br>
        <!--
        <label>Photo</label>
        <input type="file" name="picture" class="form-control" required>
        <br>
        -->
        <button type="submit" class="btn btn-primary"><?= $lang[$_SESSION['lang']]["Save"] ?></button>
    </form>
</div>
    </div>
    <div class="col-xs-1">

    </div>
</div>

