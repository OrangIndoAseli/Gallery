<?php
class Komentar {
    private $conn;
    private $table_name = "komentarfoto";

    private $KomentarID;

    private $FotoID;

    private $IsiKomentar;

    private $TanggalKomentar;


public function __construct($db) {
    $this->conn = $db;
}

public function getCommentsByPhoto($fotoID) {
    // Query SQL untuk mengambil komentar dan Username pengguna yang memberi komentar
    $query = "SELECT k.KomentarID, k.FotoID, k.UserID, k.IsiKomentar, k.TanggalKomentar, u.Username
              FROM komentarfoto k
              LEFT JOIN user u ON k.UserID = u.UserID
              WHERE k.FotoID = :fotoID 
              ORDER BY k.TanggalKomentar DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':fotoID', $fotoID, PDO::PARAM_INT);
    $stmt->execute();
    
    // Mengambil semua komentar dan mengembalikan hasilnya
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




public function createKomen($fotoID, $isiKomentar, $userID) {
    // Set TanggalKomentar to the current date and time
    $tanggalKomentar = date('Y-m-d H:i:s');

    // Prepare the SQL query to insert a new comment
    $query = "INSERT INTO komentarfoto (FotoID, UserID, IsiKomentar, TanggalKomentar)
              VALUES (:fotoID, :userID, :isiKomentar, :tanggalKomentar)";
    $stmt = $this->conn->prepare($query);

    // Bind parameters
    $stmt->bindParam(':fotoID', $fotoID, PDO::PARAM_INT);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT); // Menambahkan UserID
    $stmt->bindParam(':isiKomentar', $isiKomentar, PDO::PARAM_STR);
    $stmt->bindParam(':tanggalKomentar', $tanggalKomentar, PDO::PARAM_STR);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        // Return the ID of the newly created comment
        return $this->conn->lastInsertId();
    } else {
        // Handle error (optional: log it or throw an exception)
        return false;
    }
}


public function deleteKomen($komentarID, $userID) {
    $query = "DELETE FROM komentarfoto WHERE KomentarID = :komentarID AND UserID = :userID";
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':komentarID', $komentarID, PDO::PARAM_INT);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);

    return $stmt->execute();
}


}