<?php 
    $uploadOk = 1; $result ="";
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $docName = $Model->test_input($_POST['docName']);
        $sec = $Model->test_input(($_POST['sec']));
        if(!empty($sec)){
            $uploadOk = 0;
        }
        if(empty($docName)){
            $uploadOk = 0;
        }

        if (!empty($_FILES["picture"])){
            $target_dir = "./img/staff/";
            $poster = $target_dir . basename($_FILES["picture"]["name"]);
            $imageFileType = pathinfo($poster,PATHINFO_EXTENSION);
    
            $check = getimagesize($_FILES["picture"]["tmp_name"]);
    
            if($check === false) {
                $uploadOk = 0;
                $pictureErr = "Le fichier n'est pas une image ou est trop volumineux";
            }
    
            if ($_FILES["picture"]["size"] > 8000000) {
                $pictureErr = "Le fichier image est trop volumineux. Il doit être inférieur ou égal à 8 Mo";
                $uploadOk = 0;
            }
    
            if($imageFileType != "GIF" && $imageFileType != "PNG" && $imageFileType != "JPG" && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                $pictureErr = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                $uploadOk = 0;
            }

            $imageContent = file_get_contents($_FILES["picture"]["tmp_name"]);
            $imageString = base64_encode($imageContent);
    
            if ($uploadOk == 1){
                $data = array($docName, $imageString,  $imageFileType, date("Y:m:d"), $_GET['ref']);
                $result = $Model->UploadStaffFile($data);
            }
        }
    
    
    }
?>
<p style="color:red;"><?= $result ?></p>
<div class="row" style="margin-top: 50px;">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["StaffFiles"] ?></label>
        </p>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
    <div class="col-md-5 col-sm-5 col-xs-5">
          <div class="curved-box">
            <h4><?= $lang[$_SESSION['lang']]["UploadNewFile"] ?></h4>
                <form action="" method="post" enctype="multipart/form-data">
                <label><?= $lang[$_SESSION['lang']]["DocName"] ?></label>
                <input type="text" name="docName" class="form-control">
                <br>
                <label><?= $lang[$_SESSION['lang']]["File"] ?></label>
                <input type="file" name="picture" class="form-control">
                <br>
                <p>
                    <label class="sec">Sec</label>
                    <input type="text" class="form-control sec" name="sec" value="">
                </p> 
                <button type="submit" class="btn btn-primary" ><?= $lang[$_SESSION['lang']]["Upload"] ?></button>
                </form>
          </div>
    </div>
    <div class="col-md-5 col-sm-5 col-xs-5">
    <div class="curved-box">
            <h4><?= $lang[$_SESSION['lang']]["ExistingFiles"] ?></h4>
                <table class="table table-bordered table-responsive">
                    <tr>
                        <td><?= $lang[$_SESSION['lang']]["DocName"] ?></td>
                        <td><?= $lang[$_SESSION['lang']]["DocFile"] ?></td>
                    </tr>
                    <?php 
                        $files = $Model->GetStaffFiles($_GET['ref']);
                        if(!empty($files)){
                            foreach ($files as $file){
                                ?>
                                <tr class="normal-tr">
                                    <td><?= $file['doc_name'] ?></td>
                                    <td>
                                        <?php
                                             $data = base64_decode($file['doc_data']);
                                             $fichier = "./img/staff/" . $file["doc_name"] . '.'.$file["data_ext"];
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
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>