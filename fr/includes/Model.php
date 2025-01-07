<?php
/**
 * Created by PhpStorm.
 * User: Halsey
 * Date: 20/05/2020
 * Time: 13:26
 */
include "Database.php";
class Model extends Database
{
    private $section;

    public function __construct($sec) {
        $this->section = $sec;
    }

    public function get_section() {
        return $this->section;
    }

    public function StartUserSession($username){
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM users WHERE phone = ?");

            $stmt->execute([$username]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function LoginUser($username, $password){
        $pw = $this->HashPassword($password);
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT phone, password FROM users WHERE phone = ? AND password = ?");

            $stmt->execute([$username, $pw]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        if (count($rows) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function GetUserPassword($userid){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");

            $stmt->execute([$userid]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows[0]['password'];
    }

    public function UpdateUserPassword($user_id, $newp){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE users SET password = ? WHERE id = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$newp, $user_id] );
    
            $conn = null;
    
            return "Password updated successfully";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function GetUserInfo($username){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM users WHERE phone = ?");

            $stmt->execute([$username]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function RegisterNewUser(array $data){
    try {
        $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO users(name, role, phone, email, subjects, password, dateof, section) VALUES (?, ?, ?, ?, ? , ?, ?, ?)";

        // use exec() because no results are returned
        $statement = $conn->prepare($sql);
        $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], date("Y-m-d H:i:s"), 0] );

        $conn = null;

        return "User registered successfully";

    }
    catch(PDOException $e)
    {
        $conn = null;
        return $e->getMessage();
    }
    }

    public function UpdateUser(array $data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE users SET name = ?, role = ?, phone = ?, email = ?, subjects = ? WHERE id = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5]] );
    
            $conn = null;
    
            return "User updated successfully";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    
    public function GetAllUsers(){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM users WHERE role = ? AND section = ?");

            $stmt->execute(['Teacher', $this->get_section()]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetUser($userId){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");

            $stmt->execute([$userId]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetSchoolInfo($id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM school_info WHERE id = ?");

            $stmt->execute([$id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function UpdateSchoolInfo(array $data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE school_info SET name = ?, motto = ?, email = ?, phone = ?, website = ?, pobox = ? WHERE id = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]] );
    
            $conn = null;
    
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function NewSchoolInfo(array $data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "INSERT INTO school_info (name, motto, email, phone, website, pobox) VALUES(?,?,?,?,?,?)";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5]] );
    
            $conn = null;
    
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function RegisterNewClass(array $data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "INSERT INTO classes(general_name, sub_name, cycle, mockable, practo, cm) VALUES (?, ?, ?, ?, ?, ?)";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5]]);
    
            $conn = null;
    
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function EndAcademicYear(){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE academic_year SET status =  ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([0]);
    
            $conn = null;
    
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function StartNewAcademicYear($data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "INSERT INTO academic_year(start, end, status) VALUES (?, ?, ?)";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], 1]);
    
            $conn = null;
    
            return "New Year Started";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function GetSchoolClasses($cycle, $section){

        $classes_list = [];
        if($section == 0){
            $general_names = ['FORM ONE', 'FORM TWO', 'FORM THREE', 'FORM FOUR', 'FORM FIVE', 'LOWER SIXTH', 'UPPER SIXTH'];
        }else{
            $general_names = ['SIXIEME', 'CINQUIEME', 'TROISIEME', 'QUATRIEME', 'SECONDE', 'PREMIER', 'TERMINALE'];
        }
       
        foreach($general_names as $gn){
            $classes = $this->GetClassesInFormsAndCycle($gn, $cycle, $section);
            if(!empty($classes)){
                foreach($classes as $class){
                    array_push($classes_list, ['id' => $class['id'], 'general_name' => $class['general_name'], 'sub_name' => $class['sub_name'], 'cycle' => $class['cycle'], 'mockable' => $class['mockable']]);
                }
            }
        }
        return $classes_list; 
    }

    public function GetClassesInFormsAndCycle($form, $cycle, $section){
        $rows = array();
            try {
                $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $stmt = $conn->prepare("SELECT * FROM classes WHERE general_name = ? AND cycle = ? AND section = ? ORDER BY sub_name");
    
                $stmt->execute([$form, $cycle, $section]);
    
                // set the resulting array to associative
                $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
                $rows = $stmt->fetchAll();
    
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            $conn = null;
    
        
            return $rows;
    
    }

    public function RegisterNewSubject(array $data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "INSERT INTO subjects(subject, class_name, coef, rep_group, section, hours, practo) VALUES (?, ?, ?, ?, ?, ?,?)";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[2], $data[5]]);
    
            $conn = null;
    
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function SubjectExists($class, $subject){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM subjects WHERE subject = ? AND class_name = ?");

            $stmt->execute([$subject, $class]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        if (count($rows) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function GetAllClasses($section){
        $classes_list = [];
        $general_names = ['FORM ONE', 'FORM TWO', 'FORM THREE', 'FORM FOUR', 'FORM FIVE', 'LOWER SIXTH', 'UPPER SIXTH'];
        foreach($general_names as $gn){
            $classes = $this->GetClassesInForms($gn, $section);
            if(!empty($classes)){
                foreach($classes as $class){
                    array_push($classes_list, ['id' => $class['id'], 'general_name' => $class['general_name'], 'sub_name' => $class['sub_name'], 'cycle' => $class['cycle'], 'mockable' => $class['mockable']]);
                }
            }
        }
        return $classes_list;    
    }

    public function GetAClass($class_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM classes WHERE id = ?");

            $stmt->execute([$class_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function UpdateClass($data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE  classes SET general_name = ?, sub_name = ?, cycle = ?, mockable = ?, practo = ?, section = ?, cm = ?  WHERE id = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7]]);
    
            $conn = null;
    
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function ViewClassSubjects($class_name){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM subjects WHERE class_name = ? ORDER BY subject ASC");

            $stmt->execute([$class_name]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetAllExams($year){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM exams WHERE academic_year = ? AND section = ?");

            $stmt->execute([$year, $this->get_section()]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetMockExam($year, $examType){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM exams WHERE academic_year = ? AND sequence = ?");

            $stmt->execute([$year, $examType]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetMockExamsForYear($year){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM exams WHERE sequence = 'PRE-MOCK' OR sequence = 'MOCK' AND academic_year = ?");

            $stmt->execute([$year]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }


    public function GetCurrentExam(){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM exams WHERE status = ? AND section = ?");

            $stmt->execute(['1', $this->get_section()]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetClosedExams($section){
        $rows = array();
        if(isset($this->GetCurrentYear()[0]['id'])){
            $currentYear = $this->GetCurrentYear()[0]['id'];
        }else{
            $currentYear = 0;
        }
        
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT * FROM exams WHERE status = ? AND academic_year = ? AND section = ?");
    
            $stmt->execute(['0', $currentYear, $section]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        return $rows;
    }

    public function GetCurrentYear(){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM academic_year WHERE status = '1'");

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

    public function GetYear($year_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM academic_year WHERE id = ?");

            $stmt->execute([$year_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function EndSequence(){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE exams SET status = ? WHERE section = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([0, $this->get_section()]);
    
            $conn = null;
    
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function RegisterNewSequence($data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "INSERT INTO exams(term, sequence, academic_year, weighted, percentage, status, section) VALUES (?, ?, ?,?,?,?,?)";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]]);
    
            $conn = null;
    
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function RegisterStudentToClass(array $data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "INSERT INTO academicyear_class(student_code, academic_year_id, class_id) VALUES (?, ?, ?)";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2]]);
    
            $conn = null;

            
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }


    public function UpdateStudentStatusInClass($student){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE academicyear_class SET status = ? WHERE student_code = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([0, $student]);
    
            $conn = null;

            
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }


    public function UpdateStudentToClass(array $data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE academicyear_class SET class_id = ? WHERE academic_year_id = ? AND student_code = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2]]);
    
            $conn = null;

            
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function RegisterNewStudent(array $data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "INSERT INTO students(name, gender,dob, pob, guardian, guardian_number, guardian_email, guardian_address, student_code, mother_name, father_name, adm_num, section ) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?,?, ?, ?)";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11], 0]);
    
            $conn = null;

            
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function UpdateStudent(array $data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE  students SET name = ?, gender = ? ,dob = ? , pob = ?, guardian =? , guardian_number = ?, guardian_email =?, guardian_address =?, mother_name = ?, father_name = ?, adm_num = ? WHERE student_code = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11]]);
    
            $conn = null;

            
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function SearchStudent($searh_string){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE '%$searh_string%' AND section =?");

            $stmt->execute([$this->get_section()]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetClassId($year, $code){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT class_id FROM academicyear_class WHERE academic_year_id = ? AND student_code = ?");

            $stmt->execute([$year, $code]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        if(count($rows) == 0){
            return "";
        }else{
            return $rows[0]['class_id'];
        }
        
    }

    public function GetStudent($student_code, $section){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM students WHERE student_code = ? AND section = ?");

            $stmt->execute([$student_code, $section]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function SetStudentPicture($data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE students SET picture = ?, picture_ext = ?  WHERE student_code = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2]]);
    
            $conn = null;

            
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function UploadStudentFile($data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "INSERT INTO student_files(doc_name, doc_data, data_ext, dateof, student_code) VALUES (?, ?, ?, ?, ?)";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4]]);
    
            $conn = null;

            
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function GetStudentFiles($student_code){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM student_files WHERE student_code = ?");

            $stmt->execute([$student_code]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function UploadStaffFile($data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "INSERT INTO staff_files(doc_name, doc_data, data_ext, dateof, staff_id) VALUES (?, ?, ?, ?, ?)";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4]]);
    
            $conn = null;

            
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function GetStaffFiles($student_code){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM staff_files WHERE staff_id = ?");

            $stmt->execute([$student_code]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetAllSubjects(){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT DISTINCT(subject) FROM subjects WHERE section =? Order by subject ASC");

            $stmt->execute([$this->get_section()]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function UpdateStaffSubjects($data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE staff_subjects SET class_id = ?  WHERE staff_id = ? AND academic_year = ? AND subject = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[2], $data[0], $data[1], $data[3]]);
    
            $conn = null;

            
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function RegisterNewStaffSubjects($data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "INSERT INTO staff_subjects(staff_id, academic_year, class_id, subject) VALUES (?, ?, ?, ?)";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3]]);
    
            $conn = null;

            
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function GetStaffSubjects($staff_id, $academic_year){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM staff_subjects WHERE staff_id = ? AND academic_year = ? Order by subject ASC");

            $stmt->execute([$staff_id, $academic_year]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function SearchStaff($searh_string){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM users WHERE name LIKE '%$searh_string%' AND section = ?");

            $stmt->execute([$this->get_section()]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetAcademicYearClass($class_id, $academic_year){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM academicyear_class WHERE class_id = ? AND academic_year_id = ?");

            $stmt->execute([$class_id, $academic_year]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function StudentCurrentClass($student_code, $academic_year){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM academicyear_class WHERE student_code = ? AND academic_year_id = ?");

            $stmt->execute([$student_code, $academic_year]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function SaveMark($data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "INSERT INTO mark_sheet(student_code, class_id, academic_year, subject, exam, mark, competence) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]]);
    
            $conn = null;
    
            
            return "Mark Saved";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function UpdateMark($data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE mark_sheet SET mark = ?, competence = ? WHERE student_code = ? AND class_id = ? AND academic_year = ? AND subject = ? AND exam = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[5], $data[6], $data[0], $data[1], $data[2], $data[3], $data[4]]);
    
            $conn = null;
    
            
            return "Mark Saved";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function DeleteMark($data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "DELETE FROM mark_sheet WHERE student_code = ? AND class_id = ? AND academic_year = ? AND subject = ? AND exam = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4]]);
    
            $conn = null;
    
            
            return "Mark Deleted";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function EntryExists($data){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT * FROM mark_sheet WHERE student_code = ? AND class_id = ? AND academic_year = ? AND subject = ? AND exam = ?");
    
            $stmt->execute([$data[0], $data[1], $data[2], $data[3], $data[4]]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        if (count($rows) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function GetAMark(array $data){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT mark FROM mark_sheet WHERE student_code = ? AND class_id = ? AND academic_year = ? AND subject = ? AND exam = ?");

            $stmt->execute([$data[0], $data[1], $data[2], $data[3], $data[4]]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        if(!empty($rows)){
            return $rows[0]['mark'];
        }else{
            return "";
        }

       
    }

    public function StudentCodesPerYear($class_id, $academic_year){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT student_code FROM academicyear_class WHERE class_id = ? AND academic_year_id = ?");

            $stmt->execute([$class_id, $academic_year]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetAcademicYears(){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM academic_year");

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

    public function GetClassesPerYear($year_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT DISTINCT(class_id) FROM academicyear_class WHERE academic_year_id = ? ORDER BY class_id ASC");

            $stmt->execute([$year_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetStudentsInClass($class_id, $year_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT student_code FROM academicyear_class WHERE class_id = ? AND academic_year_id = ?");

            $stmt->execute([$class_id, $year_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetAClassName($class_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM classes WHERE id = ?");

            $stmt->execute([$class_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows[0]['general_name'].' '.$rows[0]['sub_name'];
    }

    public function GetMarkSheet($yearId, $classId, $examId){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM mark_sheet WHERE academic_year = ? AND class_id = ? AND exam =?");

            $stmt->execute([$yearId, $classId, $examId]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetStudentsMarks($year_id, $class_id, $exam_id, $student_code){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM mark_sheet WHERE academic_year = ? AND class_id = ? AND exam =? AND student_code = ? ORDER BY subject ASC");

            $stmt->execute([$year_id, $class_id, $exam_id, $student_code]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetStudentsPassPapers($year_id, $class_id, $exam_id, $student_code){
        $class_cycle = $this->GetAClass($class_id)[0]['cycle'];
        $limits = $this->Grade();
        $lim = 0;
        if ($class_cycle == "FIRST"){
            $lim = $limits['OL']['OLCmin'];
        }else{
            $lim = $limits['AL']['ALEmin'];
        }
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM mark_sheet WHERE academic_year = ? AND class_id = ? AND exam =? AND student_code = ? AND mark >= $lim ORDER BY subject ASC");

            $stmt->execute([$year_id, $class_id, $exam_id, $student_code]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return count($rows);
    }

    public function GetTermName($exam_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM exams WHERE id = ?");

            $stmt->execute([$exam_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return strToUpper($rows[0]['term'].' TERM');
    }

    public function GetSequenceName($exam_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM exams WHERE id = ?");

            $stmt->execute([$exam_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return strToUpper('SEQUENCE '.$rows[0]['sequence']);
    }

    public function GetYearName($year_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM academic_year WHERE id = ?");

            $stmt->execute([$year_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows[0]['start'].'/'.$rows[0]['end'].' ACADEMIC YEAR';
    }

    public function GetCoefficient($subject, $class_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT coef FROM subjects WHERE subject = ? AND class_name = ?");

            $stmt->execute([$subject, $class_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        if(count($rows) > 0){
            return (int) $rows[0]['coef'];
        }else{
            return 0;
        }

    }

    public function GetSubjectTeacher($subject, $class_id, $year_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT staff_id FROM staff_subjects WHERE subject = ? AND class_id = ? AND academic_year = ?");

            $stmt->execute([$subject, $class_id, $year_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        if (count($rows) == 0){
            return $this->GetMultipleClassTeacher($subject, $class_id, $year_id);
            
        }else{
            return $rows[0]['staff_id'];
        }
    }

    public function GetMultipleClassTeacher($subject, $class_id, $year_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT class_id, staff_id FROM staff_subjects WHERE subject = ? AND academic_year = ?");

            $stmt->execute([$subject, $year_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        $classes = [];

        if (!empty($rows)){
            for($i = 0; $i <count($rows); $i++) {
                if (strlen($rows[$i]['class_id']) > 1){
                    $classes[$rows[$i]['staff_id']] = explode(',', $rows[$i]['class_id']);
                }else{
                    $classes[$rows[$i]['staff_id']] = explode(',', $rows[$i]['class_id']);
                }
                
                if (in_array($class_id, $classes[$rows[$i]['staff_id']])){
                    return $rows[$i]['staff_id'];
                }else{
                    continue;
                }
                
            }
        }else{
            return 1;
        }
        
    }

    public function GetStaffName($staff_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");

            $stmt->execute([$staff_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;


        if(!empty($rows)){
            $name = explode(' ', $rows[0]['name']);
            return $name[0];
        }else{
            $name = "";
            return $name= "";
        }
        
    }

    public function SubjectRank($subject, $student, $year_id, $class_id, $exam_id ){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM mark_sheet WHERE subject = ? AND academic_year = ? AND class_id = ? AND exam =? ORDER BY mark DESC");

            $stmt->execute([$subject, $year_id, $class_id, $exam_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        $rank = []; $rnk = 0;

        foreach($rows as $row){
            array_push($rank, $row['student_code']);
        }

        foreach ($rank as $key => $r){
            if ($r==$student){
                $rnk = $key + 1;
            }
        }

        return $rnk;
    }

    public function NumberOfPapersSeq($student, $year_id, $class_id, $exam_id ){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM mark_sheet WHERE academic_year = ? AND class_id = ? AND exam =? AND student_code = ? AND mark >= 10");

            $stmt->execute([$year_id, $class_id, $exam_id, $student]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return count($rows);
    }

    public function Average(array $numbers){
        $count = count($numbers);
        $sum = 0;
        $avg = 0.0;
        for ($i = 0; $i < $count; $i++){
            if(!is_numeric($numbers[$i])){
                $sum = $sum + 0;
            }else{
                $sum += $numbers[$i];
            }
        }

        if ($count > 0 ){
            $avg = round(($sum / $count),2);
        }

        return $avg;
    }

    public function NumberOfPapersTerm($student, $year_id, $class_id, $term_name ){
        $exam_ids = $this->ExamsForTerm($term_name, $year_id, $this->get_section());
        $subs = $this->ViewClassSubjects($class_id);
        $mark1 = 0.00; $mark2 = 0.00;
        $subjects = 0;
        foreach($subs as $sub){
            $mark1 = $this->GetAMark([$student, $class_id, $year_id, $sub['subject'], $exam_ids[0]['id']]);
            $mark2 = $this->GetAMark([$student, $class_id, $year_id, $sub['subject'], $exam_ids[1]['id']]);
            if($this->Average([$mark1, $mark2]) >= 10){
                $subjects++;
            }
        }        
        return $subjects;
    }

    public function GetAllTerms($year){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT DISTINCT(term) FROM exams WHERE academic_year = ?");

            $stmt->execute([$year]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetSequenceCompetence($year_id, $class_id, $exam_id, $student_code, $subject){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT competence FROM mark_sheet WHERE academic_year = ? AND class_id = ? AND exam =? AND student_code = ? AND subject = ?");
    
            $stmt->execute([$year_id, $class_id, $exam_id, $student_code, $subject]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
    
        if(count($rows) < 1){
            return '';
        }else{
            return $rows[0]['competence'];
        }
        
    }

    public function GetTermBest($year_id, $class_id, $term_id){
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT student, AVG(average) as av FROM computed_averages WHERE year_id =? AND class_id = ? AND term =? GROUP BY student , term , year_id ORDER BY av DESC");
    
            $stmt->execute([$year_id, $class_id, $term_id]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        if(!empty($rows)){
            $lastIndex = count($rows);
            $lastIndex--;
            $data = ['best'=> round($rows[0]['av'], 2), 'last' => round($rows[$lastIndex]['av'], 2)];
            return $data;
        }else{
            return '';
        }
        
        
    }

    public function ExamsForTerm($term_name, $year_id, $section){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM exams WHERE term = ? AND academic_year = ? AND sequence <> 'MOCK' AND sequence <> 'PRE-MOCK' AND section = ? ORDER BY id ASC");

            $stmt->execute([$term_name, $year_id, $section]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function GetSubjectInMarkSheet($yearId, $classId, $examId, $subject){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM mark_sheet WHERE academic_year = ? AND class_id = ? AND exam =? AND subject =?");

            $stmt->execute([$yearId, $classId, $examId, $subject]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function AnnualAverage($year_id, $class_id, $student_code){
        $first_term_exams = $this->ExamsForTerm('First', $year_id, $this->get_section());
        $second_term_exams = $this->ExamsForTerm('Second', $year_id, $this->get_section());
        $third_term_exams = $this->ExamsForTerm('Third', $year_id, $this->get_section());

        $term_ids = [];  $average = 0.00; $ann_average = 0.00; $terms = 0;

        if(!empty($first_term_exams)){
            foreach($first_term_exams as $fmark){
                if($fmark['sequence'] == "ONE" || $fmark['sequence'] == "TWO"){
                    array_push($term_ids, $fmark['id']);
                }
            }
        }

        if(!empty($second_term_exams)){
            foreach($second_term_exams as $fmark){
                if($fmark['sequence'] == "ONE" || $fmark['sequence'] == "TWO"){
                    array_push($term_ids, $fmark['id']);
                }
            }
        }

        if(!empty($third_term_exams)){
            foreach($third_term_exams as $fmark){
                if($fmark['sequence'] == "ONE" || $fmark['sequence'] == "TWO"){
                    array_push($term_ids, $fmark['id']);
                }
            }
        }

        if(!empty($term_ids)){
            foreach($term_ids as $id){
                $marks = $this->GetStudentsMarks($year_id, $class_id, $id, $student_code);
                $total_coef = 0;
                $total_marks = 0;
    
                foreach ($marks as $mark){
                    $coef = $this->GetCoefficient($mark['subject'], $class_id);
                    $total = $mark['mark'] * $coef;
                    $total_coef = $total_coef + $coef;
                    $total_marks = $total_marks + $total;
                }

                if ($total_coef >0){
                    $average += round($total_marks/$total_coef, 2);
                }
                
            }
            $terms = count($term_ids);
        }

        if($terms > 0){
            $ann_average = round($average/$terms, 2);
        }

        return $ann_average;
    }

    public function GetMockableClasses($section){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT * FROM classes WHERE mockable = ? AND section = ?");
    
            $stmt->execute(['1', $section]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        return $rows;
    }
    

    public function IsMockable($class_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM classes WHERE id = ?");

            $stmt->execute([$class_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        if($rows[0]['mockable'] == 1){
            return true;
        }else{
            return false;
        }

    }

    public function GetStudentsSatForExam($year_id, $class_id, $exam_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT DISTINCT(student_code) FROM mark_sheet WHERE academic_year = ? AND class_id = ? AND exam =?");

            $stmt->execute([$year_id, $class_id, $exam_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function Grade(){
        $grades = $this->ContentExists('mock_grades', 'id', 1);
        $OLAmin = ''; $OLAmax = ''; $OLBmin = ''; $OLBmax = ''; $OLCmin = ''; $OLCmax = ''; $OLDmin = ''; $OLDmax = '';
        $OLEmin = ''; $OLEmax = ''; $OLUmin = ''; $OLUmax = '';
        $ALAmin = ''; $ALAmax = ''; $ALBmin = ''; $ALBmax = ''; $ALCmin = ''; $ALCmax = ''; $ALDmin = ''; $ALDmax = '';
        $ALEmin = ''; $ALEmax = ''; $ALOmin = ''; $ALOmax = ''; $ALFmin = ''; $ALFmax = '';
        if (!empty($grades)){
            $AL = explode(',', $grades[0]['AL']);
            $ALAmin = explode(':', $AL[0])[0]; 
            $ALAmax = explode(':', $AL[0])[1]; 
            $ALBmin = explode(':', $AL[1])[0]; 
            $ALBmax = explode(':', $AL[1])[1]; 
            $ALCmin = explode(':', $AL[2])[0]; 
            $ALCmax = explode(':', $AL[2])[1]; 
            $ALDmin = explode(':', $AL[3])[0]; 
            $ALDmax = explode(':', $AL[3])[1];
            $ALEmin = explode(':', $AL[4])[0]; 
            $ALEmax = explode(':', $AL[4])[1]; 
            $ALOmin = explode(':', $AL[5])[0]; 
            $ALOmax = explode(':', $AL[5])[1]; 
            $ALFmin = explode(':', $AL[6])[0]; 
            $ALFmax = explode(':', $AL[6])[1];
    
            $OL = explode(',', $grades[0]['OL']);
            $OLAmin = explode(':', $OL[0])[0]; 
            $OLAmax = explode(':', $OL[0])[1]; 
            $OLBmin = explode(':', $OL[1])[0]; 
            $OLBmax = explode(':', $OL[1])[1]; 
            $OLCmin = explode(':', $OL[2])[0]; 
            $OLCmax = explode(':', $OL[2])[1]; 
            $OLDmin = explode(':', $OL[3])[0]; 
            $OLDmax = explode(':', $OL[3])[1];
            $OLEmin = explode(':', $OL[4])[0]; 
            $OLEmax = explode(':', $OL[4])[1]; 
            $OLUmin = explode(':', $OL[5])[0];
            $OLUmax = explode(':', $OL[5])[1];
        }

        return [
            'OL' => [
        'OLAmin'=> $OLAmin, 'OLAmax'=>$OLAmax, 'OLBmin'=> $OLBmin, 'OLBmax'=>$OLBmax,
        'OLCmin'=> $OLCmin, 'OLCmax'=>$OLCmax,'OLDmin'=> $OLDmin, 'OLDmax'=>$OLDmax,'OLEmin'=> $OLEmin, 'OLEmax'=>$OLEmax,
        'OLUmin'=> $OLUmin, 'OLUmax'=>$OLUmax],
            'AL'=>['ALAmin'=>$ALAmin,'ALAmax'=>$ALAmax,'ALBmin'=>$ALBmin,'ALBmax'=>$ALBmax,'ALCmin'=>$ALCmin,'ALCmax'=>$ALCmax,
            'ALDmin'=>$ALDmin,'ALDmax'=>$ALDmax,'ALEmin'=>$ALEmin,'ALEmax'=>$ALEmax,'ALOmin'=>$ALOmin,'ALOmax'=>$ALOmax,
            'ALFmin'=>$ALFmin,'ALFmax'=>$ALFmax]
        ];
    }

    public function CountOLevelGrade($grade, $year_id, $class_id, $exam_id, $subject){
        $limits = $this->Grade();
        $lcb = 0.0; $ucb = 0.0;
        if($grade == 'A'){
            $lcb = $limits['OL']['OLAmin'];
            $ucb = $limits['OL']['OLAmax'];
            $sql = "SELECT student_code FROM mark_sheet WHERE mark >= $lcb AND academic_year = ? AND class_id = ? AND exam =? AND subject =?";
        }elseif($grade == 'B'){
            $lcb = $limits['OL']['OLBmin'];
            $ucb = $limits['OL']['OLBmax'];
            $sql = "SELECT student_code FROM mark_sheet WHERE mark BETWEEN $lcb AND $ucb AND academic_year = ? AND class_id = ? AND exam =? AND subject =?";
        }elseif($grade == 'C'){
            $lcb = $limits['OL']['OLCmin'];
            $ucb = $limits['OL']['OLCmax'];
            $sql = "SELECT student_code FROM mark_sheet WHERE mark BETWEEN $lcb AND $ucb AND academic_year = ? AND class_id = ? AND exam =? AND subject =?";
        }elseif($grade == 'D'){
            $lcb = $limits['OL']['OLDmin'];
            $ucb = $limits['OL']['OLDmax'];
            $sql = "SELECT student_code FROM mark_sheet WHERE mark BETWEEN $lcb AND $ucb AND academic_year = ? AND class_id = ? AND exam =? AND subject =?";
        }elseif($grade == 'E'){
            $lcb = $limits['OL']['OLEmin'];
            $ucb = $limits['OL']['OLEmax'];
            $sql = "SELECT student_code FROM mark_sheet WHERE mark BETWEEN $lcb AND $ucb AND academic_year = ? AND class_id = ? AND exam =? AND subject =?";
        }elseif($grade == 'U'){
            $lcb = $limits['OL']['OLUmin'];
            $ucb = $limits['OL']['OLUmax'];
            $sql = "SELECT student_code FROM mark_sheet WHERE mark <= $ucb AND academic_year = ? AND class_id = ? AND exam =? AND subject =?";

        }
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare($sql);

            $stmt->execute([$year_id, $class_id, $exam_id, $subject]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return count($rows);
    }

    public function CountALevelGrade($grade, $year_id, $class_id, $exam_id, $subject){
        $limits = $this->Grade();
        $lcb = 0.0; $ucb = 0.0;
        if($grade == 'A'){
            $lcb = $limits['AL']['ALAmin'];
            $ucb = $limits['AL']['ALAmax'];
            $sql = "SELECT student_code FROM mark_sheet WHERE mark >= $lcb AND academic_year = ? AND class_id = ? AND exam =? AND subject =?";
        }elseif($grade == 'B'){
            $lcb = $limits['AL']['ALBmin'];
            $ucb = $limits['AL']['ALBmax'];
            $sql = "SELECT student_code FROM mark_sheet WHERE mark BETWEEN $lcb AND $ucb AND academic_year = ? AND class_id = ? AND exam =? AND subject =?";
        }elseif($grade == 'C'){
            $lcb = $limits['AL']['ALCmin'];
            $ucb = $limits['AL']['ALCmax'];
            $sql = "SELECT student_code FROM mark_sheet WHERE mark BETWEEN $lcb AND $ucb AND academic_year = ? AND class_id = ? AND exam =? AND subject =?";
        }elseif($grade == 'D'){
            $lcb = $limits['AL']['ALDmin'];
            $ucb = $limits['AL']['ALDmax'];
            $sql = "SELECT student_code FROM mark_sheet WHERE mark BETWEEN $lcb AND $ucb AND academic_year = ? AND class_id = ? AND exam =? AND subject =?";
        }elseif($grade == 'E'){
            $lcb = $limits['AL']['ALEmin'];
            $ucb = $limits['AL']['ALEmax'];
            $sql = "SELECT student_code FROM mark_sheet WHERE mark BETWEEN $lcb AND $ucb AND academic_year = ? AND class_id = ? AND exam =? AND subject =?";
        }elseif($grade == 'O'){
            $lcb = $limits['AL']['ALFmin'];
            $ucb = $limits['AL']['ALFmax'];
            $sql = "SELECT student_code FROM mark_sheet WHERE mark BETWEEN $lcb AND $ucb AND academic_year = ? AND class_id = ? AND exam =? AND subject =?";
        }elseif($grade == 'F'){
            $lcb = $limits['AL']['ALFmin'];
            $ucb = $limits['AL']['ALFmax'];
            $sql = "SELECT student_code FROM mark_sheet WHERE mark <= $ucb AND academic_year = ? AND class_id = ? AND exam =? AND subject =?";
        }
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare($sql);

            $stmt->execute([$year_id, $class_id, $exam_id, $subject]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return count($rows);
    }

    public function OLGrade($student_code, $year_id, $class_id, $exam_id, $subject){
        $mark = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT mark FROM mark_sheet WHERE student_code = ? AND academic_year = ? AND class_id = ? AND exam =? AND subject =?");

            $stmt->execute([$student_code, $year_id, $class_id, $exam_id, $subject]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $mark = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        $limits = $this->Grade();
        $remark = "";
        if(isset($mark[0]['mark'])){
            if($mark[0]['mark'] <= $limits['OL']['OLUmax']){
                $remark = "U";
            }elseif($mark[0]['mark'] >= $limits['OL']['OLEmin'] && $mark[0]['mark'] <= $limits['OL']['OLEmax']){
                $remark = "E";
            }elseif($mark[0]['mark'] >= $limits['OL']['OLDmin'] && $mark[0]['mark'] <= $limits['OL']['OLDmax']){
                $remark = "D";
            }elseif($mark[0]['mark'] >= $limits['OL']['OLCmin'] && $mark[0]['mark'] <= $limits['OL']['OLCmax']){
                $remark = "C";
            }elseif($mark[0]['mark'] >= $limits['OL']['OLBmin'] && $mark[0]['mark'] <= $limits['OL']['OLBmax']){
                $remark = "B";
            }elseif($mark[0]['mark'] >= $limits['OL']['OLAmin']){
                $remark = "A";
            }
        }else{
            $remark = "";
        }
        return $remark;
    }

    public function ALGrade($student_code, $year_id, $class_id, $exam_id, $subject){
        $mark = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT mark FROM mark_sheet WHERE student_code = ? AND academic_year = ? AND class_id = ? AND exam =? AND subject =?");

            $stmt->execute([$student_code, $year_id, $class_id, $exam_id, $subject]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $mark = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
        $limits = $this->Grade();
        $remark = "";
        if(isset($mark[0]['mark'])){
            if($mark[0]['mark'] <= $limits['AL']['ALFmax']){
                $remark = "F";
            }elseif($mark[0]['mark'] >= $limits['AL']['ALOmin'] && $mark[0]['mark'] <= $limits['AL']['ALOmax']){
                $remark = "O";
            }elseif($mark[0]['mark'] >= $limits['AL']['ALEmin'] && $mark[0]['mark'] <= $limits['AL']['ALEmax']){
                $remark = "E";
            }elseif($mark[0]['mark'] >= $limits['AL']['ALDmin'] && $mark[0]['mark'] <= $limits['AL']['ALDmax']){
                $remark = "D";
            }elseif($mark[0]['mark'] >= $limits['AL']['ALCmin'] && $mark[0]['mark'] <= $limits['AL']['ALCmax']){
                $remark = "C";
            }elseif($mark[0]['mark'] >= $limits['AL']['ALBmin'] && $mark[0]['mark'] <= $limits['AL']['ALBmax']){
                $remark = "B";
            }elseif($mark[0]['mark'] >= $limits['AL']['ALAmin']){
                $remark = "A";
            }
        }else{
            $remark = "";
        }
        

        return $remark;
    }

    public function SaveAbsence($student_code, $year, $class_id, $term, $abs, $type){
        if($type == 'abs'){
            $sql = "INSERT INTO absences(student_code, academic_year, class_id, term, absences, punishment, warning, suspension, justabs) VALUES (?, ?, ?, ?, ?, 0, 0, 0, 0)";
        }elseif($type == 'pun'){
            $sql = "INSERT INTO absences(student_code, academic_year, class_id, term, absences, punishment, warning, suspension, justabs) VALUES (?, ?, ?, ?, 0, ?, 0, 0, 0)";
        }elseif($type == 'war'){
            $sql = "INSERT INTO absences(student_code, academic_year, class_id, term, absences, punishment, warning, suspension, justabs) VALUES (?, ?, ?, ?, 0, 0, ?, 0, 0)";
        }elseif($type == 'sus'){
            $sql = "INSERT INTO absences(student_code, academic_year, class_id, term, absences, punishment, warning, suspension, justabs) VALUES (?, ?, ?, ?, 0, 0, 0, ?, 0)";
        }elseif($type == 'justabs'){
            $sql = "INSERT INTO absences(student_code, academic_year, class_id, term, absences, punishment, warning, suspension, justabs) VALUES (?, ?, ?, ?, 0, 0, 0, 0, ?)";
        }
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$student_code, $year, $class_id, $term, $abs]);
    
            $conn = null;
    
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function CountAbsences($year_id, $class_id, $term, $student_code, $type){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT SUM($type) as absences FROM absences WHERE academic_year = ? AND class_id = ? AND term =? AND student_code = ?");

            $stmt->execute([$year_id, $class_id, $term, $student_code]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        if(count($rows) > 0){
            return $rows[0]['absences'];
        }else{
            return 0;
        }
            
    }

    public function CountAbsencesYear($year_id, $class_id, $student_code){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT SUM(absences) as absences FROM absences WHERE academic_year = ? AND class_id = ? AND student_code = ?");

            $stmt->execute([$year_id, $class_id, $student_code]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        if(count($rows) > 0){
            return $rows[0]['absences'];
        }else{
            return 0;
        }
            
    }

    public function GetAdmins(){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM users WHERE role = ?");

            $stmt->execute(['Admin']);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function ResetUserPassword($userid){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE users SET password = ? WHERE id = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$this->DefaultUserPassword(), $userid] );
    
            $conn = null;
    
            return "Password updated successfully";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function AllMarkSheetSubjects($student_code){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT DISTINCT(subject) FROM mark_sheet WHERE student_code = ? ORDER BY subject ASC");

            $stmt->execute([$student_code]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function TranscriptClasses($student_code, $cycle){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM academicyear_class WHERE student_code = ? ORDER BY academic_year_id ASC");

            $stmt->execute([$student_code]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        //

        $classes = [];

        foreach($rows as $row){
            if($this->GetAClass($row['class_id'])[0]['cycle'] == $cycle){
                array_push($classes, ['id'=>$row['id'], 'student_code'=>$row['student_code'], 'academic_year_id' => $row['academic_year_id'], 'class_id'=>$row['class_id']]);
            }
        }

        return $classes;
    }


    public function YearNameDigits($year_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM academic_year WHERE id = ?");

            $stmt->execute([$year_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows[0]['start'].'/'.$rows[0]['end'];
    }

    public function MocksForTerm($term_name, $year_id, $class_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM exams WHERE term = ? AND academic_year = ? AND sequence <> 'TWO' ORDER BY id ASC");

            $stmt->execute([$term_name, $year_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }


    public function GetTranscriptMark($term_name, $subject, $student_code, $year_id, $class_id){
        //
        /*
        if($this->IsMockable($class_id)){
            $term_exams_ids = $this->MocksForTerm($term_name, $year_id, $class_id);
        }else{
            $term_exams_ids = $this->ExamsForTerm($term_name, $year_id, $this->get_section());
        }
        */
        $final_mark = "";

        $term_exams_ids = $this->ExamsForTerm($term_name, $year_id, $this->get_section());
        count($term_exams_ids);
        if(count($term_exams_ids) == 2){
            $exam1 = $term_exams_ids[0]['id'];
            $exam2 = $term_exams_ids[1]['id'];
            $mark1 = $this->GetStudentsMarksForSubject($year_id, $class_id, $exam1, $student_code, $subject);
            $mark2 = $this->GetStudentsMarksForSubject($year_id, $class_id, $exam2, $student_code, $subject);
            //$coef = $this->GetCoefficient($subject, $class_id);
            if(!is_numeric($mark1)){
                $mark1 = 0;
            }
            if(!is_numeric($mark2)){
                $mark2 = 0;
            }

            $av = ($mark1 + $mark2)/2;
            $final_mark = $av;
        }elseif(count($term_exams_ids) == 1){
            $exam1 = $term_exams_ids[0]['id'];
            $mark1 = $this->GetStudentsMarksForSubject($year_id, $class_id, $exam1, $student_code, $subject);
            //$coef = $this->GetCoefficient($subject, $class_id);
            $final_mark = $mark1;
        }elseif(count($term_exams_ids) == 0){

        }

        if(!is_numeric($final_mark)){
            return '';
        }else{
            return round($final_mark,2);
        }
    }

    public function GetStudentsMarksForSubject($year_id, $class_id, $exam_id, $student_code, $subject){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM mark_sheet WHERE academic_year = ? AND class_id = ? AND exam =? AND student_code = ? AND subject = ?");

            $stmt->execute([$year_id, $class_id, $exam_id, $student_code, $subject]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
         
        if (!empty($rows)){
            return $rows[0]['mark'];
        }else{
            return " ";
        }
        
    }

    public function GetTranscriptPosition($year_id, $class_id, $term_id, $student_code){
        $position = "";
        //Get exam ids for the term
        $term_exams_ids = $this->ExamsForTerm($term_id, $year_id, $this->get_section());

        if (!empty($term_exams_ids)){
            //Calculate mean marks for the two terms
        $exam1 = $this->GetMarkSheet($year_id, $class_id, $term_exams_ids[0]['id']);
        $exam2 = $this->GetMarkSheet($year_id, $class_id, $term_exams_ids[1]['id']);

        $student_codes1 = []; $student_codes2 = [];
        $data1 = []; $data2 = [];
        $students_totals1 = []; $students_totals2 = [];

        $term_student_codes = []; 

    foreach($exam1 as $student){
        if(!in_array($student['student_code'], $student_codes1)){
            array_push($student_codes1, $student['student_code']);
        }
        if(!in_array($student['student_code'], $term_student_codes)){
            array_push($term_student_codes, $student['student_code']);
        }
    }

    foreach($exam2 as $student){
        if(!in_array($student['student_code'], $student_codes2)){
            array_push($student_codes2, $student['student_code']);
        }
        if(!in_array($student['student_code'], $term_student_codes)){
            array_push($term_student_codes, $student['student_code']);
        }
    }

    $class_av = 0;  $position_array = [];
    $class_average = 0 ;
    foreach($student_codes1 as $student){
        $marks = $this->GetStudentsMarks($year_id, $class_id, $term_exams_ids[0]['id'], $student);
        $total_coef = 0;
        $total_marks = 0;

        foreach ($marks as $mark){
            $coef = $this->GetCoefficient($mark['subject'], $class_id);
            $total = $mark['mark'] * $coef;
            $total_coef = $total_coef + $coef;
            $total_marks = $total_marks + $total;
            
        array_push($data1, [  
            'student' => $student,   
            'subject'=>$mark['subject'],
            'mark'=>$mark['mark'], 
            'coef'=>$coef, 
            'total'=> $total,
        ]);
        }
        
        $average = round($total_marks/$total_coef, 2);
        $students_totals1[$student] = [
            'marks'=>$data1, 
            'total_coef'=>$total_coef, 
            'total_marks' =>$total_marks,
            'average'=>$average,
        ];
        
    }

    foreach($student_codes2 as $student){
        $marks = $this->GetStudentsMarks($year_id, $class_id, $term_exams_ids[1]['id'], $student);
        $total_coef = 0;
        $total_marks = 0;

        foreach ($marks as $mark){
            $coef = $this->GetCoefficient($mark['subject'], $class_id);
            $total = $mark['mark'] * $coef;
            $total_coef = $total_coef + $coef;
            $total_marks = $total_marks + $total;
            
        array_push($data2, [  
            'student' => $student,   
            'subject'=>$mark['subject'],
            'mark'=>$mark['mark'], 
            'coef'=>$coef, 
            'total'=> $total,
        ]);
        }
        
        $average = round($total_marks/$total_coef, 2);
        $students_totals2[$student] = [
            'marks'=>$data2, 
            'total_coef'=>$total_coef, 
            'total_marks' =>$total_marks,
            'average'=>$average,
        ];
        
    }
//print_r($students_totals2);
            foreach ($term_student_codes as $student){
                if(!isset($students_totals1[$student]) || !isset($students_totals2[$student])){
                    continue;
                }
                $av1 =  $students_totals1[$student]['average'];
                $av2 =  $students_totals2[$student]['average'];
                $average = round(($av1 + $av2)/2, 2);
                $position_array[$student] = $average;

                $class_av = $class_av + $average;
                $class_average = round($class_av/count($term_student_codes), 2);

            }
            arsort($position_array);

            $KEYS = array_keys($position_array);
        
            for ($i = 0; $i < count($KEYS); $i++){ 
                if($KEYS[$i] == $student_code){
                    $pos = $i + 1;
                    $position = $pos.'/'.count($position_array);
                }        
            }
        }

        return $position;
}

public function GetPreviousAcademicYears(){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM academic_year WHERE status = 0");

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

public function GetPromotionList($class_id, $year_id){
    $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM academicyear_class WHERE class_id = ? AND academic_year_id  = ? AND status = ? ");

            $stmt->execute([$class_id, $year_id, 1]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        //

        $students = [];
        foreach($rows as $row){
            $stud = $this->GetStudent($row['student_code'], $this->get_section());
            $students[$row['student_code']] = $stud[0]['name'];
        }
        asort($students);

        return $students;
}

public function TermAverage($student_code, $term, $year_id, $class_id){
        $term_exams = $this->ExamsForTerm($term, $year_id, $this->get_section());
        
        $term_ids = [];  $average = 0.00; $term_average = 0.00; $terms = 0;

        if(!empty($term_exams)){
            foreach($term_exams as $fmark){
                if($fmark['sequence'] == "ONE" || $fmark['sequence'] == "TWO"){
                    array_push($term_ids, $fmark['id']);
                }
            }
        }

        if(!empty($term_ids)){
            foreach($term_ids as $id){
                $marks = $this->GetStudentsMarks($year_id, $class_id, $id, $student_code);
                $total_coef = 0;
                $total_marks = 0;
    
                foreach ($marks as $mark){
                    $coef = $this->GetCoefficient($mark['subject'], $class_id);
                    $total = $mark['mark'] * $coef;
                    $total_coef = $total_coef + $coef;
                    $total_marks = $total_marks + $total;
                }

                if ($total_coef >0){
                    $average += round($total_marks/$total_coef, 2);
                }
                
            }
            $terms = count($term_ids);
        }

        if($terms > 0){
            $term_average = round($average/$terms, 2);
        }

        return $term_average;
}

public function GetEvents($month_year){
    $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM events WHERE monthYear = ?");

            $stmt->execute([$month_year]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
}

public function RegisterNewEvent($event, $date, $duration, $color, $montYear){
    try {
        $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO events(event, dateof, duration, colored, monthYear) VALUES (?, ?, ?, ?, ?)";

        // use exec() because no results are returned
        $statement = $conn->prepare($sql);
        $statement->execute([$event, $date, $duration, $color, $montYear]);

        $conn = null;

        return "Successful";

    }
    catch(PDOException $e)
    {
        $conn = null;
        return $e->getMessage();
    }
}

function SendLetter($Subject, $msg, $email)
{
    $result = "";

    //Set Template header
    $header = '<!DOCTYPE html><html><head><title>Information center</title></head><body>
	<div style="background-color:navy;padding:20px; font-size:20pt; color:white; font-weight:bold; text-align:center;">'.
        '<img src="https://www.classmasterpro.cm/img/logo.png" alt="logo" /></div>';


    //Set template footer
    $footer = '<footer style="background-color:navy;">
    <div class="row">
        <div style="display:inline-block; width:30%; font-size: 9pt;">
            <span style="color: white">&copy; 2018 &nbsp; '.$this->GetSchoolInfo(1)[0]['name'].'</span>
        </div>
        <div style="display:inline-block; width:30%; font-size: 9pt;">
            <span style="color:white">Fueled by <a style="font-weight:bold; color: white; text-decoration: none;" target="_blank" href="https://www.classmasterpro.cm">ClassMaster Pro</a></span>
        </div>
    </div>
</footer>
<body>
<html>';

    $to = $email;
    $subject = $Subject;
    $message = $header .'<p style="font-style: italic; color:black; font-weight: bold;">' . $msg . "</p>".$footer;
    $headers = "From: ".$this->GetSchoolInfo(1)[0]['name']."<".$this->GetSchoolInfo(1)[0]['name'].">\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    if (mail($to, $subject, $message, $headers)) {
        $result = $to." => Sent";
    } else {
        $result = $to." => Not sent";
    }
    
    return $result;
}

public function GuardianEmails($classes){
    $students = []; $emails = [];
    foreach ($classes as $class_id){
        $studs = $this->GetStudentsInClass($class_id, $this->GetCurrentYear()[0]['id']);
        foreach($studs as $s){
            array_push($students, $s['student_code']);
        }
    }

    foreach($students as $code){
        array_push($emails, $this->GetStudent($code, $this->get_section())[0]['guardian_email']);
    }

    return $emails;
}

public function NewConduct($type, $date, $title, $desc, $imageString, $imageFileType, $student_code){
    try {
        $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO conducts(`typeof`, `date`, `tittle`, `description`, `photo`, `photo_ext`, `student_code`) VALUES (?,?,?,?,?,?,?)";

        // use exec() because no results are returned
        $statement = $conn->prepare($sql);
        $statement->execute([$type, $date, $title, $desc, $imageString, $imageFileType, $student_code]);

        $conn = null;

        return "Successful";

    }
    catch(PDOException $e)
    {
        $conn = null;
        return $e->getMessage();
    }
}

public function StudentConducts($student_code){
    $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM conducts WHERE student_code = ?");

            $stmt->execute([$student_code]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
}

public function GetExamsForYear($year_id, $class_id, $section){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM exams WHERE academic_year = ? AND section = ? ORDER BY id ASC");

        $stmt->execute([$year_id, $section]);

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
    $exam_ids = [];
    if($this->IsMockable($class_id) == false){
        foreach($rows as $row){
            if($row['sequence'] != 'PRE-MOCK' && $row['sequence'] != 'MOCK'){
                array_push($exam_ids, $row['id']);
            }
        }
    }else{
        foreach($rows as $row){
            if($row['sequence'] != 'TWO'){
                array_push($exam_ids, $row['id']);
            }
        }
    }

    return $exam_ids;
}

public function GetStudentCurrentClass($student_code){
    $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT class_id FROM academicyear_class WHERE academic_year_id = ? AND student_code = ?");

            $stmt->execute([$this->GetCurrentYear()[0]['id'], $student_code]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows[0]['class_id'];
}

public function ChangeStudentClass($new_class, $student_code){
    try {
        $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE academicyear_class SET class_id = ? WHERE student_code = ? AND academic_year_id = ?";

        // use exec() because no results are returned
        $statement = $conn->prepare($sql);
        $statement->execute([$new_class, $student_code, $this->GetCurrentYear()[0]['id']] );

        $conn = null;

        return "Class modified successfully";

    }
    catch(PDOException $e)
    {
        $conn = null;
        return $e->getMessage();
    }
}

public function GetClassesInForms($form, $section){
    $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM classes WHERE general_name = ? AND section = ? ORDER BY sub_name");

            $stmt->execute([$form, $section]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

    
        return $rows;

}

public function HighestMark($year_id, $class_id, $exam_id, $subject){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT MAX(mark) as mark FROM mark_sheet WHERE academic_year = ? AND class_id = ? AND exam =? AND subject = ?");

        $stmt->execute([$year_id, $class_id, $exam_id, $subject]);

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
     
    if (!empty($rows)){
        return $rows[0]['mark'];
    }else{
        return "";
    }
    
}

public function LowestMark($year_id, $class_id, $exam_id, $subject){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT MIN(mark) as mark FROM mark_sheet WHERE academic_year = ? AND class_id = ? AND exam =? AND subject = ?");

        $stmt->execute([$year_id, $class_id, $exam_id, $subject]);

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
     
    if (!empty($rows)){
        return $rows[0]['mark'];
    }else{
        return "";
    }
    
}

public function AverageMark($year_id, $class_id, $exam_id, $subject){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT AVG(mark) as mark FROM mark_sheet WHERE academic_year = ? AND class_id = ? AND exam =? AND subject = ?");

        $stmt->execute([$year_id, $class_id, $exam_id, $subject]);

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
     
    if (!empty($rows) && $rows[0]['mark'] > 0){
        return $rows[0]['mark'];
    }else{
        return "";
    }
    
}

public function GetStudentsPassedExam($year_id, $class_id, $exam_id, $subject){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT Count(mark) as passed FROM mark_sheet WHERE academic_year = ? AND class_id = ? AND exam =? AND subject = ? AND mark >= 10");

        $stmt->execute([$year_id, $class_id, $exam_id, $subject]);

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;

    return $rows[0]['passed'];
}

public function GetGenderPassedExam($year_id, $class_id, $exam_id, $subject){
    $rows = array();
    $males = 0; $female = 0;
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM mark_sheet WHERE academic_year = ? AND class_id = ? AND exam =? AND subject = ? AND mark >= 10");

        $stmt->execute([$year_id, $class_id, $exam_id, $subject]);

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;

    foreach($rows as $row){
        if($this->GetStudent($row['student_code'], $this->get_section())[0]['gender'] == 'M' ){
            $males++;
        }else{
            $female++;
        }
    }

    return [$males, $female];
}

public function MarksForSubjectPerYear($year_id, $class_id){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT student_code, subject, AVG(mark) as mark FROM mark_sheet WHERE academic_year = ? AND class_id = ? GROUP BY student_code, subject");

        $stmt->execute([$year_id, $class_id]);

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
    $dta = [];
    if(!empty($rows)){
        foreach($rows as $row){
            $dta[$row['student_code'].$row['subject']] = round($row['mark'], 2);
        }
    }

    return $dta;
}

public function TermsInYear($year_id){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT DISTINCT(term) as terms FROM exams WHERE academic_year = ?");

        $stmt->execute([$year_id]);

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;

    return $rows;
}

public function AnnualPosition($year_id, $class_id, $student_code){
    $final = 0; $answer = 0;
    $terms = $this->TermsInYear($year_id);
    if(count($terms) >0){
        foreach($terms as $t){
            $pos =  explode('/', $this->GetTranscriptPosition($year_id, $class_id, $t['terms'], $student_code));
            $final = $final + $pos[0];
        }
        $answer = round($final/count($terms), 0);
    }
    return $answer;
}

public function StudentsInAgeRange($age, $class_id, $year_id, $gender){
    $students = $this->GetStudentsInClass($class_id, $year_id);
    $total = 0; 
    if (!empty($students)){
        $now = date("Y");
        foreach($students as $s){
            $student = $this->GetStudent($s['student_code'], $this->get_section());
            $dob = explode('-', $student[0]['dob'])[0];
            $student_age = (int)$now - (int)$dob;

            if($age == '<=11'){
                if($student_age <= 11  && $gender == $student[0]['gender']){
                    $total++;
                }
            }elseif($age == '>=24'){
                if($student_age >= 24  && $gender == $student[0]['gender']){
                    $total++;
                }
            }else{
                if($student_age == $age  && $gender == $student[0]['gender']){
                    $total++;
                }
            }
        }
    }
    return $total;
}

public function ShortClassName($class_id){
    $longName = $this->GetAClassName($class_id);
    $arr = explode(' ', $longName);
    $classes_codes = ['ONE'=>'1', 'TWO'=>'2', 'THREE'=>'3', 'FOUR'=>'4', 'FIVE'=>'5', 'SIXTH'=>'S'];
    $shortName = "";
    if(count($arr) == 2){
        $p1 = substr($arr[0], 0, 2);
        $p2 = substr($arr[1], 0, 1);
        $shortName = $p1.$p2;
    }elseif(count($arr) == 3){
        $p1 = substr($arr[0], 0, 1);
        $p2 = $classes_codes[$arr[1]];
        $p3 = substr($arr[2], 0, 1);
        $shortName = $p1.$p2.$p3;
    }
    return $shortName;
}

public function NewFees($class, $total, $reg, $pta, $first, $second, $type, $user_id ){
    try {
        $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO fee_settings(class_id, totalfee, registration, pta, first_ins, second_ins, typeof, user_id) VALUES (?, ?, ?, ?, ? , ?, ?, ?)";

        // use exec() because no results are returned
        $statement = $conn->prepare($sql);
        $statement->execute([$class, $total, $reg, $pta, $first, $second, $type, $user_id] );

        $conn = null;

        return "Successful";

    }
    catch(PDOException $e)
    {
        $conn = null;
        return $e->getMessage();
    }     
}

public function UpdateFees($class, $total, $reg, $pta, $first, $second, $type, $user_id ){
    try {
        $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE fee_settings SET totalfee = ?, registration =?, pta =?, first_ins = ?, second_ins = ?, user_id = ? WHERE class_id = ? AND typeof = ?";

        // use exec() because no results are returned
        $statement = $conn->prepare($sql);
        $statement->execute([$total, $reg, $pta, $first, $second, $user_id, $class, $type] );

        $conn = null;

        return "Successful";

    }
    catch(PDOException $e)
    {
        $conn = null;
        return $e->getMessage();
    }     
}

public function GetFeeStructure($class_id, $type){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM fee_settings WHERE class_id = ? AND typeof = ?");

        $stmt->execute([$class_id, $type]);

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;

    return $rows[0];
}

public function NewReason($reason, $percent, $user_id){
    try {
        $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO reasons(reason, percent, user_id) VALUES (?, ?, ?)";

        // use exec() because no results are returned
        $statement = $conn->prepare($sql);
        $statement->execute([$reason, $percent, $user_id] );

        $conn = null;

        return "Successful";

    }
    catch(PDOException $e)
    {
        $conn = null;
        return $e->getMessage();
    }     
}

public function GetDiscountReasons(){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM reasons");

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

public function GetDiscountReason($id){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM reasons WHERE id = ?");

        $stmt->execute([$id]);

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;

    return $rows[0];
}

public function UpdateReason($reason, $percent, $user_id, $id){
    try {
        $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE reasons SET reason = ?, percent = ?, user_id = ? WHERE id = ?";

        // use exec() because no results are returned
        $statement = $conn->prepare($sql);
        $statement->execute([$reason, $percent, $user_id, $id] );

        $conn = null;

        return "Successful";

    }
    catch(PDOException $e)
    {
        $conn = null;
        return $e->getMessage();
    }     

}

public function GetRevenueSources(){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM revenue_sources");

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

public function NewExpSource($reason, $user_id){
    try {
        $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO expense_sources(source, user_id) VALUES (?, ?)";

        // use exec() because no results are returned
        $statement = $conn->prepare($sql);
        $statement->execute([$reason, $user_id] );

        $conn = null;

        return "Successful";

    }
    catch(PDOException $e)
    {
        $conn = null;
        return $e->getMessage();
    }     
}

public function NewRevSource($reason, $user_id){
    try {
        $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO revenue_sources(source, user_id) VALUES (?, ?)";

        // use exec() because no results are returned
        $statement = $conn->prepare($sql);
        $statement->execute([$reason, $user_id] );

        $conn = null;

        return "Successful";

    }
    catch(PDOException $e)
    {
        $conn = null;
        return $e->getMessage();
    }     
}

public function ExpenseSource($id){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM expense_sources WHERE id = ?");

        $stmt->execute([$id]);

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;

    return $rows[0];
}

public function RevenueSource($id){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM revenue_sources WHERE id = ?");

        $stmt->execute([$id]);

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;

    return $rows[0];
}
public function UpdateRevenueSource($source, $user_id, $id){
    try {
        $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE revenue_sources SET source = ?, user_id = ? WHERE id = ?";

        // use exec() because no results are returned
        $statement = $conn->prepare($sql);
        $statement->execute([$source, $user_id, $id] );

        $conn = null;

        return "Successful";

    }
    catch(PDOException $e)
    {
        $conn = null;
        return $e->getMessage();
    }     

}

public function UpdateExpenseSource($source, $user_id, $id){
    try {
        $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE expense_sources SET source = ?, user_id = ? WHERE id = ?";

        // use exec() because no results are returned
        $statement = $conn->prepare($sql);
        $statement->execute([$source, $user_id, $id] );

        $conn = null;

        return "Successful";

    }
    catch(PDOException $e)
    {
        $conn = null;
        return $e->getMessage();
    }     

}

public function GetStudentsFees($student_code, $class_id, $year_id){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT SUM(totalpaid) as fees FROM fees WHERE student_code = ? AND class_id = ? AND academic_year = ?");

        $stmt->execute([$student_code, $class_id, $year_id]);

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;

    if(empty($rows[0]['fees'])){
        return 0;
    }else{
        return $rows[0]['fees'];
    }

}

public function FeeType($student_code){
    $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT DISTINCT(academic_year) as year_id FROM fees WHERE student_code = ?");

        $stmt->execute([$student_code]);

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;

    if(count($rows) <= 1){
        return 'new';
    }else{
        return 'old';
    }
}

public function NewFeePayment($student_code, $year_id, $class_id, $amount, $date, $montYear, $yearOnly, $user_id){
    try {
        $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO fees(student_code, academic_year, class_id, totalpaid, dateof, monthYear, yearOnly, user_id) VALUES (?, ?,?, ?,?, ?,?, ?)";

        // use exec() because no results are returned
        $statement = $conn->prepare($sql);
        $statement->execute([$student_code, $year_id, $class_id, $amount, $date, $montYear, $yearOnly, $user_id] );

        $conn = null;

        return "Successful";

    }
    catch(PDOException $e)
    {
        $conn = null;
        return $e->getMessage();
    }     
}

public function NewCash($amount, $source, $user_id, $dateof, $monthYear, $yearOnly){
    try {
        $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO other_cash(amount, source, user_id, dateof, monthYear, yearOnly) VALUES (?,?,?,?,?,?)";

        // use exec() because no results are returned
        $statement = $conn->prepare($sql);
        $statement->execute([$amount, $source, $user_id, $dateof, $monthYear, $yearOnly] );

        $conn = null;

        return "Successful";

    }
    catch(PDOException $e)
    {
        $conn = null;
        return $e->getMessage();
    }     
}

    public function NewPayout($amount, $receiver, $head, $reason, $user_id, $dateof, $monthYear, $yearOnly){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO expenses(amount, receiver, expense_id, reason, user_id, dateof, monthYear, yearOnly) VALUES (?,?,?,?,?,?,?,?)";

            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$amount, $receiver, $head, $reason, $user_id, $dateof, $monthYear, $yearOnly] );

            $conn = null;

            return "Successful";

        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }     
    }

    public function NewDiscount($student_code, $discount, $user_id, $dateof, $monthYear, $yearOnly){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());

            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO discount(student_code, discount_id, user_id, dateof, monthYear, yearOnly) VALUES (?,?,?,?,?,?)";

            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$student_code, $discount, $user_id, $dateof, $monthYear, $yearOnly] );

            $conn = null;

            return "Successful";

        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }     
    }

    public function GetDiscount($student_code){
        $rows = array();
    try {
        $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM discount WHERE student_code = ? AND status = ?");

        $stmt->execute([$student_code, 1]);

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;

    if(count($rows) < 1){
        return false;
    }else{
        return $rows[0];
    }
    }

    public function GetExpenseSources(){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT * FROM expense_sources");
    
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

    public function GetAnnualAverage($year_id, $class_id){
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT student, AVG(average) as av FROM computed_averages WHERE year_id =? AND class_id = ? GROUP BY student, year_id ORDER BY av DESC");
    
            $stmt->execute([$year_id, $class_id]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        $positions = [];
        for($i = 0; $i<count($rows); $i++){
            $positions[$rows[$i]['student']] = round($rows[$i]['av'], 2);
        }
        
        return $positions;
        
    }

    public function GetAnnualPosition($year_id, $class_id){
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT student, AVG(average) as av FROM computed_averages WHERE year_id =? AND class_id = ? GROUP BY student, year_id ORDER BY av DESC");
    
            $stmt->execute([$year_id, $class_id]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        $positions = [];
        for($i = 0; $i<count($rows); $i++){
            $positions[$rows[$i]['student']] = $i +1;
        }
        
        return $positions;
        
    }

    public function GetTermAverageForStudent($year_id, $class_id, $term_id){
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT student, AVG(average) as av FROM computed_averages WHERE year_id =? AND class_id = ? AND term =? GROUP BY student , term , year_id ORDER BY av DESC");
    
            $stmt->execute([$year_id, $class_id, $term_id]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        $averages = [];
            for($i = 0; $i<count($rows); $i++){
                $averages[$rows[$i]['student']] = round($rows[$i]['av'], 2);
            }
            
        return $averages;
        
    }

    public function FeeDrive($year_id, $class_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT student_code AS code, SUM(totalpaid) AS total FROM fees WHERE academic_year = ? AND class_id = ? GROUP BY student_code");
    
            $stmt->execute([$year_id, $class_id]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null; 
    
        return $rows;
    }

    public function GetFeesLeft($student_code, $class_id, $year_id){
        $feeType = $this->FeeType($student_code);
        $class_fees = $this->GetFeeStructure($class_id, $feeType);
        $amount = $this->GetStudentsFees($student_code, $class_id, $year_id);
        $discount = $this->GetDiscount($student_code);
        if ($discount != false){
            $dis_id = $discount['discount_id'];
            $percent = $this->GetDiscountReason($dis_id)['percent'];
            $installments = $class_fees['first_ins'] + $class_fees['second_ins'];
            $dis_amt = round(($percent/100)*$installments, 0);
            $left = (int)$class_fees['totalfee'] - $dis_amt - (int)$amount;
            return $left;
        }else{
            $left = (int)$class_fees['totalfee'] - (int)$amount;
            return $left;
        }
    }

    public function Figure($number){
        if (strlen($number) == 3){
            return $number;
        }

        if (strlen($number) == 4){
            return substr($number, 0, 1).','.substr($number, 1, 3);
        }

        if (strlen($number) == 5){
            return substr($number, 0, 2).','.substr($number, 2, 3);
        }

        if (strlen($number) == 6){
            return substr($number, 0, 3).','.substr($number, 3, 3);
        }

        if (strlen($number) == 7){
            return substr($number, 0, 1).','.substr($number, 1, 3).','.substr($number, 4, 3);
        }
        
        
    }

    public function UpdateFeesClassChange($student_code, $academic_year, $new_class){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE fees SET class_id = ? WHERE student_code = ? AND academic_year = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$new_class, $student_code, $academic_year] );
    
            $conn = null;
    
            return "Successful";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function GetRepGroup($subject, $class_id, $section){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT rep_group FROM subjects WHERE subject = ? AND class_name = ? AND section = ?");
    
            $stmt->execute([$subject, $class_id, $section]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        if(!empty($rows)){
            return $rows[0]['rep_group'];
        }else{
            return 1;
        }
    }

    public function TotalExists($subject, $student, $exam_id, $year_id, $class_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT * FROM computed_totals WHERE subject = ? AND student = ? AND exam_id =? AND year_id = ? AND class_id = ?");
    
            $stmt->execute([$subject, $student, $exam_id, $year_id, $class_id]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        if(count($rows) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function UpdateStudentTotal(array $data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE computed_totals SET total = ?, `rank` = ?, remark =?, grade = ?, subject_group =? WHERE subject =? AND student =? AND exam_id =? AND year_id =? AND class_id = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[4], $data[5], $data[6], $data[7], $data[8], $data[0], $data[1], $data[2], $data[3], $data[9]]);
    
            $conn = null;
    
            
            return "Ok";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function NewStudentTotal($data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "INSERT INTO computed_totals(subject, student, exam_id, year_id, total, `rank`, remark, grade, subject_group, class_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9]]);
    
            $conn = null;
    
            
            return "Ok";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return "New Total ".$e->getMessage();
        }
    }

    public function AverageExists($student, $exam_id, $year_id, $class_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT * FROM computed_averages WHERE student = ? AND exam_id =? AND year_id = ? AND class_id = ?");
    
            $stmt->execute([$student, $exam_id, $year_id, $class_id]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        if(empty($rows)){
            return false;
        }else{
            return true;
        }
    }

    public function UpdateStudentAverage($data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE computed_averages SET position = ?, term =?, average =?, overall_remark =?, annual_av =? WHERE student = ? AND exam_id = ? AND year_id = ? AND class_id = ?";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([ $data[3], $data[4], $data[5], $data[6], $data[7], $data[0], $data[1], $data[2], $data[8]]);
    
            $conn = null;
    
            
            return "Ok";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function NewStudentAverage($data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "INSERT INTO computed_averages(student, exam_id, year_id, position, term, average, overall_remark, annual_av, class_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            // use exec() because no results are returned
            $statement = $conn->prepare($sql);
            $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8]]);
    
            $conn = null;
    
            
            return "Ok";
    
        }
        catch(PDOException $e)
        {
            $conn = null;
            return $e->getMessage();
        }
    }

    public function GetStudentAverages($exam_id, $class_id, $year_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT student, average FROM computed_averages WHERE class_id = ? AND exam_id =? AND year_id = ? ORDER BY average DESC");
    
            $stmt->execute([$class_id, $exam_id, $year_id]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        $averages = [];
        for($i = 0; $i<count($rows); $i++){
            $averages[$rows[$i]['student']] = round($rows[$i]['average'], 2);
        }
        
        return $averages;
    }

    public function GetSequencePosition($year_id, $class_id, $exam_id){
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT student, average as av FROM computed_averages WHERE year_id =? AND class_id = ? AND exam_id =? ORDER BY av DESC");
    
            $stmt->execute([$year_id, $class_id, $exam_id]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        $positions = [];
        for($i = 0; $i<count($rows); $i++){
            $positions[$rows[$i]['student']] = $i +1;
        }
        
        return $positions;
        
    }

    public function GetClassAverage($exam_id, $class_id, $year_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT AVG(average) AS class_average FROM computed_averages WHERE class_id = ? AND exam_id =? AND year_id = ?");
    
            $stmt->execute([$class_id, $exam_id, $year_id]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        return $rows[0]['class_average'];
    }

    public function GetPosition($year_id, $class_id, $term_id){
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT student, AVG(average) as av FROM computed_averages WHERE year_id =? AND class_id = ? AND term =? GROUP BY student , term , year_id ORDER BY av DESC");

            $stmt->execute([$year_id, $class_id, $term_id]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        $positions = [];
        for($i = 0; $i<count($rows); $i++){
            $positions[$rows[$i]['student']] = $i +1;
        }
        
        return $positions;
        
    }

    public function ClassAverageForTerm($year_id, $class_id, $term_id){
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT AVG(average) as av FROM computed_averages WHERE year_id =? AND class_id = ? AND term =?");
    
            $stmt->execute([$year_id, $class_id, $term_id]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        return round($rows[0]['av'], 2);
        
    }

    public function GetAverages($exam_id, $class_id, $year_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT * FROM computed_averages WHERE class_id = ? AND exam_id =? AND year_id = ? ORDER BY average DESC");
    
            $stmt->execute([$class_id, $exam_id, $year_id]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        return $rows;
    }

    public function GetStudentTotals($exam_id, $class_id, $year_id, $student, $subject){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT * FROM computed_totals WHERE class_id = ? AND exam_id =? AND year_id = ? AND student = ? AND subject = ?");
    
            $stmt->execute([$class_id, $exam_id, $year_id, $student, $subject]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        if(count($rows) > 0){
            return $rows[0];
        }else{
            return [];
        }
    
    }

    public function GetClassBest($exam_id, $class_id, $year_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT MAX(average) AS class_average FROM computed_averages WHERE class_id = ? AND exam_id =? AND year_id = ?");
    
            $stmt->execute([$class_id, $exam_id, $year_id]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        return $rows[0]['class_average'];
    }

    public function GetClassLast($exam_id, $class_id, $year_id){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());
    
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $stmt = $conn->prepare("SELECT MIN(average) AS class_average FROM computed_averages WHERE class_id = ? AND exam_id =? AND year_id = ?");
    
            $stmt->execute([$class_id, $exam_id, $year_id]);
    
            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $rows = $stmt->fetchAll();
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
    
        return $rows[0]['class_average'];
    }

    public function ContentExists($tableName, $criteria, $criteria_value){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM $tableName WHERE $criteria = ?");

            $stmt->execute([$criteria_value]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function Insert($table, array $data){
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

    public function GetAllWithCriteria($table, array $criteria, $snippet = null){
        $crit =[];
        foreach($criteria as $key=>$value) {
            array_push($crit, $key."='".$value."'");
        }
        $sql_criteria = implode(' AND ', $crit);
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM $table WHERE ".$sql_criteria." ".$snippet);

            $stmt->execute();

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function ClassTeachers($class, $day){
        $teachers = [];
        $staff_subjects = $this->ContentExists('staff_subjects', 'academic_year', $this->GetCurrentYear()[0]['id']);
        foreach ($staff_subjects as $s){
            $classes = explode(',', $s['class_id']);
            $staff_days = explode(',', $this->ContentExists('staff_days', 'staff_id', $s['staff_id'])[0]['dow']);
            if(in_array($class, $classes)){
                array_push($teachers, 
                [
                    $s['staff_id']=> [
                        'subject'=>$s['subject'],
                         'days'=>in_array($day, $staff_days) ? $day : ''
                    ]
                ]
                );
            }
            
        }
        
        return $teachers;
    }

    public function Initials($word){
        $words = explode(' ', $word);
        $initials = [];
        foreach($words as $w){
            $s = substr($w, 0,1);
            array_push($initials, $s);
        }
        return implode('', $initials);
    }

    public function GetSomeStudent($student_code, $section){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT id, `name`, gender, dob, pob, guardian, guardian_number, student_code, mother_name, father_name, adm_num, section  FROM students WHERE student_code = ? AND section = ?");

            $stmt->execute([$student_code, $section]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function Languages(){
        $rows = ['FRENCH', 'ENGLISH LANGUAGE', 'AUXILLIARY ENGLISH'];
        return $rows;
    }

    public function Sciences(){
        $rows = 
        [
        'MATHEMATICS', 
        'PURE MATHEMATICS', 
        'PURE MATHS WITH MECHS', 
        'PURE MATHS WITH STATS', 
        'CHEMISTRY',
        'BIOLOGY', 
        'PHYSICS',
        'FURTHER MATHEMATICS',
        'COMPUTER SCIENCE', 
        'INFORMATION AND COMMUNICATION TECHNOLOGY',
        'HUMAN BIOLOGY',
        'GEOLOGY',
        'FOOD AND NUTRITION',
    ];
        return $rows;
    }

    public function Arts(){
        $rows = 
        [
        'HISTORY', 
        'CITIZENSHIP', 
        'GEOGRAPHY', 
        'ECONOMICS', 
        'COMMERCE',
        'LITERATURE',
        'HOME ECONOMICS',
        'LOGIC'           
    ];
        return $rows;
    }

    public function OtherSubjects(){
        $rows = 
        [
        'PUBLIC SPEAKING/CC', 
        'SPORTS', 
        'MANUAL LABOUR', 
        'MORAL EDUCATION', 
        'RELIGIOUS STUDIES'          
    ];
        return $rows;
    }

}