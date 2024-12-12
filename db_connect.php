<?php
class Database {
    private $host = "localhost";
    private $db_name = "crmtest";
    private $username = "root";
    private $password = "";
    private $conn = null;

    public function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET NAMES utf8");
            return $this->conn;
        } catch(PDOException $e) {
            echo "連線錯誤: " . $e->getMessage();
            return null;
        }
    }
}
?> 