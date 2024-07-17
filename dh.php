<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'kasir') {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];
$query1 = "SELECT id FROM users WHERE username='$username'";
$result1 = mysqli_query($koneksi, $query1);
$row = mysqli_fetch_array($result1);
$idKasir = $row['id'];

// Initialize variables
$namaBarang = '';
$harga = 0;
$idBarang = 0;

// Initialize cart if not set
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bayarkan'])) {
    $namaBarang = $_POST['namaBarang'];
    $harga = $_POST['harga'];
    $idBarang = $_POST['idBarang'];

    $_SESSION['keranjang'][] = [
        'namaBarang' => $namaBarang,
        'harga' => $harga,
        'idBarang' => $idBarang,
        'jumlah' => 1
    ];
}

$totalHarga2 = 0;
foreach ($_SESSION['keranjang'] as $item) {
    $totalHarga2 += $item['harga'] * $item['jumlah']; 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpankan'])) {
    if (empty($_SESSION['keranjang'])) {
        echo "<script>alert('Keranjang kosong. Silakan tambahkan barang terlebih dahulu.');</script>";
    } else {
        $namaPembeli = mysqli_real_escape_string($koneksi, $_POST['namaPembeli']);
        $berat = (int)$_POST['berat'];

        $totalHarga = $_SESSION['totalHarga'] * $berat;

        $now = date('Y-m-d H:i:s');
        $tgl_bln_thn = date('d M Y', strtotime($now));
        
        // Insert into pelanggan table
        $insert_pelanggan = "INSERT INTO pelanggan (namaPelanggan, totalHarga, tanggal) 
                             VALUES ('$namaPembeli', '$totalHarga', '$tgl_bln_thn')";
        if (mysqli_query($koneksi, $insert_pelanggan)) {
            // Get the last inserted id for pelanggan
            $idPelanggan = mysqli_insert_id($koneksi);

            // Insert into penjualan table
            foreach ($_SESSION['keranjang'] as $item) {
                $idBarang = $item['idBarang'];
                $jumlahBarang = $item['jumlah'];
                $insert_penjualan = "INSERT INTO penjualan (idBarang, idKasir, idPelanggan, jumlahBarang) 
                                     VALUES ('$idBarang', '$idKasir', '$idPelanggan', '$jumlahBarang')";
                mysqli_query($koneksi, $insert_penjualan);
            }

            // Clear session setelah transaksi berhasil
            $_SESSION['keranjang'] = [];
            $_SESSION['lastid'] = 0;
            $_SESSION['totalHarga'] = 0;

            echo "<script>alert('Data berhasil disimpan');
            window.location.href = 'index.php';
            </script>";
        } else {
            // Handle error
            echo "Error: " . mysqli_error($koneksi);
        }
        exit;
    }
}

if (isset($_POST['bayarkan'])) {
    $totalHarga = 0;
    $harga1 = 0;
    $namaBarang1 = '';
    foreach ($_SESSION['keranjang'] as $item) {
        $totalHarga += $item['harga'] * $item['jumlah'];
        $harga1 = $item['harga'];
        $namaBarang1 = $item['namaBarang'];
    }
    $_SESSION['totalHarga'] = $totalHarga;
    $_SESSION['namaBarang1'] = $namaBarang1;
    $_SESSION['harga1'] = $harga1;
}

$totalHa = 0;
$berat = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['hitung'])) {
    $berat = (int)$_POST['berat'];

    foreach ($_SESSION['keranjang'] as $item) {
        $totalHa = $item['harga'] * $berat;
    }
}

// Display modal on form submission
$showModal = isset($_POST['bayarkan']) || isset($_POST['hitung']);

