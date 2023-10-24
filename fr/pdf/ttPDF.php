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
        $this->Image('../img/pagebkg2.png',0,0,205);
        $this->Image('../img/letterhead.png',50,2,200);
       
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
$pdf = new PDF('L');
$type = $_GET['type'];

//Get required Information from DB
#School Classes
$classes = $Model->GetAllClasses($section);
$time_settings = $Model->ContentExists('time_settings', 'id', 1);
$weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$timeTable = [];
$t = $Model->ClassTeachers(1, 'Monday');
print_r($t);
if(!empty($time_settings)){
    if ($type == 'school'){
        foreach($classes as $class){
            $class_name = $class['general_name'].' '.$class['sub_name'];
            $timeTable[$class_name] = [
                'Day0'=> ['','','','','','','','','',''],
                'Day1'=> ['','','','','','','','','',''],
                'Day2'=> ['','','','','','','','','',''],
                'Day3'=> ['','','','','','','','','',''],
                'Day4'=> ['','','','','','','','','','']
            ];

            for ($j = 0; $j < 5; $j++){
                for ($i = 0; $i < 9; $i++){
                    if($timeTable[$class_name]["Day$j"][$i] == ''){
                       // $timeTable[$class_name]["Day$j"][$i] = $Model->TTSubject($class['id']);
                    }
                }
            }

            $pdf->AddPage('O');
            $pdf->AliasNbPages();
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(30,5, "", 0);
            $pdf->Ln();
            $pdf->Cell(30,5, "", 0);
            $pdf->SetFont('Times','B',14);
            $pdf->Cell(30,5, "", 0);
            $pdf->Cell(10,5,strToUpper($lang[$_SESSION['lang']]['ClassTimeTable']).': '.$class['general_name'].' '.$class['sub_name'].' - '.$Model->GetYearName($Model->GetCurrentYear()[0]['id']),0);
            $pdf->Ln();
            $pdf->SetFont('Arial','B',9);
            $pdf->Ln();
    
            if($class['general_name'] == 'FORM ONE' || $class['general_name'] == 'FORM TWO' || $class['general_name'] == 'FORM THREE'){
                $pdf->Cell(25,15,'Time           Day',1);
                $pdf -> Line(35, 55, 10, 70);
                $devotionStart = strtotime($time_settings[0]['startTime']) - 20*60;
                $startTime = strtotime($time_settings[0]['startTime']);
                $endTime = strtotime($time_settings[0]['startTime']) + $time_settings[0]['periodLen']*60;
                $pdf->Cell(20,15,date('H:i', $devotionStart).' - '.date('H:i', $startTime),1, 0, 'C');
                $pdf->Cell(25,15,date('H:i', $startTime).' - '.date('H:i', $endTime),1, 0, 'C');
    
                for($i = 2; $i< $time_settings[0]['oneToThree'] + 1; $i++){
                    if ($i == $time_settings[0]['breakAfter']+1){
                        $pdf->Cell(15,15,"BREAK",1, 0, 'C');
                        $startTime = $endTime + $time_settings[0]['breakLen'] * 60;
                        $endTime = $startTime + $time_settings[0]['periodLen']*60;
                        $pdf->Cell(25,15,date('H:i', $startTime).' - '.date('H:i', $endTime),1, 0, 'C');
                    }else{
                        $startTime = $endTime;
                        $endTime = $startTime + $time_settings[0]['periodLen']*60;
                        $pdf->Cell(25,15,date('H:i', $startTime).' - '.date('H:i', $endTime),1, 0, 'C');
                    }
                }
                $pdf->Ln();
                foreach($weekdays as $k => $day){
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(25,15,$day,1, 0, 'C');
                    $pdf->SetFont('Arial','B',9);
                    $devotionStart = strtotime($time_settings[0]['startTime']) - 20*60;
                    $startTime = strtotime($time_settings[0]['startTime']);
                    $endTime = strtotime($time_settings[0]['startTime']) + $time_settings[0]['periodLen']*60;
                    $pdf->Cell(20,15,'',1, 0, 'C');
                    $pdf->Cell(25,15,$timeTable[$class_name]["Day$k"][0],1, 0, 'C');
                    $slot = 0;
                    for($i = 2; $i< $time_settings[0]['oneToThree'] + 1; $i++){
                        ++$slot;
                        if ($i == $time_settings[0]['breakAfter']+1){
                            $pdf->SetFillColor(128,128,128);
                            $pdf->Cell(15,15," ",1, 0, 'C', true);
                            $startTime = $endTime + $time_settings[0]['breakLen'] * 60;
                            $endTime = $startTime + $time_settings[0]['periodLen']*60;
                            $pdf->Cell(25,15,$timeTable[$class_name]["Day$k"][$slot],1, 0, 'C');
                        }else{
                            $startTime = $endTime;
                            $endTime = $startTime + $time_settings[0]['periodLen']*60;
                            $pdf->Cell(25,15,$timeTable[$class_name]["Day$k"][$slot],1, 0, 'C');
                        }
                    }
                    $pdf->Ln();
                }
                
            }else if($class['general_name'] == 'FORM FOUR' || $class['general_name'] == 'FORM FIVE'){
                $pdf->Cell(25,15,'Time           Day',1);
                $pdf -> Line(35, 55, 10, 70);
                $devotionStart = strtotime($time_settings[0]['startTime']) - 20*60;
                $startTime = strtotime($time_settings[0]['startTime']);
                $endTime = strtotime($time_settings[0]['startTime']) + $time_settings[0]['periodLen']*60;
                $pdf->Cell(20,15,date('H:i', $devotionStart).' - '.date('H:i', $startTime),1, 0, 'C');
                $pdf->Cell(25,15,date('H:i', $startTime).' - '.date('H:i', $endTime),1, 0, 'C');
    
                for($i = 2; $i< $time_settings[0]['fourToFive'] + 1; $i++){
                    if ($i == $time_settings[0]['breakAfter']+1){
                        $pdf->Cell(15,15,"BREAK",1, 0, 'C');
                        $startTime = $endTime + $time_settings[0]['breakLen'] * 60;
                        $endTime = $startTime + $time_settings[0]['periodLen']*60;
                        $pdf->Cell(25,15,date('H:i', $startTime).' - '.date('H:i', $endTime),1, 0, 'C');
                    }else{
                        $startTime = $endTime;
                        $endTime = $startTime + $time_settings[0]['periodLen']*60;
                        $pdf->Cell(25,15,date('H:i', $startTime).' - '.date('H:i', $endTime),1, 0, 'C');
                    }
                }
                $pdf->Ln();
                foreach($weekdays as $day){
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(25,15,$day,1, 0, 'C');
                    $pdf->SetFont('Arial','B',9);
                    $devotionStart = strtotime($time_settings[0]['startTime']) - 20*60;
                    $startTime = strtotime($time_settings[0]['startTime']);
                    $endTime = strtotime($time_settings[0]['startTime']) + $time_settings[0]['periodLen']*60;
                    $pdf->Cell(20,15,'',1, 0, 'C');
                    $pdf->Cell(25,15,'',1, 0, 'C');
                    for($i = 2; $i< $time_settings[0]['fourToFive'] + 1; $i++){
                        if ($i == $time_settings[0]['breakAfter']+1){
                            $pdf->SetFillColor(128,128,128);
                            $pdf->Cell(15,15," ",1, 0, 'C', true);
                            $startTime = $endTime + $time_settings[0]['breakLen'] * 60;
                            $endTime = $startTime + $time_settings[0]['periodLen']*60;
                            $pdf->Cell(25,15,' ',1, 0, 'C');
                        }else{
                            $startTime = $endTime;
                            $endTime = $startTime + $time_settings[0]['periodLen']*60;
                            $pdf->Cell(25,15,' ',1, 0, 'C');
                        }
                    }
                    $pdf->Ln();
                }
                
            }else if($class['general_name'] == 'LOWER SIXTH' || $class['general_name'] == 'UPPER SIXTH'){
                $pdf->Cell(25,15,'Time           Day',1);
                $pdf -> Line(35, 55, 10, 70);
                $devotionStart = strtotime($time_settings[0]['startTime']) - 20*60;
                $startTime = strtotime($time_settings[0]['startTime']);
                $endTime = strtotime($time_settings[0]['startTime']) + $time_settings[0]['periodLen']*60;
                $pdf->Cell(22,15,date('H:i', $devotionStart).' - '.date('H:i', $startTime),1, 0, 'C');
                $pdf->Cell(22,15,date('H:i', $startTime).' - '.date('H:i', $endTime),1, 0, 'C');
    
                for($i = 2; $i< $time_settings[0]['lssToUss'] + 1; $i++){
                    if ($i == $time_settings[0]['breakAfter']+1){
                        $pdf->Cell(15,15,"BREAK",1, 0, 'C');
                        $startTime = $endTime + $time_settings[0]['breakLen'] * 60;
                        $endTime = $startTime + $time_settings[0]['periodLen']*60;
                        $pdf->Cell(22,15,date('H:i', $startTime).' - '.date('H:i', $endTime),1, 0, 'C');
                    }else{
                        $startTime = $endTime;
                        $endTime = $startTime + $time_settings[0]['periodLen']*60;
                        $pdf->Cell(22,15,date('H:i', $startTime).' - '.date('H:i', $endTime),1, 0, 'C');
                    }
                }
                $pdf->Ln();
                foreach($weekdays as $day){
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(25,15,$day,1, 0, 'C');
                    $pdf->SetFont('Arial','B',9);
                    $devotionStart = strtotime($time_settings[0]['startTime']) - 20*60;
                    $startTime = strtotime($time_settings[0]['startTime']);
                    $endTime = strtotime($time_settings[0]['startTime']) + $time_settings[0]['periodLen']*60;
                    $pdf->Cell(22,15,'',1, 0, 'C');
                    $pdf->Cell(22,15,'',1, 0, 'C');
                    for($i = 2; $i< $time_settings[0]['lssToUss'] + 1; $i++){
                        if ($i == $time_settings[0]['breakAfter']+1){
                            $pdf->SetFillColor(128,128,128);
                            $pdf->Cell(15,15," ",1, 0, 'C', true);
                            $startTime = $endTime + $time_settings[0]['breakLen'] * 60;
                            $endTime = $startTime + $time_settings[0]['periodLen']*60;
                            $pdf->Cell(22,15,' ',1, 0, 'C');
                        }else{
                            $startTime = $endTime;
                            $endTime = $startTime + $time_settings[0]['periodLen']*60;
                            $pdf->Cell(22,15,' ',1, 0, 'C');
                        }
                    }
                    $pdf->Ln();
                }
            }
        }
    }else if ($type == 'class'){
    
    }else if ($type == 'teacher'){
    
    }
}

$pdf->Output();
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}