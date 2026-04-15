<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit;
}

if(isset($_POST['simpan'])) {
    $owner_id = $_SESSION['user_id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_properti']);
    $tipe = $_POST['tipe'];
    $kamar = $_POST['kamar'];
    $kamar_mandi = $_POST['kamar_mandi'];
    $harga = $_POST['harga'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $status = $_POST['status'];


    $query = "INSERT INTO properti (owner_id, nama_properti, tipe, kamar, kamar_mandi, harga, deskripsi, alamat, status) 
              VALUES ('$owner_id', '$nama', '$tipe', '$kamar', '$kamar_mandi', '$harga', '$deskripsi', '$alamat', '$status')";

    if(mysqli_query($conn, $query)) {
        echo "<script>
                alert('Hore! Properti berhasil ditambahkan.');
                window.location.href = 'dashboard_owner.php';
              </script>";
    } else {
        echo "<script>alert('Waduh, gagal menambahkan properti: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Properti - Sewa Properti</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4; 
            margin: 0; 
            padding: 40px; 
        }

        .form-container { 
            background: white; 
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 3px 10px rgba(0,0,0,0.1); 
            max-width: 600px; 
            margin: 0 auto; 
        }

        h2 { 
            margin-top: 0; 
            color: #d11212; 
        }

        .form-group { 
            margin-bottom: 15px; 
        }

        .form-group label { 
            display: block; 
            font-weight: bold; 
            margin-bottom: 5px; 
            color: #333; 
        }

        .form-group input, 
        .form-group select, 
        .form-group textarea { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box; 
        }

        .btn-simpan { 
            background: #d11212; 
            color: white; 
            border: none; 
            padding: 12px 20px; 
            font-weight: bold; 
            border-radius: 4px; 
            cursor: pointer; 
            width: 100%; 
            font-size: 16px;
        }

        .btn-simpan:hover { 
            background: #a80e0e; 
        }

        .btn-kembali { 
            display: inline-block; 
            margin-bottom: 20px; 
            color: #555; 
            text-decoration: none; 
            font-weight: bold; 
        }

        .btn-kembali:hover { 
            text-decoration: underline; 
        }
    </style>
</head>
<body>

    <div class="form-container">
        <a href="dashboard_owner.php" class="btn-kembali">&larr; Kembali ke Dashboard</a>
        <h2>Tambah Properti Baru</h2>
        
        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Properti (Contoh: Kos Eksklusif Dago)</label>
                <input type="text" name="nama_properti" required>
            </div>
            
            <div class="form-group">
                <label>Tipe Properti</label>
                <select name="tipe" required>
                    <option value="rumah">Rumah</option>
                    <option value="apartemen">Apartemen</option>
                    <option value="kos">Kamar Kos</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Jumlah Kamar Tidur</label>
                <input type="number" name="kamar" required min="1">
            </div>
            
            <div class="form-group">
                <label>Tipe Kamar Mandi</label>
                <select name="kamar_mandi" required>
                    <option value="dalam">Kamar Mandi Dalam</option>
                    <option value="luar">Kamar Mandi Luar</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Harga Sewa (Hanya Angka, contoh: 5000000)</label>
                <input type="number" name="harga" required min="100000">
            </div>

            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" rows="3" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Deskripsi Properti (Fasilitas, lokasi terdekat, dll)</label>
                <textarea name="deskripsi" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label>Status Properti</label>
                <select name="status" required>
                    <option value="TERSEDIA">Tersedia</option>
                    <option value="TIDAK TERSEDIA">Tidak Tersedia</option>
                </select>
            </div>
            
            <button type="submit" name="simpan" class="btn-simpan">Simpan Properti</button>
        </form>
    </div>

</body>
</html>