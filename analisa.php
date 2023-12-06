<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/config.php'; ?>

<?php
require 'vendor/autoload.php';

?>

<head>
    <title><?php echo $language["Dashboard"]; ?> | Minia - Admin & Dashboard Template</title>

    <?php include 'layouts/head.php'; ?>

    <link href="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />

    <?php include 'layouts/head-style.php'; ?>
</head>

<?php include 'layouts/body.php'; ?>

<!-- Begin page -->
<div id="layout-wrapper">
    <?php include 'layouts/menu.php'; ?>
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Analisa Algoritma Eclat</h4>
                        </div>
                    </div>
                </div>
                <form id="eclatForm" action="" method="POST">
                    <div class="row mb-4">
                        <label for="support" class="col-sm-3 col-form-label">Min Support</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="support" name="support" placeholder="Enter Min Support">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="confidence" class="col-sm-3 col-form-label">Min Confidence</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="confidence" name="confidence" placeholder="Enter Min Confidence">
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-9">
                            <div>
                                <button type="submit" class="btn btn-primary w-md">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- Display result table here -->
                <div id="resultTable"></div>
                <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Tangkap nilai minimum support dan confidence dari form
    $minSupport = $_POST['support'];
    $minConfidence = $_POST['confidence'];

    // Panggil fungsi untuk menghitung Eclat
    calculateEclat($minSupport, $minConfidence);
}

// Fungsi untuk menampilkan hasil Eclat
// function displayResult($result)
// {
//     // Cek apakah ada data hasil Eclat
//     if (empty($result['itemsetHorizontal'])) {
//         echo 'No results found.';
//         return;
//     }

//     // Tampilkan tabel untuk hasil Eclat
//     echo '<table border="1">';
//     echo '<tr><th>Horizontal</th><th>Itemset Vertical</th><th>Intersection</th><th>Support</th><th>Association Rule</th></tr>';

//     // Iterasi melalui hasil Eclat dan tampilkan dalam tabel
//     foreach ($result['itemsetHorizontal'] as $key => $horizontal) {
//         $vertical = $result['itemsetVertical'][$key];
//         $intersection = implode(', ', $result['intersection'][$key]);
//         $support = $result['support'][$key];
//         $associationRule = implode(', ', $result['associationRule'][$key]);

//         echo "<tr><td>$horizontal</td><td>$vertical</td><td>$intersection</td><td>$support</td><td>$associationRule</td></tr>";
//     }

//     echo '</table>';
// }

function displayResult($result)
{
    global $link; // Memastikan variabel global $link dapat digunakan di dalam fungsi

    // Periksa apakah koneksi database sudah ada atau belum
    if ($link === null) {
        die("Database connection is not established");
    }
    // Cek apakah ada data hasil Eclat
    if (empty($result['itemsetHorizontal'])) {
        echo 'No results found.';
        return;
    }
    
    // Data transaksi dari database
    $transaksiQuery = $link->query("SELECT * FROM transaksi");
    $transaksiData = [];
    while ($row = $transaksiQuery->fetch_assoc()) {
        $transaksiData[] = $row;
    }

    $transaksi = []; 
    // Mengelompokkan data transaksi berdasarkan id_transaksi
    $transaksiGrouped = [];
    foreach ($transaksiData as $item) {
        $transaksiGrouped[$item['id_transaksi']][] = $item['kode_barang'];
    }

    // Mengumpulkan semua kode barang yang unik
    $uniqueBarang = array_unique(array_column($transaksiData, 'kode_barang'));

    // Membuat tabel
    echo '<h3>Itemset Horizontal</h3>';

    echo '<div class="table-responsive">';
    echo '<table class="table mb-0 table-bordered">';
    // Header tabel
    echo '<tr><td>Transaksi</td>';
    foreach ($uniqueBarang as $kodeBarang) {
        echo "<td><b>$kodeBarang</b></td>";
    }
    echo '</tr>';

    // Isi tabel
    foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
        echo "<tr><td>$idTransaksi</td>";
        foreach ($uniqueBarang as $kodeBarang) {
            $cellValue = in_array($kodeBarang, $barangTransaksi) ? '✔' : '';
            echo "<td>$cellValue</td>";
        }
        echo '</tr>';
    }
    echo '</table>';
    echo '</div>';



    // Tampilkan tabel untuk hasil Eclat
    // Membuat tabel
    echo '</br>';
    echo '<h3>Itemset Vertikal</h3>';

    echo '<div class="table-responsive">';
    echo '<table class="table mb-0 table-bordered">';
    // Header tabel
    echo '<tr><td>Barang</td>';
    foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
        echo "<td>$idTransaksi</td>";
    }
    echo '</tr>';

    // Isi tabel
    foreach ($uniqueBarang as $kodeBarang) {
        echo "<tr><td>$kodeBarang</td>";
        foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
            $cellValue = in_array($kodeBarang, $barangTransaksi) ? '✔' : '';
            echo "<td>$cellValue</td>";
        }
        echo '</tr>';
    }
    echo '</table>';
    echo '</div>';


    // Tampilkan tabel untuk hasil Eclat
    echo '</br>';

    // Mengelompokkan data transaksi berdasarkan kode barang
