//Functionality functions

function MarksheetPremock(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    let subj = $('#subjects').val();
    if(year !== "" && cls !== ""){
        window.open('./pdf/MarkSheetMockPdf.php?year_id='+year+'&class_id='+cls+'&subject='+subj+'&type=PRE-MOCK');
    }else{
        alert("You must select the three parameters : Year, Class and subject");
    }
}

function MarkClassMasterSheet(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    let exam = $('#exam').val();
    if(year !== "" && cls !== "" && exam !== ""){
        window.location.href = 'index.php?p=mockClassSheet&year_id='+year+'&class_id='+cls+'&exam_id='+exam;
    }else{
        alert("You must select the three parameters: year, class and exam");
    }
}

function MarksheetMock(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    let subj = $('#subjects').val();
    if(year !== "" && cls !== ""){
        window.open('./pdf/MarkSheetMockPdf.php?year_id='+year+'&class_id='+cls+'&subject='+subj+'&type=MOCK');
    }else{
        alert("You must select the three parameters : Year, Class and subject");
    }
}

function SchoolTimeTable(){
    let type = $('#timetable').val();
    if (type !== undefined && type !== ""){
        window.open('./pdf/ttPDF.php?type='+type);
    }else{
        alert("Select the type of timetable to make")
    }
}

function GetMocksForYear(elem){
    let id = elem.value;
    $.ajax({
        type: "POST",
        url: "./html/ajax.php",
        data: {year_id:id, 'action':"GetMocksForYear"},
        dataType: 'html',
        success: function (data) {
            $('#exam').html(data);
        },
        error: function () {
            console.log(Error().message);
        }
    });
}

function PrintableClassListPrimary(elem){
    let class_id = elem.value;
    $.ajax({
        type: "POST",
        url: "./html/primaryAjax.php",
        data: {classID:class_id, 'action':"PrintableClassList"},
        dataType: 'html',
        success: function (data) {
            $('#class_list').html(data);
        },
        error: function () {
            console.log(Error().message);
        }
    });
}

function primaryFeeDriveList(){
    let year = $('#yearp').val();
    let cls = $('#c_namep').val();
    let crit = $('#crit_namep').val();
    let amt = $('#amountp').val();
    if(year !== "" && cls !== "" && amt !== "" && crit !== ""){
        window.open('./pdf/primaryFeeDrivePdf.php?year_id='+year+'&class_id='+cls+'&crit='+crit+'&amt='+amt);
    }else if(year !== "" && cls == "" && amt !== "" && crit !== ""){
        window.open('./pdf/primaryFeeDrivePdf.php?year_id='+year+'&class_id='+cls+'&crit='+crit+'&amt='+amt);
    }else{
        alert("You must select all the parameters and enter an amount")
    }
}

function FeeDriveList(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    let crit = $('#crit_name').val();
    let amt = $('#amount').val();
    if(year !== "" && cls !== "" && amt !== "" && crit !== ""){
        window.open('./pdf/FeeDrivePdf.php?year_id='+year+'&class_id='+cls+'&crit='+crit+'&amt='+amt);
    }else if(year !== "" && cls == "" && amt !== "" && crit !== ""){
        window.open('./pdf/FeeDrivePdf.php?year_id='+year+'&class_id='+cls+'&crit='+crit+'&amt='+amt);
    }else{
        alert("You must select all the parameters and enter an amount")
    }

}

function ComputeTerm(){
    let year = $('#term_year').val();
    let cls = $('#term_class').val();
    let term = $('#term_term').val();

    if(year !== "" && cls !== "" && exam !== ""){
        $('#loading').show();
        $.ajax({
            type: "POST",
            url: "./html/ajax.php",
            data: {Year:year, klass:cls, Term:term, 'action':"ComputeTerm"},
            dataType: 'html',
            success: function (data) {
                alert(data);
                if(data == 'Computation complete'){
                    $('#loading').hide();
                }
            },
            error: function () {
                console.log(Error().message);
            }
        });
    }else{
        alert("You must select the three parameters : Year, Class and Term");
    }
    
}

