<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$pesan = "";

if(isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $update_query = "UPDATE users SET nama = '$nama', email = '$email' WHERE id = '$user_id'";
    
    if(mysqli_query($conn, $update_query)) {
        $_SESSION['nama'] = $nama; 
        $pesan = "<div class='alert alert-success'>Profil admin berhasil diperbarui!</div>";
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal memperbarui profil.</div>";
    }
}

$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Admin</title>
    <style>
         :root {
            --primary-red: #e74c3c;
            --dark-red: #c0392b;
            --sidebar-bg: #2c3e50;
            --light-bg: #f4f7f6;
            --text-dark: #2c3e50;
        }

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Segoe UI', Roboto, sans-serif; 
        }

        body { 
            background-color: var(--light-bg); 
            display: flex; 
            height: 100vh; 
            overflow: hidden; 
        }

        .sidebar { 
            width: 260px; 
            background-color: var(--sidebar-bg); 
            color: white; 
            display: flex; 
            flex-direction: column; 
            transition: all 0.3s;
        }

        .sidebar-header { 
            height: 70px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 18px; 
            font-weight: 800; 
            background-color: #1a252f; 
            letter-spacing: 2px; 
            color: var(--primary-red);
            border-bottom: 1px solid #34495e;
        }

        .sidebar-menu { 
            flex: 1; 
            padding: 20px 0; 
        }

        .sidebar-menu a { 
            display: flex;
            align-items: center;
            padding: 15px 25px; 
            color: #bdc3c7; 
            text-decoration: none; 
            transition: 0.2s; 
            font-size: 14px;
            font-weight: 500;
        }

        .sidebar-menu a:hover, 
        .sidebar-menu a.active { 
            background-color: #34495e; 
            color: var(--primary-red);
            border-left: 5px solid var(--primary-red);
        }

        .sidebar-menu .logout { 
            margin-top: 30px; 
            border-top: 1px solid #34495e; 
            color: #e74c3c; 
        }

        .sidebar-menu .logout:hover { 
            background-color: var(--primary-red); 
            color: white; 
        }
        
        .main-wrapper { 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
            overflow: hidden; 
        }

        .topbar { 
            height: 70px; 
            background-color: white; 
            display: flex; 
            align-items: center; 
            padding: 0 40px; 
            box-shadow: 0 2px 15px rgba(0,0,0,0.05); 
            font-weight: bold;
            font-size: 18px;
            color: #333;
        }

        .content { 
            padding: 40px; 
            overflow-y: auto; 
            flex: 1; 
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.04);
            max-width: 600px;
        }

        .form-container h3 {
            margin-bottom: 25px;
            color: #333;
            border-bottom: 2px solid #f1f2f6;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column; 
        }

        .form-group label {
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: bold;
            color: #555;
        }

        .form-group input {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-red);
        }

        .btn-simpan {
            background-color: var(--primary-red);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
            transition: 0.2s;
            margin-top: 10px;
        }

        .btn-simpan:hover {
            background-color: var(--dark-red);
        }

        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: bold;
        }

        .alert-success {
            background-color: #eef9f1;
            color: #27ae60;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #fdf2f2;
            color: #e74c3c;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
    <?php
    $current_page = basename($_SERVER['PHP_SELF']);
    ?>
    <aside class="sidebar">
    <div class="sidebar-header">PANEL ADMIN</div>
    <div class="sidebar-menu">
        <a href="dashboard_admin.php" class="<?= ($current_page == 'dashboard_admin.php') ? 'active' : ''; ?>">Dashboard</a>
        <a href="kelola_user.php" class="<?= ($current_page == 'kelola_user.php') ? 'active' : ''; ?>">Kelola Pengguna</a>
        <a href="kelola_properti.php" class="<?= ($current_page == 'kelola_properti.php') ? 'active' : ''; ?>">Data Properti</a>
        <a href="laporan_transaksi.php" class="<?= ($current_page == 'laporan_transaksi.php') ? 'active' : ''; ?>">Laporan Transaksi</a>
        <a href="pengaturan_admin.php" class="<?= ($current_page == 'pengaturan_admin.php') ? 'active' : ''; ?>">Pengaturan</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            Pengaturan Akun Admin
        </header>
        <main class="content">
            
            <div class="form-container">
                <h3>Profil Administrator</h3>
                
                <?= $pesan; ?>

                <form action="" method="POST">
                    <div class="form-group">
                        <label>Nama Lengkap Admin</label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($user['nama'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email Admin</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? ''); ?>" required>
                    </div>

                    <button type="submit" name="simpan" class="btn-simpan">Simpan Perubahan</button>
                </form>
            </div>

        </main>
    </div>
</body>
</html>