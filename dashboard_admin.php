<?php
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$nama_admin = $_SESSION['nama'] ?? 'Administrator';

$query_users = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$row_users = mysqli_fetch_assoc($query_users);
$total_users = $row_users['total'];

$query_properti = mysqli_query($conn, "SELECT COUNT(*) as total FROM properti");
$row_properti = mysqli_fetch_assoc($query_properti);
$total_properti = $row_properti['total'];

$query_transaksi = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE status = 'Disetujui'");
$row_transaksi = mysqli_fetch_assoc($query_transaksi);
$total_transaksi = $row_transaksi['total'] ?? 0;

$query_pendapatan = mysqli_query($conn, "SELECT SUM(properti.harga) as total FROM transaksi 
                                        JOIN properti ON transaksi.properti_id = properti.id 
                                        WHERE transaksi.status IN ('Disetujui', 'Validasi Bayar', 'Lunas')");
$row_pendapatan = mysqli_fetch_assoc($query_pendapatan);
$total_semua = $row_pendapatan['total'] ?? 0;
$pendapatan_admin = $total_semua * 0.15;
$fmt_total        = "Rp " . number_format($total_semua, 0, ',', '.');
$fmt_admin        = "Rp " . number_format($pendapatan_admin, 0, ',', '.');

$query_recent_users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Sewa Properti</title>
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
            justify-content: space-between; 
            padding: 0 40px; 
            box-shadow: 0 2px 15px rgba(0,0,0,0.05); 
            z-index: 10; 
        }

        .user-info { 
            font-weight: 600; 
            color: var(--text-dark); 
            font-size: 16px; 
        }

        .content { 
            padding: 40px; 
            overflow-y: auto; 
            flex: 1; 
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

        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            gap: 25px; 
            margin-bottom: 40px; 
        }

        .stat-card { 
            background: white; 
            padding: 25px; 
            border-radius: 12px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); 
            border-left: 6px solid #3498db;
        }

        .stat-card.orange { border-left-color: #f39c12; }
        .stat-card.green  { border-left-color: #27ae60; }

        .stat-card h3 { 
            font-size: 13px; 
            color: #7f8c8d; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            margin-bottom: 10px; 
        }

        .stat-card .value { 
            font-size: 28px; 
            font-weight: 800; 
            color: var(--text-dark); 
        }

        .table-container { 
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 4px 25px rgba(0,0,0,0.04); 
            overflow: hidden; 
            margin-bottom: 40px;
            border-top: 4px solid var(--sidebar-bg);
        }

        .table-header-title { 
            padding: 20px 30px; 
            border-bottom: 1px solid #f1f2f6; 
            font-weight: 700; 
            color: var(--text-dark); 
            font-size: 18px; 
        }

        table { width: 100%; border-collapse: collapse; }

        th { 
            background-color: #fcfdfe; 
            color: #95a5a6; 
            padding: 18px 30px; 
            text-transform: uppercase; 
            font-size: 12px; 
            text-align: left; 
        }

        td { 
            padding: 18px 30px; 
            border-bottom: 1px solid #f1f2f6; 
            font-size: 14px; 
            color: #444; 
        }

        tr:last-child td { border-bottom: none; }
        tr:hover { background-color: #fafbfc; }

        .badge { 
            padding: 6px 12px; 
            border-radius: 20px; 
            font-weight: 700; 
            font-size: 11px; 
            text-transform: uppercase;
        }

        .role-admin  { background: #fdf2f2; color: #e74c3c; }
        .role-owner  { background: #eef9f1; color: #27ae60; }
        .role-tenant { background: #ebf5fb; color: #2980b9; }

        .btn-detail { 
            text-decoration: none; 
            padding: 8px 16px; 
            border-radius: 6px; 
            font-size: 12px; 
            font-weight: 700; 
            background: var(--sidebar-bg); 
            color: white; 
            display: inline-block; 
            transition: 0.2s;
        }

        .btn-detail:hover { opacity: 0.8; transform: scale(1.05); }

        .kosong { 
            text-align: center; 
            padding: 50px; 
            color: #bdc3c7; 
            font-style: italic; 
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">PANEL ADMIN</div>
        <div class="sidebar-menu">
            <a href="dashboard_admin.php" class="active">Dashboard</a>
            <a href="kelola_user.php">Kelola Pengguna</a>
            <a href="kelola_properti.php">Data Properti</a>
            <a href="laporan_transaksi.php">Laporan Transaksi</a>
            <a href="pengaturan_admin.php">Pengaturan</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <div class="user-info">Halo, Administrator <?php echo htmlspecialchars($nama_admin); ?>!</div>
            <div>
                <span style="background: var(--primary-red); color: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);">
                    A
                </span>
            </div>
        </header>

        <main class="content">
            <div class="page-header">
                <h2>Ringkasan Sistem</h2>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Pengguna</h3>
                    <div class="value"><?php echo $total_users; ?></div>
                </div>
                <div class="stat-card orange">
                    <h3>Total Properti</h3>
                    <div class="value"><?php echo $total_properti; ?></div>
                </div>
                <div class="stat-card green">
                    <h3>Total Transaksi</h3>
                    <div class="value"><?php echo $total_transaksi; ?></div>
                </div>
                <div class="stat-card" style="border-left-color:#8e44ad;">
                    <h3>Total Pendapatan Platform</h3>
                    <div class="value" style="font-size:20px; color:#8e44ad;"><?php echo $fmt_total; ?></div>
                </div>
                <div class="stat-card" style="border-left-color:#e74c3c;">
                    <h3>Komisi Admin (15%)</h3>
                    <div class="value" style="font-size:20px; color:#e74c3c;"><?php echo $fmt_admin; ?></div>
                </div>
            </div>

            <div class="table-container">
                <div class="table-header-title">Pendaftaran User Terbaru</div>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>Username / Email</th>
                            <th>Role</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(mysqli_num_rows($query_recent_users) > 0) {
                            while($row = mysqli_fetch_assoc($query_recent_users)) {
                                $nama = htmlspecialchars($row['nama']); 
                                $kontak = '-';
                                if (isset($row['email']) && !empty($row['email'])) {
                                    $kontak = htmlspecialchars($row['email']);
                                } elseif (isset($row['username']) && !empty($row['username'])) {
                                    $kontak = htmlspecialchars($row['username']);
                                }
                                $role = htmlspecialchars($row['role']);
                                $role_class = "role-" . strtolower($role);
                                
                                echo "<tr>
                                        <td><strong>{$nama}</strong></td>
                                        <td>{$kontak}</td>
                                        <td><span class='badge {$role_class}'>{$role}</span></td>
                                        <td style='text-align: center;'>
                                            <a href='detail_user.php?id={$row['id']}' class='btn-detail'>Detail</a>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='kosong'>Belum ada data user terbaru.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

</body>
</html>