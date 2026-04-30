<?php
require 'koneksi.php';
// Pengecekan khusus Admin
if(!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$nama_admin = $_SESSION['nama'] ?? 'Administrator';

$id_properti = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = mysqli_query($conn, "SELECT properti.*, users.nama as nama_owner, users.email 
                              FROM properti 
                              JOIN users ON properti.owner_id = users.id 
                              WHERE properti.id = '$id_properti'");

$data = mysqli_fetch_assoc($query);

if(!$data) {
    echo "<script>alert('Data properti tidak ditemukan!'); window.location='kelola_properti.php';</script>";
    exit;
}

$status_properti = isset($data['status']) ? htmlspecialchars($data['status']) : 'TERSEDIA';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Properti | Admin</title>
    <style>
        :root { 
            --primary-red: #e74c3c; 
            --sidebar-bg: #2c3e50; 
            --light-bg: #f4f7f6; 
            --text-dark: #2c3e50; 
        }

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Segoe UI', sans-serif; 
        }

        body { 
            background: var(--light-bg); 
            display: flex; 
            height: 100vh; 
            overflow: hidden; 
        }

        .sidebar { 
            width: 260px; 
            background: var(--sidebar-bg); 
            color: white; 
            display: flex; 
            flex-direction: column; 
        }

        .sidebar-header { 
            height: 70px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 18px; 
            font-weight: 800; 
            color: var(--primary-red); 
            background: #1a252f; 
            border-bottom: 1px solid #34495e; 
        }

        .sidebar-menu { 
            flex: 1; 
            padding: 20px 0; 
        }

        .sidebar-menu a { 
            display: flex; 
            padding: 15px 25px; 
            color: #bdc3c7; 
            text-decoration: none; 
            font-size: 14px; 
            transition: 0.2s; 
        }

        .sidebar-menu a:hover, 
        .sidebar-menu a.active { 
            background: #34495e; 
            color: var(--primary-red); 
            border-left: 5px solid var(--primary-red); 
        }

        .main-wrapper { 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
            overflow: hidden; 
        }

        .topbar { 
            height: 70px; 
            background: white; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            padding: 0 40px; 
            box-shadow: 0 2px 15px rgba(0,0,0,0.05); 
        }

        .content { 
            padding: 40px; 
            overflow-y: auto; 
        }

        .page-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 30px; 
        }

        .page-header h2 { 
            color: var(--text-dark); 
            font-weight: 700; 
        }

        .btn-back {
            background: #95a5a6; 
            color: white; 
            padding: 10px 20px; 
            text-decoration: none; 
            border-radius: 6px; 
            font-weight: 600; 
            font-size: 14px; 
            transition: 0.3s;
        }

        .btn-back:hover { 
            background: #7f8c8d; 
        }

        .detail-card {
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 4px 25px rgba(0,0,0,0.04);
            padding: 40px; 
            max-width: 800px; 
            border-top: 4px solid var(--primary-red);
        }

        .detail-header {
            border-bottom: 1px solid #f1f2f6; 
            padding-bottom: 20px; 
            margin-bottom: 30px;
        }

        .detail-header h3 { 
            font-size: 24px; 
            color: var(--text-dark); 
            margin-bottom: 10px; 
        }
        
        .badge { 
            padding: 6px 12px; 
            border-radius: 20px; 
            font-weight: 700; 
            font-size: 11px; 
            text-transform: uppercase; 
            display: inline-block;
            background: #ebf5fb; 
            color: #2980b9;
        }

        .detail-grid {
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 25px;
        }

        .detail-item {
            background: #f8f9fa; 
            padding: 20px; 
            border-radius: 8px; 
            border-left: 4px solid #bdc3c7;
        }

        .detail-item label {
            display: block; 
            font-size: 12px; 
            color: #7f8c8d; 
            text-transform: uppercase; 
            font-weight: 600; 
            margin-bottom: 8px;
        }

        .detail-item p { 
            font-size: 16px; 
            color: var(--text-dark); 
            font-weight: 500; 
        }

        .full-width {
            grid-column: span 2;
        }

    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">PANEL ADMIN</div>
        <div class="sidebar-menu">
            <a href="dashboard_admin.php">Dashboard</a>
            <a href="kelola_user.php">Kelola Pengguna</a>
            <a href="kelola_properti.php" class="active">Data Properti</a>
            <a href="laporan.php">Laporan Transaksi</a>
            <a href="pengaturan.php">Pengaturan</a>
            <a href="logout.php" style="color: #e74c3c; margin-top: 20px; border-top: 1px solid #34495e;">Logout</a>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <div class="user-info" style="font-weight: bold; font-size: 18px; color: var(--text-dark);">Detail Properti</div>
            <div style="font-weight: bold; color: var(--primary-red);"><?php echo htmlspecialchars($nama_admin); ?></div>
        </header>

        <main class="content">
            <div class="page-header">
                <h2>Informasi Lengkap Properti</h2>
                <a href="kelola_properti.php" class="btn-back">Kembali</a>
            </div>

            <div class="detail-card">
                <div class="detail-header">
                    <h3><?php echo htmlspecialchars($data['nama_properti']); ?></h3>
                    <span class="badge"><?php echo $status_properti; ?></span>
                </div>

                <div class="detail-item full-width">
                 <label>Lokasi / Alamat</label>
                     <p>
                       <?php echo !empty($data['alamat']) ? htmlspecialchars($data['alamat']) : 'Alamat belum diisi'; ?>
                     </p>
                </div>
                    <div class="detail-item">
                        <label>Harga Sewa</label>
                        <p>Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?></p>
                    </div>
                    
                    <div class="detail-item">
                        <label>Pemilik (Owner)</label>
                        <p><?php echo htmlspecialchars($data['nama_owner']); ?></p>
                    </div>
                    <div class="detail-item">
                        <label>Kontak Owner</label>
                        <p>
                            <?php 
                                echo isset($data['email']) ? htmlspecialchars($data['email']) : 'Tidak ada data email'; 
                            ?>
                        </p>
                    </div>
                    
                    <div class="detail-item full-width">
                        <label>Deskripsi Properti</label>
                        <p style="line-height: 1.6;">
                            <?php 
                                echo isset($data['deskripsi']) ? nl2br(htmlspecialchars($data['deskripsi'])) : 'Tidak ada deskripsi.'; 
                            ?>
                        </p>
                    </div>
                </div>
            </div>

        </main>
    </div>

</body>
</html>