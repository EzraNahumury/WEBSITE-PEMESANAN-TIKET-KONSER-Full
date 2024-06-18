<?php
include_once "dbconnect.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['signup'])) {
        // Proses sign-up
        $namaLengkap = $_POST['NamaLengkap'];
        $username = $_POST['Username'];
        $password = $_POST['Password'];
        $confirmPassword = $_POST['ConfirmPassword'];

        // Validasi jika password dan confirm password tidak cocok
        if ($password != $confirmPassword) {
            echo "<script>alert('Password dan Confirm Password tidak cocok!');</script>";
        } else {
            // Cek apakah username sudah ada di database
            $query = "SELECT * FROM users WHERE username = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<script>alert('Username sudah terdaftar!');</script>";
            } else {
                // Masukkan pengguna baru ke database
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO users (namaLengkap, username, password) VALUES (?, ?, ?)";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("sss", $namaLengkap, $username, $hashedPassword);

                if ($stmt->execute()) {
                    echo "<script>
                            alert('Registrasi berhasil!');
                            window.location.href = 'login.php';
                          </script>";
                } else {
                    echo "<script>alert('Terjadi kesalahan, coba lagi.');</script>";
                }
            }
            $stmt->close();
        }
    } elseif (isset($_POST['login'])) {
        // Proses login
        $username = $_POST['Username'];
        $password = $_POST['Password'];

        // Cek apakah username ada di database
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Username ditemukan, verifikasi password
            $user = $result->fetch_assoc();
            if ($password == $user['password']) {
                // Password benar, simpan nama lengkap di session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['namaLengkap'] = $user['namaLengkap'];
                
                // Arahkan ke halaman yang sesuai jika ada data quantity di sesi
                if (isset($_SESSION['quantity'])) {
                    header("Location: about.php?artist_id=" . $_SESSION['artist_id']);
                    exit();
                } else {
                    // Arahkan ke halaman Home jika tidak ada data quantity
                    header("Location: Home.php");
                    exit();
                }
            } else {
                echo "<script>alert('Password salah!');</script>";
            }
        } else {
            echo "<script>
                    alert('Username tidak ditemukan!');
                    window.location.href = 'login.php';
                  </script>";
        }

        $stmt->close();
    }
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Document</title>
</head>
<body>
<div class="container" id="container">
    <div class="form-container sign-up-container" id="sign-up-container">
        <form action="login.php" method="POST">
            <h1>Create Account</h1>
            <span>or use your email for registration</span><br><b></b>
            <input type="text" placeholder="Name" name="NamaLengkap" required />
            <input type="text" placeholder="Username" name="Username" required />
            <input type="password" placeholder="Password" name="Password" required />
            <input type="password" placeholder="Confirm Password" name="ConfirmPassword" required /><br>
            <button type="submit" name="signup">Sign Up</button>
        </form>
    </div>
    <div class="form-container sign-in-container" id="sign-in-container">
        <form action="login.php" method="POST">
            <h1>LOGIN</h1>
            <div class="social-container">
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span>or use your account</span>
            <input type="text" placeholder="Username" name="Username" required />
            <input type="password" placeholder="Password" name="Password" required />
            <a href="#">Forgot your password?</a>
            <button type="submit" name="login">LOGIN</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Welcome Back!</h1>
                <p>To keep connected with us please login with your personal info</p>
                <button class="ghost" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Hello, Friend!</h1>
                <p>Enter your personal details and start journey with us</p>
                <button class="ghost" id="signUp">Sign Up</button>
            </div>
        </div>
    </div>
</div>

<script src="login.js"></script>
</body>
</html>