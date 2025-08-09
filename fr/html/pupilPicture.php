<?php 
$result = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (!empty($_FILES["picture"])){
            $target_dir = "./img/students/";
            $poster = $target_dir . basename($_FILES["picture"]["name"]);
            $uploadOk = 1;
            $imageFileType = pathinfo($poster,PATHINFO_EXTENSION);
    
            $check = getimagesize($_FILES["picture"]["tmp_name"]);
    
            if($check === false) {
                $uploadOk = 0;
                $pictureErr = "The file is not a picture or it is too large";
            }
    
            if ($_FILES["picture"]["size"] > 8000000) {
                $pictureErr = "The file is too large. It must be at most 8 megabytes";
                $uploadOk = 0;
            }
    
            if($imageFileType != "GIF" && $imageFileType != "PNG" && $imageFileType != "JPG" && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                $pictureErr = "Only JPG, JPEG, PNG et GIF file are allowed.";
                $uploadOk = 0;
            }

            $imageContent = file_get_contents($_FILES["picture"]["tmp_name"]);
            $imageString = base64_encode($imageContent);
    
            if ($uploadOk == 1){
                $data = array($imageString, $imageFileType, $_GET['ref']);
                $result = $Primodel->SetPupilPicture($data);
            }
        }
    
    
    }
?>
<p style="color:red;"><?= $result ?></p>
<div class="row" style="margin-top: 50px;">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
            <label id="label1"><?= $lang[$_SESSION['lang']]["SetStudentPhoto"] ?></label>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
</div>
<div class="row" style="margin-top: 50px;">
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
    <div class="col-md-10 col-sm-10 col-xs-10">
        <form action="" method="post" enctype="multipart/form-data">
            <label><?= $lang[$_SESSION['lang']]["SelectPicture"] ?></label>
            <input type="file" name="picture" class="form-control" required="required">
            <br>
            <button type="submit" class="btn btn-primary"><?= $lang[$_SESSION['lang']]["Save"] ?></button>
        </form>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>