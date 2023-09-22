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
include "../includes/PrimaryModel.php";
include "../includes/Lang.php";
$Model = new Model($section);
$Primodel = new PrimaryModel($section);
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
$crit = $_GET['crit'];
$amt = $_GET['amt'];
$total = 0; $g_total =0;

if ($class_id == ''){
    $classes = $Primodel->GetAllPrimaryClasses();
    $pdf = new PDF();
//header
    $pdf->AddPage();
//footer page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(30, 7, "", 0);
    $pdf->Ln();
    $pdf->Cell(40, 7, "", 0);
    foreach ($classes as $class){
        $class_total = 0;
        $students = $Model->FeeDrive($year_id, $class['id']);
        $pdf->Cell(30, 7, "", 0);
        $pdf->Ln();
        $pdf->Cell(40, 7, "", 0);
        $pdf->Cell(10,7,$Model->GetYearName($year_id)." - ".$Model->GetAClassName($class['id'])." - FEES LIST",0);
        $pdf->Ln();
        $pdf->Cell(114,7,strToUpper($lang[$_SESSION['lang']]['Name']),1);
        $pdf->Cell(60,7,strToUpper($lang[$_SESSION['lang']]["Amount"]),1);
        $pdf->Ln();
        foreach ($students as $stud){
            $left = $Model->GetFeesLeft($stud['code'], $class['id'], $year_id);
            if ($crit == '>'){
                if ($left > $amt){
                    $class_total += $left;
                    $g_total += $left;
                    $pdf->Cell(114,7,$Primodel->GetPupil($stud['code'])[0]['name'],1);
                    $pdf->Cell(60,7,$Model->Figure($left),1);
                    $pdf->Ln();
                }
            }else if ($crit == '<'){
                if ($left < $amt){
                    $class_total += $left;
                    $g_total += $left;
                    $pdf->Cell(114,7,$Primodel->GetPupil($stud['code'])[0]['name'],1);
                    $pdf->Cell(60,7,$Model->Figure($left),1);
                    $pdf->Ln();
                } 
            }else{
                if ($left == $amt){
                    $class_total += $left;
                    $g_total += $left;
                    $pdf->Cell(114,7,$Primodel->GetPupil($stud['code'])[0]['name'],1);
                    $pdf->Cell(60,7,$Model->Figure($left),1);
                    $pdf->Ln();
                }
            }
        }
        $pdf->Cell(114,7,"TOTAL",1);
        $pdf->Cell(60,7,$Model->Figure($class_total),1);
        $pdf->Ln();
    }
    $pdf->Ln();
    $pdf->Cell(114,7,"GRAND TOTAL",1);
    $pdf->Cell(60,7,$Model->Figure($g_total),1);
}else{

$students = $Model->FeeDrive($year_id, $class_id);

$pdf = new PDF();
//header
    $pdf->AddPage();
//foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(30, 7, "", 0);
    $pdf->Ln();
    $pdf->Cell(40, 7, "", 0);
    $pdf->Cell(10,7,$Model->GetYearName($year_id)." - ".$Model->GetAClassName($class_id)." - FEES LIST",0);
    $pdf->Ln();
    $pdf->Cell(114,7,strToUpper($lang[$_SESSION['lang']]['Name']),1);
    $pdf->Cell(60,7,strToUpper($lang[$_SESSION['lang']]["Amount"]),1);
    $pdf->Ln();
    foreach ($students as $stud){
        $left = $Model->GetFeesLeft($stud['code'], $class_id, $year_id);
        if ($crit == '>'){
            if ($left > $amt){
                $total += $left;
                $pdf->Cell(114,7,$Primodel->GetPupil($stud['code'])[0]['name'],1);
                $pdf->Cell(60,7,$Model->Figure($left),1);
                $pdf->Ln();
            }
        }else if ($crit == '<'){
            if ($left < $amt){
                $total += $left;
                $pdf->Cell(114,7,$Primodel->GetPupil($stud['code'])[0]['name'],1);
                $pdf->Cell(60,7,$Model->Figure($left),1);
                $pdf->Ln();
            } 
        }else{
            if ($left == $amt){
                $total += $left;
                $pdf->Cell(114,7,$Primodel->GetPupil($stud['code'])[0]['name'],1);
                $pdf->Cell(60,7,$Model->Figure($left),1);
                $pdf->Ln();
            }
        }
    }
    $pdf->Cell(114,7,"TOTAL",1);
    $pdf->Cell(60,7,$Model->Figure($total),1);
}
$pdf->Output();
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}