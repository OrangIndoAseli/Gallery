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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="stylesheet" href="./assets/styleLogin.css">
</head>
<body>
<div class="card">
    <h2>Login</h2>
    <form action="index.php" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    <!-- Tautan untuk Register -->
    <div class="registerLink">
        <a href="app/views/register_views.php">Don't have an account? Register</a>
    </div>
</div>

</body>
</html>
