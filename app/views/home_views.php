<?php 
// Mengaktifkan session
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/.././controllers/C_upload.php';
require_once __DIR__ . '/../models/Foto.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['UserID'])) {
    header("Location: ../../index.php"); // Arahkan kembali ke halaman login jika belum login
    exit();
}

// Cek jika ada permintaan untuk logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {

    // Hapus semua data sesi
    session_unset(); 
    session_destroy(); // Hancurkan sesi

    // Redirect ke halaman login
    header("Location: ../../index.php"); 
    exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}

// Inisialisasi database dan model Album
$db = new Database();
$fotoModel = new Foto($db->getConnection());

// Ambil semua album berdasarkan UserID yang sedang login
$userID = $_SESSION['UserID']; // Ambil UserID dari session
$uploadedPhotos = $fotoModel->getAllPhotos(); // Ambil foto berdasarkan UserID, default ke array kosong jika tidak ada


// Jika pengguna sudah login, tampilkan halaman home
$namaLengkap = $_SESSION['NamaLengkap'];
?>

<!-- home.php -->
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/styleHome.css"> 
</head>
<body>

    <!-- Navbar -->
    <?php include __DIR__ . '/../../assets/navbar/navbar.php'; ?>

    <!-- Konten Utama -->
    <div class="home-content mt-5">
        <h1>Welcome, <b><?php echo $namaLengkap; ?></b></h1>
        <p>Ini adalah halaman utama MyGallery</p>
    </div>
    <div class="home-content mt-5">
    <div class="card">
        <div class="card-body">
        <div class="scrollable-row">
            <div class="row">
                <?php if (!empty($uploadedPhotos)): ?>
                    <?php foreach ($uploadedPhotos as $foto): ?>
                        <div class="col-md-6 mb-3">
                        <div class="card">
                        <img src="<?php echo htmlspecialchars($foto['LokasiFile']); ?>" class="d-block w-100 card-img-top" alt="Foto">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($foto['JudulFoto']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($foto['DeskripsiFoto']); ?></p>
                            <p class="card-text">
                                <small class="text-muted">Album: <?php echo htmlspecialchars($foto['NamaAlbum']); ?></small>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Diupload oleh: <?php echo htmlspecialchars($foto['Username']); ?></small>
                            </p>
                            <p class="card-text"><small class="text-muted">Tanggal Unggah: <?php echo htmlspecialchars($foto['TanggalUnggah']); ?></small></p>
                        </div>
                     </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>



  
     <!-- Link Bootstrap JS dan jQuery untuk interaksi -->
     <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</body>
</html>


