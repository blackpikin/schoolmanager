<div class="row" style="margin-top: 10px;">
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">
        <p>
            <h2 id="label1"><?= $lang[$_SESSION['lang']]["Reports"] ?></h2>
        </p>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4">

    </div>
</div>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4 curved-box">
        <button class="btn btn-primary button-width" onclick="GotoPage('classLists')"><?= $lang[$_SESSION['lang']]["Class lists"] ?>&nbsp;&nbsp;</button>
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
    <div class="col-md-4 col-sm-4 col-xs-4 curved-box">
        <button class="btn btn-primary button-width" onclick="GotoPage('sequenceStatistics')"><?= $lang[$_SESSION['lang']]['Sequence statistics'] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('reportCards')"><?= $lang[$_SESSION['lang']]['Report cards'] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('mockRep')"><?= $lang[$_SESSION['lang']]['Pre-mock/Mock'] ?></button>
        <br>  
        <br> 
        <!-- <button class="btn btn-primary button-width" onclick="GotoPage('timeTable')"><?= ''//$lang[$_SESSION['lang']]['timetable'] ?></button> -->
        <button class="btn btn-primary button-width" onclick="GotoPage('convertMarks')"><?= $lang[$_SESSION['lang']]['ConvertMarks'] ?></button>
        <br>        
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
</div>
<div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-md-4 col-sm-4 col-xs-4 curved-box">
    <button class="btn btn-primary button-width" onclick="GotoPage('editClosedExams')"><?= $lang[$_SESSION['lang']]['Closed Exams'] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('codesList')"><?= $lang[$_SESSION['lang']]['Codes Lists'] ?></button>
        <br>
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('printMarksheets')"><?= $lang[$_SESSION['lang']]['Print Marksheet'] ?></button>
        <br>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4 curved-box">
        <button class="btn btn-primary button-width" onclick="GotoPage('recAbsences')"><?= $lang[$_SESSION['lang']]['Record absences'] ?></button>
        <br> 
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('sendEmail')"><?= $lang[$_SESSION['lang']]['Email parents'] ?></button>
         <br>  
         <br>
          <!-- <button class="btn btn-primary button-width" onclick="GotoPage('sendSMS')">SMS aux parents</button> 
         <br>
         <br>
-->
        <button class="btn btn-primary button-width" onclick="GotoPage('annualStats')"><?= $lang[$_SESSION['lang']]['Annual Marksheet'] ?></button>
        <br> 
        <br>
        <button class="btn btn-primary button-width" onclick="GotoPage('phoneBase')"><?= $lang[$_SESSION['lang']]['phonebook'] ?></button>
        <br>       
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">

    </div>
</div>