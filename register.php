<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sewa Properti</title>
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
        
        .register-wrapper { 
            display: flex; 
            background: #fff; 
            width: 850px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
        }
        
        .register-left { 
            padding: 30px 40px; 
            width: 55%; 
            border-right: 1px solid #ddd; 
            box-sizing: border-box;
        }
        
        .register-right { 
            padding: 40px; 
            width: 45%; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: center; 
            text-align: center;
            box-sizing: border-box;
            background-color: #fafafa;
        }

        h2 { font-size: 22px; margin-bottom: 20px; color: #111; margin-top: 0; }
        
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; font-size: 13px; color: #333; font-weight: bold;}
        .input-group input, .input-group select { 
            width: 100%; 
            padding: 9px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box; 
            font-size: 14px;
        }

        .btn-register { 
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
        .btn-register:hover { background-color: #a80e0e; }

        .login-section { 
            margin-top: 20px; 
            text-align: center; 
            font-size: 14px;
        }
        .login-section a {
            color: #d11212;
            text-decoration: none;
            font-weight: bold;
        }
        .login-section a:hover { text-decoration: underline; }

        .logo-placeholder { 
            width: 150px; 
            height: 150px; 
            background-color: #d11212; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: white; 
            font-size: 60px; 
            font-weight: bold; 
            letter-spacing: -4px;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

   <?php

    require 'koneksi.php';

    if(isset($_POST['register'])) {

        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $role = mysqli_real_escape_string($conn, $_POST['role']);
        $password = $_POST['password'];

        $cek_email = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        
        if(mysqli_num_rows($cek_email) > 0) {
            echo "<script>alert('Pendaftaran gagal: Email sudah terdaftar!');</script>";
        } else {

            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO users (nama, email, role, password) 
                      VALUES ('$nama', '$email', '$role', '$password_hashed')";

            if(mysqli_query($conn, $query)) {
                echo "<script>
                        alert('Pendaftaran berhasil! Silakan login.');
                        window.location.href = 'login.php';
                      </script>";
            } else {
                echo "<script>alert('Terjadi kesalahan sistem!');</script>";
            }
        }
    }
    ?>

    <div class="register-wrapper">
        <div class="register-left">
            <h2>Daftar Akun Baru</h2>
            <form action="" method="POST">
                <div class="input-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Misal: Fahad" required>
                </div>
                <div class="input-group">
                    <label>Email / Username</label>
                    <input type="email" name="email" placeholder="email@contoh.com" required>
                </div>
                <div class="input-group">
                    <label>Mendaftar Sebagai</label>
                    <select name="role" required>
                        <option value="">-- Pilih Peran --</option>
                        <option value="tenant">Penyewa (Tenant)</option>
                        <option value="owner">Pemilik Properti (Owner)</option>
                    </select>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Buat password" required>
                </div>
                <button type="submit" name="register" class="btn-register">Register Now</button>
            </form>

            <div class="login-section">
                <p>Sudah punya akun? <a href="login.php">Log in di sini</a></p>
            </div>
        </div>

        <div class="register-right">
            <div class="logo-placeholder">SP</div>
            
            <h3 style="color: #d11212; margin-bottom: 10px; font-size: 18px;">Ayo sewa properti impianmu sekarang!</h3>
            <p style="font-size: 13px; color: #555; line-height: 1.5;">Temukan properti yang sesuai dengan kebutuhan Anda dan mulai pengalaman menyewa yang nyaman.</p>
        </div>
    </div>

</body>
</html>