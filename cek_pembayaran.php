<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit;
}

$id_trx = $_GET['id'] ?? null;

$query = "SELECT transaksi.*, users.nama as nama_penyewa, properti.nama_properti 
          FROM transaksi 
          JOIN users ON transaksi.tenant_id = users.id 
          JOIN properti ON transaksi.properti_id = properti.id 
          WHERE transaksi.id = '$id_trx'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if(!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='dashboard_owner.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cek Pembayaran - Sewa Properti</title>
    <style>
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        img { max-width: 100%; border: 1px solid #ddd; margin-top: 10px; }
        .btn-konfirmasi { background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Detail Pembayaran</h2>
    <p>Penyewa: <strong><?= $data['nama_penyewa'] ?></strong></p>
    <p>Properti: <strong><?= $data['nama_properti'] ?></strong></p>
    
    <p>Bukti Transfer:</p>
    <?php if($data['bukti_bayar']): ?>
        <img src="uploads/<?= $data['bukti_bayar'] ?>" alt="Bukti Bayar">
    <?php else: ?>
        <p>Belum ada bukti bayar.</p>
    <?php endif; ?>
    
    <br>
    <a href="proses_konfirmasi.php?id=<?= $data['id'] ?>&status=Lunas" class="btn-konfirmasi">Konfirmasi Pembayaran Lunas</a>
    <a href="dashboard_owner.php">Kembali</a>
</div>
</body>
</html>