function ShowCompute(elem){
    $('#sequence').hide();
    $('#term').hide();
    $('#premock').hide();
    $('#mock').hide();
    let exam = elem.value;
    if(exam != ""){
        $('#'+exam).show();
    }
}

function ComputeSequence(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    let exam = $('#exam').val();

    if(year !== "" && cls !== "" && exam !== ""){
        $('#loading').show();
        $.ajax({
            type: "POST",
            url: "./html/ajax.php",
            data: {Year:year, klass:cls, Exam:exam, 'action':"ComputeSequence"},
            dataType: 'html',
            success: function (data) {
                alert(data);
                if(data == 'Computation complete'){
                    $('#loading').hide();
                }
            },
            error: function () {
                console.log(Error().message);
            }
        });
    }else{
        alert("You must select the three parameters : Year, Class and Examination");
    }
    
}

function LoadFees(student_code, class_id){
    $.ajax({
        type: "POST",
        url: "./html/ajax.php",
        data: {student:student_code, klass:class_id, 'action':"LoadFees"},
        dataType: 'html',
        success: function (data) {
            $('#fee_bkd').html(data);
        },
        error: function () {
            console.log(Error().message);
        }
    });
}

function LoadPrimaryFees(student_code, class_id){
    $.ajax({
        type: "POST",
        url: "./html/primaryAjax.php",
        data: {student:student_code, klass:class_id, 'action':"LoadFees"},
        dataType: 'html',
        success: function (data) {
            $('#fee_bkd').html(data);
        },
        error: function () {
            console.log(Error().message);
        }
    });
}


function LoadFeeSettings(){
    let cls = $('#c_name').val();
    let typ = '';
    if($('#new').is(':checked')){
        typ = 'new'
    }else if($('#old').is(':checked')){
        typ = 'old'
    }

    if(cls != '' && typ != ''){
        $.ajax({
            type: "POST",
            url: "./html/ajax.php",
            data: {classID:cls, Type:typ, 'action':"FeeSettings"},
            dataType: 'html',
            success: function (data) {
                $('#fee_list').html(data);
            },
            error: function () {
                console.log(Error().message);
            }
        });
    }else{
        alert("Select the class and student type");
    }

}

function ComputeTotalFee(){
    let reg = Number(document.getElementById('reg').value);
    let pta = Number(document.getElementById('pta').value);
    let firstIns = Number(document.getElementById('first').value);
    let secondIns = Number(document.getElementById('second').value);
    let total = reg + pta + firstIns + secondIns;
    document.getElementById('total').value = total;
}

function SubmitSearch(){
    document.getElementById("search").submit();
}

function ClassList(elem){
    let class_id = elem.value;
    document.getElementById("perclass").submit();
}

function MasterMarksheetPrint(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    if(year !== "" && cls !== ""){
        window.open('./pdf/MasterMarkSheetPdf.php?year_id='+year+'&class_id='+cls);
    }else{
        alert("You must select the two parameters: year and class");
    }
}

function MarksheetPrint(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    let subj = $('#subjects').val();
    if(year !== "" && cls !== ""){
        window.open('./pdf/MarkSheetPdf.php?year_id='+year+'&class_id='+cls+'&subject='+subj);
    }else{
        alert("You must select the three parameters : Year, Class and subject");
    }
}


function AnnualSummary(){
    let year = $('#term_year').val();
    let cls = $('#term_class').val();

    if(year !== "" && cls !== ""){
        window.open('./pdf/annualSummaryPDF.php?year_id='+year+'&class_id='+cls);
    }else{
        alert("You must select the two parameters : Year and Class");
    }
    
}

function CodeList(elem){
    let class_id = elem.value;
    $.ajax({
        type: "POST",
        url: "./html/ajax.php",
        data: {classID:class_id, 'action':"CodeList"},
        dataType: 'html',
        success: function (data) {
            $('#class_list').html(data);
        },
        error: function () {
            console.log(Error().message);
        }
    });
}

