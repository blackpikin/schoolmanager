<div class="row" style="margin-top: 10px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1">Fee drive lists</label>
        </p>
    </div>
    <div class="col-xs-4">

    </div>
</div>

<h3>Primary</h3>
<div class="row curved-box" >
    <div class="col-xs-2">
    <label><?= $lang[$_SESSION['lang']]['AcademicYear'] ?></label>
            <br>
        <select id="yearp" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
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
    <div class="col-xs-2">
    <label><?= $lang[$_SESSION['lang']]["Select the class"] ?></label>
        <select id="c_namep" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]["Choose one"] ?></option>
            <?php 
            $classes = $Primodel->GetAllPrimaryClasses();
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
    <div class="col-xs-2">
    <label><?= $lang[$_SESSION['lang']]["FeeCriteria"] ?></label>
        <select id="crit_namep" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]["Choose one"] ?></option>
            <option value="<">those owing less than</option>
            <option value=">">those owing more than</option>
            <option value="=">those owing</option>
        </select>
    </div>
    <div class="col-xs-2">
    <label><?= $lang[$_SESSION['lang']]["EnterAnAmount"] ?></label>
    <input id="amountp" type="text" name="amount" placeholder="e.g. 50000" class="form-control" >
    </div>
    <div class="col-xs-2">
        <br>
        <button class="btn btn-primary" onclick="primaryFeeDriveList()">Show list</button>
    </div>
</div>
<br>
<h3>Secondary</h3>
<div class="row curved-box" >
    <div class="col-xs-2">
    <label><?= $lang[$_SESSION['lang']]['AcademicYear'] ?></label>
            <br>
        <select id="year" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
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
    <div class="col-xs-2">
    <label><?= $lang[$_SESSION['lang']]["Select the class"] ?></label>
        <select id="c_name" class="form-control">
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
    </div>
    <div class="col-xs-2">
    <label><?= $lang[$_SESSION['lang']]["FeeCriteria"] ?></label>
        <select id="crit_name" class="form-control">
            <option value=""><?= $lang[$_SESSION['lang']]["Choose one"] ?></option>
            <option value="<">those owing less than</option>
            <option value=">">those owing more than</option>
            <option value="=">those owing</option>
        </select>
    </div>
    <div class="col-xs-2">
    <label><?= $lang[$_SESSION['lang']]["EnterAnAmount"] ?></label>
    <input id="amount" type="text" name="amount" placeholder="e.g. 50000" class="form-control" >
    </div>
    <div class="col-xs-2">
        <br>
        <button class="btn btn-primary" onclick="FeeDriveList()">Show list</button>
    </div>
</div>