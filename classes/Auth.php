<?php
class Auth {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE username = :user LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user", $username);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Database fandi_12rpl_paket3 berisi dua jenis password:
            // bcrypt (password_hash) untuk akun baru dan MD5 untuk akun lama (lulu, asep)
            $valid = password_verify($password, $row['password']) || (md5($password) === $row['password']);

            if($valid) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $row['id_user'];
                $_SESSION['nis']     = $row['nis'];
                $_SESSION['nama']    = $row['nama_lengkap'];
                $_SESSION['level']   = $row['level'];

                return $row['level']; // Mengembalikan 'admin' atau 'siswa'
            }
        }
        return false;
    }
}
?>