<div class="row" style="margin-top: 10px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["DatafromEduna"] ?></label>
        </p>
    </div>
    <div class="col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-xs-1">
        
    </div>
    <div class="col-xs-5 curved-box">
        <button class="btn btn-primary button-width" onclick="GotoPage('eduna/classlist')"><?= $lang[$_SESSION['lang']]['Class lists'] ?></button>
        <br>  <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('eduna/reportcards')"><?= $lang[$_SESSION['lang']]['Report cards'] ?></button>
        <br> 
    </div>
    <div class="col-xs-5 curved-box">
        <button class="btn btn-primary button-width" onclick="GotoPage('eduna/transcript')"><?= $lang[$_SESSION['lang']]['Transcript1'] ?></button>
        <br>  <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('eduna/transcript2')"><?= $lang[$_SESSION['lang']]['Transcript2'] ?></button>
        <br> 
    </div>
    <div class="col-xs-1">

    </div>
</div>