<?php
session_start();
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'tenant') {
    header("Location: login.php");
    exit;
}

if(isset($_GET['id'])) {
    $properti_id = $_GET['id'];
    $tenant_id = $_SESSION['user_id'];
    $tanggal_sewa = date('Y-m-d');

    $query = "INSERT INTO transaksi (tenant_id, properti_id, tanggal_sewa, status) 
              VALUES ('$tenant_id', '$properti_id', '$tanggal_sewa', 'Menunggu Konfirmasi')";

    if(mysqli_query($conn, $query)) {
        echo "<script>
                alert('Berhasil! Permintaan sewa telah dikirim ke Owner.');
                window.location.href = 'dashboard_tenant.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: katalog_properti.php");
    exit;
}
?>