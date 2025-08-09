<div class="row" style="margin-top: 10px;">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["phonebook"] ?></label>
        </p>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-6 col-sm-6 col-xs-6">
        <label><?= $lang[$_SESSION['lang']]["Select the class"] ?></label>
        <select id="c_name" class="form-control" onchange="PhoneList(this)">
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
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-xs-11">
        <div id="class_list">
            <span><i><?= $lang[$_SESSION['lang']]["No class selected"] ?></i></span>
        </div>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">

    </div>
</div>