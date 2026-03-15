<?php
session_start(); // WAJIB paling atas, sebelum ada spasi atau HTML

// Proses login / register
$pesan = "";

// inisialisasi user
if(!isset($_SESSION['users'])){
    $_SESSION['users'] = [];
}

// POST handling
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $action = $_POST['action'] ?? "";
    $username = htmlspecialchars(trim($_POST['username'] ?? ""));
    $password = trim($_POST['password'] ?? "");

    if($action == "login"){
        if(empty($username) || empty($password)){
            $pesan = "Username dan password harus diisi!";
        }
        elseif(!isset($_SESSION['users'][$username])){
            $pesan = "Akun belum terdaftar!";
        }
        elseif(password_verify($password, $_SESSION['users'][$username])){
            // SET SESSION LOGIN
            $_SESSION['login'] = true;
            $_SESSION['login_user'] = $username;

            // REDIRECT
            header("Location: Admin.php");
            exit;
        }
        else{
            $pesan = "Username atau password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login & Register</title>
<link rel="stylesheet" href="login.css">
</head>
<body>

<div class="auth-container">
<div class="auth-box">

<a href="homepage.html" class="back-home">Kembali</a>

<h2 id="formTitle">Login</h2>

<p style="color:red;"><?php echo $pesan; ?></p>

<!-- LOGIN -->
<form id="loginForm" method="POST">
    <input type="hidden" name="action" value="login">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <p class="switch">Belum punya akun? <span id="toRegister">Daftar</span></p>
</form>

<!-- REGISTER -->
<form id="registerForm" class="hidden" method="POST">
    <input type="hidden" name="action" value="register">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
    <button type="submit">Register</button>
    <p class="switch">Sudah punya akun? <span id="toLogin">Login</span></p>
</form>

</div>
</div>

<script>
    // Toggle form login/register
    const toRegister = document.getElementById('toRegister');
    const toLogin = document.getElementById('toLogin');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const formTitle = document.getElementById('formTitle');

    toRegister.addEventListener('click', () => {
        loginForm.classList.add('hidden');
        registerForm.classList.remove('hidden');
        formTitle.textContent = "Register";
    });

    toLogin.addEventListener('click', () => {
        registerForm.classList.add('hidden');
        loginForm.classList.remove('hidden');
        formTitle.textContent = "Login";
    });
</script>

</body>
</html>