// Menghitung Frequent Pattern
$minFrequentCount = 1; // Ganti dengan nilai minimum frekuensi yang diinginkan
$frequentPatterns = [];

foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
    foreach ($uniqueBarang as $kodeBarang) {
        $count = array_count_values($barangTransaksi)[$kodeBarang] ?? 0;

        if ($count >= $minFrequentCount) {
            if (!isset($frequentPatterns[$kodeBarang])) {
                $frequentPatterns[$kodeBarang] = [
                    'TidList' => [],
                    'FrequentCount' => 0,
                    'Support' => 0, // Menambah kolom Support
                ];
            }

            $frequentPatterns[$kodeBarang]['TidList'][] = $idTransaksi;
            $frequentPatterns[$kodeBarang]['FrequentCount'] += $count;
        }
    }
}

// Menghitung dan menyimpan Support untuk setiap pattern
$totalTransaksi = count($transaksiGrouped);
foreach ($frequentPatterns as &$pattern) {
    $pattern['Support'] = ($pattern['FrequentCount'] / $totalTransaksi) * 100;
}

// Menampilkan Frequent Pattern dengan tambahan kolom Support
echo '<h3>Frequent Pattern</h3>';
echo '<div class="table-responsive">';
echo '<table class="table mb-0" border="2">';
echo '<tr><th>Item</th><th>Tid List</th><th>Frequent pattern</th><th>Support</th></tr>';

foreach ($frequentPatterns as $kodeBarang => $pattern) {
    $tidList = implode(', ', $pattern['TidList']);
    $frequentCount = $pattern['FrequentCount'];
    $support = round($pattern['Support'], 2); // Pembulatan ke 2 desimal

    echo "<tr><td>$kodeBarang</td><td>$tidList</td><td>$frequentCount</td><td>$support%</td></tr>";
}

echo '</table>';
echo '</div>';

echo '</br>';
$minFrequentCount = 3; // Ganti dengan nilai minimum frekuensi yang diinginkan

$frequent1Itemsets = [];

foreach ($frequentPatterns as $kodeBarang => $pattern) {
    if ($pattern['FrequentCount'] >= $minFrequentCount) {
        $frequent1Itemsets[$kodeBarang] = $pattern;
    }
}

// Menampilkan Frequent 1-Itemsets
echo '<h3>frequent 1-itemsets dg minsup</h3>';
echo '<div class="table-responsive">';
echo '<table class="table mb-0" border="2">';
echo '<tr><th>Item</th><th>Tid List</th><th>Frequent pattern</th><th>Support</th></tr>';

