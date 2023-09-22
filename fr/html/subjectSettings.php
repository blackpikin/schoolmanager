<?php 

    $result = ""; $subject = "";
    $class_name_first = []; $class_name_second = []; $f_group = ""; $s_group ="";
    $coeff = ""; $err = false; $Fpracto = 0; $Spracto = 0;

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $subject = $Model->test_input($_POST['nameOfSubject']);

        if(isset($_POST['first'])){
            $class_name_first = $_POST['first'];
            $f_group = isset($_POST['group']) ? $_POST['group'] :0;
        }

        if(isset($_POST['second'])){
            $class_name_second = $_POST['second'];
            $s_group = isset($_POST['Sgroup']) ? $_POST['Sgroup'] : 0;
        }
        
        
        $coef_first = $_POST['coef_first'];
        $coef_second = $_POST['coef_second'];

        if (empty($subject)){
            $err = true;
            $result = "Enter the name of the subject";
        }

        if (empty($class_name_first) && empty($class_name_second)){
            $err = true;
            $result = "Select class or classes from the list";
        }

        if (isset($_POST['Fpracto'])){
            $Fpracto = 1;
        }

        if (isset($_POST['Spracto'])){
            $Spracto = 1;
        }
        

        if(!$err){
            if (!empty($class_name_first)){
               foreach($class_name_first as $class){
                $data = [strToUpper($subject), $class, $coef_first, $f_group, $section, $Fpracto];
                if ($Model->SubjectExists($class, strToUpper($subject)) == false){
                   $result = $Model->RegisterNewSubject($data);
                }
                   
               }
            }

            if (!empty($class_name_second)){
                foreach($class_name_second as $class){
                    $data = [strToUpper($subject), $class, $coef_second, $s_group, $section, $Spracto];
                    if ($Model->SubjectExists($class, strToUpper($subject)) == false){
                        $result = $Model->RegisterNewSubject($data);
                    }
                }
             }
        }
    }
?>
<br>
<p style="color:red;"><?= $result ?></p>
<br>
<div class="row">
    <div class="col-xs-11">
    <p>
        <label id="label1"><?= $lang[$_SESSION['lang']]['NewSubject'] ?></label>
    </p>
    <div class='curved-box' style="padding-left:30px;">
    <form action="" method="post";>
    <p>
        <label><?= $lang[$_SESSION['lang']]['SubjectTitle'] ?></label>
        <input type="text" required="required" class="form-control" name="nameOfSubject">
    </p>
    <p>
        <label><?= $lang[$_SESSION['lang']]['Class'] ?></label>
        <div class="row ">
            <div class="curved-box col-xs-6">
                <span style="font-weight:bold;"><?= $lang[$_SESSION['lang']]['FIRST'] ?> CYCLE</span><br>
                    <?php 
                        $cycle1 = $Model->GetSchoolClasses('FIRST', $section);
                        if (!empty($cycle1)){
                            foreach($cycle1 as $class){
                                ?>
                                <input type="checkbox" name="first[]" value="<?= $class['id'] ?>" >
                                <?php
                                if($class['general_name'] != $class['sub_name']){
                                   echo $class['general_name'].' '.$class['sub_name'];
                                }else{
                                    echo $class['general_name'];
                                }
                             ?>
                                <br>
                                <?php
                            }
                        }
                    ?>
                    <label><?= $lang[$_SESSION['lang']]['CoefFor'] ?>  <?= $lang[$_SESSION['lang']]['FIRST'] ?> cycle</label>
                    <select class="form-control" name="coef_first">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                    <div class="curved-box">
            <input type="radio" name="group" value="1">&nbsp;<?= $lang[$_SESSION['lang']]['Group'] ?> 1 (General)
            &nbsp;&nbsp;&nbsp;
            <input type="radio" name="group" value="2">&nbsp;<?= $lang[$_SESSION['lang']]['Group'] ?> 2 (Science)
            &nbsp;&nbsp;&nbsp;
            <input type="radio" name="group" value="3">&nbsp;<?= $lang[$_SESSION['lang']]['Group'] ?> 3 (Arts)
        </div>
        <input type="checkbox" name="Fpracto[]">&nbsp;<label>Subject has practicals</label>
            </div>
            <div class="col-xs-1"></div>
            <div class="curved-box col-xs-5">
                <span style="font-weight:bold;"><?= $lang[$_SESSION['lang']]['SECOND'] ?> CYCLE</span><br>
                <?php 
                        $cycle1 = $Model->GetSchoolClasses('SECOND', $section);
                        if (!empty($cycle1)){
                            foreach($cycle1 as $class){
                                ?>
                                <input type="checkbox" name="second[]" value="<?= $class['id'] ?>" > 
                                <?php
                                if($class['general_name'] != $class['sub_name']){
                                   echo $class['general_name'].' '.$class['sub_name'];
                                }else{
                                    echo $class['general_name'];
                                }
                             ?>
                                <br>
                                <?php
                            }
                        }
                    ?>
                    <p>
            <label><?= $lang[$_SESSION['lang']]['CoefFor'] ?> <?= $lang[$_SESSION['lang']]['SECOND'] ?> Cycle</label>
            <select class="form-control" name="coef_second">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
            </select>
    </p>
        <div class="curved-box">
            <input type="radio" name="Sgroup" value="1">&nbsp;<?= $lang[$_SESSION['lang']]['Group'] ?> 1 (General)
            &nbsp;&nbsp;&nbsp;
            <input type="radio" name="Sgroup" value="2">&nbsp;<?= $lang[$_SESSION['lang']]['Group'] ?> 2 (Science)
            &nbsp;&nbsp;&nbsp;
            <input type="radio" name="Sgroup" value="3">&nbsp;<?= $lang[$_SESSION['lang']]['Group'] ?> 3 (Arts)
        </div>
        <input type="checkbox" name="Spracto[]">&nbsp;<label>Subject has practicals</label>
            </div>
        </div>  
    </p>
    <br>
        <button type="submit" class="btn btn-primary" name="save"><?= $lang[$_SESSION['lang']]['Save'] ?></button> 
    </form>
    </div>
    </div>
   
    <div class="col-xs-1">

    </div>
</div>
        
            