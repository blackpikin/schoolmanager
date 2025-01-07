<?php 
//1. Get the student's information
//2. Get the students classes
    $student_code = $_GET['ref'];
    //1. Get the student' information
    $student_data = $Model->GetAllWithCriteria('students', ['student_code' => $student_code]);
    
    //2. Get the student's classes
    $first_cycle_classes = $Model->TranscriptClasses($student_code, 'FIRST');
    $second_cycle_classes = $Model->TranscriptClasses($student_code, 'SECOND');
?>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8 ">
        <label id="label1"><?= $lang[$_SESSION['lang']]["Report cards"] ?> of <?= $student_data[0]['name'] ?></label>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
</div>
<div class="row">
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
    <div class="col-md-9 col-sm-9 col-xs-9 ">
    <?php 
    if (!empty($first_cycle_classes) or !empty($second_cycle_classes)){
        ?>
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-header">
                    <td><?= $lang[$_SESSION['lang']]["Class"] ?></td>
                    <td><?= $lang[$_SESSION['lang']]["Report cards"] ?></td>
                </thead>
                <?php
                //Load First cycle classes on the table
                    if(!empty($first_cycle_classes)){
                        foreach ($first_cycle_classes as $class){
                            ?>
                                <tr>
                                    <td><?= $Model->GetAClassName($class['class_id']) ?></td>
                                    <td><?php 
                                        $terms = $Model->GetAllTerms($class['academic_year_id']); 
                                        foreach ($terms as $term){
                                            ?>
                                                <a class="btn btn-success" onclick="window.open('./pdf/studentRepPdf.php?ref=<?= $student_code ?>&term_id=<?= $term['term'] ?>&class_id=<?= $class['class_id'] ?>&year_id=<?= $class['academic_year_id'] ?>')" ><?= $term['term'] ?> term</a>&nbsp;|&nbsp;
                                            <?php
                                        }
                                    ?>
                                    </td>
                                </tr>
                            <?php
                        }
                    }

                    //Load Second cycle classes on the table
                    if(!empty($second_cycle_classes)){
                        foreach ($second_cycle_classes as $class){
                            ?>
                                <tr>
                                    <td><?= $Model->GetAClassName($class['class_id']) ?></td>
                                    <td><?php 
                                        $terms = $Model->GetAllTerms($class['academic_year_id']); 
                                        foreach ($terms as $term){
                                            ?>
                                                <a class="btn btn-success" onclick="window.open('./pdf/studentRepPdf.php?ref=<?= $student_code ?>&term_id=<?= $term['term'] ?>&class_id=<?= $class['class_id'] ?>&year_id=<?= $class['academic_year_id'] ?>')" ><?= $term['term'] ?> term</a>&nbsp;|&nbsp;
                                            <?php
                                        }
                                    ?>
                                    </td>
                                </tr>
                            <?php
                        }
                    }
                ?>
            </table>
        <?php
    }
?>
    </div>
    <div class="col-xs-2">

    </div>
</div>

