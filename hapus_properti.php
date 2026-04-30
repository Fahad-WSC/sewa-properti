<?php
require 'koneksi.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit;
}

if(isset($_GET['id'])) {
    $id_properti = $_GET['id'];
    $owner_id = $_SESSION['user_id'];

    $query = "DELETE FROM properti WHERE id = '$id_properti' AND owner_id = '$owner_id'";
    
    if(mysqli_query($conn, $query)) {
        echo "<script>
                alert('Sip! Properti berhasil dihapus.');
                document.location.href = 'dashboard_owner.php';
              </script>";
    } else {
        echo "<script>
                alert('Waduh, gagal menghapus data.');
                document.location.href = 'dashboard_owner.php';
              </script>";
    }
} else {
    header("Location: dashboard_owner.php");
}
?>