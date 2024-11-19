<?php

class Like {
    private $conn;
    private $table_name = "likefoto";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Menambahkan like ke foto
    public function likePhoto($fotoID, $userID) {
        $query = "INSERT INTO {$this->table_name} (FotoID, UserID, TanggalLike) 
                  VALUES (:fotoID, :userID, NOW())";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':fotoID', $fotoID);
        $stmt->bindParam(':userID', $userID);

        return $stmt->execute();
    }

    // Menghapus like dari foto
    public function unlikePhoto($fotoID, $userID) {
        $query = "DELETE FROM {$this->table_name} 
                  WHERE FotoID = :fotoID AND UserID = :userID";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':fotoID', $fotoID);
        $stmt->bindParam(':userID', $userID);

        return $stmt->execute();
    }

    // Menghitung jumlah like pada foto tertentu
    public function getLikeCount($fotoID) {
        $query = "SELECT COUNT(*) AS like_count 
                  FROM {$this->table_name} 
                  WHERE FotoID = :fotoID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fotoID', $fotoID);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Mengecek apakah foto sudah di-like oleh user tertentu
    public function isLiked($fotoID, $userID) {
        $query = "SELECT 1 
                  FROM {$this->table_name} 
                  WHERE FotoID = :fotoID AND UserID = :userID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fotoID', $fotoID);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
