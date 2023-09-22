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

    public function Update($table, $data, $criteria){
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

    public function GetAllWithCriteria($table, $criteria){
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

    public function SearchWithCriteria($table, $criteria){
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
}