// Query to get product data
$result = mysqli_query($koneksi, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aman.Store</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .main-content {
            display: flex;
        }
        .product-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            height: auto;
            flex: 1;
        }
        .product-item {
            height: 100%;
        }
        .cart-container {
            background-color: #FEF3C7;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-left: 5px;
        }
        body {
            background-color: #393646;
        }
        .navi, nav {
            background-color: #1c1917;
        }
        .footer {
            background-color: #1c1917;
            color: #ffffff;
            padding: 20px 0;
            text-align: center;
            width: 100%;
        }
        .footer a {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .footer a:hover {
            color: #ffffff;
            opacity: 0.7;
        }
    </style>
</head>

<body>
<nav class="nav text-white border-gray-200 dark:bg-gray-900 dark:border-gray-700 p-4">
    <div class="navi max-w-screen-xl flex items-center justify-between mx-0">
        <div class="flex items-center space-x-3">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="gambar/shop.jpg" class="h-9 w-10" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">UD.ROTUA</span>
            </a>
        </div>
        <div class="flex items-center ml-auto space-x-2">
            <p class="text-lg">Selamat Datang <?= $username ?>, Anda login sebagai Kasir</p>
            <a href="logout.php" class="bg-yellow-200 rounded px-4 py-2 text-black">LOGOUT</a>
        </div>
    </div>
</nav>

<div class="p-2 w-[1400px] mb-12 h-[500px]">
<?php include'header.php'; ?>
</div>
<div class="main-content p-4 mt-5 mx-auto pl-0 max-w-screen-xl flex flex-col md:flex-row gap-4">
    <div class="product-container w-[400px] rounded h-auto">
        <?php while ($row = mysqli_fetch_array($result)): ?>
            <div class="product-item flex flex-col justify-center items-center w-full h-80 rounded-lg bg-gray-300 py-1">
                <div class="flex h-40 w-full justify-center items-center p-1">
                    <img src="images/<?php echo $row['foto']; ?>" style="max-width: 100%; max-height: 100%;border-radius: 10px;" alt="<?php echo $row['namaBarang']; ?>">
                </div>
                <h2 class="text-2xl mb-3 font-bold"><?= $row['namaBarang'] ?></h2>
                <div class="h-25 mb-1">
                    <p class="text-sm"><?= $row['deskripsi'] ?></p>
                </div>
                <strong><p class="text-sm">Rp<?= number_format($row['harga'], 0, ',', '.') ?>,00/(KG/Biji)</p></strong> 
                <form method="POST" action="#barang">
                    <input type="hidden" name="namaBarang" value="<?= $row['namaBarang'] ?>">
                    <input type="hidden" name="harga" value="<?= $row['harga'] ?>">
                    <input type="hidden" name="idBarang" value="<?= $row['idBarang'] ?>">
                    <button type="submit" name="bayarkan" class="block bg-green-300 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Terima Barang</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="cart-container w-full md:w-[300px] p-4 bg-yellow-200 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4">Keranjang</h2>
        <?php if (!empty($_SESSION['keranjang'])): ?>
            <ul>
                <?php foreach ($_SESSION['keranjang'] as $item): ?>
                    <li class="mb-2">
                        <strong><?= $item['namaBarang'] ?></strong><br>
                        Harga: Rp<?= number_format($item['harga'], 0, ',', '.') ?>,00<br>
                        Jumlah: <?= $item['jumlah'] ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="font-bold">Total: Rp<?= number_format($totalHarga2, 0, ',', '.') ?>,00</p>
        <?php else: ?>
            <p>Keranjang kosong.</p>
        <?php endif; ?>
    </div>

    <?php if ($showModal): ?>
        <div id="barang" class="fixed inset-0 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 shadow-lg max-w-md w-full">
                <h2 class="text-xl font-bold mb-4">Informasi Barang</h2>
                <p>Nama Barang: <?= $_SESSION['namaBarang1'] ?></p>
                <p>Harga: Rp<?= number_format($_SESSION['harga1'], 0, ',', '.') ?>,00</p>
                <p>Total Harga: Rp<?= number_format($_SESSION['totalHarga'], 0, ',', '.') ?>,00</p>
                <form method="POST" action="#barang">
                    <div class="mb-4">
                        <label for="berat" class="block text-sm font-medium text-gray-700">Berat/Banyak Biji</label>
                        <input type="number" name="berat" id="berat" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                    </div>
                    <div class="mb-4">
                        <label for="namaPembeli" class="block text-sm font-medium text-gray-700">Nama Pembeli</label>
                        <input type="text" name="namaPembeli" id="namaPembeli" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                    </div>
                    <button type="submit" name="simpankan" class="block w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">Simpan</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<footer class="footer">
    <p>&copy; 2024 Aman.Store. All rights reserved.</p>
</footer>

</body>
</html>

<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'kasir') {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];
$query1 = "SELECT id FROM users WHERE username='$username'";
$result1 = mysqli_query($koneksi, $query1);
while ($row = mysqli_fetch_array($result1)) {
    $idKasir = $row['id'];
}
 