foreach ($frequent1Itemsets as $kodeBarang => $pattern) {
    $tidList = implode(', ', $pattern['TidList']);
    $frequentCount = $pattern['FrequentCount'];
    $support = round($pattern['Support'], 2); // Pembulatan ke 2 desimal

    echo "<tr><td>$kodeBarang</td><td>$tidList</td><td>$frequentCount</td><td>$support%</td></tr>";
}

echo '</table>';
echo '</div>';

echo '</br>';

    // Membuat tabel
// echo '<h3>Penyilangan Itemset</h3>';

// echo '<div class="table-responsive">';
// echo '<table class="table mb-0" border="2">';
// // Header tabel
// echo '<tr><td>No</td><td>Barang</td><td>Transaksi</td></tr>';

// // Inisialisasi nomor urut
// $nomorUrut = 1;

// // Isi tabel
// foreach ($result['itemsetHorizontal'] as $key => $horizontal) {
//     $vertical = $result['itemsetVertical'][$key];
//     $intersection = implode('-', $result['intersection'][$key]);

//     if ($intersection !== '') {
//         echo "<tr><td>$nomorUrut</td><td>($horizontal)</td><td>($intersection)</td></tr>";
//         $nomorUrut++;
//     }
// }
// echo '</table>';
// echo '</div>';


    // ... Lanjutkan dengan menampilkan tabel untuk Support dan Association Rule

        // ... (Kode sebelumnya)

    // Tampilkan tabel untuk hasil Eclat
    echo '</br>';

        // Membuat tabel
    // echo '<h3>Itemset Support</h3>';

    // echo '<div class="table-responsive">';
    // echo '<table class="table mb-0" border="2">';
    // // Header tabel
    // echo '<tr><td>No</td><td>Barang</td><td>Transaksi</td><td>Support</td><td>Confidence</td></tr>';

    // // Inisialisasi nomor urut
    // $nomorUrut = 1;

    // // Isi tabel
    // foreach ($result['itemsetHorizontal'] as $key => $horizontal) {
    //     $vertical = $result['itemsetVertical'][$key];
    //     $intersection = implode('-', $result['intersection'][$key]);
    //     $support = number_format($result['support'][$key], 2);
    //     $confidence = number_format($result['associationRule'][$key][2], 2);

    //     if ($intersection !== '') {
    //         echo "<tr><td>$nomorUrut</td><td>($horizontal)</td><td>($intersection)</td><td>$support</td><td>$confidence</td></tr>";
    //         $nomorUrut++;
    //     }
    // }
    // echo '</table>';
    // echo '</div>';


    // Tampilkan tabel untuk hasil Eclat
    // echo '<h3>Association Rule</h3>';
    // echo '<table border="1">';
    // echo '<tr><th>NO</th><th>RULE</th><th>SUPPORT</th><th>CONFIDENCE</th></tr>';
    // for ($i = 0; $i < count($result['associationRule']); $i++) {
    //     $rule = implode(' dan ', $result['associationRule'][$i][0]);
    //     $support = $result['associationRule'][$i][1];
    //     $confidence = $result['associationRule'][$i][2];
    //     echo "<tr><td>" . ($i + 1) . "</td><td>Jika konsumen membeli $rule maka membeli</td><td>$support</td><td>$confidence</td></tr>";
    // }
    // echo '</table>';

    // ... (Kode setelahnya)


    // ...
}


