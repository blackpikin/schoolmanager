<?php 
    $result = "";
    $grades = $Model->ContentExists('mock_grades', 'id', 1);
    $OLAmin = ''; $OLAmax = ''; $OLBmin = ''; $OLBmax = ''; $OLCmin = ''; $OLCmax = ''; $OLDmin = ''; $OLDmax = '';
    $OLEmin = ''; $OLEmax = ''; $OLUmin = ''; $OLUmax = '';
    $ALAmin = ''; $ALAmax = ''; $ALBmin = ''; $ALBmax = ''; $ALCmin = ''; $ALCmax = ''; $ALDmin = ''; $ALDmax = '';
    $ALEmin = ''; $ALEmax = ''; $ALOmin = ''; $ALOmax = ''; $ALFmin = ''; $ALFmax = '';
    if (!empty($grades)){
        $AL = explode(',', $grades[0]['AL']);
        $ALAmin = explode(':', $AL[0])[0]; 
        $ALAmax = explode(':', $AL[0])[1]; 
        $ALBmin = explode(':', $AL[1])[0]; 
        $ALBmax = explode(':', $AL[1])[1]; 
        $ALCmin = explode(':', $AL[2])[0]; 
        $ALCmax = explode(':', $AL[2])[1]; 
        $ALDmin = explode(':', $AL[3])[0]; 
        $ALDmax = explode(':', $AL[3])[1];
        $ALEmin = explode(':', $AL[4])[0]; 
        $ALEmax = explode(':', $AL[4])[1]; 
        $ALOmin = explode(':', $AL[5])[0]; 
        $ALOmax = explode(':', $AL[5])[1]; 
        $ALFmin = explode(':', $AL[6])[0]; 
        $ALFmax = explode(':', $AL[6])[1];

        $OL = explode(',', $grades[0]['OL']);
        $OLAmin = explode(':', $OL[0])[0]; 
        $OLAmax = explode(':', $OL[0])[1]; 
        $OLBmin = explode(':', $OL[1])[0]; 
        $OLBmax = explode(':', $OL[1])[1]; 
        $OLCmin = explode(':', $OL[2])[0]; 
        $OLCmax = explode(':', $OL[2])[1]; 
        $OLDmin = explode(':', $OL[3])[0]; 
        $OLDmax = explode(':', $OL[3])[1];
        $OLEmin = explode(':', $OL[4])[0]; 
        $OLEmax = explode(':', $OL[4])[1]; 
        $OLUmin = explode(':', $OL[5])[0];
        $OLUmax = explode(':', $OL[5])[1];
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $OLAmin = $Model->test_input($_POST['OLAmin']); $OLAmax = $Model->test_input($_POST['OLAmax']); 
        $OLBmin = $Model->test_input($_POST['OLBmin']); $OLBmax = $Model->test_input($_POST['OLBmax']); 
        $OLCmin = $Model->test_input($_POST['OLCmin']); $OLCmax = $Model->test_input($_POST['OLCmax']); 
        $OLDmin = $Model->test_input($_POST['OLDmin']); $OLDmax = $Model->test_input($_POST['OLDmax']);
        $OLEmin = $Model->test_input($_POST['OLEmin']); $OLEmax = $Model->test_input($_POST['OLEmax']); 
        $OLUmin = $Model->test_input($_POST['OLUmin']); $OLUmax = $Model->test_input($_POST['OLUmax']);

        $OLA = implode(':', [$OLAmin, $OLAmax]);
        $OLB = implode(':', [$OLBmin, $OLBmax]);
        $OLC = implode(':', [$OLCmin, $OLCmax]);
        $OLD = implode(':', [$OLDmin, $OLDmax]);
        $OLE = implode(':', [$OLEmin, $OLEmax]);
        $OLU = implode(':', [$OLUmin, $OLUmax]);

        $olevel = implode(',', [$OLA, $OLB, $OLC, $OLD, $OLE, $OLU]);

        $ALAmin = $Model->test_input($_POST['ALAmin']); $ALAmax = $Model->test_input($_POST['ALAmax']);
        $ALBmin = $Model->test_input($_POST['ALBmin']); $ALBmax = $Model->test_input($_POST['ALBmax']); 
        $ALCmin = $Model->test_input($_POST['ALCmin']); $ALCmax = $Model->test_input($_POST['ALCmax']); 
        $ALDmin = $Model->test_input($_POST['ALDmin']); $ALDmax = $Model->test_input($_POST['ALDmax']);
        $ALEmin = $Model->test_input($_POST['ALEmin']); $ALEmax = $Model->test_input($_POST['ALEmax']); 
        $ALOmin = $Model->test_input($_POST['ALOmin']); $ALOmax = $Model->test_input($_POST['ALOmax']); 
        $ALFmin = $Model->test_input($_POST['ALFmin']); $ALFmax = $Model->test_input($_POST['ALFmax']);

        $ALA = implode(':', [$ALAmin, $ALAmax]);
        $ALB = implode(':', [$ALBmin, $ALBmax]);
        $ALC = implode(':', [$ALCmin, $ALCmax]);
        $ALD = implode(':', [$ALDmin, $ALDmax]);
        $ALE = implode(':', [$ALEmin, $ALEmax]);
        $ALO = implode(':', [$ALOmin, $ALOmax]);
        $ALF = implode(':', [$ALFmin, $ALFmax]);

        $alevel = implode(',', [$ALA, $ALB, $ALC, $ALD, $ALE, $ALO, $ALF]);

        if(empty($_POST['sec'])){
            if(empty($Model->ContentExists('mock_grades', 'id', 1))){
                $result = $Model->Insert('mock_grades', ['AL'=>$alevel, 'OL'=>$olevel]);
            }else{
                $result = $Model->Update('mock_grades', ['AL'=>$alevel, 'OL'=>$olevel], ['id'=>1]);
            }
        }
    }
