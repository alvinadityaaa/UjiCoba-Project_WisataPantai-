<?php
include 'koneksi.php';

$id = $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM reservasi WHERE id_reservasi='$id'");
$data  = mysqli_fetch_array($query);

if (isset($_POST['update'])) {
    $tgl           = $_POST['tgl_reservasi'];
    $id_pengunjung = $_POST['id_pengunjung'];
    $id_fasilitas  = $_POST['id_fasilitas'];
    $dp            = $_POST['dp'];
    $status        = $_POST['status'];

    $update = mysqli_query($koneksi, "UPDATE reservasi SET 
                tgl_reservasi='$tgl', 
                id_pengunjung='$id_pengunjung', 
                id_fasilitas='$id_fasilitas', 
                dp='$dp',
                status='$status' 
                WHERE id_reservasi='$id'");

    if ($update) {
        header("Location: reservasi.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Reservasi - Wisata Pantai</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 30px; background-color: #e0f7fa; }
        h2 { color: #006064; }
        .form-box { background: white; padding: 20px; border-radius: 10px; width: 360px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        input, select { width: 100%; padding: 10px; margin: 8px 0 15px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { background-color: #2e7d32; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; }
        button:hover { background-color: #1b5e20; }
        a { display: inline-block; margin-top: 15px; color: #00838f; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Edit Transaksi Reservasi</h2>

    <div class="form-box">
        <form method="POST" action="">
            <label>Tanggal Sewa:</label>
            <input type="date" name="tgl_reservasi" value="<?= $data['tgl_reservasi']; ?>" required>
            
            <label>Pilih Pengunjung:</label>
            <select name="id_pengunjung" required>
                <?php
                $pengunjung = mysqli_query($koneksi, "SELECT * FROM pengunjung");
                while ($p = mysqli_fetch_array($pengunjung)) {
                    $selected = ($p['id_pengunjung'] == $data['id_pengunjung']) ? 'selected' : '';
                    echo "<option value='$p[id_pengunjung]' $selected>$p[nama_pengunjung]</option>";
                }
                ?>
            </select>

            <label>Pilih Fasilitas:</label>
            <select name="id_fasilitas" required>
                <?php
                $fasilitas = mysqli_query($koneksi, "SELECT * FROM fasilitas");
                while ($f = mysqli_fetch_array($fasilitas)) {
                    $selected = ($f['id_fasilitas'] == $data['id_fasilitas']) ? 'selected' : '';
                    echo "<option value='$f[id_fasilitas]' $selected>$f[nama_fasilitas] (Rp ".number_format($f['harga'],0,',','.').")</option>";
                }
                ?>
            </select>

            <label>Nominal DP / Bayar (Rp):</label>
            <input type="number" name="dp" value="<?= $data['dp']; ?>" required>

            <label>Status Pembayaran:</label>
            <select name="status" required>
                <option value="Belum Bayar" <?= ($data['status'] == 'Belum Bayar') ? 'selected' : ''; ?>>Belum Bayar</option>
                <option value="DP (Uang Muka)" <?= ($data['status'] == 'DP (Uang Muka)') ? 'selected' : ''; ?>>DP (Uang Muka)</option>
                <option value="Lunas" <?= ($data['status'] == 'Lunas') ? 'selected' : ''; ?>>Lunas</option>
            </select>

            <button type="submit" name="update">Simpan Perubahan</button>
        </form>
        <a href="reservasi.php">← Batal / Kembali</a>
    </div>

</body>
</html>