<?php 
    $classes = ""; $subject = ""; $msg = ""; $err = false; $result = [];
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $classes = $_POST['classes'];
        $subject = $_POST['subject'];
        $msg = $_POST['msg'];

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
    <div class="col-xs-3">

    </div>
    <div class="col-xs-6">
        <h2 id="label1">Send SMS to Parents</h2>
    </div>
    <div class="col-xs-3">

    </div>
</div>
<div class="row">
    <div class="col-xs-7">
        <div class="curved-box">
            <form action="" method="post">
            <h5>Select the classes:</h5>
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
            <h5>Enter the Sender(11 characters max with no spaces):</h5>
            <input type="text" value="" name="subject" required="required" class="form-control" placeholder="Enter the sender's name">
            <br>
            <h5>Enter the message:</h5>
            <textarea rows="8" name="msg" class="form-control" placeholder="Enter the message" required="required"></textarea>
            <br>
            <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
    <div class="col-xs-4 curved-box">
            <h4>SMS Sender's Results</h4>
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