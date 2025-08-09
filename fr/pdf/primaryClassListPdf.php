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
include '../includes/Lang.php';
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
 
    $class_id = $_GET['ref'];
    $students = $Model->StudentCodesPerYear($class_id, $Model->GetCurrentYear()[0]['id']);
    $result = [];
    $mixed_stds = [];
    if(!empty($students)){
        foreach($students as $student){
            $std = $Primodel->GetPupil($student['student_code']);
            $mixed_stds[$student['student_code']] = $std[0]['name'];
        }

        asort($mixed_stds);
        $sn = 0;

        foreach($mixed_stds as $code => $student){
            array_push($result, ['sn'=>++$sn,'name'=>$student, 'gender'=>$Primodel->GetPupil($code)[0]['gender'],'t1'=>' ', 't2'=>' ', 't3'=>' ', 't4'=>' ', 't5'=>' ', 't6'=>' ' ]);
        }
    }

    $className = $Model->GetAClass($class_id)[0]['general_name'].' '.$Model->GetAClass($class_id)[0]['sub_name'];

//Generate PDF file//
///////////////////
$pdf = new PDF();
//header
    $pdf->AddPage();
//foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(10,7,$lang[$_SESSION['lang']]['Class lists'].': '.$className.' - '.$Model->GetYearName($Model->GetCurrentYear()[0]['id']),0);
    $pdf->Ln();
    $pdf->Cell(25,7,'SN',1);
    $pdf->Cell(75,7,$lang[$_SESSION['lang']]['Name'],1);
    $pdf->Cell(20,7,$lang[$_SESSION['lang']]['Gender'],1);
    $pdf->Cell(13,7,'T1',1);
    $pdf->Cell(13,7,'T2',1);
    $pdf->Cell(13,7,'T3',1);
    $pdf->Cell(13,7,'T4',1);
    $pdf->Cell(13,7,'T5',1);
    $pdf->Cell(13,7,'T6',1);

    $pdf->SetFont('Arial','',9);
    foreach($result as $row) {
        $pdf->Ln(7);
        foreach($row as $key=> $column){
            if($key == 'sn'){
                $pdf->Cell(25,7,$column,1);
            }elseif ($key == 'name'){
                $pdf->Cell(75,7,$column,1);
            }elseif($key == 'gender'){
                $pdf->Cell(20,7,$column,1);
            }else{
                $pdf->Cell(13,7,$column,1);
            }
        }
    }
    $pdf->Output();
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}
?>