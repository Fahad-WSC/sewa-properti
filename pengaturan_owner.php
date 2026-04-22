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
$val_email = isset($user['email']) ? $user['email'] : ''; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Akun - Owner</title>
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
            background: var(--primary-red); 
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
                        <label>Email</label> <input type="email" name="email" value="<?php echo $val_email; ?>"> </div>

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