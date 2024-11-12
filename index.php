<?php

include_once './app/controllers/C_login.php';
// Inisialisasi variabel pesan
$message = '';

// Jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginController = new C_login();
    $message = $loginController->loginUser($_POST['username'], $_POST['password']);

    // Jika login berhasil, arahkan ke halaman home
    if ($message === "Login berhasil!") {
        // Menyimpan data user di session
        $_SESSION['UserID'] = $loginController->$user->UserID; // Simpan UserID
        $_SESSION['NamaLengkap'] = $loginController->$user->NamaLengkap; // Simpan Nama Lengkap
        header("Location: /app.views/home_views.php"); // Arahkan ke halaman home
        exit(); // Hentikan eksekusi script setelah pengalihan
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>MyGallery</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="./assets/icon/photo.png" rel="icon"> <!-- Replace with your favicon link if needed -->
  <link href="./assets/icon/photo.png"  alt="logo" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

  <!-- Additional CSS Libraries -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.bubble.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-datatables/3.1.3/style.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="" rel="stylesheet"> <!-- Replace with your own CSS file if needed -->
</head>

<body>

  <!-- Main Content Goes Here -->
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
            <!--start logo-->
              <div class="d-flex justify-content-center py-4">
                <a class="logo d-flex align-items-center w-auto">
                  <center><img src="./assets/icon/photo.png" alt="logo" style="width: 40px; height: auto;"></center>
                  <span class="logo-text ms-2">MyGallery</span>
                </a>
              </div><!-- End Logo -->
              <div class="card mb-3">
                <div class="card-body">
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                  </div>
                  <form class="row g-3 needs-validation" novalidate action="index.php" method="post">
                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <input type="text" name="username" class="form-control" id="yourUsername" placeholder="Username" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <label for="pass" class="form-label">Password</label>
                      <div class="input-group has-validation">
                        <input type="password" name="password" class="form-control" id="pass" required placeholder="Password">
                        <span id="mybutton" onclick="change()" class="input-group-text">
                          <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                            <path fill-rule="evenodd" d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                          </svg>
                        </span>
                        <div class="invalid-feedback">Please enter your password.</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Login</button>
                      <?php if (!empty($message)): ?>
                    <div class="message"><?php echo $message; ?></div>
                    <?php endif; ?> 
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Don't have account? <a href="app/views/register_views.php">Create an account</a></p>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <!-- Vendor JS Files -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.5/apexcharts.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.2.1/chart.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.0/echarts.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/simple-datatables/3.1.3/simple-datatables.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.0/tinymce.min.js"></script>

  <!-- Custom JS -->
  <script src="./assets/js/script.js"></script> <!-- Replace with your own JS file if needed -->
  <script src="./assets/js/hidemesseges.js"></script> <!-- Replace with your own JS file if needed -->
</body>

</html>

