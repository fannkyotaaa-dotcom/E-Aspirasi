x<?php
class Kategori {
    private $conn;
    private $table_name = "kategori";

    public $id_kategori;
    public $nama_kategori;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Read Semua Data
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_kategori DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Create (Tambah Data)
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nama_kategori=:nama";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nama", $this->nama_kategori);
        return $stmt->execute();
    }

    // Delete (Hapus Data)
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_kategori = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Ambil satu data kategori berdasarkan ID (untuk mengisi form edit)
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_kategori = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fungsi Update Data
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama_kategori=:nama WHERE id_kategori=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nama", $this->nama_kategori);
        $stmt->bindParam(":id", $this->id_kategori);
        return $stmt->execute();
    }
}
?>