<?php
$err = false; $result = "";
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset( $_POST['student'])){
            $students = $_POST['student'];
            $year = $_POST['new_year'];
            $class_id = $_POST['new_class'];

            //$curr_class = $_POST['curr_class'];
            //$curr_year = $_POST['curr_year'];

            if (empty($year)){
                $err = true;
                $result = "Select the new academic year";
            }

            if (empty($class_id)){
                $err = true;
                $result = "Select the new class";
            }

            if(!$err){
                foreach($students as $stud){
                    $data = [$stud, $year, $class_id];
                    $result = $Model->UpdateStudentStatusInClass($stud);
                    $result = $Model->RegisterStudentToClass($data);
                    $result = $Model->NewFeePayment($stud, $year, $class_id, 0, date('Y-m-d'), date('m-Y'), date('Y'), $_SESSION['id']);
                }
            }
        }else{
            $result = "No students selected";
        }
        
    }


?>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-9 col-sm-9 col-xs-9">
        <h2 id="label1"><?= $lang[$_SESSION['lang']]['PromoteStudents'] ?></h2>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>
<div class="row ">
<span style="color:red;"><?= $result ?></span>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            
        <div class="col-md-2 col-sm-2 col-xs-2 ">
           
           </div>
            <div class="col-md-8 col-sm-8 col-xs-8 curved-box">
                    <label><?= $lang[$_SESSION['lang']]['SelectCurrentClass'] ?></label>
                    <select id="curr_class" class="form-control">
                    <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
                    <?php 
                    $lng = $_SESSION['lang'];
                    $section = 0;
                    if($lng == 'fr'){
                        $section = 1;
                    }
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
                <label><?= $lang[$_SESSION['lang']]['SelectYearEnded'] ?></label>
            <br>
            <select id="curr_year" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
            <?php 
            $years = $Model->GetPreviousAcademicYears();
            if(!empty($years)){
                foreach($years as $year){
                    ?>
                    <option value="<?= $year['id'] ?>">
                        <?php 
                            echo $year['start'].'/'.$year['end'];
                        ?>
                    </option>
                    <?php
                }
            }
            ?>

        </select>
        <br>
        <button class="btn btn-primary" onclick="ShowPromotionList()"><?= $lang[$_SESSION['lang']]['View'] ?></button>
        <div id="promote_list">
                
        </div>
        </div>
            <div class="col-md-2 col-sm-2 col-xs-2 ">
           
            </div>
        </div>
    </div>
</div>

