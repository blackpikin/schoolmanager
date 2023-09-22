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
include '../includes/Lang.php';
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
///////////////////////////////////////////////////////////////////////////////
$year_id = $_GET['year_id'];
    $classes = $Model->GetAllClasses($section);
    //print_r($classes);
    $agesDDES = ['<=11', 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, '>=24'];
    $pdf = new PDF();
    //header
    $pdf->AddPage('O');
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(30, 7, "", 0);
    $pdf->Ln();
    $pdf->Cell(30, 7, "", 0);
    $pdf->SetFont('Times','B',14);
    $pdf->Cell(70, 7, "", 0);
    $pdf->Cell(10,7,$lang[$_SESSION['lang']]['Gender statistics']." - ".$Model->GetYearName($year_id),0,0);
    $pdf->Ln();

    $pdf->SetFillColor(0,0,128);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Times','B',9);
    $pdf->Cell(10,7,'Ages',1,0,'',true);
    foreach($classes as $class){
        $pdf->Cell(14,7,$Model->ShortClassName($class['id']),1,0,'',true);
    }
    $pdf->SetTextColor(0,0,0);    
    $pdf->Ln();
    $pdf->SetFont('Times','B',7);
    $pdf->Cell(10,7,$lang[$_SESSION['lang']]['years'],1);
    foreach($classes as $class){
        $pdf->Cell(7,7,$lang[$_SESSION['lang']]['boy'],1);
        $pdf->Cell(7,7,$lang[$_SESSION['lang']]['girl'],1);
    }
    $pdf->Ln();
    $pdf->SetFont('Times','',9);
    foreach ($agesDDES as $age){
        $pdf->Cell(10,7,$age,1);
        foreach($classes as $class){
           $pdf->Cell(7,7,$Model->StudentsInAgeRange($age, $class['id'], $year_id, 'M'),1);
           $pdf->Cell(7,7,$Model->StudentsInAgeRange($age, $class['id'], $year_id, 'F'),1);
        }
        $pdf->Ln();
    }
    $pdf->Output();
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}