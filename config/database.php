<?php
class Database
{
    private $host = "localhost:3307";
    private $username = "root";
    private $password = "1234";
    private $database = "local_deposits_db";
    private $conn;

    public function getConnection(){
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>