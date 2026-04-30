<?php
require 'koneksi.php';

// Ambil data properti (sesuaikan query ini dengan struktur tabel aslimu)
$query = "SELECT * FROM properti ORDER BY id DESC"; 
$result = mysqli_query($conn, $query);

// Cek jika kosong
if (mysqli_num_rows($result) == 0) {
    echo "<p class='empty-text'>Belum ada properti yang tersedia.</p>";
    exit;
}

// Looping untuk menampilkan card properti
while($row = mysqli_fetch_assoc($result)) {
    ?>
    <!-- Ini contoh HTML Card Properti, sesuaikan dengan desain CSS kamu -->
    <div class="properti-card">
        <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" alt="Foto Properti">
        <h3><?= htmlspecialchars($row['nama_properti']) ?></h3>
        <p>Harga: Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
        <a href="detail_properti.php?id=<?= $row['id'] ?>" class="btn btn-outline">Lihat Detail</a>
    </div>
    <?php
}
?>