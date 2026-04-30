<?php
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

    // Validasi harga agar tidak melebihi batas database (Integer Overflow)
    if ($harga > 2000000000) {
        echo "<script>alert('Nominal harga sewa terlalu besar!'); window.history.back();</script>";
        exit;
    }

    // Fungsi bantuan untuk mengurus upload foto
    function uploadFoto($input_name) {
        if(isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES[$input_name]['tmp_name'];
            $file_name = $_FILES[$input_name]['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Batasi hanya file gambar yang boleh diupload
            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
            if(in_array($file_ext, $allowed_ext)) {
                // Buat nama file unik agar tidak bentrok jika namanya sama
                $new_file_name = uniqid() . '-' . time() . '.' . $file_ext;
                $destination = 'uploads/' . $new_file_name;
                
                if(move_uploaded_file($tmp_name, $destination)) {
                    return $new_file_name; // Kembalikan nama file baru untuk disimpan ke DB
                }
            }
        }
        return ""; // Kembalikan string kosong jika tidak ada upload atau gagal
    }

    // Proses upload ketiga foto
    $foto_utama = uploadFoto('foto_utama');
    $foto_2 = uploadFoto('foto_2');
    $foto_3 = uploadFoto('foto_3');

    // Pastikan foto utama wajib terisi
    if(empty($foto_utama)) {
        echo "<script>alert('Gagal! Foto Utama wajib diisi dan harus berupa gambar (JPG/PNG).'); window.history.back();</script>";
        exit;
    }

    // Masukkan data beserta nama file foto ke database
    // Pastikan tabel properti kamu sudah memiliki kolom foto_utama, foto_2, dan foto_3
    $query = "INSERT INTO properti (owner_id, nama_properti, tipe, kamar, kamar_mandi, harga, deskripsi, alamat, status, foto_utama, foto_2, foto_3) 
              VALUES ('$owner_id', '$nama', '$tipe', '$kamar', '$kamar_mandi', '$harga', '$deskripsi', '$alamat', '$status', '$foto_utama', '$foto_2', '$foto_3')";

    if(mysqli_query($conn, $query)) {
        echo "<script>
                alert('Hore! Properti beserta foto berhasil ditambahkan.');
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

        .form-group input[type="file"] {
            padding: 7px;
            background: #f9f9f9;
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
            margin-top: 10px;
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

        .upload-section {
            background: #fff5f5;
            padding: 15px;
            border: 1px dashed #d11212;
            border-radius: 6px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <a href="dashboard_owner.php" class="btn-kembali">&larr; Kembali ke Dashboard</a>
        <h2>Tambah Properti Baru</h2>
        
        <!-- WAJIB ADA enctype="multipart/form-data" UNTUK UPLOAD FILE -->
        <form action="" method="POST" enctype="multipart/form-data">
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
                <input type="number" name="kamar" required min="1" max="50">
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
                <input type="number" name="harga" required min="100000" max="2000000000">
            </div>

            <!-- AREA UPLOAD FOTO -->
            <div class="upload-section">
                <div class="form-group">
                    <label>Foto Utama (Wajib)</label>
                    <input type="file" name="foto_utama" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label>Foto Tambahan 1 (Opsional)</label>
                    <input type="file" name="foto_2" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Foto Tambahan 2 (Opsional)</label>
                    <input type="file" name="foto_3" accept="image/*">
                </div>
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