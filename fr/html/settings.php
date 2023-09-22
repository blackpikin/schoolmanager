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
        <button class="btn btn-primary button-width" onclick="GotoPage('schoolSettings')"><?= $lang[$_SESSION['lang']]["SchoolSettings"] ?>&nbsp;&nbsp;</button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('classes')"><?= $lang[$_SESSION['lang']]["ClassSettings"] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('academicYearSettings')"><?= $lang[$_SESSION['lang']]["AcademicYear"] ?>&nbsp;&nbsp;</button>
        <br>
        <br>
        <button class="btn btn-warning button-width" onclick="GotoPage('eduna')"><?= $lang[$_SESSION['lang']]["DatafromEduna"] ?>&nbsp;&nbsp;</button>
        <br>
        <br>
        <button class="btn btn-danger button-width" onclick="GotoPage('compute')"><?= $lang[$_SESSION['lang']]["ComputeResults"] ?>&nbsp;&nbsp;</button>
        <br>
    </div>
    <div class="col-xs-4 curved-box">
        <button class="btn btn-primary button-width" onclick="GotoPage('users')"><?= $lang[$_SESSION['lang']]["StaffSettings"] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('promoteStudents')"><?= $lang[$_SESSION['lang']]["PromoteStudents"] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('subjectSettings')"><?= $lang[$_SESSION['lang']]["SubjectSettings"] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('examSettings')"><?= $lang[$_SESSION['lang']]["ExamSettings"] ?></button>
        <br>  
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('mockGrades')"><?= $lang[$_SESSION['lang']]["MockGrades"] ?></button>
        <br>              
    </div>
    <div class="col-xs-2">

    </div>
</div>
<div class="row">
<div class="col-xs-2">

</div>
<div class="col-xs-4 curved-box">
    <button class="btn btn-primary button-width" onclick="GotoPage('timeSettings')"><?= $lang[$_SESSION['lang']]["TimeSettings"] ?>&nbsp;&nbsp;</button>
</div>
<div class="col-xs-4">
                 
</div>
<div class="col-xs-2">

</div>
</div>