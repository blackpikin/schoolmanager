<?php
session_start();
if (isset($_SESSION['id'])){
    if (time() - $_SESSION['timer']  > 3400 ){
        session_destroy();
        header('Location: ../');
    }else{
        $_SESSION['timer'] = time();
    }
}

$lng = $_SESSION['lang'];
    $section = 0;
    if($lng == 'fr'){
        $section = 1;
    }
if(isset($_SESSION['username']) && $_SESSION['username'] !== ""){
//include connection file
include "../includes/Model.php";
include "../includes/Lang.php";
$Model = new Model($section);
include_once('../lib/fpdf.php');
 
class PDF extends FPDF
{
// Page header
function Header()
    {
        // Logo
        $this->Image('../img/letterhead.png',50,2,200);
       
        // Line break
        $this->Ln(30);
    }
 
    // Page footer
    function Footer()
    {
        $this->Image('../img/footer.png',2,260,200);
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        
    }
}
    $year_id = $_GET['year_id'];
    $class_id = $_GET['class_id'];

    /*
    $students = $Model->StudentCodesPerYear($class_id, $year_id);
    $result = [];
    $mixed_stds = [];
    if(!empty($students)){
        foreach($students as $student){
            $std = $Model->GetStudent($student['student_code']);
            $mixed_stds[$student['student_code']] = $std[0]['name'];
        }

        asort($mixed_stds);
    }
*/

    $averages = $Model->GetAnnualAverage($year_id, $class_id);
    $students = $Model->GetAnnualPosition($year_id, $class_id);
    $term1 = $Model->GetTermAverageForStudent($year_id, $class_id, 'FIRST TERM');
    $term2 = $Model->GetTermAverageForStudent($year_id, $class_id, 'SECOND TERM');
    $term3 = $Model->GetTermAverageForStudent($year_id, $class_id, 'THIRD TERM');
    $marksPerYear = $Model->MarksForSubjectPerYear($year_id, $class_id);

    $className = $Model->GetAClass($class_id)[0]['general_name'].' '.$Model->GetAClass($class_id)[0]['sub_name'];
    $exams = $Model->GetExamsForYear($year_id, $class_id, $section);
    $subjects = $Model->ViewClassSubjects($class_id);
    $sn = 1;
    //Generate PDF file//
///////////////////
$pdf = new PDF();
//header
    $pdf->AddPage('O');
//foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(10,7,strToUpper($lang[$_SESSION['lang']]['mastermarksheet'] .' - ').$className." - ".$Model->GetYearName($year_id),0);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(5,7,'SN',1);
    $pdf->Cell(50,7,$lang[$_SESSION['lang']]['Name'] ,1);
    $pdf->Cell(8,7,$lang[$_SESSION['lang']]['Gender'] ,1);
    foreach($subjects as $s){
        $pdf->Cell(9,7,substr($s['subject'], 0, 4),1);
    }
    $pdf->Cell(10,7,$lang[$_SESSION['lang']]['termShort'].'1',1);
    $pdf->Cell(10,7,$lang[$_SESSION['lang']]['termShort'].'2',1);
    $pdf->Cell(10,7,$lang[$_SESSION['lang']]['termShort'].'3',1);
    $pdf->Cell(10,7,$lang[$_SESSION['lang']]['annual'] ,1);
    $pdf->Cell(8,7,$lang[$_SESSION['lang']]['Rank'] ,1);
    $pdf->Cell(8,7,'Abs',1);

    $pdf->Ln();
    $pdf->SetFont('Arial','',7);
    foreach($averages as $code => $annual){
        $st = $Model->GetStudent($code, $section);
        $pdf->Cell(5,7,$sn++,1);
        $pdf->Cell(50,7,$st[0]['name'],1);
        $pdf->Cell(8,7,$st[0]['gender'],1);
        foreach($subjects as $s){
            $key = $code.$s['subject'];
            $pdf->Cell(9,7,$marksPerYear[$key],1);
        }

        if(!empty($term1)){
            $pdf->Cell(10,7,$term1[$code],1);
        }else{
            $pdf->Cell(10,7,'',1);
        }

        if(!empty($term2)){
            $pdf->Cell(10,7,$term2[$code],1);
        }else{
            $pdf->Cell(10,7,'',1);
        }

        if(!empty($term3)){
            $pdf->Cell(10,7,$term3[$code],1);
        }else{
            $pdf->Cell(10,7,'',1);
        }
        
        $pdf->Cell(10,7,$averages[$code],1);
        $pdf->Cell(8,7,$students[$code],1);
        $pdf->Cell(8,7,$Model->CountAbsencesYear($year_id, $class_id, $code),1);
        $pdf->Ln();
    }

    $pdf->Output();
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}