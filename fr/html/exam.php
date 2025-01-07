<?php 

$term = ""; $sequence = "";
$err = "";
$result = "";


if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $percent= 100;
    if(isset($_POST['percent']) && $_POST['percent'] != ''){
        $percent= $_POST['percent'];
    }
    $lng = $_SESSION['lang'];
    $section = 0;
    if($lng == 'fr'){
        $section = 1;
    }
    $data = [$_POST['term'], $_POST['seq'], $Model->GetCurrentYear()[0]['id'], $_POST['weighted'], $percent,  1, $section];
    $result = $Model->EndSequence();
    $result = $Model->RegisterNewSequence($data);
}

?>
<br><p style="color:red;"><?= $result ?></p><br>
<div class="row">
    <div class="col-md-1 col-sm-1  col-xs-1">

    </div>
    <div class="col-md-6 col-sm-6  col-xs-6">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]['NewExam'] ?></label>
        </p>
    </div>
    <div class="col-md-5 col-sm-5  col-xs-5">

    </div>
</div>
<div class="row">
    <div class="col-md-1 col-sm-1  col-xs-1">

    </div>
    <div class="col-md-6 col-sm-6  col-xs-6">
        <form action="" method="post">
        <p>
            <label><?= $lang[$_SESSION['lang']]['TERM'] ?></label>
            <select class="form-control" name="term">
                <option value=""><?= $lang[$_SESSION['lang']]['Select the term'] ?></option>
                <option value="First"><?= $lang[$_SESSION['lang']]['FIRST'] ?></option>
                <option value="Second"><?= $lang[$_SESSION['lang']]['SECOND'] ?></option>
                <option value="Third"><?= $lang[$_SESSION['lang']]['THIRD'] ?></option>
            </select>
        </p>
        <p>
            <label>Sequence</label>
            <select class="form-control" name="seq">
                <option value=""><?= $lang[$_SESSION['lang']]['Choose one'] ?></option>
                <option value="ONE"><?= $lang[$_SESSION['lang']]['ONE'] ?></option>
                <option value="TWO"><?= $lang[$_SESSION['lang']]['TWO'] ?></option>
                <option value="PRE-MOCK">PRE-MOCK</option>
                <option value="MOCK">MOCK</option>
            </select>
        </p>
        <label><?= $lang[$_SESSION['lang']]['Weighted'] ?></label>
        <div class="curved-box">
            <input type="radio" name="weighted" value="1">&nbsp;<?= $lang[$_SESSION['lang']]['Yes'] ?>
            &nbsp;&nbsp;&nbsp;
            <input type="radio" name="weighted" value="0">&nbsp;<?= $lang[$_SESSION['lang']]['No'] ?>
        </div>
        <label><?= $lang[$_SESSION['lang']]['SiOui'] ?></label>
        <input type="number" class="form-control" name="percent" placeholder="<?= $lang[$_SESSION['lang']]['eg30'] ?>">
        <br>
        <button type="submit" class="btn btn-primary"><?= $lang[$_SESSION['lang']]['Save'] ?></button>
        </form>
    </div>
    <div class="col-md-5 col-sm-5 col-xs-5">

    </div>
</div>