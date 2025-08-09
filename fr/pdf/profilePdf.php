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
$pdf = new PDF();
//header
    $pdf->AddPage();
//foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(30, 7, "", 0);
    $pdf->Ln();
    $pdf->Cell(60, 7, "", 0);
    $data = $Model->GetStudent($_GET['ref'], $section);
    if(empty($data)){
        $data = $Primodel->GetPupil($_GET['ref']);
    }

    $pdf->Cell(30,7,"STUDENT'S PROFILE",0);
    if($data[0]['picture'] != ""){
        $d = base64_decode($data[0]['picture']);
        $file = "../img/students/" . $data[0]["student_code"] . '.'.$data[0]["picture_ext"];
        $success = file_put_contents($file, $d);
        $pdf->Cell(35,30,$pdf->Image($file,160,55,20,20),0); 
    }
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(70,7,'Name',1);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(110,7,$data[0]['name'],1);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(70,7,'Gender',1);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(110,7,$data[0]['gender'],1);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(70,7,'Date of birth',1);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(110,7,$data[0]['dob'],1);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(70,7,'Place of birth',1);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(110,7,$data[0]['pob'],1);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(70,7,"Father",1);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(110,7,$data[0]['father_name'],1);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(70,7,"Mother",1);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(110,7,$data[0]['mother_name'],1);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(70,7,"Admission number",1);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(110,7,$data[0]['adm_num'],1);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(70,7,'Guardian',1);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(110,7,$data[0]['guardian'],1);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(70,7,"Guardian's number",1);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(110,7,$data[0]['guardian_number'],1);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(70,7,'Guardian\'s email',1);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(110,7,$data[0]['guardian_email'],1);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(70,7,'Guardian\' address',1);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(110,7,$data[0]['guardian_address'],1);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(70,7,'Code',1);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(110,7,$data[0]['student_code'],1);
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Cell(60, 7, "", 0);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(30,7,"STUDENT'S RESULTS",0);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(60,7,'Class',1);
    $pdf->Cell(40,7,'Term 1 Average',1);
    $pdf->Cell(40,7,'Term 2 Average',1);
    $pdf->Cell(40,7,'Term 3 Average',1);
    $pdf->Ln();
    
    $classes = $Model->TranscriptClasses($data[0]['student_code'], 'FIRST');
    foreach($classes as $class){
        $term1_av = $Model->TermAverage($data[0]['student_code'], 'First', $class['academic_year_id'], $class['class_id']);
        $term2_av = $Model->TermAverage($data[0]['student_code'], 'Second', $class['academic_year_id'], $class['class_id']);
        $term3_av = $Model->TermAverage($data[0]['student_code'], 'Third', $class['academic_year_id'], $class['class_id']);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(60,7,$Model->GetAClassName($class['class_id']).' - '.$Model->YearNameDigits($class['academic_year_id']),1);
        $pdf->Cell(40,7,$term1_av,1);
        $pdf->Cell(40,7,$term2_av,1);
        $pdf->Cell(40,7,$term3_av,1);
        $pdf->Ln();
    }

    
    $conducts = $Model->StudentConducts($_GET['ref']);
    if(!empty($conducts)){
        $pdf->Ln();
        $pdf->Cell(60, 7, "", 0);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(30,7,"OTHER INFORMATION",0);
        $pdf->Ln();
        $pdf->Cell(25,7,'Date',0);
        $pdf->Cell(155,7,'Description',0);
        foreach($conducts as $conduct){
            $pdf->Ln();
            $date = New DateTime($conduct['date']); 
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(25,7, date_format($date, "d-m-Y"),0);
            $pdf->MultiCell(155,7,$conduct['tittle']."\n".$conduct['description'],0);
            if($conduct['photo'] != ""){
                //$d = base64_decode($conduct['photo']);
                //$file = "../img/students/" . $conduct["student_code"] . '1.'.$conduct["photo_ext"];
                //$success = file_put_contents($file, $d);
            }
            //$pdf->Cell(50,7,$pdf->Image($file,160,55,45,45),0);
        }
    }

    $pdf->Output();
}else{
    echo '<h3>You have logged out or your session expired</h3>';
}
    
