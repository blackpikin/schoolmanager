<?php 
    $name = ""; $motto = ""; $email =""; $phone = ""; $website = ""; $pobox = ""; $err= false;
    $result = "";
    $info = $Model->GetSchoolInfo(2);
    if(!empty($info)){
        $name = $info[0]['name']; 
        $motto = $info[0]['motto']; 
        $email =$info[0]['email']; 
        $phone = $info[0]['phone']; 
        $website = $info[0]['website']; 
        $pobox = $info[0]['pobox'];
    }else{
        $name = ""; $motto = ""; $email =""; $phone = ""; $website = ""; $pobox = "";
    }

    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $name = $Model->test_input($_POST['nameOfSchool']);
        $motto = $Model->test_input($_POST['mottoOfSchool']);
        $email = $Model->test_input($_POST['email']);
        $phone = $Model->test_input($_POST['phone']);
        $website = $Model->test_input($_POST['website']);
        $pobox = $Model->test_input($_POST['pobox']);
        $sec = $Model->test_input(($_POST['sec']));

        if(!empty($sec)){
            $err = true;
        }

        if(empty($name)){
            $err = true;
            $result = "Enter the name of the school";
        }

        if(empty($motto)){
            $err = true;
            $result = "Enter the Motto of the school";
        }

        if(empty($phone)){
            $err = true;
            $result = "Phone number of the school";
        }

        if(empty($pobox)){
            $err = true;
            $result = "Enter the postal address of the school";
        }

        if(!$err){
            $data = [$name, $motto, $email, $phone, $website, $pobox, 2];
            if(empty($Model->ContentExists('school_info', 'id', 2))){
                $result = $Model->NewSchoolInfo($data);
            }else{
                $result = $Model->UpdateSchoolInfo($data);
            }
            
            
        }

    }


?>
<br>
<p style="color:red;"><?= $result ?></p>
<br>
<div class="row">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-8">
            <p style="text-align:center;">
                <label id="label1"><?= $lang[$_SESSION['lang']]["SchoolSettings"] ?></label>
            </p>
        <div class="curved-box">
            <form action="" method="post">
            <p>
               <label><?= $lang[$_SESSION['lang']]["SchoolName"] ?></label>
               <input type="text" value="<?= $name ?>" required="required" class="form-control" name="nameOfSchool">
            </p>
            
            <p>
                <label><?= $lang[$_SESSION['lang']]["SchoolMotto"] ?></label>
                <input type="text" required="required" class="form-control" name="mottoOfSchool" value="<?= $motto ?>">
            </p>
            <p>
               
            </p>
            <p>
               <label><?= $lang[$_SESSION['lang']]["SchoolEmail"] ?></label>
               <input type="email" value="<?= $email ?>" required="required" class="form-control" name="email">
            </p>
            
            <p>
                <label><?= $lang[$_SESSION['lang']]["SchoolPhone"] ?></label>
                <input type="number" required="required" class="form-control" name="phone" value="<?= $phone ?>">
            </p>
        
            <p>
               <label><?= $lang[$_SESSION['lang']]["Website"] ?></label> 
               <input type="text" required="required" class="form-control" name="website" value="<?= $website ?>">
            </p>
            <p>
               <label><?= $lang[$_SESSION['lang']]["pobox"] ?></label> 
               <input type="text" required="required" class="form-control" name="pobox" value="<?= $pobox ?>">
            </p>
            <p>
                <label class="sec">Sec</label>
               <input type="text" class="form-control sec" name="sec" value="">
            </p>
            <button class="btn btn-primary"><?= $lang[$_SESSION['lang']]["Save"] ?></button>
            </form>
        </div>
    </div>
    <div class="col-xs-2">

    </div>
</div>
