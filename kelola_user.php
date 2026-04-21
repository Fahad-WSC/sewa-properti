<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$nama_admin = $_SESSION['nama'] ?? 'Administrator';

if(isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM users WHERE id = '$id_hapus'");
    echo "<script>alert('User berhasil dihapus'); window.location='kelola_user.php';</script>";
}

$query = mysqli_query($conn, "SELECT * FROM users ORDER BY role ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola User | Admin</title>
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

        .table-container { 
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 4px 25px rgba(0,0,0,0.04); 
            overflow: hidden; 
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
        }

        th { 
            background: #fcfdfe; 
            color: #95a5a6; 
            padding: 18px 30px; 
            text-align: left; 
            font-size: 12px; 
            text-transform: uppercase; 
        }

        td { 
            padding: 18px 30px; 
            border-bottom: 1px solid #f1f2f6; 
            font-size: 14px; 
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: #fafbfc;
        }

        .badge { 
            padding: 5px 10px; 
            border-radius: 20px; 
            font-size: 11px; 
            font-weight: bold; 
            text-transform: uppercase; 
        }

        .role-admin { background: #fdf2f2; color: #e74c3c; }
        .role-owner { background: #eef9f1; color: #27ae60; }
        .role-tenant { background: #ebf5fb; color: #2980b9; }

        .btn-action { 
            text-decoration: none; 
            font-size: 12px; 
            font-weight: bold; 
            padding: 5px 10px; 
            border-radius: 4px; 
            margin-right: 5px; 
            display: inline-block;
        }

        .btn-detail { background: #e3f2fd; color: #1565c0; }
        .btn-del { background: #ffebee; color: #c62828; }

        .btn-action:hover { opacity: 0.8; }
    </style>
</head>
<body>
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
            <div class="user-info" style="font-weight: bold; font-size: 18px; color: var(--text-dark);">Data Seluruh Pengguna</div>
            <div style="font-weight: bold; color: var(--primary-red);"><?php echo htmlspecialchars($nama_admin); ?></div>
        </header>

        <main class="content">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email / Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($query)) : ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['nama']); ?></strong></td>
                            <td>
                                <?php 
                                    if(!empty($row['email'])) {
                                        echo htmlspecialchars($row['email']);
                                    } else {
                                        echo htmlspecialchars($row['username']);
                                    }
                                ?>
                            </td>
                            <td><span class="badge role-<?php echo strtolower($row['role']); ?>"><?php echo htmlspecialchars($row['role']); ?></span></td>
                            <td>
                                <a href="detail_user.php?id=<?php echo $row['id']; ?>" class="btn-action btn-detail">Detail</a>
                                <a href="kelola_user.php?hapus=<?php echo $row['id']; ?>" class="btn-action btn-del" onclick="return confirm('Hapus user ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>