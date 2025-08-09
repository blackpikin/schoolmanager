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
$averages = $Model->GetAnnualAverage($year_id, $class_id);
$students = $Model->GetAnnualPosition($year_id, $class_id);
$term1 = $Model->GetTermAverageForStudent($year_id, $class_id, 'FIRST TERM');
$term2 = $Model->GetTermAverageForStudent($year_id, $class_id, 'SECOND TERM');
$term3 = $Model->GetTermAverageForStudent($year_id, $class_id, 'THIRD TERM');

$pdf = new PDF();
//header
    $pdf->AddPage();
//foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(30, 7, "", 0);
    $pdf->Ln();
    $pdf->Cell(40, 7, "", 0);
    $pdf->Cell(10,7,$lang[$_SESSION['lang']]["AnnSumm"]." - ".$Model->GetAClassName($class_id)." - ".$Model->YearNameDigits($year_id),0);
    $pdf->Ln();
    $pdf->Cell(99,7,$lang[$_SESSION['lang']]['Name'],1);
    $pdf->Cell(15,7,$lang[$_SESSION['lang']]["Gender"],1);
    $pdf->Cell(15,7,$lang[$_SESSION['lang']]["termShort"].'1',1);
    $pdf->Cell(15,7,$lang[$_SESSION['lang']]["termShort"].'2',1);
    $pdf->Cell(15,7,$lang[$_SESSION['lang']]["termShort"].'3',1);
    $pdf->Cell(15,7,$lang[$_SESSION['lang']]["annual"],1);
    $pdf->Cell(15,7,'Remark',1);
    $pdf->Ln();
    foreach ($students as $code => $annual){
        $s = $Model->GetStudent($code, $section);
        $pdf->Cell(99,7,$s[0]['name'],1);
        $pdf->Cell(15,7,$s[0]['gender'],1);
        if(!empty($term1)){
            $pdf->Cell(15,7,$term1[$code],1);
        }else{
            $pdf->Cell(15,7,'',1);
        }
        
        if(!empty($term2)){
            $pdf->Cell(15,7,$term2[$code],1);
        }else{
            $pdf->Cell(15,7,'',1);
        }
        if(!empty($term3)){
            $pdf->Cell(15,7,$term3[$code],1);
        }else{
            $pdf->Cell(15,7,'',1);
        }
        $pdf->Cell(15,7,$averages[$code] ,1);
        $pdf->Cell(15,7,'',1);
        $pdf->Ln();
    }

    $pdf->Output();
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}