// Initialize variables
$namaBarang = '';
$harga = 0;
$idBarang = 0;

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bayarkan'])) {
    $namaBarang = $_POST['namaBarang'];
    $harga = $_POST['harga'];
    $idBarang = $_POST['idBarang'];

    $_SESSION['keranjang'][] = [
        'namaBarang' => $namaBarang,
        'harga' => $harga,
        'idBarang' => $idBarang,
        'jumlah' => 1
    ];
}

$totalHarga2 = 0;
foreach ($_SESSION['keranjang'] as $item) {
    $totalHarga2 += $item['harga'] * $item['jumlah']; 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpankan'])) {
    if (empty($_SESSION['keranjang'])) {
        echo "<script>alert('Keranjang kosong. Silakan tambahkan barang terlebih dahulu.');</script>";
    } else {
        $namaPembeli = mysqli_real_escape_string($koneksi, $_POST['namaPembeli']);
        $berat = (int)$_POST['berat'];

        $totalHarga = $_SESSION['totalHarga'] * $berat;

        $now = date('Y-m-d H:i:s');
        $tgl_bln_thn = date('d M Y', strtotime($now));
        
        // Insert into pelanggan table
        $insert_pelanggan = "INSERT INTO pelanggan (namaPelanggan, totalHarga, tanggal) 
                             VALUES ('$namaPembeli', '$totalHarga', '$tgl_bln_thn')";
        if (mysqli_query($koneksi, $insert_pelanggan)) {
            // Get the last inserted id for pelanggan
            $idPelanggan = mysqli_insert_id($koneksi);

            // Insert into penjualan table
            foreach ($_SESSION['keranjang'] as $item) {
                $idBarang = $item['idBarang'];
                $jumlahBarang = $item['jumlah'];
                $insert_penjualan = "INSERT INTO penjualan (idBarang, idKasir, idPelanggan, jumlahBarang) 
                                     VALUES ('$idBarang', '$idKasir', '$idPelanggan', '$jumlahBarang')";
                mysqli_query($koneksi, $insert_penjualan);
            }

            // Clear session setelah transaksi berhasil
            $_SESSION['keranjang'] = [];
            $_SESSION['lastid'] = 0;
            $_SESSION['totalHarga'] = 0;

            echo "<script>alert('Data berhasil disimpan');
            window.location.href = 'index.php';
            </script>";
        } else {
            // Handle error
            echo "Error: " . mysqli_error($koneksi);
        }
        exit;
    }
}

if (isset($_POST['bayarkan'])) {
    $totalHarga = 0;
    $harga1 = 0;
    $namaBarang1 = '';
    foreach ($_SESSION['keranjang'] as $item) {
        $totalHarga = $item['harga'] * $item['jumlah'];
        $harga1 = $item['harga'];
        $namaBarang1 = $item['namaBarang'];
    }
    $_SESSION['totalHarga'] = $totalHarga;
    $_SESSION['namaBarang1'] = $namaBarang1;
    $_SESSION['harga1'] = $harga1;
}

$totalHa = 0;
$berat = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['hitung'])) {
    $berat = (int)$_POST['berat'];

    foreach ($_SESSION['keranjang'] as $item) {
        $totalHa = $item['harga'] * $berat;
    }
}

// Display modal on form submission
$showModal = isset($_POST['bayarkan']) || isset($_POST['hitung']);