function LoadTerms(tag){
    let yr = $('#term_year').val();
    if (yr != ""){
        $.ajax({
            type: "POST",
            url: "./html/ajax.php",
            data: {year:yr, 'action':"LoadTerms"},
            dataType: 'html',
            success: function (data) {
                $('#'+tag).html(data);
            },
            error: function () {
                console.log(Error().message);
            }
        });
    }
}

function LoadSequences(tag){
    let yr = $('#year').val();
    if (yr != ""){
        $.ajax({
            type: "POST",
            url: "./html/ajax.php",
            data: {year:yr, 'action':"LoadSequences"},
            dataType: 'html',
            success: function (data) {
                $('#'+tag).html(data);
            },
            error: function () {
                console.log(Error().message);
            }
        });
    }
}


function ShowPromotionList(){
    let cls = $('#curr_class').val();
    let yr = $('#curr_year').val();

    if(cls !== "" && yr !== "" ){
        $.ajax({
            type: "POST",
            url: "./html/ajax.php",
            data: {klass:cls, year:yr, 'action':"ShowPromotionList"},
            dataType: 'html',
            success: function (data) {
                $('#promote_list').html(data);
            },
            error: function () {
                console.log(Error().message);
            }
        });
    }else{
        alert("You must select the three parameters: exam, class and subject");
    }
}

function ResetUserPassword(userid){
    if(confirm("Are you sure you want to reset this user's password? It can't be undone \n Voulez-vous vraiment réinitialiser le mot de passe de cet utilisateur ? Il ne peut pas être annulé")){
        $.ajax({
            type: "POST",
            url: "./html/ajax.php",
            data: {user:userid, 'action':"ResetPw"},
            dataType: 'html',
            success: function (data) {
                alert(data);
            },
            error: function () {
                console.log(Error().message);
            }
        });
    }
}

function ShowClosedExamList(num){
    if(num !== undefined && num !== null && num !== ''){
        let exam = $('#exam2').val();
        let klass = $('#c_name2').val();
        let subject = $('#subjects2').val();
        if(exam !== "" && klass !== "" && subject !== ""){
            $.ajax({
                type: "POST",
                url: "./html/ajax.php",
                data: {Exam:exam, Klass:klass, Subject:subject, 'action':"ShowClosedExamList"},
                dataType: 'html',
                success: function (data) {
                    $('#closed_exam_list_copy').html(data);
                },
                error: function () {
                    console.log(Error().message);
                }
            });
        }else{
            alert("You must select the three parameters: exam, class and subject");
        }
    }else{
        let exam = $('#exam').val();
        let klass = $('#c_name').val();
        let subject = $('#subjects').val();
        if(exam !== "" && klass !== "" && subject !== ""){
            $.ajax({
                type: "POST",
                url: "./html/ajax.php",
                data: {Exam:exam, Klass:klass, Subject:subject, 'action':"ShowClosedExamList"},
                dataType: 'html',
                success: function (data) {
                    $('#closed_exam_list').html(data);
                },
                error: function () {
                    console.log(Error().message);
                }
            });
        }else{
            alert("You must select the three parameters: exam, class and subject");
        }
    }
}

function CopyMarks(){
        let exam = $('#exam').val();
        let klass = $('#c_name').val();
        let subject = $('#subjects').val();

        let exam2 = $('#exam2').val();
        let klass2 = $('#c_name2').val();
        let subject2 = $('#subjects2').val();

        if (exam == exam2) {
            alert("You can't copy an exam to itself");
            return;
        }

        if (klass !== klass2){
            alert("Unmatched Class");
            return;
        }

        if (subject !== subject2){
            alert("Unmatched Subject");
            return;
        }

        if(exam !== "" && klass !== "" && subject !== "" && exam2 !== "" && klass2 !== "" && subject2 !== ""){
            if(confirm("Are you sure you want to copy these marks to the selected exam?")){
                ShowBlankExam();
                $.ajax({
                    type: "POST",
                    url: "./html/ajax.php",
                    data: {Exam:exam, Klass:klass, Subject:subject, Exam2:exam2, Klass2:klass, Subject2:subject2, 'action':"CopyMarks"},
                    dataType: 'html',
                    success: function (data) {
                        $('#closed_exam_list_copy').html(data);
                    },
                    error: function () {
                        console.log(Error().message);
                    }
                });
            }
        }else{
            alert("You must select the six parameters: exam, class and subject");
        }
}

