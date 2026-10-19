<?php
include 'koneksi.php';

//PROSES TAMBAH RESERVASI
if (isset($_POST['tambah'])) {
    $tgl           = $_POST['tgl_reservasi'];
    $id_pengunjung = $_POST['id_pengunjung'];
    $status        = $_POST['status'];
    $dp            = $_POST['dp']; // Nominal DP dari form
    
    $fasilitas_pilihan = isset($_POST['id_fasilitas']) ? $_POST['id_fasilitas'] : [];

    if (!empty($fasilitas_pilihan)) {
        foreach ($fasilitas_pilihan as $id_fasilitas) {
            mysqli_query($koneksi, "INSERT INTO reservasi (tgl_reservasi, id_pengunjung, id_fasilitas, dp, status) 
                                     VALUES ('$tgl', '$id_pengunjung', '$id_fasilitas', '$dp', '$status')");
        }
    }

    header("Location: reservasi.php");
}

// PROSES HAPUS RESERVASI
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM reservasi WHERE id_reservasi='$id'");
    header("Location: reservasi.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Reservasi - Wisata Pantai</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 30px; background-color: #e0f7fa; }
        h2, h3 { color: #006064; }
        .form-box { background: white; padding: 20px; border-radius: 10px; width: 360px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 30px; }
        input[type=date], input[type=number], select { width: 100%; padding: 10px; margin: 8px 0 15px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        
        .checkbox-group { background: #f9f9f9; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin: 8px 0 15px 0; max-height: 120px; overflow-y: auto; }
        .checkbox-item { margin-bottom: 8px; font-size: 14px; display: flex; align-items: center; }
        .checkbox-item input { margin-right: 10px; width: 16px; height: 16px; cursor: pointer; }

        button { background-color: #00838f; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; }
        button:hover { background-color: #006064; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; font-size: 14px; }
        th { background-color: #00838f; color: white; }
        a { text-decoration: none; font-weight: bold; }
        .btn-edit { color: #2e7d32; margin-right: 15px; }
        .btn-hapus { color: #c62828; }
        
        .nav-menu { margin-bottom: 25px; }
        .nav-menu a { background-color: #00838f; color: white; padding: 10px 15px; border-radius: 5px; margin-right: 10px; font-size: 14px; display: inline-block; }
        .nav-menu a:hover { background-color: #006064; }
        .nav-active { background-color: #004d40 !important; }
    </style>
</head>
<body>

    <div class="nav-menu">
        <a href="pengunjung.php">Data Pengunjung</a>
        <a href="fasilitas.php">Data Fasilitas</a>
        <a href="reservasi.php" class="nav-active">Transaksi Reservasi</a>
    </div>

    <h2>Sistem Wisata Pantai — Transaksi Reservasi</h2>

    <div class="form-box">
        <h3>Tambah Reservasi Baru</h3>
        <form method="POST" action="">
            <label>Tanggal Sewa:</label>
            <input type="date" name="tgl_reservasi" required>
            
            <label>Pilih Pengunjung:</label>
            <select name="id_pengunjung" required>
                <option value="">-- Pilih Pengunjung --</option>
                <?php
                $pengunjung = mysqli_query($koneksi, "SELECT * FROM pengunjung");
                while ($p = mysqli_fetch_array($pengunjung)) {
                    echo "<option value='$p[id_pengunjung]'>$p[nama_pengunjung]</option>";
                }
                ?>
            </select>

            <label>Pilih Fasilitas (Bisa Centang Banyak):</label>
            <div class="checkbox-group">
                <?php
                $fasilitas = mysqli_query($koneksi, "SELECT * FROM fasilitas");
                while ($f = mysqli_fetch_array($fasilitas)) {
                ?>
                    <div class="checkbox-item">
                        <input type="checkbox" name="id_fasilitas[]" value="<?= $f['id_fasilitas']; ?>">
                        <label><?= $f['nama_fasilitas']; ?> (Rp <?= number_format($f['harga'],0,',','.'); ?>)</label>
                    </div>
                <?php } ?>
            </div>

            <label>Nominal DP / Bayar Awalan (Rp):</label>
            <input type="number" name="dp" placeholder="Masukkan nominal DP (Isi 0 jika belum bayar)" value="0" required>

            <label>Status Pembayaran:</label>
            <select name="status" required>
                <option value="Belum Bayar">Belum Bayar</option>
                <option value="DP (Uang Muka)">DP (Uang Muka)</option>
                <option value="Lunas">Lunas</option>
            </select>

            <button type="submit" name="tambah">Simpan Reservasi</button>
        </form>
    </div>

    <h3>Daftar Transaksi Reservasi</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Pengunjung</th>
            <th>Fasilitas</th>
            <th>Harga Total</th>
            <th>DP Dibayar</th>
            <th>Sisa Bayar</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        $query_relasi = "SELECT * FROM reservasi 
                         JOIN pengunjung ON reservasi.id_pengunjung = pengunjung.id_pengunjung 
                         JOIN fasilitas ON reservasi.id_fasilitas = fasilitas.id_fasilitas 
                         ORDER BY id_reservasi DESC";

        $data = mysqli_query($koneksi, $query_relasi);
        
        while ($d = mysqli_fetch_array($data)) {
            //Jika status Lunas, Sisa Bayar otomatis 0
            if ($d['status'] == 'Lunas') {
                $sisa_bayar = 0;
            } else {
                $sisa_bayar = $d['harga'] - $d['dp'];
            }
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $d['tgl_reservasi']; ?></td>
            <td><?= $d['nama_pengunjung']; ?></td>
            <td><?= $d['nama_fasilitas']; ?></td>
            <td>Rp <?= number_format($d['harga'], 0, ',', '.'); ?></td>
            <td>Rp <?= number_format($d['dp'], 0, ',', '.'); ?></td>
            <td style="color: <?= ($sisa_bayar > 0) ? '#c62828' : '#2e7d32'; ?>; font-weight: bold;">
                Rp <?= number_format($sisa_bayar, 0, ',', '.'); ?>
            </td>
            <td><b><?= $d['status']; ?></b></td>
            <td>
                <a href="edit_reservasi.php?id=<?= $d['id_reservasi']; ?>" class="btn-edit">Edit</a>
                <a href="reservasi.php?hapus=<?= $d['id_reservasi']; ?>" class="btn-hapus" onclick="return confirm('Hapus transaksi ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>