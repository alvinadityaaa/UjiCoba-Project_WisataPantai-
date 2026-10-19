<?php
include 'koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Ambil data pengunjung berdasarkan ID
$query = mysqli_query($koneksi, "SELECT * FROM pengunjung WHERE id_pengunjung='$id'");
$data  = mysqli_fetch_array($query);

// Proses Update Data
if (isset($_POST['update'])) {
    $nama  = $_POST['nama_pengunjung'];
    $no_hp = $_POST['no_hp'];

    $update = mysqli_query($koneksi, "UPDATE pengunjung SET nama_pengunjung='$nama', no_hp='$no_hp' WHERE id_pengunjung='$id'");

    if ($update) {
        header("Location: pengunjung.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pengunjung - Wisata Pantai</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 30px; background-color: #e0f7fa; }
        h2 { color: #006064; }
        .form-box { background: white; padding: 20px; border-radius: 10px; width: 320px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        input[type=text] { width: 100%; padding: 10px; margin: 8px 0 15px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { background-color: #2e7d32; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; }
        button:hover { background-color: #1b5e20; }
        a { display: inline-block; margin-top: 15px; color: #00838f; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Edit Data Pengunjung</h2>

    <div class="form-box">
        <form method="POST" action="">
            <label>Nama Pengunjung:</label>
            <input type="text" name="nama_pengunjung" value="<?= $data['nama_pengunjung']; ?>" required>
            
            <label>No. HP / WhatsApp:</label>
            <input type="text" name="no_hp" value="<?= $data['no_hp']; ?>" required>

            <button type="submit" name="update">Simpan Perubahan</button>
        </form>
        <a href="pengunjung.php">← Batal / Kembali</a>
    </div>

</body>
</html>