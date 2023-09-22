<div class="row" style="margin-top: 10px;">
    <div class="col-xs-4">

    </div>
    <div class="col-xs-4">
        <p>
            <label id="label1"><?= $lang[$_SESSION['lang']]["Settings"] ?></label>
        </p>
    </div>
    <div class="col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-xs-2">

    </div>
    <div class="col-xs-4 curved-box">
        <button class="btn btn-primary button-width" onclick="GotoPage('primaryschoolSettings')"><?= $lang[$_SESSION['lang']]["SchoolSettings"] ?>&nbsp;&nbsp;</button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('primaryclasses')"><?= $lang[$_SESSION['lang']]["ClassSettings"] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('academicYearSettings')"><?= $lang[$_SESSION['lang']]["AcademicYear"] ?>&nbsp;&nbsp;</button>
        <br>
        <br>
    </div>
    <div class="col-xs-4 curved-box">
        <button class="btn btn-primary button-width" onclick="GotoPage('primaryUsers')"><?= $lang[$_SESSION['lang']]["StaffSettings"] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('primarypromoteStudents')"><?= $lang[$_SESSION['lang']]["PromoteStudents"] ?></button>
        <br>
        <br>           
    </div>
    <div class="col-xs-2">

    </div>
</div>