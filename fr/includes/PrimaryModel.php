<?php
class PrimaryModel extends Database
{
    private $section;

    public function __construct($sec) {
        $this->section = $sec;
    }

    public function get_section() {
        return $this->section;
    }
    
    public function GetAllPrimaryClasses(){
        $classes_list = [];
        $general_names = ['DAY-CARE','PRE-NURSERY', 'NURSERY ONE','NURSERY TWO','CLASS ONE', 'CLASS TWO', 'CLASS THREE', 'CLASS FOUR', 'CLASS FIVE', 'CLASS SIX'];
        foreach($general_names as $gn){
            $classes = $this->GetClassesInClass($gn, $this->get_section());
            if(!empty($classes)){
                foreach($classes as $class){
                    array_push($classes_list, ['id' => $class['id'], 'general_name' => $class['general_name'], 'sub_name' => $class['sub_name'], 'cycle' => $class['cycle'], 'mockable' => $class['mockable']]);
                }
            }
        }
        return $classes_list;    
    }

    public function GetClassesInClass($form, $section){
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

    public function GetAllPrimaryUsers(){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM users WHERE role = ? AND section = ?");

            $stmt->execute(['Primary-Teacher', $this->get_section()]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function RegisterNewPupil(array $data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "INSERT INTO pupils(name, gender,dob, pob, guardian, guardian_number, guardian_email, guardian_address, student_code, mother_name, father_name, adm_num, section ) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?,?, ?, ?)";
    
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

    public function GetPupil($student_code){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM pupils WHERE student_code = ? AND section = ?");

            $stmt->execute([$student_code, $this->get_section()]);

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $rows = $stmt->fetchAll();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;

        return $rows;
    }

    public function SearchPupil($searh_string){
        $rows = array();
        try {
            $conn = new PDO("mysql:host=" . $this->ServerName() . ";dbname=" . $this->DatabaseName(), $this->UserName(), $this->Password());

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM pupils WHERE name LIKE '%$searh_string%' AND section =?");

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

    
    public function UpdatePupil(array $data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE pupils SET name = ?, gender = ? ,dob = ? , pob = ?, guardian =? , guardian_number = ?, guardian_email =?, guardian_address =?, mother_name = ?, father_name = ?, adm_num = ? WHERE student_code = ?";
    
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
    
    public function SetPupilPicture($data){
        try {
            $conn = new PDO("mysql:host=".$this->ServerName().";dbname=".$this->DatabaseName(), $this->UserName(), $this->Password());
    
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "UPDATE pupils SET picture = ?, picture_ext = ?  WHERE student_code = ?";
    
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
}