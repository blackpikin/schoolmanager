<?php
include "../includes/Model.php";
$section =0;
$Model = new Model($section);
$results = [];
$year_id = $Model->GetCurrentYear()[0]['id'];
$class_id = $_GET['ref'];
$className = $Model->GetAClassName($class_id);
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="'.$className.'_data.csv"');
$myfile = fopen("php://output", "wb") or die("Unable to open file!");
//Write first line
fputcsv($myfile, ['id', 'name', 'gender', 'dob', 'pob', 'guardian', 'guardian_number', 'student_code', 'mother_name', 'father_name', 'adm_num', 'section']);
$students = $Model->GetStudentsInClass($class_id, $year_id);
foreach ($students as $student){
    $data = $Model->GetSomeStudent($student['student_code'], $section)[0];
    array_push($results, $data);
}

//write the rest of the data
foreach ($results as $line => $value) {
    $val = implode(",", $value);
    fputcsv($myfile, $value);
}
fclose($myfile);