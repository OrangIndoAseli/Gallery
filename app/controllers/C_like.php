<?php
// Include database connection dan model
require_once '../../config/database.php';
require_once '../models/Like.php';

class C_like {
    private $db;
    private $likeModel;

    public function __construct() {
        // Initialize database connection dan model
        $this->db = new Database();
        $this->likeModel = new Like($this->db->getConnection());
    }

    // Fungsi untuk menambahkan atau menghapus like
    public function toggleLike($fotoID, $userID) {
        // Periksa apakah foto sudah di-like oleh user
        $isLiked = $this->likeModel->isLiked($fotoID, $userID);

        if ($isLiked) {
            // Hapus like jika sudah di-like
            $this->likeModel->unlikePhoto($fotoID, $userID);
            $status = 'unliked';
        } else {
            // Tambahkan like jika belum di-like
            $this->likeModel->likePhoto($fotoID, $userID);
            $status = 'liked';
        }

        // Kembalikan jumlah like terbaru
        $likeCount = $this->likeModel->getLikeCount($fotoID);

        // Mengembalikan response JSON
        return json_encode([
            'status' => $status, 
            'likeCount' => $likeCount
        ]);
    }

    // Fungsi untuk menangani request dari AJAX
    public function handleLikeRequest() {
        // Ambil data dari permintaan AJAX
        $data = json_decode(file_get_contents('php://input'), true);

        // Periksa apakah data yang diperlukan ada
        if (isset($data['fotoID'], $data['userID'])) {
            $fotoID = $data['fotoID'];
            $userID = $data['userID'];

            // Panggil fungsi toggleLike dan echo response
            echo $this->toggleLike($fotoID, $userID);
        } else {
            // Jika data tidak lengkap, kirim response error
            echo json_encode([
                'status' => 'error', 
                'message' => 'Data tidak lengkap'
            ]);
        }
    }
}

// Inisialisasi controller dan handle request
$likeController = new C_like();
$likeController->handleLikeRequest();
?>
