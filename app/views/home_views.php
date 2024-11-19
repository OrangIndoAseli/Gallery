<?php
// Pastikan session dimulai dan kode PHP sudah ada sebelumnya
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../controllers/C_upload.php';
require_once __DIR__ . '/../controllers/C_komentar.php';
require_once __DIR__ . '/../models/Foto.php'; 
require_once __DIR__ . '/../models/Komentar.php'; // Import model komentar

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

// Inisialisasi database dan model
$db = new Database();
$fotoModel = new Foto($db->getConnection());
$komentarModel = new Komentar($db->getConnection()); // Initialize model komentar

// Ambil semua foto yang diupload oleh user yang sedang login
$userID = $_SESSION['UserID']; // Ambil UserID dari session
$uploadedPhotos = $fotoModel->getAllPhotos($userID); // Fetch all photos uploaded by the user
// Include like count for each photo

foreach ($uploadedPhotos as &$foto) {
    $foto['LikeCount'] = $fotoModel->getLikeCount($foto['FotoID']);
    $foto['TanggalLike'] = $fotoModel->getLikeDate($foto['FotoID']);
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../assets/styleNav.css"> 
    <link rel="stylesheet" href="../../assets/styleHome.css"> 
</head>
<body>

    <!-- Navbar -->
    <?php include __DIR__ . '/../../assets/navbar/navbar.php'; ?>

    <!-- Konten Utama -->
    <div class="home-content mt-5">
        <center><h1>Welcome, <b><?php echo $namaLengkap; ?></b></h1></center>
        <center><p>Ini adalah halaman utama MyGallery</p></center>
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
                                    <!-- Use Bootstrap classes to limit image size and make it responsive -->
                                    <img src="<?php echo htmlspecialchars($foto['LokasiFile']); ?>" 
                                         class="card-img-top img-fluid" 
                                         alt="Foto" 
                                         style="max-height: 300px; object-fit: cover;"
                                         data-toggle="modal" 
                                         data-target="#photoModal<?php echo $foto['FotoID']; ?>"> <!-- Modal Trigger -->
                                    <div class="card-body">
                                        <!-- Display photo title -->
                                        <h5 class="card-title"><?php echo htmlspecialchars($foto['JudulFoto']); ?></h5>
                                        <!-- Display description of the photo -->
                                        <p class="card-text"><?php echo htmlspecialchars($foto['DeskripsiFoto']); ?></p>

                                        <!-- Display the user who uploaded the photo and the upload date -->
                                        <p class="card-text">
                                            <small class="text-muted">Diupload oleh: <?php echo htmlspecialchars($foto['Username']); ?></small>
                                        </p>
                                        <p class="card-text">
                                            <small class="text-muted">Tanggal Unggah: <?php echo htmlspecialchars($foto['TanggalUnggah']); ?></small>
                                        </p>
                                              <!-- Ikon Komentar -->
                                            <p class="card-text">
                                                <i class="fas fa-comment"></i> 
                                                <?php 
                                                    // Menampilkan jumlah komentar
                                                    $comments = $komentarModel->getCommentsByPhoto($foto['FotoID']);
                                                    echo count($comments) . " Comments";
                                                ?>
                                            </p>

                                            <!-- Like/Unlike Button -->
                                            <button class="like-button" data-foto-id="<?php echo $foto['FotoID']; ?>" onclick="toggleLike(this)">
                                                <i class="fa fa-heart"></i> Like <span class="like-count"><?php echo $foto['LikeCount']; ?></span>
                                            </button>
                                            <p class="like-date">
                                                Last Liked: <?php echo $foto['TanggalLike'] ? $foto['TanggalLike'] : 'Never liked yet'; ?>
                                            </p>
                                            <br>

                                           <!-- Form Tambah Komentar -->
                                            <form method="POST" action="../controllers/C_komentar.php">
                                                <div class="form-group">
                                                    <input type="hidden" name="fotoID" value="<?php echo $foto['FotoID']; ?>">
                                                    <input type="hidden" name="userID" value="<?php echo $_SESSION['UserID']; ?>"> <!-- Hidden UserID -->
                                                    <textarea name="isiKomentar" class="form-control" placeholder="Write a comment..." required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm" name="action" value="create">Post Comment</button>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                                <!-- Modal untuk foto -->
                                <div class="modal fade" id="photoModal<?php echo $foto['FotoID']; ?>" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel<?php echo $foto['FotoID']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="photoModalLabel<?php echo $foto['FotoID']; ?>">
                                                    <?php echo htmlspecialchars($foto['JudulFoto']); ?>
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="<?php echo htmlspecialchars($foto['LokasiFile']); ?>" class="img-fluid" alt="Original Foto">
                                                <p><?php echo htmlspecialchars($foto['DeskripsiFoto']); ?></p>
                                                 <!-- Tampilkan Komentar -->
                                                 <h6>Comments:</h6>
                                                <div class="comments-section">
                                                    <?php
                                                    $comments = $komentarModel->getCommentsByPhoto($foto['FotoID']);
                                                    if (!empty($comments)) {
                                                        foreach ($comments as $comment) {
                                                            echo "<div class='comment-item'>";
                                                            echo "<strong>" . htmlspecialchars($comment['Username']) . ":</strong>"; // Menampilkan Username
                                                            echo "<p>" . htmlspecialchars($comment['IsiKomentar']) . "</p>";
                                                            echo "<small class='text-muted'>" . htmlspecialchars($comment['TanggalKomentar']) . "</small>";

                                                            // Tampilkan tombol hapus jika UserID dari komentar sama dengan UserID sesi
                                                            if (isset($_SESSION['UserID']) && $_SESSION['UserID'] == $comment['UserID']) {
                                                                echo "<form method='POST' action='../controllers/C_komentar.php' style='display:inline;' onsubmit='return confirm(\"Apakah Anda yakin ingin menghapus komentar ini?\");'>";
                                                                echo "<input type='hidden' name='komentarID' value='" . htmlspecialchars($comment['KomentarID']) . "'>";
                                                                echo "<button type='submit' name='action' value='delete' class='btn btn-danger btn-sm float-right' title='Hapus Komentar'>X</button>";
                                                                echo "</form>";
                                                            }

                                                            echo "</div>";
                                                        }
                                                    } else {
                                                        echo "<p>No comments yet. Be the first to comment!</p>";
                                                    }
                                                    ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No photos available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
       // Function to handle the like button click event
function toggleLike(button) {
    const fotoID = button.dataset.fotoId;  // Retrieve the fotoID
    const userID = <?php echo $_SESSION['UserID']; ?>;  // Retrieve the userID (assumed to be in session)
    const likeCountSpan = button.querySelector('.like-count');  // Get the like count span

    // Send an AJAX request to toggle like/unlike
    fetch('../controllers/C_like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            fotoID: fotoID,
            userID: userID
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'liked' || data.status === 'unliked') {
            // Update the like count
            likeCountSpan.textContent = data.likeCount;

            // Toggle the button's appearance (liked/unliked)
            button.classList.toggle('liked', data.status === 'liked');
        } else {
            console.error('Error:', data.message);  // If there's an error
        }
    })
    .catch(error => console.error('Error:', error));
}

    </script>
    

    
    <!-- Link Bootstrap JS dan jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
