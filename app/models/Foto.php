<?php
class Foto {
    private $conn;
    private $table_name = "foto";
    private $FotoID;
    private $JudulFoto;
    private $DeskripsiFoto;
    private $TanggalUnggah;
    private $LokasiFile;
    private $AlbumID;
    private $UserID;

// Konstruktor untuk menginisialisasi koneksi database
    public function __construct($db) {
    $this->conn = $db;
}

  // Fungsi untuk mengupload foto
  public function uploadFoto($judul, $deskripsi, $lokasiFile, $albumID, $userID) {
    $query = "INSERT INTO foto (JudulFoto, DeskripsiFoto, LokasiFile, AlbumID, UserID, TanggalUnggah) VALUES (:JudulFoto, :DeskripsiFoto, :LokasiFile, :AlbumID, :UserID, :TanggalUnggah)";
    $stmt = $this->conn->prepare($query);

    // Ambil tanggal unggah saat ini
    $tanggalUnggah = date('Y-m-d H:i:s');

    // Binding parameter
    $stmt->bindParam(':JudulFoto', $judul);
    $stmt->bindParam(':DeskripsiFoto', $deskripsi);
    $stmt->bindParam(':LokasiFile', $lokasiFile);
    $stmt->bindParam(':AlbumID', $albumID);
    $stmt->bindParam(':UserID', $userID);
    $stmt->bindParam(':TanggalUnggah', $tanggalUnggah);

    // Eksekusi dan kembalikan hasilnya
    return $stmt->execute();
}

// Fungsi untuk mengedit foto
    public function editFoto($fotoID, $judul, $deskripsi) {
    $query = "UPDATE " . $this->table_name . " SET JudulFoto = ?, DeskripsiFoto = ? WHERE FotoID = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ssi", $judul, $deskripsi, $fotoID);
    return $stmt->execute(); // Kembalikan hasil eksekusi
}

// Fungsi untuk menghapus foto
    public function deleteFoto($fotoID) {
    $query = "DELETE FROM " . $this->table_name . " WHERE FotoID = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $fotoID);
    return $stmt->execute(); // Kembalikan hasil eksekusi
}

// Contoh metode dalam model Foto
public function getPhotosByUser($userID) {
    $query = "SELECT f.FotoID, f.JudulFoto, f.DeskripsiFoto, f.TanggalUnggah, f.LokasiFile, 
                     a.NamaAlbum, u.Username
              FROM foto AS f
              JOIN album AS a ON f.AlbumID = a.AlbumID
              JOIN user AS u ON f.UserID = u.UserID
              WHERE f.UserID = :UserID";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':UserID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function getAlbumsByUser($userID) {
    $query = "SELECT * FROM album WHERE UserID = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Fungsi lain yang diperlukan (seperti mendapatkan foto) bisa ditambahkan di sini
}
?>