// Query to get product data
$result = mysqli_query($koneksi, "SELECT * FROM produk");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aman.Store</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .main-content {
            display: flex;
        }
        .product-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            height: auto;
            flex: 1;
        }
        .product-item {
            height: 100%;
        }
        .cart-container {
            background-color: #FEF3C7;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-left: 5px;
        }
        body {
            background-color: #393646;
        }
        .navi, nav {
            background-color: #1c1917;
        }
        .footer {
            background-color: #1c1917;
            color: #ffffff;
            padding: 20px 0;
            text-align: center;
            width: 100%;
        }
        .footer a {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .footer a:hover {
            color: #ffffff;
            opacity: 0.7;
        }
    </style>
</head>

<body>
<nav class="nav text-white border-gray-200 dark:bg-gray-900 dark:border-gray-700 p-4">
    <div class="navi max-w-screen-xl flex items-center justify-between mx-0">
        <div class="flex items-center space-x-3">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="gambar/shop.jpg" class="h-9 w-10" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">UD.ROTUA</span>
            </a>
        </div>
        <div class="flex items-center ml-auto space-x-2">
            <p class="text-lg">Selamat Datang <?= $username ?>, Anda login sebagai Kasir</p>
            <a href="logout.php" class="bg-yellow-200 rounded px-4 py-2 text-black">LOGOUT</a>
        </div>
    </div>
</nav>

<div class="p-2 w-[1400px] mb-12 h-[500px]">
<?php include'header.php'; ?>
</div>
<div class="main-content p-4 mt-5 mx-auto pl-0 max-w-screen-xl flex flex-col md:flex-row gap-4">
    <div class="product-container w-[400px] rounded h-auto">
        <?php while ($row = mysqli_fetch_array($result)): ?>
            <div class="product-item flex flex-col justify-center items-center w-full h-80 rounded-lg bg-gray-300 py-1">
                <div class="flex h-40 w-full justify-center items-center p-1">
                    <img src="images/<?php echo $row['foto']; ?>" style="max-width: 100%; max-height: 100%;border-radius: 10px;" alt="<?php echo $row['namaBarang']; ?>">
                </div>
                <h2 class="text-2xl mb-3 font-bold"><?= $row['namaBarang'] ?></h2>
                <div class="h-25 mb-1">
                    <p class="text-sm"><?= $row['deskripsi'] ?></p>
                </div>
                <strong><p class="text-sm">Rp<?= number_format($row['harga'], 0, ',', '.') ?>,00/(KG/Biji)</p></strong> 
                <form method="POST" action="#barang">
                    <input type="hidden" name="namaBarang" value="<?= $row['namaBarang'] ?>">
                    <input type="hidden" name="harga" value="<?= $row['harga'] ?>">
                    <input type="hidden" name="idBarang" value="<?= $row['idBarang'] ?>">
                    <button type="submit" name="bayarkan" class="block bg-green-300 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center border-black">Terima Barang</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<br>
<footer class="footer">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <p class="text-center sm:text-left">Jl. Ampera No. 12, Medan</p>
        <div class="flex space-x-4">
            <a href="https://www.instagram.com/manhagai_htb" target="_blank" class="text-gray-300 hover:text-gray-400 transition duration-300">
                <span class="">Instagram</span>
                <img src="gambar/ig3.png" class="h-6 w-6 mx-10" fill="currentColor" viewBox="0 0 24 24"></img>
            </a>
            <a href="https://github.com/amanhutabarat17" target="" class="text-white hover:text-gray-400 transition duration-300">
                <span class="">amanhutabarat17</span>
                <img src="gambar/github-mark-white.png" class="h-6 w-6 mx-10" fill="currentColor" viewBox="0 0 24 24"></img>
            </a>
        </div>
    </div>
</footer>

<?php if ($showModal): ?>
<div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
        <h2 class="text-2xl font-bold mb-4">Data Penjualan</h2>
        <form method="POST" action="">
            <table class="w-full mb-4">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Nama Barang</th>
                        <th class="border px-4 py-2">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border px-4 py-2"><?= $_SESSION['namaBarang1'] ?></td>
                        <td class="border px-4 py-2 font-bold">Rp<?= number_format($_SESSION['harga1'], 0, ',', '.') ?>,00/KG/BIJI</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>Total</td>
                        <td class="border px-4 py-2 font-bold"><?=$_SESSION['totalHarga'] * $berat ?></td>
                    </tr>
                </tfoot>
            </table>
            <div class="flex justify-start items-center gap-3 mb-4">
                <label for="namaPembeli">Nama Petani</label>
                <input type="text" name="namaPembeli" value="<?= isset($_POST['namaPembeli']) ? $_POST['namaPembeli'] : '' ?>" id="namaPembeli" class="px-4 py-2 rounded-lg w-40">
            </div>
            <div class="flex justify-start items-center gap-3 mb-4">
                <label for="berat">Berat/Jumlah Barang</label>
                <input type="text" name="berat" id="berat" class="px-4 py-2 rounded-lg w-40" value="<?= isset($_POST['berat']) ? $_POST['berat'] : '' ?>">
                <button type="submit" name="hitung" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Hitung</button>
            </div>
            <div class="flex justify-end">
                
                <button type="submit" name="simpankan" onclick="window.open('cetak_struk.php', '_blank')"<?php mysqli_close($koneksi); ?> class="bg-blue-500 text-white px-4 py-2 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
</body>

</html>

