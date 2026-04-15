<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$nama_admin = $_SESSION['nama'] ?? 'Administrator';

$id_user = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id_user'");
$data_user = mysqli_fetch_assoc($query_user);

if(!$data_user) {
    echo "<script>alert('Data user tidak ditemukan!'); window.location='dashboard_admin.php';</script>";
    exit;
}

$kontak = '-';
if (isset($data_user['email']) && !empty($data_user['email'])) {
    $kontak = htmlspecialchars($data_user['email']);
} elseif (isset($data_user['username']) && !empty($data_user['username'])) {
    $kontak = htmlspecialchars($data_user['username']);
}

$role = htmlspecialchars($data_user['role']);
$role_class = "role-" . strtolower($role);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail User | Admin Panel</title>
    <style>
        :root {
            --primary-red: #e74c3c;
            --dark-red: #c0392b;
            --sidebar-bg: #2c3e50;
            --light-bg: #f4f7f6;
            --text-dark: #2c3e50;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Roboto, sans-serif; }

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

        .sidebar-menu { flex: 1; padding: 20px 0; }
        .sidebar-menu a { 
            display: flex; align-items: center; padding: 15px 25px; 
            color: #bdc3c7; text-decoration: none; transition: 0.2s; font-size: 14px; font-weight: 500;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active { 
            background-color: #34495e; color: var(--primary-red); border-left: 5px solid var(--primary-red);
        }
        .sidebar-menu .logout { margin-top: 30px; border-top: 1px solid #34495e; color: #e74c3c; }
        .sidebar-menu .logout:hover { background-color: var(--primary-red); color: white; }

        .main-wrapper { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .topbar { 
            height: 70px; background-color: white; display: flex; align-items: center; 
            justify-content: space-between; padding: 0 40px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); z-index: 10; 
        }
        .user-info { font-weight: 600; color: var(--text-dark); font-size: 16px; }
        .content { padding: 40px; overflow-y: auto; flex: 1; }

        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-header h2 { color: var(--text-dark); font-weight: 700; }

        .btn-back {
            background: #95a5a6; color: white; padding: 10px 20px; text-decoration: none; 
            border-radius: 6px; font-weight: 600; font-size: 14px; transition: 0.3s;
        }
        .btn-back:hover { background: #7f8c8d; }

        .profile-card {
            background: white; border-radius: 12px; box-shadow: 0 4px 25px rgba(0,0,0,0.04);
            padding: 40px; max-width: 800px; margin: 0 auto; border-top: 4px solid var(--primary-red);
        }

        .profile-header {
            display: flex; align-items: center; gap: 20px; border-bottom: 1px solid #f1f2f6; padding-bottom: 30px; margin-bottom: 30px;
        }

        .avatar {
            width: 80px; height: 80px; background: var(--sidebar-bg); color: white; 
            display: flex; align-items: center; justify-content: center; border-radius: 50%;
            font-size: 32px; font-weight: bold; box-shadow: 0 4px 10px rgba(44, 62, 80, 0.2);
        }

        .profile-title h3 { font-size: 24px; color: var(--text-dark); margin-bottom: 5px; }
        
        .badge { 
            padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 11px; text-transform: uppercase; display: inline-block;
        }
        .role-admin { background: #fdf2f2; color: #e74c3c; }
        .role-owner { background: #eef9f1; color: #27ae60; }
        .role-tenant { background: #ebf5fb; color: #2980b9; }

        .detail-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 25px;
        }

        .detail-item {
            background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #bdc3c7;
        }

        .detail-item label {
            display: block; font-size: 12px; color: #7f8c8d; text-transform: uppercase; font-weight: 600; margin-bottom: 8px;
        }

        .detail-item p { font-size: 16px; color: var(--text-dark); font-weight: 500; }

    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">PANEL ADMIN</div>
        <div class="sidebar-menu">
            <a href="dashboard_admin.php">Dashboard</a>
            <a href="kelola_user.php" class="active">Kelola Pengguna</a>
            <a href="kelola_properti.php">Data Properti</a>
            <a href="laporan.php">Laporan Transaksi</a>
            <a href="pengaturan.php">Pengaturan</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <div class="user-info">Halo, Administrator <?php echo htmlspecialchars($nama_admin); ?>!</div>
            <div>
                <span style="background: var(--primary-red); color: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold;">A</span>
            </div>
        </header>

        <main class="content">
            <div class="page-header">
                <h2>Profil Pengguna</h2>
                <a href="dashboard_admin.php" class="btn-back">⬅ Kembali</a>
            </div>

            <div class="profile-card">
                <div class="profile-header">
                    <div class="avatar">
                        <?php echo strtoupper(substr($data_user['nama'], 0, 1)); ?>
                    </div>
                    <div class="profile-title">
                        <h3><?php echo htmlspecialchars($data_user['nama']); ?></h3>
                        <span class="badge <?php echo $role_class; ?>"><?php echo $role; ?></span>
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="detail-item">
                        <label>ID Pengguna</label>
                        <p>#USR-<?php echo str_pad($data_user['id'], 4, '0', STR_PAD_LEFT); ?></p>
                    </div>
                    <div class="detail-item">
                        <label>Nama Lengkap</label>
                        <p><?php echo htmlspecialchars($data_user['nama']); ?></p>
                    </div>
                    <div class="detail-item">
                        <label>Username / Email</label>
                        <p><?php echo $kontak; ?></p>
                    </div>
                    <div class="detail-item">
                        <label>Hak Akses (Role)</label>
                        <p style="text-transform: capitalize;"><?php echo $role; ?></p>
                    </div>
                </div>
            </div>

        </main>
    </div>

</body>
</html>