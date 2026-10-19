<?php
class Database {
    private $host = "localhost";
    private $db_name = "fandi_12rpl_paket3";
    private $username = "root";
    private $password = ""; // Kosongkan jika menggunakan XAMPP standar
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Koneksi Error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>