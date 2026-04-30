<?php
require 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'tenant') {
    header("Location: login.php");
    exit;
}

$tenant_id = $_SESSION['user_id'];
$id_sewa = $_GET['id_sewa'] ?? null;

if (!$id_sewa) {
    echo "<script>alert('ID Pesanan tidak ditemukan!'); window.location='dashboard_tenant.php';</script>";
    exit;
}

$query = mysqli_query($conn, "SELECT transaksi.*, properti.nama_properti 
                              FROM transaksi 
                              JOIN properti ON transaksi.properti_id = properti.id 
                              WHERE transaksi.id = '$id_sewa' AND transaksi.tenant_id = '$tenant_id'");
$data_sewa = mysqli_fetch_assoc($query);

if (!$data_sewa || $data_sewa['status'] != 'Disetujui') {
    echo "<script>alert('Pesanan tidak valid atau belum disetujui!'); window.location='dashboard_tenant.php';</script>";
    exit;
}

if (isset($_POST['upload_bukti'])) {
    $nama_file = $_FILES['bukti_bayar']['name'];
    $ukuran_file = $_FILES['bukti_bayar']['size'];
    $error = $_FILES['bukti_bayar']['error'];
    $tmp_name = $_FILES['bukti_bayar']['tmp_name'];

    if ($error === 4) {
        echo "<script>alert('Pilih gambar bukti pembayaran terlebih dahulu!');</script>";
    } else if ($error !== 0) {
        echo "<script>alert('Upload gagal! Error code: $error');</script>";
    } else {
        $ekstensi_valid = ['jpg', 'jpeg', 'png'];
        $ekstensi_gambar = explode('.', $nama_file);
        $ekstensi_gambar = strtolower(end($ekstensi_gambar));

        if (!in_array($ekstensi_gambar, $ekstensi_valid)) {
            echo "<script>alert('Yang Anda upload bukan gambar! (Hanya JPG/PNG)');</script>";
        } else if ($ukuran_file > 2000000) {
            echo "<script>alert('Ukuran gambar terlalu besar! (Maks 2MB)');</script>";
        } else {
            $nama_file_baru = uniqid() . '-' . $id_sewa . '.' . $ekstensi_gambar;
            $target_path = '/var/www/html/uploads/' . $nama_file_baru;

            if (!move_uploaded_file($tmp_name, $target_path)) {
                echo "<script>alert('Gagal menyimpan file! Pastikan folder uploads dapat ditulis.');</script>";
            } else {
                $update = mysqli_query($conn, "UPDATE transaksi SET 
                                   bukti_bayar = '$nama_file_baru', 
                                   status = 'Validasi Bayar' 
                                   WHERE id = '$id_sewa'");

                if ($update) {
                    echo "<script>
                            alert('Bukti pembayaran berhasil diunggah! Menunggu konfirmasi dari Owner.');
                            window.location='dashboard_tenant.php';
                          </script>";
                } else {
                    echo "<script>alert('Gagal memperbarui data pembayaran!');</script>";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran - Sewa Properti</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4; 
            margin: 0; 
        }

        .navbar { 
            background-color: #d11212; 
            padding: 15px 40px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            color: white; 
        }

        .navbar .logo { 
            font-size: 22px; 
            font-weight: bold; 
        }

        .navbar .menu a { 
            color: white; 
            text-decoration: none; 
            margin-left: 20px; 
            font-size: 14px; 
            font-weight: bold; 
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 5px;
            margin-top: 0;
        }

        .subtitle {
            text-align: center;
            color: #777;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .info-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
            line-height: 1.5;
            color: #856404;
        }

        .property-info {
            background-color: #f8f9fa;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 25px;
        }

        .property-info p {
            margin: 5px 0;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 2px dashed #ccc;
            border-radius: 5px;
            background-color: #fafafa;
            cursor: pointer;
            box-sizing: border-box;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #d11212;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background-color: #b00f0f;
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #555;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-back:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">Sewa Properti</div>
        <div class="menu">
            <span>Halo, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</span>
            <a href="katalog_properti.php">Cari Properti</a>
            <a href="dashboard_tenant.php">Pesanan Saya</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Konfirmasi Pembayaran</h2>
        <p class="subtitle">Upload bukti transfer untuk menyelesaikan pesanan Anda.</p>

        <div class="property-info">
            <p><strong>No Pesanan:</strong> #TRX-00<?= $data_sewa['id']; ?></p>
            <p><strong>Properti:</strong> <?= $data_sewa['nama_properti']; ?></p>
        </div>

        <div class="info-box">
            <strong>Instruksi Pembayaran:</strong><br>
            Silakan transfer sesuai kesepakatan harga ke rekening berikut:<br>
            <strong>BCA: 1234567890 (a.n Sewa Properti)</strong><br>
            <strong>BNI: 1234567890 (a.n Sewa Properti)</strong><br>
            <strong>BRI: 1234567890 (a.n Sewa Properti)</strong><br>
            <strong>MANDIRI: 1234567890 (a.n Sewa Properti)</strong><br>
            Pastikan Anda menyimpan bukti transfer untuk diunggah di bawah ini.
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="bukti_bayar">Upload Bukti Transfer (Format: JPG/PNG, Maks: 2MB)</label>
                <input type="file" name="bukti_bayar" id="bukti_bayar" accept=".jpg, .jpeg, .png" required>
            </div>

            <button type="submit" name="upload_bukti" class="btn-submit">Kirim Bukti Pembayaran</button>
        </form>
        
        <a href="dashboard_tenant.php" class="btn-back">&larr; Kembali ke Dashboard</a>
    </div>

</body>
</html>