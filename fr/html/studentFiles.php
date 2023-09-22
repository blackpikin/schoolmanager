<?php 
$uploadOk = 1; $result ="";
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $docName = $Model->test_input($_POST['docName']);
        if(empty($docName)){
            $uploadOk = 0;
        }

        if (!empty($_FILES["picture"])){
            $target_dir = "./img/students/";
            $poster = $target_dir . basename($_FILES["picture"]["name"]);
            $imageFileType = pathinfo($poster,PATHINFO_EXTENSION);
    
            $check = getimagesize($_FILES["picture"]["tmp_name"]);
    
            if($check === false) {
                $uploadOk = 0;
                $pictureErr = "The file is not an image or it is too large";
            }
    
            if ($_FILES["picture"]["size"] > 8000000) {
                $pictureErr = "The file is too large. It must be at most 8 megabytes";
                $uploadOk = 0;
            }
    
            if($imageFileType != "GIF" && $imageFileType != "PNG" && $imageFileType != "JPG" && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                $pictureErr = "Only JPG, JPEG, PNG et GIF files are allowed";
                $uploadOk = 0;
            }

            $imageContent = file_get_contents($_FILES["picture"]["tmp_name"]);
            $imageString = base64_encode($imageContent);
    
            if ($uploadOk == 1){
                $data = array($docName, $imageString,  $imageFileType, date("Y:m:d"), $_GET['ref']);
                $result = $Model->UploadStudentFile($data);
            }
        }
    
    
    }
?>
<p style="color:red;"><?= $result ?></p>
<div class="row" style="margin-top: 50px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["StudentFiles"] ?></label>
        </p>
    </div>
    <div class="col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-xs-1">

    </div>
    <div class="col-xs-5">
          <div class="curved-box">
            <h4><?= $lang[$_SESSION['lang']]["UploadANewFile"] ?></h4>
                <form action="" method="post" enctype="multipart/form-data">
                <label><?= $lang[$_SESSION['lang']]["DocName"] ?></label>
                <input type="text" name="docName" class="form-control">
                <br>
                <label><?= $lang[$_SESSION['lang']]["FileImage"] ?></label>
                <input type="file" name="picture" class="form-control">
                <br>
                <button type="submit" class="btn btn-primary" ><?= $lang[$_SESSION['lang']]["Upload"] ?></button>
                </form>
          </div>
    </div>
    <div class="col-xs-5">
    <div class="curved-box">
            <h4><?= $lang[$_SESSION['lang']]["ExistingFiles"] ?></h4>
                <table class="table table-bordered table-responsive">
                    <tr>
                        <td><?= $lang[$_SESSION['lang']]["DocName"] ?></td>
                        <td><?= $lang[$_SESSION['lang']]["File"] ?></td>
                    </tr>
                    <?php 
                        $files = $Model->GetStudentFiles($_GET['ref']);
                        if(!empty($files)){
                            foreach ($files as $file){
                                ?>
                                <tr>
                                    <td><?= $file['doc_name'] ?></td>
                                    <td>
                                        <?php
                                             $data = base64_decode($file['doc_data']);
                                             $fichier = "./img/students/" . $file["doc_name"] . '.'.$file["data_ext"];
                                            $success = file_put_contents($fichier, $data);
                                            ?>
                                                 <a target="blank" href="<?= $fichier ?>"><?= $lang[$_SESSION['lang']]["Download"] ?> <?= $file["doc_name"] ?></a>
                                            <?php
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    
                    ?>
                </table>
          </div>
    </div>
    <div class="col-xs-1">

    </div>
</div>