?>
<div class="row" style="margin-top: 10px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["MockGrades"] ?></label>
        </p>
        <span style="color:red;"><?= $result ?></span>
    </div>
    <div class="col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-xs-2">
        
    </div>
    <div class="col-xs-4 curved-box">
    <form action="" method="post">
            <h3>Ordinary Level</h3>
            <label>A grade</label>
            <br>
            <span>From</span>
            <input type="text" class="form-control"  value="<?= $OLAmin ?>" name="OLAmin" required min="0" max="20">
            <span>To</span><input type="text" class="form-control" value="<?= $OLAmax ?>" name="OLAmax" required min="0" max="20">
            <br>
            <label>B grade</label>
            <br>
            <span>From</span>
            <input type="text" class="form-control"  value="<?= $OLBmin ?>" name="OLBmin" required min="0" max="20">
            <span>To</span><input type="text" class="form-control" value="<?= $OLBmax ?>" name="OLBmax" required min="0" max="20">
            <br>
            <label>C grade</label>
            <br>
            <span>From</span>
            <input type="text" class="form-control"  value="<?= $OLCmin ?>" name="OLCmin" required min="0" max="20">
            <span>To</span><input type="text" class="form-control" value="<?= $OLCmax ?>" name="OLCmax" required min="0" max="20">
            <br>
            <label>D grade</label>
            <br>
            <span>From</span>
            <input type="text" class="form-control"  value="<?= $OLDmin ?>" name="OLDmin" required min="0" max="20">
            <span>To</span><input type="text" class="form-control" value="<?= $OLDmax ?>" name="OLDmax" required min="0" max="20">
            <br>
            <label>E grade</label>
            <br>
            <span>From</span>
            <input type="text" class="form-control"  value="<?= $OLEmin ?>" name="OLEmin" required min="0" max="20">
            <span>To</span><input type="text" class="form-control" value="<?= $OLEmax ?>" name="OLEmax" required min="0" max="20">
            <br>
            <label>U grade</label>
            <br>
            <span>From</span>
            <input type="text" class="form-control"  value="<?= $OLUmin ?>" name="OLUmin" required min="0" max="20">
            <span>To</span><input type="text" class="form-control" value="<?= $OLUmax ?>" name="OLUmax" required min="0" max="20">
            <p>
                <label class="sec">Sec</label>
               <input type="text" class="form-control sec" name="sec" value="">
            </p>
            <br>
            <button class="btn btn-primary button-width">Save</button>
    </div>
    <div class="col-xs-4 curved-box">
    <h3>Advanced Level</h3>
            <label>A grade</label>
            <br>
            <span>From</span>
            <input type="text" class="form-control"  value="<?= $ALAmin ?>" name="ALAmin" required min="0" max="20">
            <span>To</span><input type="text" class="form-control" value="<?= $ALAmax ?>" name="ALAmax" required min="0" max="20">
            <br>
            <label>B grade</label>
            <br>
            <span>From</span>
            <input type="text" class="form-control"  value="<?= $ALBmin ?>" name="ALBmin" required min="0" max="20">
            <span>To</span><input type="text" class="form-control" value="<?= $ALBmax ?>" name="ALBmax" required min="0" max="20">
            <br>
            <label>C grade</label>
            <br>
            <span>From</span>
            <input type="text" class="form-control"  value="<?= $ALCmin ?>" name="ALCmin" required min="0" max="20">
            <span>To</span><input type="text" class="form-control" value="<?= $ALCmax ?>" name="ALCmax" required min="0" max="20">
            <br>
            <label>D grade</label>
            <br>
            <span>From</span>
            <input type="text" class="form-control"  value="<?= $ALDmin ?>" name="ALDmin" required min="0" max="20">
            <span>To</span><input type="text" class="form-control" value="<?= $ALDmax ?>" name="ALDmax" required min="0" max="20">
            <br>
            <label>E grade</label>
            <br>
            <span>From</span>
            <input type="text" class="form-control"  value="<?= $ALEmin ?>" name="ALEmin" required min="0" max="20">
            <span>To</span><input type="text" class="form-control" value="<?= $ALEmax ?>" name="ALEmax"  required min="0" max="20">
            <br>
            <label>O grade</label>
            <br>
            <span>From</span>
            <input type="text" class="form-control"  value="<?= $ALOmin ?>" name="ALOmin"  required min="0" max="20">
            <span>To</span><input type="text" class="form-control" value="<?= $ALOmax ?>" name="ALOmax"  required min="0" max="20">
            <br>
            <label>F grade</label>
            <br>
            <span>From</span>
            <input type="text" class="form-control"  value="<?= $ALFmin ?>" name="ALFmin"  required min="0" max="20">
            <span>To</span><input type="text" class="form-control" value="<?= $ALFmax ?>" name="ALFmax"  required min="0" max="20"> 
        </form>         
    </div>
    <div class="col-xs-2">

    </div>
</div>