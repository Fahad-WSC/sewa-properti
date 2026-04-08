<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit;
}

$id_properti = $_GET['id'];
$owner_id = $_SESSION['user_id'];

$query_get = "SELECT * FROM properti WHERE id = '$id_properti' AND owner_id = '$owner_id'";
$result = mysqli_query($conn, $query_get);

if(mysqli_num_rows($result) == 0) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='dashboard_owner.php';</script>";
    exit;
}

$properti = mysqli_fetch_assoc($result);

if(isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_properti']);
    $tipe = $_POST['tipe'];
    $kamar = $_POST['kamar'];
    $kamar_mandi = $_POST['kamar_mandi'];
    $harga = $_POST['harga'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $query_update = "UPDATE properti SET 
                    nama_properti = '$nama', 
                    tipe = '$tipe', 
                    kamar = '$kamar', 
                    kamar_mandi = '$kamar_mandi', 
                    harga = '$harga', 
                    deskripsi = '$deskripsi' 
                    WHERE id = '$id_properti' AND owner_id = '$owner_id'";

    if(mysqli_query($conn, $query_update)) {
        echo "<script>alert('Properti berhasil diperbarui!'); window.location='dashboard_owner.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui properti!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Properti - Sewa Properti</title>
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
    color: #ffc107; 
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
    background: #ffc107; 
    color: #333; 
    border: none; 
    padding: 12px 20px; 
    font-weight: bold; 
    border-radius: 4px; 
    cursor: pointer; 
    width: 100%; 
    font-size: 16px;
}

.btn-simpan:hover { 
    background: #e0a800; 
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
        <a href="dashboard_owner.php" class="btn-kembali">← Batal & Kembali</a>
        <h2>Edit Data Properti</h2>
        
        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Properti</label>
                <input type="text" name="nama_properti" value="<?php echo $properti['nama_properti']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Tipe Properti</label>
                <select name="tipe" required>
                    <option value="rumah" <?php if($properti['tipe'] == 'rumah') echo 'selected'; ?>>Rumah</option>
                    <option value="apartemen" <?php if($properti['tipe'] == 'apartemen') echo 'selected'; ?>>Apartemen</option>
                    <option value="kos" <?php if($properti['tipe'] == 'kos') echo 'selected'; ?>>Kamar Kos</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Jumlah Kamar Tidur</label>
                <input type="number" name="kamar" value="<?php echo $properti['kamar']; ?>" required min="1">
            </div>
            
            <div class="form-group">
                <label>Tipe Kamar Mandi</label>
                <select name="kamar_mandi" required>
                    <option value="dalam" <?php if($properti['kamar_mandi'] == 'dalam') echo 'selected'; ?>>Kamar Mandi Dalam</option>
                    <option value="luar" <?php if($properti['kamar_mandi'] == 'luar') echo 'selected'; ?>>Kamar Mandi Luar</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Harga Sewa</label>
                <input type="number" name="harga" value="<?php echo $properti['harga']; ?>" required min="100000">
            </div>
            
            <div class="form-group">
                <label>Deskripsi Properti</label>
                <textarea name="deskripsi" rows="4" required><?php echo $properti['deskripsi']; ?></textarea>
            </div>
            
            <button type="submit" name="update" class="btn-simpan">Update Properti</button>
        </form>
    </div>

</body>
</html>