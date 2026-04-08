<?php
require 'koneksi.php';

$nama = "Super Admin";
$email = "admin@sewa.com"; 
$password_asli = "admin123"; 
$role = "admin";

$password_hash = password_hash($password_asli, PASSWORD_DEFAULT);

$query = "INSERT INTO users (nama, email, password, role) 
          VALUES ('$nama', '$email', '$password_hash', '$role')";

if(mysqli_query($conn, $query)) {
    echo "<h3>Sukses!</h3>";
    echo "Akun admin berhasil dibuat.<br>";
    echo "Email: <b>$email</b><br>";
    echo "Password: <b>$password_asli</b><br>";
    echo "<br><a href='login.php'>Klik di sini untuk Login</a>";
} else {
    echo "Gagal membuat akun: " . mysqli_error($conn);
}
?>