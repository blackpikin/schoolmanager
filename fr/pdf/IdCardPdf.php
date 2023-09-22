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
        //$this->Image('../img/letterhead.png',2,2,200);
       
        // Line break
        //$this->Ln(30);
    }
 
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        //$this->SetY(-15);
        // Arial italic 8
        //$this->SetFont('Arial','I',8);
        // Page number
        //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}
///////////////////////////////////////////////////////////////////////////////
$pdf = new PDF();
//header
    $pdf->AddPage();
//footer page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',8);
 // Get data needed for the page   
$year_id = $_GET['year_id'];
$class_id = $_GET['class'];
$expire = $_GET['expire'];
$student_count = 0;

$students = $Model->GetStudentsInClass($class_id, $year_id);
foreach ($students as $i => $s){
    $student_count++;
    $data = $Model->GetStudent($s['student_code'], $section);
    if($i % 2 != 0){
        if ($data[0]['picture'] != ""){
            if($pdf->GetY() < 110){
                $pdf->SetXY(110, 10);
            }else{
                $pdf->SetXY($pdf->GetX()+40, $pdf->GetY()-46);
            }
            $pdf->Image('../img/idbkg.jpg',$pdf->GetX(),$pdf->GetY(),95, 52);
            //header information
            $pdf->Image('../img/idheader.png',$pdf->GetX(),$pdf->GetY()+1,95);
    
            //Image information
            $d = base64_decode($data[0]['picture']);
            $file = "../img/students/" . $data[0]["student_code"] . '.'.$data[0]["picture_ext"];
            $success = file_put_contents($file, $d);
            $pdf->Image($file, $pdf->GetX()+1, $pdf->GetY()+10, 33.78, 33.78);
            
            $pdf->SetTextColor(0,0,0);
            //student information
            $pdf->SetXY($pdf->GetX()+35, $pdf->GetY()+11);
            $pdf->Cell(15,7,$lang[$_SESSION['lang']]["Name"].":".$data[0]["name"],0);
            $pdf->SetXY($pdf->GetX()-15, $pdf->GetY()+7);
            $date = New DateTime($data[0]["dob"]);
            $pdf->Cell(15,7,$lang[$_SESSION['lang']]["DOB"].":". date_format($date, "d F Y"),0);
            $pdf->SetXY($pdf->GetX()-15, $pdf->GetY()+7);
            $pdf->Cell(15,7,$lang[$_SESSION['lang']]["Gender"].":".$data[0]["gender"],0);
            $pdf->SetXY($pdf->GetX()-15, $pdf->GetY()+7);
            $pdf->Cell(15,7,$lang[$_SESSION['lang']]["Class"].":".$Model->GetAClassName($class_id),0);
            $pdf->SetXY($pdf->GetX()-15, $pdf->GetY()+7);
            $date = New DateTime($expire);
            $pdf->Cell(15,7,$lang[$_SESSION['lang']]["expirationdate"].":".date_format($date, "d F Y"),0);
            $pdf->Ln();
            $pdf->SetFillColor(0,0,255);
            $pdf->SetTextColor(255,255,255);
            $pdf->SetXY(111, $pdf->GetY());
            $pdf->Cell(60,4,$lang[$_SESSION['lang']]["Academic year"].":".$Model->YearNameDigits($year_id),0, 0,"", true);
            $pdf->Ln();
        }
        
    }else{
        $pdf->Ln();
        if($data[0]['picture'] != ""){
            //bkg
        $pdf->Image('../img/idbkg.jpg',$pdf->GetX(),$pdf->GetY(),95, 52);
        //header information
        $pdf->Image('../img/idheader.png',$pdf->GetX(),$pdf->GetY()+1,95);

        //Image information
        $d = base64_decode($data[0]['picture']);
        $file = "../img/students/" . $data[0]["student_code"] . '.'.$data[0]["picture_ext"];
        $success = file_put_contents($file, $d);
        $pdf->Image($file, $pdf->GetX()+1, $pdf->GetY()+10, 33.78, 33.78);

        $pdf->SetTextColor(0,0,0);
        //student information
        $pdf->SetXY($pdf->GetX()+35, $pdf->GetY()+11);
        $pdf->Cell(15,7,$lang[$_SESSION['lang']]["Name"].":".$data[0]["name"],0);
        $pdf->SetXY($pdf->GetX()-15, $pdf->GetY()+7);
        $date = New DateTime($data[0]["dob"]);
        $pdf->Cell(15,7,$lang[$_SESSION['lang']]["DOB"].":".date_format($date, "d F Y"),0);
        $pdf->SetXY($pdf->GetX()-15, $pdf->GetY()+7);
        $pdf->Cell(15,7,$lang[$_SESSION['lang']]["Gender"].":".$data[0]["gender"],0);
        $pdf->SetXY($pdf->GetX()-15, $pdf->GetY()+7);
        $pdf->Cell(15,7,$lang[$_SESSION['lang']]["Class"].":".$Model->GetAClassName($class_id),0);
        $pdf->SetXY($pdf->GetX()-15, $pdf->GetY()+7);
        $date = New DateTime($expire);
        $pdf->Cell(15,7,$lang[$_SESSION['lang']]["expirationdate"].":".date_format($date, "d F Y"),0);
        $pdf->Ln();
        $pdf->SetFillColor(0,0,255);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(60,4,$lang[$_SESSION['lang']]["Academic year"].":".$Model->YearNameDigits($year_id),0, 0,"", true);
        }
    }

    if($student_count == 10){
        $pdf->AddPage();
        $student_count = 0;
    }
}

$pdf->Output();
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}