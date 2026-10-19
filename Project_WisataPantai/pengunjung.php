<?php
include 'koneksi.php';

//PROSES TAMBAH DATA (LOGIKA INPUT)
if (isset($_POST['tambah'])) {
    $nama  = $_POST['nama_pengunjung'];
    $no_hp = $_POST['no_hp'];

    $query = mysqli_query($koneksi, "INSERT INTO pengunjung (nama_pengunjung, no_hp) VALUES ('$nama', '$no_hp')");
    
    if ($query) {
        header("Location: pengunjung.php");
    }
}

//PROSES HAPUS DATA
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM pengunjung WHERE id_pengunjung='$id'");
    header("Location: pengunjung.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Pengunjung - Wisata Pantai</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 30px; background-color: #e0f7fa; }
        h2, h3 { color: #006064; }
        .form-box { background: white; padding: 20px; border-radius: 10px; width: 320px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 30px; }
        input[type=text] { width: 100%; padding: 10px; margin: 8px 0 15px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { background-color: #00838f; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; }
        button:hover { background-color: #006064; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #00838f; color: white; }
        a { text-decoration: none; font-weight: bold; }
        .btn-edit { color: #2e7d32; margin-right: 15px; }
        .btn-hapus { color: #c62828; }
        
        /* TAMBAHAN CSS UNTUK MENU NAVIGASI */
        .nav-menu { margin-bottom: 25px; }
        .nav-menu a { background-color: #00838f; color: white; padding: 10px 15px; border-radius: 5px; margin-right: 10px; font-size: 14px; display: inline-block;}
        .nav-menu a:hover { background-color: #006064; }
        .nav-active { background-color: #004d40 !important; } /* Warna lebih gelap penanda halaman saat ini */
    </style>
</head>
<body>

    <!-- TAMBAHAN MENU NAVIGASI -->
    <div class="nav-menu">
        <a href="pengunjung.php" class="nav-active">Data Pengunjung</a>
        <a href="fasilitas.php">Data Fasilitas</a>
        <a href="reservasi.php">Transaksi Reservasi</a>
    </div>

    <h2>Sistem Wisata Pantai — Data Pengunjung</h2>

    <!-- FORM INPUT PENGUNJUNG -->
    <div class="form-box">
        <h3>Tambah Pengunjung Baru</h3>
        <form method="POST" action="">
            <label>Nama Pengunjung:</label>
            <input type="text" name="nama_pengunjung" placeholder="Masukkan nama..." required>
            
            <label>No. HP / WhatsApp:</label>
            <input type="text" name="no_hp" placeholder="Contoh: 08123456789" required>

            <button type="submit" name="tambah">Simpan Data</button>
        </form>
    </div>

    <!-- TABEL DOKUMEN / TAMPIL DATA -->
    <h3>Daftar Pengunjung Terdaftar</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Pengunjung</th>
            <th>No. HP / WA</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        $data = mysqli_query($koneksi, "SELECT * FROM pengunjung ORDER BY id_pengunjung DESC");
        
        while ($d = mysqli_fetch_array($data)) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $d['nama_pengunjung']; ?></td>
            <td><?= $d['no_hp']; ?></td>
            <td>
                <a href="edit_pengunjung.php?id=<?= $d['id_pengunjung']; ?>" class="btn-edit">Edit</a>
                <a href="pengunjung.php?hapus=<?= $d['id_pengunjung']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>