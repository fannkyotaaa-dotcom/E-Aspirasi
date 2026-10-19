<?php
class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($username, $password, $nama_lengkap, $nis) {
        $nis = trim($nis);
        if ($nis === '') {
            return "NIS wajib diisi!";
        }

        // Cek apakah username sudah ada
        $check = "SELECT id_user FROM " . $this->table_name . " WHERE username = :user";
        $stmtCheck = $this->conn->prepare($check);
        $stmtCheck->bindParam(":user", $username);
        $stmtCheck->execute();

        if($stmtCheck->rowCount() > 0) {
            return "Username sudah digunakan!";
        }

        // NIS dipakai sebagai penghubung ke tabel aspirasi, jadi tidak boleh ganda
        $checkNis = "SELECT id_user FROM " . $this->table_name . " WHERE nis = :nis";
        $stmtNis = $this->conn->prepare($checkNis);
        $stmtNis->bindParam(":nis", $nis);
        $stmtNis->execute();

        if($stmtNis->rowCount() > 0) {
            return "NIS sudah terdaftar!";
        }

        // Insert data baru
        $query = "INSERT INTO " . $this->table_name . "
                  SET nis=:nis, username=:user, password=:pass, nama_lengkap=:nama, level='siswa'";

        $stmt = $this->conn->prepare($query);
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(":nis", $nis);
        $stmt->bindParam(":user", $username);
        $stmt->bindParam(":pass", $hashed_pass);
        $stmt->bindParam(":nama", $nama_lengkap);

        if($stmt->execute()) {
            return true;
        }
        return "Gagal mendaftar, coba lagi.";
    }

    public function updateProfile($id_user, $nama, $password = null) {
        if ($password) {
            // Jika password diisi, update nama dan password
            $query = "UPDATE " . $this->table_name . " SET nama_lengkap = :nama, password = :pass WHERE id_user = :id";
            $stmt = $this->conn->prepare($query);
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(":pass", $hashed_pass);
        } else {
            // Jika password kosong, hanya update nama
            $query = "UPDATE " . $this->table_name . " SET nama_lengkap = :nama WHERE id_user = :id";
            $stmt = $this->conn->prepare($query);
        }

        $stmt->bindParam(":nama", $nama);
        $stmt->bindParam(":id", $id_user);

        return $stmt->execute();
    }

    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_user = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>