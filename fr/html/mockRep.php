<div class="row" style="margin-top: 10px;">
    <div class="col-md-3 col-sm-3 col-xs-3">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <p>
            <label id="label1">Generate Pre-Mock / Mock Slips</label>
        </p>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
       <div class="row curved-box">
       <h4>Pre-Mock / Mock Slips</h4>
       <div class="col-md-3 col-sm-3 col-xs-3">
            <label>Select academic year</label>
            <br>
        <select id="year" class="form-control" onchange="GetMocksForYear(this)">
            <option value="">Select a year</option>
            <?php 
            $years = $Model->GetAcademicYears();
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
       </div>
       <div class="col-md-3 col-sm-3 col-xs-3">
       <label>Select the class</label>
        <select id="c_name" class="form-control">
            <option value="">Select a class</option>
            <?php 
            $classes = $Model->GetMockableClasses($section);
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
       </div>
       <div class="col-md-3 col-sm-3 col-xs-3">
           <label value="">Select the Exam</label>
           <select id="exam" class="form-control">
                <option value="">Select examination</option>
           </select>
       </div>
       <div class="col-md-3 col-sm-3 col-xs-3">
           <br>
            <button class="btn btn-primary button-width" onclick="MockReport()">View Slips</button>
            <br><br>
            <button class="btn btn-primary button-width" onclick="MockStats()">View Statistics</button>
            <br>
            <br>
            <button class="btn btn-primary button-width" onclick="MasterSheet()">Subject Master sheet</button>
            <br>
            <br>
            <button class="btn btn-primary button-width" onclick="ClassMasterSheet()">Class Master sheet</button>
            <br>
            <br>
            <button class="btn btn-primary button-width" onclick="MarkClassMasterSheet()">Marks Master sheet</button>
       </div>
       </div>
    </div>
</div>
<div class="row" style="margin-top: 10px;">
    <div class="col-md-3 col-sm-3 col-xs-3">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["MockGrades"] ?></label>
        </p>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>
<div class="row curved-box">
    <div class="col-md-2 col-sm-2 col-xs-2">
    <label value="">Select the Exam</label>
        <select class="form-control" id="exam2">
        <option value=""><?= $lang[$_SESSION['lang']]["Choose one"] ?></option>
            <option value="premock"><?= $lang[$_SESSION['lang']]["PRE-MOCK"] ?></option>
            <option value="mock"><?= $lang[$_SESSION['lang']]["MOCK"] ?></option>
          </select>
    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
    <label value="">Select the academic year</label>
          <select id="year2" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]["Choose one"] ?></option>
            <?php 
            $years = $Model->GetAcademicYears();
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
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">
    <br>
            <br>
            <button class="btn btn-warning button-width" onclick="ViewMockSettings()"><?= $lang[$_SESSION['lang']]["MockGrades"] ?></button>
    </div>
<br>
<br>
<br>
<br>
<script>
    function ViewMockSettings(){
        let exam = document.getElementById('exam2').value;
        let year = document.getElementById('year2').value;
        GotoPage('mockGrades&exam='+exam+'&year_id='+year);
    }
</script>