function ShowBlankExam(){
        let exam = $('#exam').val();
        let klass = $('#c_name').val();
        let subject = $('#subjects').val();
        if(exam !== "" && klass !== "" && subject !== ""){
            $.ajax({
                type: "POST",
                url: "./html/ajax.php",
                data: {Exam:exam, Klass:klass, Subject:subject, 'action':"ShowBlankExam"},
                dataType: 'html',
                success: function (data) {
                    $('#closed_exam_list').html(data);
                },
                error: function () {
                    console.log(Error().message);
                }
            });
        }else{
            alert("You must select the three parameters: exam, class and subject");
        }
}

function SetClassId(elem, num){
    let class_id = elem.value;
if(num !== undefined && num !== null && num !== ''){
    $.ajax({
        type: "POST",
        url: "./html/ajax.php",
        data: {classID:class_id,'action':"SetClassId"},
        dataType: 'html',
        success: function (data) {
            $('#subjects'+num).html(data); 
        },
        error: function () {
            console.log(Error().message);
        }
    });
}else{
    $.ajax({
        type: "POST",
        url: "./html/ajax.php",
        data: {classID:class_id,'action':"SetClassId"},
        dataType: 'html',
        success: function (data) {
            $('#subjects').html(data); 
        },
        error: function () {
            console.log(Error().message);
        }
    });
} 
}

function TermSummary(){
    let year = $('#term_year').val();
    let cls = $('#term_class').val();
    let term = $('#term_term').val();

    if(year !== "" && cls !== "" && term !== ""){
        window.open('./pdf/TermSummaryPdf.php?&year_id='+year+'&class_id='+cls+'&term_id='+term+'&type=term');
    }else{
        alert("You must select the three parameters : Year, Class and Term");
    }
    
}

function SequenceSummary(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    let exam = $('#exam').val();

    if(year !== "" && cls !== "" && exam !== ""){
        window.open('./pdf/SequenceSummaryPdf.php?year_id='+year+'&class_id='+cls+'&exam_id='+exam+'&type=seq');
    }else{
        alert("You must select the three parameters : Year, Class and Examination");
    }
}

function GenerateSchoolIDs(){
    let year_id = $('#year').val();
    let cls = $('#c_name').val();
    let expire = $('#expire').val();
    if(year !== "" && cls !== "" && expire !== ""){
        window.open('./pdf/IdCardPdf.php?year_id='+year_id+'&class='+cls+'&expire='+expire, '_blank');
    }else{
        alert("You must select the three parameters: year, class and expiration");
    }
}

function SaveAbsence(elem){
    let student_id = elem.id;
    let abs = elem.value;
    let klas = $('#'+student_id).attr('data-klass');
    let term = $('#'+student_id).attr('data-term');
    let year = $('#'+student_id).attr('data-year');
    let typ = "";

    if($('#abs').is(':checked')){
        typ = 'abs'
    }else if($('#pun').is(':checked')){
        typ = 'pun'
    }else if($('#war').is(':checked')){
        typ = 'war'
    }else if($('#sus').is(':checked')){
        typ = 'sus'
    }else if($('#justabs').is(':checked')){
        typ = 'justabs'
    }
   
    if (abs !== "" && abs !== undefined && typ !== ""){
       
        $.ajax({
            type: "POST",
            url: "./html/ajax.php",
            data: {studentID:student_id,Abs:abs,classID:klas,Term:term,Year:year,Type:typ,'action':"SaveAbsent"},
            dataType: 'html',
            success: function (data) {
                $('#result').html(data); 
            },
            error: function () {
                console.log(Error().message);
            }
        });
        
    }
}

