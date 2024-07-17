<?php
require_once ('koneksi.php');
session_start();


$sql = "SELECT MAX(idpelanggan) AS max_id FROM pelanggan";
$result1 = mysqli_query($koneksi,$sql);

$row =mysqli_fetch_assoc($result1);
$maxId = $row['max_id'];

// Ambil id pelanggan terakhir yang disimpan
$query = "SELECT * FROM pelanggan where idpelanggan='$maxId'";
$result = mysqli_query($koneksi, $query);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['idpelanggan'] = $row['idpelanggan'];
    $_SESSION['namapelanggan'] = $row['namapelanggan'];
    $_SESSION['totalHarga'] = $row['totalharga'];
    $idPelanggan = $_SESSION['idpelanggan'];
    $namaPelanggan = $_SESSION['namapelanggan'];
    $totalHarga = $_SESSION['totalHarga'];
     $now = date('Y-m-d H:i:s');
     $tanggal= date('d M Y', strtotime($now));

    // Ambil detail barang dari tabel penjualan
    $query_penjualan = "SELECT penjualan.*, produk.namaBarang FROM penjualan
                        INNER JOIN produk ON penjualan.idBarang = produk.idBarang
                        WHERE penjualan.idPelanggan = $idPelanggan";
    $result_penjualan = mysqli_query($koneksi, $query_penjualan);
} else {
    // Handle error jika data pelanggan tidak ditemukan
    echo "Data pelanggan tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Struk Pembelian</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        table th {
            background-color: #f0f0f0;
        }

        .total {
            text-align: right;
            font-weight: bold;
        }

        /* Styles for print */
        @media print {
            body * {
                visibility: hidden;
            }

            .container, .container * {
                visibility: visible;
            }

            .container {
                position: absolute;
                left: 0;
                top: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h2>Kwitansi</h2>
        </div>

        <div class="content">
            <p><strong>Nama Petani:</strong> <?= $namaPelanggan ?></p>
            <p><strong>Tanggal diterima:</strong> <?= $tanggal ?></p>

            <table>
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah/Berat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row_penjualan = mysqli_fetch_assoc($result_penjualan)): ?>
                        <tr>
                            <td><?= $row_penjualan['namaBarang'] ?></td>
                            <td><?= $row_penjualan['jumlahBarang'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="total">
                <p><strong>Total Harga:</strong> Rp <?= number_format($totalHarga, 0, ',', '.') ?></p>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih atas Kepercayaan anda.</p>
        </div>
    </div>

    <!-- Tombol untuk kembali ke index.php -->
    <div style="text-align: center; margin-top: 20px;" class="no-print">
        <button onclick="closeWindowAndDestroySession();" class="bg-blue-500 text-white px-4 py-2 rounded-lg mr-2" name="">Tutup</button>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    
    function closeWindowAndDestroySession() {
        // Buat permintaan AJAX ke skrip PHP untuk menghancurkan sesi
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "destroy_session.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Setelah sesi dihapus, tutup jendela
                window.close();
            }
        };
        xhr.send();
    }
    </script>

</body>
</html>

<?php mysqli_close($koneksi); ?>
