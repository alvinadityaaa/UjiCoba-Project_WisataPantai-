<?php
include 'koneksi.php';

// Ambil ID fasilitas dari URL
$id = $_GET['id'];

// Ambil data fasilitas berdasarkan ID
$query = mysqli_query($koneksi, "SELECT * FROM fasilitas WHERE id_fasilitas='$id'");
$data  = mysqli_fetch_array($query);

// Proses Update Data Fasilitas
if (isset($_POST['update'])) {
    $nama  = $_POST['nama_fasilitas'];
    $harga = $_POST['harga'];

    $update = mysqli_query($koneksi, "UPDATE fasilitas SET nama_fasilitas='$nama', harga='$harga' WHERE id_fasilitas='$id'");

    if ($update) {
        header("Location: fasilitas.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Fasilitas - Wisata Pantai</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 30px; background-color: #e0f7fa; }
        h2 { color: #006064; }
        .form-box { background: white; padding: 20px; border-radius: 10px; width: 320px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        input[type=text], input[type=number] { width: 100%; padding: 10px; margin: 8px 0 15px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { background-color: #2e7d32; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; }
        button:hover { background-color: #1b5e20; }
        a { display: inline-block; margin-top: 15px; color: #00838f; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Edit Data Fasilitas</h2>

    <div class="form-box">
        <form method="POST" action="">
            <label>Nama Fasilitas / Wahana:</label>
            <input type="text" name="nama_fasilitas" value="<?= $data['nama_fasilitas']; ?>" required>
            
            <label>Harga Sewa (Rp):</label>
            <input type="number" name="harga" value="<?= $data['harga']; ?>" required>

            <button type="submit" name="update">Simpan Perubahan</button>
        </form>
        <a href="fasilitas.php">← Batal / Kembali</a>
    </div>

</body>
</html>