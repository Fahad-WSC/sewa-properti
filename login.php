<?php
session_start();
require 'koneksi.php';

if(isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

    if(mysqli_num_rows($cek_user) === 1) {
        $data = mysqli_fetch_assoc($cek_user);
        
        if(password_verify($password, $data['password'])) {
            
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['nama'] = $data['nama'];
            $_SESSION['role'] = $data['role'];

            if($data['role'] == 'tenant') {
                 header("Location: katalog_properti.php"); 
            } elseif($data['role'] == 'owner') {
                header("Location: dashboard_owner.php");
            } elseif($data['role'] == 'admin') {
                header("Location: dashboard_admin.php");
            }
            exit;
        } else {
            echo "<script>alert('Login Gagal: Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Login Gagal: Email tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sewa Properti</title>
    <style>

        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4; 
            margin: 0; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
        }
        
        .login-wrapper { 
            display: flex; 
            background: #fff; 
            width: 800px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
        }
        
        .login-left { 
            padding: 40px; 
            width: 50%; 
            border-right: 1px solid #ddd; 
            box-sizing: border-box;
        }
        
        .login-right { 
            padding: 40px; 
            width: 50%; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: center; 
            text-align: center;
            box-sizing: border-box;
        }

        h2 { font-size: 22px; margin-bottom: 25px; color: #111; }
        
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; margin-bottom: 5px; font-size: 14px; color: #333; }
        .input-group input { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box; 
            font-size: 14px;
        }

        .btn-login { 
            background-color: #d11212; 
            color: white; 
            border: none; 
            padding: 12px; 
            width: 100%; 
            font-weight: bold; 
            font-size: 16px; 
            cursor: pointer; 
            border-radius: 4px; 
            margin-top: 10px;
        }
        .btn-login:hover { background-color: #a80e0e; }

        .register-section { 
            margin-top: 30px; 
            text-align: center; 
            border-top: 1px solid #eee; 
            padding-top: 20px; 
        }
        .btn-register { 
            background-color: white; 
            color: #d11212; 
            border: 1px solid #d11212; 
            padding: 10px; 
            width: 100%; 
            font-weight: bold; 
            font-size: 14px; 
            cursor: pointer; 
            border-radius: 4px; 
            margin-top: 10px; 
            text-decoration: none; 
            display: inline-block; 
            box-sizing: border-box;
        }
        .btn-register:hover { background-color: #f9e6e6; }

        .logo-placeholder { 
            width: 180px; 
            height: 180px; 
            background-color: #d11212; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: white; 
            font-size: 70px; 
            font-weight: bold; 
            letter-spacing: -5px;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-left">
            <h2>Log in to Sewa Properti</h2>
            <form action="" method="POST">
                <div class="input-group">
                    <label>Username / Email</label>
                    <input type="text" name="username" placeholder="Masukkan username" required>
                </div>
                
                <div class="input-group" style="position: relative;">
                    <label>Password</label>
                    <input type="password" name="password" id="loginPassword" placeholder="Masukkan password" required>
                    <span id="togglePassword" style="position: absolute; right: 10px; top: 32px; cursor: pointer; font-size: 12px; color: #d11212; font-weight: bold;">SHOW</span>
                </div>

                <button type="submit" name="login" class="btn-login">Log in</button>
            </form>

            <div class="register-section">
                <p style="font-size: 14px; margin-bottom: 5px; color: #333;">New to Sewa Properti? <strong>Get Started</strong></p>
                <a href="register.php" class="btn-register">Register for Account</a>
            </div>
        </div>

        <div class="login-right">
            <div class="logo-placeholder">SP</div>
            <h3 style="color: #d11212; margin-bottom: 10px; font-size: 18px;">Ayo sewa properti impianmu sekarang!</h3>
            <p style="font-size: 13px; color: #555; line-height: 1.5;">Temukan properti yang sesuai dengan kebutuhan Anda dan mulai pengalaman menyewa yang nyaman.</p>
        </div>
    </div>

    <script src="login.js"></script>

</body>
</html>