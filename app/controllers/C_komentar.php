<?php
require_once '../../config/database.php';
require_once '../models/Komentar.php'; // Assuming your model is named Komentar

class C_komentar {
    private $db;
    private $komentar;

    // Constructor
    public function __construct($db) {
        $this->db = $db;
        $this->komentar = new Komentar($this->db->getConnection()); // Initialize the Komentar model
    }

    // Function to create a comment
    public function createComment($fotoID, $isiKomentar) {
        // Langsung akses UserID dari sesi
        if (isset($_SESSION['UserID'])) {
            $userID = $_SESSION['UserID'];
        } else {
            return "UserID tidak ditemukan, harap login terlebih dahulu.";
        }
    
        // Panggil metode createKomen dengan UserID yang sudah ada
        $result = $this->komentar->createKomen($fotoID, $isiKomentar, $userID);
    
        return $result ? "Komentar berhasil ditambahkan!" : "Gagal menambahkan komentar.";
    }
    
    
    

    // Function to get comments for a specific photo
    public function getCommentsByPhoto($fotoID) {
        if (empty($fotoID)) {
            return "FotoID is required.";
        }

        // Call the getCommentsByPhoto method from the model
        $comments = $this->komentar->getCommentsByPhoto($fotoID);

        return $comments ?: "No comments found for this photo.";
    }

    // Function to delete a comment
    public function deleteComment($komentarID) {
        // Periksa apakah UserID ada dalam sesi
        if (!isset($_SESSION['UserID'])) {
            return "Silakan login untuk menghapus komentar.";
        }
    
        $userID = $_SESSION['UserID'];
    
        // Validasi input
        if (empty($komentarID)) {
            return "Komentar ID tidak valid.";
        }
    
        // Panggil fungsi model untuk menghapus komentar
        $result = $this->komentar->deleteKomen($komentarID, $userID);
    
        return $result ? "Komentar berhasil dihapus!" : "Gagal menghapus komentar.";
    }
    
    
}

// Action Handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Start session only if not already started
    }

    // Initialize database and comment controller
    $db = new Database();
    $komentarController = new C_komentar($db);

    $action = $_POST['action'];

    try {
        $fotoID = $_POST['fotoID'] ?? '';
        $komentarID = $_POST['komentarID'] ?? '';
        $isiKomentar = $_POST['isiKomentar'] ?? '';

        // Handle different actions
        switch ($action) {
            case 'create':
                $message = $komentarController->createComment($fotoID, $isiKomentar);
                break;

            case 'delete':
                $message = $komentarController->deleteComment($_POST['komentarID']);
                break;
                
            case 'get':
                $comments = $komentarController->getCommentsByPhoto($fotoID);
                $message = is_array($comments) ? json_encode($comments) : $comments;
                header('Content-Type: application/json');
                echo $message;
                exit();

            default:
                $message = "Invalid action.";
                break;
        }

        // Redirect back to photo page or handle differently if needed
        header("Location: ../views/home_views.php?fotoID={$fotoID}&message=" . urlencode($message));
        exit();
    } catch (Exception $e) {
        header("Location: ../views/home_views.php?message=" . urlencode("Error: " . $e->getMessage()));
        exit();
    }
}
?>
