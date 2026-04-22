<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "UPDATE users SET nama='$nama', email='$email', password='$hashed_password' WHERE id='$user_id'";
    } else {
        $query = "UPDATE users SET nama='$nama', email='$email' WHERE id='$user_id'";
    }
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='pengaturan_owner.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: pengaturan_owner.php");
}
?>