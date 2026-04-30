<?php
require 'koneksi.php';
// Pastikan yang akses adalah owner
if(!isset($_SESSION['login']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit;
}

if(isset($_GET['id']) && isset($_GET['status'])) {
    $id_transaksi = $_GET['id'];
    $status_baru = $_GET['status'];

    $query = "UPDATE transaksi SET status = '$status_baru' WHERE id = '$id_transaksi'";

    if(mysqli_query($conn, $query)) {
        echo "<script>
                alert('Status pesanan berhasil diperbarui!');
                window.location.href = 'dashboard_owner.php';
              </script>";
    } else {
        echo "Gagal update: " . mysqli_error($conn);
    }
} else {
    header("Location: dashboard_owner.php");
    exit;
}
?>