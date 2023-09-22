<div class="row" style="margin-top: 10px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["Reports"] ?></label>
        </p>
    </div>
    <div class="col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-4 curved-box">
        <button class="btn btn-primary button-width" onclick="GotoPage('primaryClassLists')"><?= $lang[$_SESSION['lang']]["Class lists"] ?>&nbsp;&nbsp;</button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('genderStatistics')"><?= $lang[$_SESSION['lang']]["Gender statistics"] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('enrolmentStatistics')"><?= $lang[$_SESSION['lang']]["Enrolment statistics"] ?>&nbsp;&nbsp;</button>
        
            <br>
            <br>
         <button class="btn btn-primary button-width" onclick="GotoPage('schoolIDs')"><?= $lang[$_SESSION['lang']]["School identity cards"] ?></button>
        <br>
    </div>
    <div class="col-xs-4 curved-box">
        <button class="btn btn-primary button-width" onclick="GotoPage('codesList')"><?= $lang[$_SESSION['lang']]['Codes Lists'] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('recAbsences')"><?= $lang[$_SESSION['lang']]['Record absences'] ?></button>
        <br> 
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('sendEmail')"><?= $lang[$_SESSION['lang']]['Email parents'] ?></button>
         <br>  
         <br>
    </div>
    <div class="col-xs-2">

    </div>
</div>