function AbsencesList(){
    let year_id = $('#year').val();
    let cls = $('#c_name').val();
    let term = $('#term_term').val();
    let typ = "";

    if($('#abs').is(':checked')){
        typ = 'abs'
    }else if($('#pun').is(':checked')){
        typ = 'pun'
    }else if($('#war').is(':checked')){
        typ = 'war'
    }else if($('#sus').is(':checked')){
        typ = 'sus'
    }else if($('#justabs').is(':checked')){
        typ = 'justabs'
    }
        
    if(year !== "" && cls !== "" && term !== "" && typ !== ""){
        
        $.ajax({
            type: "POST",
            url: "./html/ajax.php",
            data: {Year:year_id,klass:cls, Term:term, 'action':"AbsencesList"},
            dataType: 'html',
            success: function (data) {
                $('#class_list').html(data);
            },
            error: function () {
                console.log(Error().message);
            }
        });
        
    }else{
        alert("You must select the four parameters: year, class and term and type");
    }
}

function ClassMasterSheet(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    let exam = $('#exam').val();
    if(year !== "" && cls !== "" && exam !== ""){
        window.location.href = 'index.php?p=mockClassSheet&year_id='+year+'&class_id='+cls+'&exam_id='+exam;
    }else{
        alert("You must select the three parameters: year, class and exam");
    }
}

function MasterSheet(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    let exam = $('#exam').val();
    if(year !== "" && cls !== "" && exam !== ""){
        window.location.href = 'index.php?p=mockMasterSheet&year_id='+year+'&class_id='+cls+'&exam_id='+exam;
    }else{
        alert("You must select the three parameters: year, class and exam");
    }
}

function MockStats(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    let exam = $('#exam').val();
    if(year !== "" && cls !== "" && exam !== ""){
        window.location.href = 'index.php?p=mockStatistics&year_id='+year+'&class_id='+cls+'&exam_id='+exam;
    }else{
        alert("You must select the three parameters: year, class and exam");
    }
}

function MockReport(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    let exam = $('#exam').val();
    if(year !== "" && cls !== "" && exam !== ""){
        window.location.href = './?p=MockResults&year_id='+year+'&class_id='+cls+'&exam_id='+exam;
    }else{
        alert("You must select the three parameters: year, class and exam");
    }
}

function SequenceStatPDF(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    let exam = $('#exam').val();
    if(year !== "" && cls !== "" && exam !== ""){
        window.open('./pdf/sequenceStatsPdf.php?year_id='+year+'&subject='+cls+'&exam_id='+exam, '_blank');
    }else{
        alert("You must select the three parameters: year, subject and exam");
    }
}

function SequenceStatAnnualPDF(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    if(year !== "" && cls !== ""){
        window.open('./pdf/sequenceStatsAnnualPdf.php?year_id='+year+'&subject='+cls, '_blank');
    }else{
        alert("You must select the parameters: year and subject");
    }
}

function SequenceStats(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    let exam = $('#exam').val();
    if(year !== "" && cls !== "" && exam !== ""){
        $.ajax({
            type: "POST",
            url: "./html/ajax.php",
            data: {Year:year, klass:cls, Exam:exam, 'action':"SequenceStats"},
            dataType: 'html',
            success: function (data) {
                $('#class_list').html(data);
            },
            error: function () {
                console.log(Error().message);
            }
        });
    }else{
        alert("You must select the three parameters: year, subject and exam");
    }
}

function SequenceReport(){
    let year = $('#year').val();
    let cls = $('#c_name').val();
    let exam = $('#exam').val();

    if(year !== "" && cls !== "" && exam !== ""){
        window.open('./pdf/SequenceRepPdf.php?year_id='+year+'&class_id='+cls+'&exam_id='+exam);
    }else{
        alert("You must select the three parameters : Year, Class and Examination");
    }
    
}

function TermReport(){
    let year = $('#term_year').val();
    let cls = $('#term_class').val();
    let term = $('#term_term').val();

    if(year !== "" && cls !== "" && term !== ""){
        window.open('./pdf/TermRepPdf.php?year_id='+year+'&class_id='+cls+'&term_id='+term);
    }else{
        alert("You must select the three parameters : Year, Class and Term");
    }
    
}

