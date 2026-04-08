<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query_user);

$val_nama = isset($user['nama']) ? $user['nama'] : '';
$val_user = isset($user['username']) ? $user['username'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Akun - Owner</title>
    <style>
        :root { --red: #e74c3c; --dark: #2c3e50; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }

        body { 
            display: flex; 
            height: 100vh; 
            background: #f4f7f6; 
        }

        .sidebar { 
            width: 260px; 
            background: var(--dark); 
            color: white; 
            display: flex; 
            flex-direction: column; 
            flex-shrink: 0; 
        }
        .sidebar-header { 
            height: 70px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: bold; 
            color: var(--red); 
            background: #1a252f; 
        }
        .sidebar-menu a { 
            display: block; 
            padding: 15px 25px; 
            color: #bdc3c7; 
            text-decoration: none; 
        }
        .sidebar-menu a.active { 
            background: #34495e; 
            color: var(--red); 
            border-left: 5px solid var(--red); 
        }

        /* AREA KANAN */
        .main-content { 
            flex: 1; 
            display: flex; 
            flex-direction: column;
        }

        .topbar { 
            height: 70px; 
            background: white; 
            display: flex; 
            align-items: center; 
            padding-left: 30px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
            font-weight: bold; 
        }

        .form-area { 
            flex: 1; 
            display: flex; 
            justify-content: center; 
            align-items: flex-start; 
            padding-top: 50px; 
        }

        .card { 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); 
            width: 100%; 
            max-width: 450px; 
        }

        .form-group { 
            margin-bottom: 20px; 
            display: flex;
            flex-direction: column; 
        }

        .form-group label { 
            font-weight: bold; 
            margin-bottom: 8px; 
            color: #555; 
            font-size: 14px;
        }

        .form-group input { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 6px; 
        }

        .form-group input:disabled { 
            background: #f9f9f9; 
            border-style: dashed; 
        }

        .btn-save { 
            background: var(--red); 
            color: white; 
            border: none; 
            padding: 14px; 
            width: 100%; 
            border-radius: 6px; 
            font-weight: bold; 
            cursor: pointer; 
        }
        
        .btn-save:hover { background: #c0392b; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">PANEL OWNER</div>
        <div class="sidebar-menu">
            <a href="dashboard_owner.php">Dashboard</a>
            <a href="properti_saya.php">Properti Saya</a>
            <a href="laporan_sewa.php">Laporan Sewa</a>
            <a href="pengaturan_owner.php" class="active">Pengaturan</a>
            <a href="logout.php" style="margin-top:20px; color:#e74c3c">Logout</a>
        </div>
    </aside>

    <div class="main-content">
        <div class="topbar">Pengaturan Akun</div>
        
        <div class="form-area">
            <div class="card">
                <h3 style="margin-bottom: 20px; color: #333;">Edit Profil Owner</h3>
                <form action="proses_update.php" method="POST">
                    
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" value="<?php echo $val_nama; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Username / Email</label>
                        <input type="text" name="username" value="<?php echo $val_user; ?>">
                    </div>

                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak ganti">
                    </div>

                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                    
                </form>
            </div>
        </div>
    </div>

</body>
</html>