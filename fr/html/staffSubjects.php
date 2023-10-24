<?php 
    $staff_id = $_GET['ref'];
    $academic_year = $Model->GetCurrentYear()[0]['id']; 
    $class_id = []; 
    $subject = "";
    $result = "";
    $err = false;
    $staff_subjects = $Model->GetStaffSubjects($staff_id, $academic_year);

    $all_subjects = [];
    $class_ids = [];
    $lng = $_SESSION['lang'];
    $section = 0;
    if($lng == 'fr'){
        $section = 1;
    }

    /*
    if (!empty($staff_subjects)){
       foreach($staff_subjects as $sub){
            $classes = explode(",", $sub['class_id']);
            foreach($classes as $class){
                array_push($all_subjects, $class);
            }
       }
    }
*/

if (!empty($staff_subjects)){
    foreach($staff_subjects as $sub){
         array_push($all_subjects, $sub['subject']);
    }
 }
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $subject = $_POST['subject'];
        $class_id = $_POST['first'];

        if (empty($class_id)){
            $result = "You must select one or more classes";
            $err = true;
        }

        if (empty($subject)){
            $result = "You must select a subject";
            $err = true;
        }
/*
        foreach($class_id as $id){
           if(!in_array($id, $all_subjects)){
               array_push($class_ids, $id); 
           }
        }
*/
        if (in_array($subject, $all_subjects)){
            //$result = "$subject already assigned to the selected staff";
           //$err = true;
        }

        /*
        if (empty($class_ids)){
            //$result = "You must select a class";
            //$err = true;
        }
        */

        if (!$err){
            $qlasses = implode(',', $class_id);
            $data = [$staff_id, $academic_year, $qlasses, $subject];
            $result = $Model->RegisterNewStaffSubjects($data);
        }

        

    }
?>
<br>
<p style="color:red;"><?= $result ?></p>
<br>
<div class="row" >
    <div class="col-xs-3">

    </div>
    <div class="col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["SubjectsFor"] ?> <?= $Model->GetUser($_GET['ref'])[0]['name'] ?></label>
        </p>
    </div>
    <div class="col-xs-1">

    </div>
</div>
<div class="row">
    <div class="col-xs-1">

    </div>
    <div class="col-xs-5">
       <div class="curved-box">
           <form action="" method="post" >
            <h4><?= $lang[$_SESSION['lang']]["Subject"] ?>s</h4>
            <select class="form-control" name="subject">
                <option value=""><?= $lang[$_SESSION['lang']]["Choose one"] ?></option>
                <?php 
                    $subjects = $Model->GetAllSubjects();
                    foreach($subjects as $subject){
                        ?>
                        <option value="<?= $subject['subject'] ?>"><?= $subject['subject'] ?></option>
                        <?php
                    }
                ?>
            </select>
            <br>
            <div class="row">
                <div class="col-xs-6">
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
                </div>
                <div class="col-xs-6">
                <?php 
                    $cycle1 = $Model->GetSchoolClasses('SECOND', $section);
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
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary"><?= $lang[$_SESSION['lang']]["Save"] ?></button>
           </form>
       </div>
    </div>
    <div class="col-xs-5">
       <div class="curved-box">
       <h4><?= $lang[$_SESSION['lang']]["ExistingSubjects"] ?></h4>
                <table class="table table-bordered table-responsive">
                    <tr>
                        <td><?= $lang[$_SESSION['lang']]["Subject"] ?></td>
                        <td><?= $lang[$_SESSION['lang']]["Class"] ?></td>
                        <td>Actions</td>
                    </tr>
                    <?php 
                   
                    if(!empty($staff_subjects)){
                        foreach($staff_subjects as $subject){
                            $classes = explode(",", $subject['class_id']);
                            foreach($classes as $class){
                                ?>
                                 <tr class="normal-tr">
                                    <td><?= $subject['subject'] ?></td>
                                    <td><?php
                                        $cl = $Model->GetAClass($class); 
                                        if($cl[0]['general_name'] != $cl[0]['sub_name']){
                                            echo $cl[0]['general_name'].' '.$cl[0]['sub_name'];
                                         }else{
                                             echo $cl[0]['general_name'];
                                         }
                                    ?></td>
                                    <td><button title="<?= $lang[$_SESSION['lang']]["DeleteThis"] ?>" class="btn btn-danger glyphicon glyphicon-trash"></button></td>
                                </tr>

                                <?php
                            }
                        }
                    }
                    ?>
                </table>    
       </div>
    </div>
    <div class="col-xs-1">

    </div>
</div>