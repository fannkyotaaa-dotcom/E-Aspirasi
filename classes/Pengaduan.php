<?php
// Class ini mengelola laporan pengaduan yang disimpan di tabel `aspirasi`
class Pengaduan {
    private $conn;
    private $table_name = "aspirasi";

    // Properti sesuai kolom di tabel aspirasi
    public $id_aspirasi;
    public $nis;
    public $id_kategori;
    public $lokasi;
    public $isi_aspirasi;
    public $foto_bukti;
    public $tanggal;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fungsi untuk Siswa: Membuat Laporan Baru
    public function buatLaporan() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET nis=:nis, id_kategori=:id_kategori, lokasi=:lokasi,
                      isi_aspirasi=:isi, foto_bukti=:foto, tanggal=:tgl, status='pending'";

        $stmt = $this->conn->prepare($query);

        // Sanitasi data
        $this->lokasi = htmlspecialchars(strip_tags($this->lokasi));
        $this->isi_aspirasi = htmlspecialchars(strip_tags($this->isi_aspirasi));

        // Bind data
        $stmt->bindParam(":nis", $this->nis);
        $stmt->bindParam(":id_kategori", $this->id_kategori);
        $stmt->bindParam(":lokasi", $this->lokasi);
        $stmt->bindParam(":isi", $this->isi_aspirasi);
        $stmt->bindParam(":foto", $this->foto_bukti);
        $stmt->bindParam(":tgl", $this->tanggal);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Fungsi untuk Admin: Melihat semua laporan (Join dengan users dan kategori)
    public function tampilkanSemua() {
        $query = "SELECT a.*, u.nama_lengkap, k.nama_kategori
                  FROM " . $this->table_name . " a
                  LEFT JOIN users u ON a.nis = u.nis
                  LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
                  ORDER BY a.tanggal DESC, a.id_aspirasi DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Fungsi untuk Admin: Update Status (pending/proses/selesai)
    public function updateStatus($id, $status_baru) {
        if (!in_array($status_baru, ['pending', 'proses', 'selesai'], true)) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id_aspirasi = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":status", $status_baru);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    public function getStatistik() {
        $query = "SELECT
                    COUNT(*) as total,
                    COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) as jml_pending,
                    COALESCE(SUM(CASE WHEN status = 'proses' THEN 1 ELSE 0 END), 0) as jml_proses,
                    COALESCE(SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END), 0) as jml_selesai
                FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Hitung laporan pending yang belum dilihat admin.
    // Tabel aspirasi tidak punya kolom "sudah dibaca", jadi batasnya berupa
    // id laporan terakhir yang sudah dilihat admin pada sesi login ini.
    public function countUnreadAdmin($lastSeenId = 0) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . "
                  WHERE status = 'pending' AND id_aspirasi > :last";
        $stmt = $this->conn->prepare($query);
        $lastSeenId = (int)$lastSeenId;
        $stmt->bindParam(":last", $lastSeenId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'];
    }

    // id laporan terbaru (dipakai untuk menandai semua laporan sudah dilihat)
    public function getMaxId() {
        $query = "SELECT COALESCE(MAX(id_aspirasi), 0) as maks FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['maks'];
    }

    // Fungsi untuk mengambil statistik khusus milik satu siswa (berdasarkan NIS)
    public function getStatistikByUser($nis) {
        $query = "SELECT
                    COUNT(*) as total,
                    COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) as jml_pending,
                    COALESCE(SUM(CASE WHEN status = 'proses' THEN 1 ELSE 0 END), 0) as jml_proses,
                    COALESCE(SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END), 0) as jml_selesai
                FROM " . $this->table_name . "
                WHERE nis = :nis";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nis', $nis);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fungsi untuk menampilkan riwayat pengaduan milik satu siswa saja
    public function tampilkanByUser($nis) {
        $query = "SELECT a.*, k.nama_kategori
                FROM " . $this->table_name . " a
                LEFT JOIN kategori k ON a.id_kategori = k.id_kategori
                WHERE a.nis = :nis
                ORDER BY a.tanggal DESC, a.id_aspirasi DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nis', $nis);
        $stmt->execute();

        return $stmt;
    }
}
?>