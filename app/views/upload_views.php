<?php
// session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/.././controllers/C_upload.php';
require_once __DIR__ . '/../models/Album.php';
require_once __DIR__ . '/../models/Foto.php';

// Periksa apakah user sudah login
if (!isset($_SESSION['UserID'])) {
    header("Location: ../../index.php"); // Arahkan kembali ke halaman login jika belum login
    exit();
}

// Inisialisasi database dan model Album
$db = new Database();
$albumModel = new Album($db->getConnection());
$fotoModel = new Foto($db->getConnection());

// Ambil semua album berdasarkan UserID yang sedang login
$userID = $_SESSION['UserID']; // Ambil UserID dari session
$albums = $albumModel->getAllAlbumsByUser($userID); // Ambil album berdasarkan UserID
$uploadedPhotos = $fotoModel->getPhotosByUser($userID); // Ambil foto berdasarkan UserID, default ke array kosong jika tidak ada
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Foto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/styleHome.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
      <!-- Navbar -->
    <?php include __DIR__ . '/../../assets/navbar/navbar.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <!-- Kolom untuk form upload -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Upload Foto</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-info"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
                        <?php endif; ?>
                        <form action="../../controllers/C_upload.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="upload">
                            <div class="form-group">
                                <label for="judul">Judul Foto</label>
                                <input type="text" class="form-control" id="judul" name="judul" required>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi Foto</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="album">Pilih Album</label>
                                <select class="form-control" id="album" name="album" required>
                                    <option value="">Pilih Album</option>
                                    <!-- Loop melalui album yang diambil dari database -->
                                    <?php foreach ($albums as $album): ?>
                                        <option value="<?= $album['AlbumID'] ?>"><?= htmlspecialchars($album['NamaAlbum']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="file">Upload Foto</label>
                                <input type="file" class="form-control" id="file" name="file" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Kolom untuk menampilkan foto yang telah diupload -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Foto yang Diupload</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php if (!empty($uploadedPhotos)): ?>
                                <?php foreach ($uploadedPhotos as $foto): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                        <img src="<?php echo htmlspecialchars($foto['LokasiFile']); ?>" class="card-img-top" alt="Foto">
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
                                            <form action="../../controllers/C_upload.php" method="post">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="fotoID" value="<?= $photo['FotoID'] ?>">
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Tidak ada foto yang diupload.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
