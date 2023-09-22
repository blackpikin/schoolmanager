<div class="row" style="margin-top: 10px;">
    <div class="col-xs-3">

    </div>
    <div class="col-xs-8">
        <p>
            <label id="label1">Generate Pre-Mock / Mock Slips</label>
        </p>
    </div>
    <div class="col-xs-1">

    </div>
</div>
<div class="row">
    <div class="col-xs-12">
       <div class="row curved-box">
       <h4>Pre-Mock / Mock Slips</h4>
       <div class="col-xs-3">
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
       <div class="col-xs-3">
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
       <div class="col-xs-3">
           <label value="">Select the Exam</label>
           <select id="exam" class="form-control">
                <option value="">Select examination</option>
           </select>
       </div>
       <div class="col-xs-3">
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

       </div>
       </div>
    </div>
</div>
<br>
<br>
<br>
<br>