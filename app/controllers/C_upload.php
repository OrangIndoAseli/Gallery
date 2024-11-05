<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '../../models/Foto.php'; // Menghubungkan model M_foto

// Periksa apakah user sudah login
if (!isset($_SESSION['UserID'])) {
    header("Location: ../../index.php"); // Arahkan kembali ke halaman login jika belum login
    exit();
}

// Class C_upload
class C_upload {
    private $foto;

    // Konstruktor untuk menginisialisasi objek M_foto
    public function __construct($db) {
        $this->foto = new Foto($db->getConnection());
    }

    // Fungsi untuk mengupload foto
    public function upload($judul, $deskripsi, $albumID, $userID) {
        // Validasi input
        if (empty($judul) || empty($deskripsi) || empty($albumID)) {
            $_SESSION['message'] = "Judul, deskripsi, dan album tidak boleh kosong.";
            header("Location: ../views/upload_views.php"); // Kembali ke halaman upload
            exit();
        }

        // Menentukan direktori untuk menyimpan file
        $targetDir = "../../assets/img/";
        $fileName = basename($_FILES['file']['name']);
        $targetFilePath = $targetDir . $fileName;

        // Upload file
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
            // Simpan informasi foto ke dalam database
            if ($this->foto->uploadFoto($judul, $deskripsi, $targetFilePath, $albumID, $userID)) {
                header("Location: ../views/upload_views.php?message=" . urlencode("Foto berhasil diupload!"));
            } else {
                header("Location: ../views/upload_views.php?message=" . urlencode("Gagal mengupload foto."));
            }
        } else {
            header("Location: ../views/upload_views.php?message=" . urlencode("Gagal memindahkan file."));
        }
    }

    // Fungsi untuk mengedit foto
    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
            $fotoID = $_POST['fotoID'] ?? '';
            $judul = $_POST['judul'] ?? '';
            $deskripsi = $_POST['deskripsi'] ?? '';

            if ($this->foto->editFoto($fotoID, $judul, $deskripsi)) {
                header("Location: ../views/photo_list_view.php?message=" . urlencode("Foto berhasil diubah!"));
            } else {
                header("Location: ../views/photo_list_view.php?message=" . urlencode("Gagal mengubah foto."));
            }
        }
    }

    // Fungsi untuk menghapus foto
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
            $fotoID = $_POST['fotoID'] ?? '';
            if ($this->foto->deleteFoto($fotoID)) {
                header("Location: ../views/photo_list_view.php?message=" . urlencode("Foto berhasil dihapus!"));
            } else {
                header("Location: ../views/photo_list_view.php?message=" . urlencode("Gagal menghapus foto."));
            }
        }
    }
}

// Inisialisasi Database dan C_upload
$db = new Database();  // Pastikan class Database sudah ada
$uploadController = new C_upload($db);

// Ambil aksi dari parameter POST
$action = $_POST['action'] ?? '';
$response = '';

// Eksekusi fungsi berdasarkan aksi yang dipilih
if ($action === 'upload') {
    $judul = $_POST['judul'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $albumID = $_POST['album'] ?? '';
    $userID = $_SESSION['UserID']; // Ambil UserID dari session
    $uploadController->upload($judul, $deskripsi, $albumID, $userID);
} elseif ($action === 'edit') {
    $fotoID = $_POST['fotoID'] ?? '';
    $judul = $_POST['judul'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $albumID = $_POST['album'] ?? '';
    $uploadController->edit($fotoID, $judul, $deskripsi, $albumID);
} elseif ($action === 'delete') {
    $fotoID = $_POST['fotoID'] ?? '';
    $uploadController->delete($fotoID);
}

?>