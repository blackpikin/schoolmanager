<div class="row" style="margin-top: 10px;">
    <div class="col-md-4">

    </div>
    <div class="col-md-4">
        <p>
            <h2 id="label1"><?= $lang[$_SESSION['lang']]["Settings"] ?></h2>
        </p>
    </div>
    <div class="col-md-4">

    </div>
</div>
<div class="row">
    <div class="col-md-2">

    </div>
    <div class="col-md-4 curved-box">
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
        <br><br>
        <button class="btn btn-primary button-width" onclick="GotoPage('cardTemplate')"><?= $lang[$_SESSION['lang']]["cardTemplate"] ?>&nbsp;&nbsp;</button>
    </div>
    <div class="col-md-4 curved-box">
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
        <br>
        <a href="./csv/saveCSV.php" class="btn btn-secondary button-width fa fa-download">&nbsp;&nbsp;Save School CSV</a>
        <br>            
    </div>
    <div class="col-md-2">

    </div>
</div>
<div class="row">
<div class="col-md-2">

</div>
<div class="col-md-4 curved-box">
    <button class="btn btn-primary button-width" onclick="GotoPage('timeSettings')"><?= $lang[$_SESSION['lang']]["TimeSettings"] ?>&nbsp;&nbsp;</button>
</div>
<div class="col-md-4">
                 
</div>
<div class="col-md-2">

</div>
</div>