// Fungsi untuk melakukan perhitungan Eclat
function calculateEclat($minSupport, $minConfidence)
{
    global $link; // Memastikan variabel global $link dapat digunakan di dalam fungsi

    // Periksa apakah koneksi database sudah ada atau belum
    if ($link === null) {
        die("Database connection is not established");
    }

    // Ambil data transaksi dari tabel transaksi
    $transaksiQuery = $link->query("SELECT * FROM transaksi");
    $transaksi = [];
    while ($row = $transaksiQuery->fetch_assoc()) {
        $transaksi[$row['id_transaksi']][] = $row['kode_barang'];
    }

    // Ambil data barang dari tabel barang
    $barangQuery = $link->query("SELECT * FROM barang");
    $barang = [];
    while ($row = $barangQuery->fetch_assoc()) {
        $barang[$row['kode_barang']] = $row['nama_barang'];
    }

    // Cek apakah ada data transaksi dan barang
    if (empty($transaksi) || empty($barang)) {
        die("No data found in transaksi or barang table.");
    }
    // Hitung Eclat
    $result = performEclatCalculation($transaksi, $minSupport, $barang);

    // Tampilkan hasil
    displayResult($result);
}

// Fungsi untuk melakukan perhitungan Eclat
function performEclatCalculation($transaksi, $minSupport, $barang)
{
    $result = [
        'transaksi' => $transaksi,  // Ta
        'itemsetHorizontal' => [],
        'itemsetVertical' => [],
        'intersection' => [],
        'support' => [],
        'associationRule' => []
    ];

    // Hitung support untuk setiap itemset
    $supportCount = [];
    foreach ($transaksi as $transaction) {
        foreach ($transaction as $item) {
            if (!isset($supportCount[$item])) {
                $supportCount[$item] = 0;
            }
            $supportCount[$item]++;
        }
    }

    // Filter itemset yang memenuhi minSupport
    $frequentItemset = array_filter($supportCount, function ($count) use ($minSupport) {
        return $count >= $minSupport;
    });

    // Ambil itemset yang memenuhi minSupport
    $frequentItemset = array_keys($frequentItemset);

    // Buat kombinasi itemset untuk mencari itemset yang sering muncul bersama
    $combinations = generateCombinations($frequentItemset, 2);

    // ...

    foreach ($combinations as $combination) {
        $itemset1 = trim($combination[0]);
        $itemset2 = trim($combination[1]);

        // Hitung support untuk penyilangan itemset
        $intersectionCount = 0;
        foreach ($transaksi as $transaction) {
            if (in_array($itemset1, $transaction) && in_array($itemset2, $transaction)) {
                $intersectionCount++;
            }
        }

        // Filter itemset penyilangan yang memenuhi minSupport
        if ($intersectionCount >= $minSupport) {
            $result['itemsetHorizontal'][] = $barang[$itemset1];
            $result['itemsetVertical'][] = $barang[$itemset2];
            $result['intersection'][] = [$barang[$itemset1], $barang[$itemset2]];
            $result['support'][] = $intersectionCount / count($transaksi);
            $result['associationRule'][] = [
                $barang[$itemset1],
                $barang[$itemset2],
                $intersectionCount / $supportCount[$itemset1]
            ];
        }
    }

    return $result;
}

// Fungsi untuk menghasilkan kombinasi dari sebuah array
function generateCombinations($items, $length)
{
    $result = [];

    if ($length == 1) {
        return array_map(function ($item) {
            return [$item];
        }, $items);
    }

    foreach ($items as $key => $item) {
        $rest = array_slice($items, $key + 1);
        $combinations = generateCombinations($rest, $length - 1);
        foreach ($combinations as $combination) {
            $result[] = array_merge([$item], $combination);
        }
    }

    return $result;
}
?>


            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <?php include 'layouts/footer.php'; ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<!-- JAVASCRIPT -->
<?php include 'layouts/vendor-scripts.php'; ?>

<!-- Plugins js-->
<script src="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>



<!-- JAVASCRIPT -->
<script src="assets/libs/jquery/jquery.min.js"></script>
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/metismenu/metisMenu.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/node-waves/waves.min.js"></script>
<script src="assets/libs/feather-icons/feather.min.js"></script>


<!-- dashboard init -->
<script src="assets/js/pages/dashboard.init.js"></script>

<!-- App js -->
<script src="assets/js/app.js"></script>

</body>

</html>
