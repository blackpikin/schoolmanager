<?php 
$lng = $_SESSION['lang'];
$section = 0;
if($lng == 'fr'){
    $section = 1;
}
    $classes = ""; $subject = ""; $msg = ""; $err = false; $result = [];
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $classes = $_POST['classes'];
        $subject = $_POST['subject'];
        $msg = $_POST['msg'];
        $sec = $Model->test_input(($_POST['sec']));
        if(!empty($sec)){
            $err = true;
        }

        if($classes == ""){
            $err = true;
        }

        if($subject == ""){
            $err = true;
        }

        if($msg == ""){
            $err = true;
        }

        $studentEmails = $Model->GuardianEmails($classes);

        foreach($studentEmails as $email){
            array_push($result, $Model->SendLetter($subject, $msg, $email));
        }
        
    }

?>
<div class="row">
    <div class="col-md-3 col-sm-3 col-xs-3">

    </div>
    <div class="col-md-6 col-sm-6 col-xs-6">
        <h2 id="label1"><?= $lang[$_SESSION['lang']]['Send Email to parents'] ?></h2>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-3">

    </div>
</div>
<div class="row">
    <div class="col-md-7 col-sm-7 col-xs-7">
        <div class="curved-box">
            <form action="" method="post">
            <h5><?= $lang[$_SESSION['lang']]['Select the class'] ?>:</h5>
            <?php 
            $classes = $Model->GetAllClasses($section);
            if(!empty($classes)){
                foreach($classes as $class){
                    ?>
                    <input type="checkbox" value="<?= $class['id'] ?>" name="classes[]">
                        <?php 
                             if($class['general_name'] != $class['sub_name']){
                                echo $class['general_name'].' '.$class['sub_name'].'<br>';
                             }else{
                                 echo $class['general_name'].'<br>';
                             }
                        ?>
                    <?php
                }
            }
            ?>
            <br>
            <h5><?= $lang[$_SESSION['lang']]['Enter the subject'] ?>:</h5>
            <input type="text" value="" name="subject" required="required" class="form-control" placeholder="<?= $lang[$_SESSION['lang']]['Enter the subject'] ?>">
            <br>
            <h5><?= $lang[$_SESSION['lang']]['Enter the message'] ?>:</h5>
            <textarea rows="8" name="msg" class="form-control" placeholder="<?= $lang[$_SESSION['lang']]['Enter the message'] ?>" required="required"></textarea>
            <p>
                <label class="sec">Sec</label>
               <input type="text" class="form-control sec" name="sec" value="">
            </p>  
            <button type="submit" class="btn btn-primary"><?= $lang[$_SESSION['lang']]['Send'] ?></button>
            </form>
        </div>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4 curved-box">
            <h4><?= $lang[$_SESSION['lang']]["Email Sender's Results"] ?></h4>
            <?php 
            if (!empty($result)){
                foreach($result as $res){
                    ?>
                    <div style="border:thin solid black;padding:5px;"><?= $res ?></div>
                <?php
                }
            }
            
            ?>
    </div>
</div>