function BritishTermReport(){
    let year = $('#term_year').val();
    let cls = $('#term_class').val();
    let term = $('#term_term').val();

    if(year !== "" && cls !== "" && term !== ""){
        window.open('./pdf/BritishTermRepPdf.php?year_id='+year+'&class_id='+cls+'&term_id='+term);
    }else{
        alert("You must select the three parameters : Year, Class and Term");
    }
    
}

function GenderStats(){
    let year_id = document.getElementById('year').value;
    if(year_id !== "" ){
        window.open('./pdf/GenderStatsPdf.php?year_id='+year_id);
    }else{
        alert("You must select the parameter: year");
    }
}


function PrintableEnrolment(elem){
    let year_id = elem.value;
    $.ajax({
        type: "POST",
        url: "./html/ajax.php",
        data: {Year:year_id, 'action':"PrintableEnrolment"},
        dataType: 'html',
        success: function (data) {
            $('#class_list').html(data);
        },
        error: function () {
            console.log(Error().message);
        }
    });
}

function PrintableClassList(elem){
    let class_id = elem.value;
    $.ajax({
        type: "POST",
        url: "./html/ajax.php",
        data: {classID:class_id, 'action':"PrintableClassList"},
        dataType: 'html',
        success: function (data) {
            $('#class_list').html(data);
        },
        error: function () {
            console.log(Error().message);
        }
    });
}

function ShowClassList(class_id, subject){
    $('#loading').show();
    $('#c_list').html("");
    $.ajax({
        type: "POST",
        url: "./html/ajax.php",
        data: {classId:class_id, Subject:subject, 'action':"ShowClassList"},
        dataType: 'html',
        success: function (data) {
            $('#c_list').html(data);
        },
        error: function () {
            console.log(Error().message);
        }
    });
}

function ShowFillMarksList(class_id, subject){
    $('#loading').show();
    $('#c_list').html("");
    $.ajax({
        type: "POST",
        url: "./html/ajax.php",
        data: {classId:class_id, Subject:subject, 'action':"ShowFillMarksList"},
        dataType: 'html',
        success: function (data) {
            $('#c_list').html(data);
        },
        error: function () {
            console.log(Error().message);
        }
    });
}

function SaveMark(elem){
    let student_id = elem.id;
    let mark = elem.value;
    let klas = $('#'+student_id).attr('data-klass');
    let exam = $('#'+student_id).attr('data-exam');
    let subject = $('#'+student_id).attr('data-subject');
    let comp = $('#competence').val();


    if (mark !== "" && mark !== undefined){
        $.ajax({
            type: "POST",
            url: "./html/ajax.php",
            data: {studentID:student_id, mark:mark, classID:klas, examID:exam, Subject:subject, competence:comp, 'action':"SaveMark"},
            dataType: 'html',
            success: function (data) {
                $('#result').html(data);
            },
            error: function () {
                console.log(Error().message);
            }
        });
    }
}

function GetAPage(pageName){

    let path = './html/' + pageName;
    fetch(path)
        .then((response) => {
        return response.text();
    })
    .then((html) => {
        document.getElementById('container').innerHTML = html
    });
}

function GotoPage(page){
    window.location = "./?p=" + page;
}

function Goto(){
    window.location = "./";
}

function CheckLoggedInUser(){
    let path = './includes/testUser.php';
    fetch(path)
        .then((response) => {
        return response.text();
    })
    .then((html) => {
        document.getElementById('container').innerHTML = html
    });
}

function DownloadCSVFile(){
    let ref = document.getElementById('c_name').value
    if(ref == ''){
        return
    }else{
        window.location.href = './csv/saveClassCSV.php'+'?ref='+ref;
    }
    
}

function PhoneList(elem){
    let class_id = elem.value;
    $.ajax({
        type: "POST",
        url: "./html/ajax.php",
        data: {classID:class_id, 'action':"PhoneList"},
        dataType: 'html',
        success: function (data) {
            $('#class_list').html(data);
        },
        error: function () {
            console.log(Error().message);
        }
    });
}

//////// END Functionality Functions///////////////////////
//////////////////////////////////////////////////////////

//Design functions
function SetPointer(elem){
    elem.style.cursor = "pointer";
}
