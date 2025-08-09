<?php
    $srch =''; $results = []; $class_id = '';
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST['srch'])){
            $srch = $Model->test_input($_POST['srch']);
            if (!empty($srch)){
                $results = $Model->SearchStudent($srch);
            }
        }else{
            $class_id = $_POST['c_name'];
            $year_id = $Model->GetCurrentYear()[0]['id'];
            $students = $Model->GetStudentsInClass($class_id, $year_id);
            foreach ($students as $student){
                $className = $Model->GetAClassName($class_id);
                array_push($results, $Model->GetStudent($student['student_code'],$section)[0]);
            }
        }
    }
?>
<div class="row" style="margin-top: 10px;">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <p>
            <h2 id="label1"><?= $lang[$_SESSION['lang']]["StudentSettings"] ?></h2>
        </p>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
</div>
<div class="row">
    <div class="">

    </div>
    <div class="col-md-9 col-sm-9 col-xs-9">
        <button class="btn btn-primary button-width" onclick="GotoPage('studentSettings')"><?= $lang[$_SESSION['lang']]["NewStudent"] ?></button>
        <br>
        <br>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-3"></div>
    </div>
    <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-6">
        <form id="perclass" action="" method="post">
            <label><?= $lang[$_SESSION['lang']]["SelectClassToviewStudents"] ?></label>
            <select id="c_name" name="c_name" class="form-control" onchange="ClassList(this)">
            <?php 
                if($class_id == ''){
                    ?>
                <option value=""><?= $lang[$_SESSION['lang']]["Choose one"] ?></option>
                <?php
                }else{
                    ?>
                <option value="<?= $class_id ?>"><?= $Model->GetAClassName($class_id)  ?></option>
                <?php
                }
            ?>
                <?php 
                $classes = $Model->GetAllClasses($section);
                if(!empty($classes)){
                    foreach($classes as $class){
                        ?>
                        <option value="<?= $class['id'] ?>"><?= $Model->GetAClassName($class['id'])  ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </form>
        <br>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6">
    <br>
    <button onclick="DownloadCSVFile()" class="btn btn-secondary button-width fa fa-download">&nbsp;&nbsp;Save class CSV</>
    </div>
    </div>

    <div class="row">
    <div class="">

    </div>
    <div class="col-md-11 col-sm-11 col-xs-11">
    <form action="" method="post">
    <div class="row">
    <div class="col-md-8 col-sm-8 col-xs-8">
    <input name="srch" style="height:55px;" type="text" value="<?= $srch ?>" placeholder="<?= $lang[$_SESSION['lang']]["SearchStudentPlaceholder"] ?>" class="form-control" >
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <button type="submit" style="height:55px;width:55px;" class="btn btn-warning fa fa-search"></button>
    </div>
    </div>
    </form>
    <br>
    <div id="student_list">
           <?php 
                if (!empty($results)){
                    ?>
                    <table class="table table-responsive table-bordered">
                        <tr class="table-header">
                            <td>#</td>
                            <td>Photo</td>
                            <td><?= $lang[$_SESSION['lang']]["Name"] ?></td>
                            <td><?= $lang[$_SESSION['lang']]["Gender"] ?></td>
                            <td><?= $lang[$_SESSION['lang']]["BornOn"] ?></td>
                            <td><?= $lang[$_SESSION['lang']]["CurrentClass"] ?></td>
                            <td><?= $lang[$_SESSION['lang']]["GuardianPhone"] ?></td>
                            <td>Actions</td>
                        </tr>
                    <?php
                    foreach ($results as $key => $result){
                        $year = $Model->GetCurrentYear()[0]['id'];
                        $studentCode = $result['student_code'];
                        $class_id = $Model->GetClassId($year, $studentCode);
                        $className = $Model->GetAClassName($class_id);
    
                        ?>
                        <tr class="normal-tr">
                        <td><?= ++$key ?></td>
                        <td>
                            <?php 
                                if($result['picture'] != ""){
                                    $data = base64_decode($result['picture']);
                                    $file = "./img/students/" . $result["student_code"] . '.'.$result["picture_ext"];
                                   $success = file_put_contents($file, $data);
                                   ?>
                                        <img src="<?= $file ?>" alt="" class="student-pic">
                                   <?php
                                }
                            ?>
                        </td>
                        <td><?= $result['name'] ?></td>
                        <td><?= $result['gender'] ?></td>
                        <td>
                            <?php 
                                $date = New DateTime($result['dob']);
                            ?>
                            <?= date_format($date, "d-m-Y")  ?>
                        </td>
                        <td><?= $className ?></td>
                        <td><?= $result['guardian_number'] ?></td>
                        <td>
                        <button title="Edit student's info" class="btn btn-outline-secondary fa fa-edit" onclick="GotoPage('modifyStudent&ref=<?= $result['student_code'] ?>')"></button>
                        <button title="Change the student's class" class="btn btn-outline-secondary fa fa-signal" onclick="GotoPage('changeStudentClass&ref=<?= $result['student_code'] ?>')"></button>
                        <button title="View Student's profile" class="btn btn-outline-secondary fa fa-list" onclick="GotoPage('studentProfile&ref=<?= $result['student_code'] ?>')"></button>
                        <button title="View student's documents" class="btn btn-outline-secondary fa fa-file" onclick="GotoPage('studentFiles&ref=<?= $result['student_code'] ?>')"></button>
                        <button title="Change student's picture" class="btn btn-outline-secondary fa fa-photo" onclick="GotoPage('studentPicture&ref=<?= $result['student_code'] ?>')"></button>
                        <button title="Certificates" class="btn btn-outline-secondary fa fa-folder-open" onclick="GotoPage('studentCert&ref=<?= $result['student_code'] ?>')"></button>
                        <button title="Student's Achievements/Conduct" class="btn btn-outline-secondary fa fa-calendar" onclick="GotoPage('studentConduct&ref=<?= $result['student_code'] ?>')"></button>
                    </td>
                        </tr>
                        <?php
                    }
                    ?>
                     </table>
                <?php
                }else{
                    ?>
                    <label style="color:red;font-size:11pt;font-style:italic"><?= $lang[$_SESSION['lang']]["NoStudentFound"] ?></label>
                    <?php
                }
                ?>
                </div>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
    </div>