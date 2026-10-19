<?php
include 'koneksi.php';

// 1. PROSES TAMBAH DATA FASILITAS
if (isset($_POST['tambah'])) {
    $nama  = $_POST['nama_fasilitas'];
    $harga = $_POST['harga'];

    $query = mysqli_query($koneksi, "INSERT INTO fasilitas (nama_fasilitas, harga) VALUES ('$nama', '$harga')");
    
    if ($query) {
        header("Location: fasilitas.php");
    }
}

// 2. PROSES HAPUS DATA
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Hapus dulu transaksi reservasi yang pakai fasilitas ini
    mysqli_query($koneksi, "DELETE FROM reservasi WHERE id_fasilitas='$id'");

    // Baru hapus fasilitas utamanya
    mysqli_query($koneksi, "DELETE FROM fasilitas WHERE id_fasilitas='$id'");

    header("Location: fasilitas.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Fasilitas - Wisata Pantai</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 30px; background-color: #e0f7fa; }
        h2, h3 { color: #006064; }
        .form-box { background: white; padding: 20px; border-radius: 10px; width: 320px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 30px; }
        input[type=text], input[type=number] { width: 100%; padding: 10px; margin: 8px 0 15px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { background-color: #00838f; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; }
        button:hover { background-color: #006064; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #00838f; color: white; }
        a { text-decoration: none; font-weight: bold; }
        .btn-edit { color: #2e7d32; margin-right: 15px; }
        .btn-hapus { color: #c62828; }
        
        /* NAVIGASI TOMBOL SERAGAM */
        .nav-menu { margin-bottom: 25px; }
        .nav-menu a { background-color: #00838f; color: white; padding: 10px 15px; border-radius: 5px; margin-right: 10px; font-size: 14px; display: inline-block; }
        .nav-menu a:hover { background-color: #006064; }
        .nav-active { background-color: #004d40 !important; }
    </style>
</head>
<body>

    <!-- MENU NAVIGASI TOMBOL -->
    <div class="nav-menu">
        <a href="pengunjung.php">Data Pengunjung</a>
        <a href="fasilitas.php" class="nav-active">Data Fasilitas</a>
        <a href="reservasi.php">Transaksi Reservasi</a>
    </div>

    <h2>Sistem Wisata Pantai — Data Fasilitas</h2>

    <!-- FORM INPUT FASILITAS -->
    <div class="form-box">
        <h3>Tambah Fasilitas Baru</h3>
        <form method="POST" action="">
            <label>Nama Fasilitas / Wahana:</label>
            <input type="text" name="nama_fasilitas" placeholder="Contoh: Banana Boat" required>
            
            <label>Harga Sewa (Rp):</label>
            <input type="number" name="harga" placeholder="Contoh: 100000" required>

            <button type="submit" name="tambah">Simpan Fasilitas</button>
        </form>
    </div>

    <!-- TABEL TAMPIL DATA -->
    <h3>Daftar Fasilitas Tersedia</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Fasilitas</th>
            <th>Harga Sewa</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        $data = mysqli_query($koneksi, "SELECT * FROM fasilitas ORDER BY id_fasilitas DESC");
        
        while ($d = mysqli_fetch_array($data)) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $d['nama_fasilitas']; ?></td>
            <td>Rp <?= number_format($d['harga'], 0, ',', '.'); ?></td>
            <td>
                <a href="edit_fasilitas.php?id=<?= $d['id_fasilitas']; ?>" class="btn-edit">Edit</a>
                <a href="fasilitas.php?hapus=<?= $d['id_fasilitas']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus fasilitas ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>