<?php
/**
 * Created by PhpStorm.
 * User: Halsey
 * Date: 20/05/2020
 * Time: 13:26
 */
include "database.php";
class EdunaModel extends Edunabase
{

    public function Insert($table, $data){
        $columns = []; $values =[]; $insertTokens = [];
        foreach($data as $key=>$value) {
            array_push($columns, $key);
            array_push($insertTokens, "?");
            array_push($values, $value);
        }

        $sql_cols = implode(",", $columns);
        $sql_values = implode(",", $insertTokens);
        
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "INSERT INTO " .$table."(". $sql_cols .") VALUES (" . $sql_values . ")";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute($values);
    
            $conn = null;
    
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function Update($table, array $data, array $criteria){
        $columns = []; $values =[]; $crit =[];
        foreach($data as $key=>$value) {
            array_push($columns, $key.'=?');
            array_push($values, $value);
        }

        foreach($criteria as $key=>$value) {
            array_push($crit, $key."='".$value."'");
        }

        $sql_cols = implode(",", $columns);
        $sql_criteria = implode(' AND ', $crit);
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE $table SET ".$sql_cols." WHERE ".$sql_criteria;
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute($values);
    
            $conn = null;
    
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function Get($table){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM $table");

            $stmt->execute();

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetAllWithCriteria($table, array $criteria){
        $crit =[];
        foreach($criteria as $key=>$value) {
            array_push($crit, $key."='".$value."'");
        }
        $sql_criteria = implode(' AND ', $crit);
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM $table WHERE ".$sql_criteria);

            $stmt->execute();

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetSomeWithCriteria($table, $kolumns, $criteria){
        $columns = []; $crit =[];
        foreach($kolumns as $value) {
            array_push($columns, $value);
        }

        foreach($criteria as $key=>$value) {
            array_push($crit, $key."='".$value."'");
        }

        $sql_cols = implode(",", $columns);
        $sql_criteria = implode(' AND ', $crit);
       
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT $sql_cols FROM $table WHERE $sql_criteria");

            $stmt->execute();

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function SearchWithCriteria($table, array $criteria){
        $crit =[];
        foreach($criteria as $key=>$value) {
            array_push($crit, $key." LIKE '%".$value."%'");
        }
        $sql_criteria = implode(' AND ', $crit);
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM $table WHERE ".$sql_criteria);

            $stmt->execute();

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function ExamsForTerm($term_id, $year_id, $subject_id, $sequence_id, $class_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT DISTINCT(id) FROM user_mgt_testsetup WHERE school_term_id = ? AND year_id = ? AND school_subject_id = ? AND term_sequence_id = ? AND school_class_id = ? ORDER BY id ASC");

            $stmt->execute([$term_id, $year_id, $subject_id, $sequence_id, $class_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        if(!empty($rows)){
            return $rows[0]['id'];
        }else{
            return "";
        }
        
    }

    public function GetTranscriptMark($term_id, $subject_id, $student_code, $academic_year_id, $class_id, $seq1_id, $seq2_id){
        $seq1 = $this->ExamsForTerm($term_id, $academic_year_id, $subject_id, $seq1_id, $class_id);
        $seq2 = $this->ExamsForTerm($term_id, $academic_year_id, $subject_id, $seq2_id, $class_id);
        if($seq1 != ""){
            if(isset($this->GetSomeWithCriteria('user_mgt_testmarks', ['marks_on_fixed_limit'], ['test_id'=>$seq1, 'yearly_student_id'=>$student_code])[0]['marks_on_fixed_limit'])){
                $mark1 = $this->GetSomeWithCriteria('user_mgt_testmarks', ['marks_on_fixed_limit'], ['test_id'=>$seq1, 'yearly_student_id'=>$student_code])[0]['marks_on_fixed_limit'];
            }else{
                $mark1 = 0;
            }
         }else{
            $mark1 = 0;
        }

        if($seq2 != ""){
            if(isset($this->GetSomeWithCriteria('user_mgt_testmarks', ['marks_on_fixed_limit'], ['test_id'=>$seq2, 'yearly_student_id'=>$student_code])[0]['marks_on_fixed_limit'])){
                $mark2 = $this->GetSomeWithCriteria('user_mgt_testmarks', ['marks_on_fixed_limit'], ['test_id'=>$seq2, 'yearly_student_id'=>$student_code])[0]['marks_on_fixed_limit'];
            }else{
                $mark2 = 0;
            }
        }else{
            $mark2 = 0;
        }
        
        $final_mark = round(($mark1 + $mark2) /2, 2);
        if($final_mark != 0){
            return $final_mark;
        }else{
            return '';
        }
        
    }

    public function SequenceMark($term_id, $subject_id, $student_code, $academic_year_id, $class_id, $seq1_id){
        $seq = $this->ExamsForTerm($term_id, $academic_year_id, $subject_id, $seq1_id, $class_id);
        if($seq != ""){
            if(isset($this->GetSomeWithCriteria('user_mgt_testmarks', ['marks_on_fixed_limit'], ['test_id'=>$seq, 'yearly_student_id'=>$student_code])[0]['marks_on_fixed_limit'])){
                $mark1 = $this->GetSomeWithCriteria('user_mgt_testmarks', ['marks_on_fixed_limit'], ['test_id'=>$seq, 'yearly_student_id'=>$student_code])[0]['marks_on_fixed_limit'];
            }else{
                $mark1 = '';
            }
         }else{
            $mark1 = '';
        }
        if($mark1 != ''){
            return round($mark1, 2);
        }else{
            return '';
        }
    }

    public function StudentDidSubject($subject_id, $student_code){
        
    }
}