<?php
    $result = '';
    $student_code = $_GET['ref'];
    $curr_class_id = $Model->GetStudentCurrentClass($student_code);
    $err = false;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $new_class = $_POST['class'];

        if(empty($new_class)){
            $err = true;
        }

        if (!$err){
            $result = $Model->ChangeStudentClass($new_class, $student_code);
        }

        if ($result == 'Class modified successfully'){
            $curr_class_id = $Model->GetStudentCurrentClass($student_code);
            $result = $Model->UpdateFeesClassChange($student_code, $Model->GetCurrentYear()[0]['id'], $curr_class_id);
        }
    }

?>
<p style="color:red;"><?= $result ?></p>
<br>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <label id="label1"><?= $lang[$_SESSION['lang']]["EditClass"] ?>: <?= $Model->GetStudent($student_code, $section)[0]['name'] ?></label>
        <form action="" method="post">
        <div class="curved-box">
        <p>
            <label><?= $lang[$_SESSION['lang']]["CurrentClass"] ?></label>
            <input type="text" disabled="disabled" required="required" class="form-control" value="<?= $Model->GetAClassName($curr_class_id) ?>">
        </p>
        <label><?= $lang[$_SESSION['lang']]["Select the class"] ?></label>
        <select id="c_name" name="class" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]["Choose one"] ?></option>
            <?php 
            $classes = $Model->GetAllClasses($section);
            if(!empty($classes)){
                foreach($classes as $class){
                    ?>
                    <option value="<?= $class['id'] ?>">
                        <?php 
                             if($class['general_name'] != $class['sub_name']){
                                echo $class['general_name'].' '.$class['sub_name'];
                             }else{
                                 echo $class['general_name'];
                             }
                        ?>
                    </option>
                    <?php
                }
            }
            ?>

        </select>
        <br>
            <button type="submit" class="btn btn-primary"><?= $lang[$_SESSION['lang']]["Modify"] ?></button>
        </form>
        
    </div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    </div>