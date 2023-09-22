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
        $this->Image('../img/letterhead.png',2,2,200);
       
        // Line break
        $this->Ln(30);
    }
 
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}
///////////////////////////////////////////////////////////////////////////////
$year_id = $_GET['year_id'];
$class_id = $_GET['class_id'];
$term_id = $_GET['term_id'];
$sequences = ['ONE'=>$lang[$_SESSION['lang']]['ONE'], 'TWO'=>$lang[$_SESSION['lang']]["TWO"]];
$terms = ['FIRST TERM'=>$lang[$_SESSION['lang']]["FIRST TERM"], 'SECOND TERM'=>$lang[$_SESSION['lang']]["SECOND TERM"], 'THIRD'=>$lang[$_SESSION['lang']]["THIRD TERM"]];
$page_title = $terms[strToUpper($term_id).' TERM']." - ".utf8_decode($lang[$_SESSION['lang']]["resultSummary"])." - ".$Model->GetAClassName($class_id);


$averages = $Model->GetTermAverageForStudent($year_id, $class_id, strToUpper($term_id).' TERM');
$positions = $Model->GetPosition($year_id, $class_id, strToUpper($term_id).' TERM');
$passed = 0;
foreach($averages as $av){
    if($av >= 10){
        $passed++;
    }
}

if(!empty($averages)){
    $percentPass = round(($passed/count($averages)*100), 2);
    $pdf = new PDF();
    //header
    $pdf->AddPage();
//foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(30, 7, "", 0);
    $pdf->Ln();
    $pdf->Cell(40, 7, "", 0);
    $pdf->Cell(10,7,strToUpper($page_title),0);
    $pdf->Ln();
    $pdf->Cell(30, 7, $lang[$_SESSION['lang']]["Onroll"].": ".count($averages), 0);
    $pdf->Cell(30, 7, $lang[$_SESSION['lang']]["Passed"].": ".$passed, 0);
    $pdf->Cell(50, 7, $lang[$_SESSION['lang']]["PercentPass"].": ".$percentPass, 0);
    $pdf->Cell(30, 7, $lang[$_SESSION['lang']]["ClassAv"]." ".$Model->ClassAverageForTerm($year_id, $class_id, strToUpper($term_id).' TERM'), 0);
    $pdf->Ln();
    $pdf->Cell(22,7,$lang[$_SESSION['lang']]["Rank"],1);
    $pdf->Cell(99,7,$lang[$_SESSION['lang']]["Name"],1);
    $pdf->Cell(20,7,$lang[$_SESSION['lang']]["Gender"],1);
    $pdf->Cell(20,7,$lang[$_SESSION['lang']]["Average"],1);
    foreach ($averages as $student_code => $average){
        $pdf->Ln();
        $s = $Model->GetStudent($student_code, $section);
        $pdf->Cell(22,7,$positions[$student_code],1);
        $pdf->Cell(99,7,$s[0]['name'],1);
        $pdf->Cell(20,7, $s[0]['gender'],1);
        $pdf->Cell(20,7, $average,1);
     }
     $pdf->Output();
}else{
    echo 'Could not generate. Make sure all exams have been written and computed';
}

    }else{
        echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
    }
