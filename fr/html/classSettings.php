<?php 
    $genName = '';
    $subName = ''; $result = ''; $cycle=''; $err = false;
    $mockable = '0'; $practo = ''; $cm = '';
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $genName = $_POST['className'];
        $subName = $Model->test_input($_POST['subName']);
        $cycle = $_POST['cycle'];
        $mockable = $_POST['mockable'];
        $practo = $_POST['practo'];
        $cm = $_POST['classmaster'];
        $sec = $Model->test_input(($_POST['sec']));
        if(!empty($sec)){
            $err = true;
        }

        if (empty($subName)){
            $err=  true;
            $result = 'Enter the sub name';
        }

        if(!$err){
            $lng = $_SESSION['lang'];
            $section = 0;
            if($lng == 'fr'){
                $section = 1;
            }
            $data = [$genName, $subName, $cycle, $mockable, $practo, $section, $cm];
            $result = $Model->RegisterNewClass($data);
        }
    }

?>
<br>
<p style="color:red;"><?= $result ?></p>
<br>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]['ClassSettings'] ?></label>
        </p>
        <div class="curved-box">
        <form action="" method="post">
        <p>
            <label><?= $lang[$_SESSION['lang']]['GeneralClassName'] ?></label>
            <select required="required" class="form-control" name="className">
                <option value=""><?= $lang[$_SESSION['lang']]['Select the class'] ?></option>
                    <?php 
                    if($section == 1){
                        ?>
                            <option value="SIXIEME">SIXIEME</option>
                            <option value="CINQUIEME">CINQUIEME</option>
                            <option value="TROISIEME">TROISIEME</option>
                            <option value="QUATRIEME">QUATRIEME</option>
                            <option value="SECONDE">SECONDE</option>
                            <option value="PREMIER">PREMIER</option>
                            <option value="TERMINALE">TERMINALE</option>
                        <?php
                    }else{
                        ?>
                            <option value="FORM ONE">FORM ONE</option>
                            <option value="FORM TWO">FORM TWO</option>
                            <option value="FORM THREE">FORM THREE</option>
                            <option value="FORM FOUR">FORM FOUR</option>
                            <option value="FORM FIVE">FORM FIVE</option>
                            <option value="LOWER SIXTH">LOWER SIXTH</option>
                            <option value="UPPER SIXTH">UPPER SIXTH</option>

                        <?php
                    }
                ?>
            </select>
        </p>
        <p>
            <label><?= $lang[$_SESSION['lang']]['SubClassName'] ?></label>
            <input type="text" required="required" class="form-control" name="subName" placeholder="<?= $lang[$_SESSION['lang']]['SubnamePlaceholder'] ?>">
        </p>
        <p>
            <label><?= $lang[$_SESSION['lang']]['Level'] ?></label>
            <select required="required" class="form-control" name="cycle">
                <option><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
                <option value='FIRST'><?= $lang[$_SESSION['lang']]['FIRST'] ?> CYCLE</option>
                <option value='SECOND'><?= $lang[$_SESSION['lang']]['SECOND'] ?> CYCLE</option>
            </select>        
        </p>
        <label><?= $lang[$_SESSION['lang']]['Mockable'] ?></label>
            <select required="required" class="form-control" name="mockable">
                <option><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
                <option value="1"><?= $lang[$_SESSION['lang']]['Yes'] ?></option>
                <option value="0"><?= $lang[$_SESSION['lang']]['No'] ?></option>
            </select> 
            <br>
            <label><?= $lang[$_SESSION['lang']]['Practo'] ?></label>
            <select required="required" class="form-control" name="practo">
                <option><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
                <option value="1"><?= $lang[$_SESSION['lang']]['Yes'] ?></option>
                <option value="0"><?= $lang[$_SESSION['lang']]['No'] ?></option>
            </select>
            <?php 
                $cms = $Model->GetAllWithCriteria('users', ['section' => $section, 'role' => 'Teacher'])
            ?>
            <br>
            <p>
            <label><?= $lang[$_SESSION['lang']]['Classmaster'] ?></label>
            <select class="form-control" name="classmaster">
                <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
                <?php 
                    if (!empty($cms)){
                        foreach ($cms as $cm){
                            ?>
                            <option value="<?= $cm['name'] ?>"><?= $cm['name'] ?></option>

                <?php
                        }
                    }
                ?>
            </select>
            </p>
            <p>
                <label class="sec">Sec</label>
               <input type="text" class="form-control sec" name="sec" value="">
            </p>  
        <br>
            <button type="submit" class="btn btn-primary"><?= $lang[$_SESSION['lang']]['Save'] ?></button>
        </form>
        </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    </div>