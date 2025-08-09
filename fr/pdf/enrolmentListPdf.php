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

$year_id = $_GET['ref'];
//Get all classes available for the selected year
$classes = $Model->GetAllClasses($section);

$data = [];

$students = [];

//Get all students enrolled in each of the classes
$Gmales = 0; $Gfemales = 0; $Gtotal = 0;

foreach ($classes as $class){
    $students = $Model->GetStudentsInClass($class['id'], $year_id);
    $males = 0; $females = 0;

     //count Males and females and total
    foreach($students as $student){
        if($Model->GetStudent($student['student_code'],$section)[0]['gender'] == 'M'){
            $males++;
        }else{
            $females++;
        }
    }
    $total = $males + $females;
    array_push($data, ['class'=>$Model->GetAClassName($class['id']), 'males'=>$males, 'females'=>$females, 'total' => $total]);
    $Gmales += $males;
    $Gfemales += $females;
    $Gtotal += $total;
}

//calculate grand total
array_push($data, ['class'=>"Total", 'males' => $Gmales, 'females' => $Gfemales, 'total'=>$Gtotal]);


$yearName = $Model->GetYear($year_id)[0]['start'].'/'.$Model->GetYear($year_id)[0]['end'];


$pdf = new PDF();
//header
    $pdf->AddPage();
//foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(10,7,$lang[$_SESSION['lang']]["Enrolment statistics"].' - '.$yearName,0);
    $pdf->Ln();
    $pdf->Cell(45,7,'Classe',1);
    $pdf->Cell(45,7,utf8_decode($lang[$_SESSION['lang']]["Male"]),1);
    $pdf->Cell(45,7,utf8_decode($lang[$_SESSION['lang']]["Female"]),1);
    $pdf->Cell(45,7,'Total',1);
    
    foreach($data as $row) {
        $pdf->Ln(7);
        foreach($row as $key=> $column){
            if($key == 'class'){
                $pdf->Cell(45,7,$column,1);
            }elseif ($key == 'males'){
                $pdf->Cell(45,7,$column,1);
            }elseif($key == 'female'){
                $pdf->Cell(45,7,$column,1);
            }else{
                $pdf->Cell(45,7,$column,1);
            }
        }
    }
    $pdf->Output();
}else{
    echo '<h3>Vous avez été déconnecté ou la fenêtre de votre navigateur a expiré. Connectez-vous à nouveau</h3>';
}
