<?php 
// Mengaktifkan session
session_start();

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
    <link rel="stylesheet" href="../../assets/styleHome.css"> 
</head>
<body>

    <!-- Navbar -->
    <?php include __DIR__ . '/../../assets/navbar/navbar.php'; ?>

    <!-- Konten Utama -->
    <div class="home-content mt-5">
        <h1>Welcome, <b><?php echo $namaLengkap; ?></b></h1>
        <p>Ini adalah halaman utama website Anda.</p>
    </div>

  
     <!-- Link Bootstrap JS dan jQuery untuk interaksi -->
     <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


