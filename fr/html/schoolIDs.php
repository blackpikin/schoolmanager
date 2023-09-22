<div class="row">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-6 ">
        <label id="label1"><?= $lang[$_SESSION['lang']]["School identity cards"] ?></label>
    </div>
    <div class="col-xs-2">

    </div>
</div>
<div class="row">
    <div class="col-xs-12">
       <div class="row curved-box">
       <div class="col-xs-3">
            <label><?= $lang[$_SESSION['lang']]["Academic year"] ?></label>
            <br>
        <select id="year" class="form-control">
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
       <div class="col-xs-3">
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
       <div class="col-xs-3">
           <label value=""><?= $lang[$_SESSION['lang']]["expirationdate"] ?></label>
           <input id="expire" type="date" class="form-control" placeholder="e.g 01/08/2020" >
       </div>
       <div class="col-xs-3">
           <br>
            <button class="btn btn-primary button-width" onclick="GenerateSchoolIDs()">Show list</button>
       </div>
       </div>
    </div>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>