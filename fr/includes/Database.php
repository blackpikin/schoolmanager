<?php
class Database
{
    private $serverName = "localhost:3306";
    private $username = "root";
    private $password = "solidus84B52@";
    //private $password = "";
   private $dbName = "qisdb";
   //private $dbName = "classmaster";
    //private $dbName = "skoo_manager";
    private $defaultPw = "12345678";

    public function ServerName(){return $this->serverName;}

    public function UserName(){return $this->username;}

    public function Password(){return $this->password;}

    public function DatabaseName(){return $this->dbName;}

    public function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function DefaultUserPassword(){
        $pw = $this->HashPassword($this->defaultPw);
        return $pw;
    }

#Hash the password
    public function HashPassword($password){
        $salt = "adfsvdRTYzxcfsoeuurdjdueyreriysdsjdbksj1234dbasjhskdgthsasgshdmwqtewuqequcvxbnzmsmmakjlmnbvOPiudkfuieyuioekdksghdyetaTYHsdOPHGsdndn";
        return hash_hmac('sha256', $password, $salt);
    }

    public function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }

    public function getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZblackpikintheSCIENTIST";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyzTHEWEBSITENAMEdsdkjsdvqkggweudqudnccsbcachqqwqw";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
        }

        return $token;
    }

}

