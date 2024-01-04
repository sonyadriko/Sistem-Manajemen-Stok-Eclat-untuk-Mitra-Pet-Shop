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



    echo '</br>';
    echo '<h3>Itemset Vertikal</h3>';

    echo '<div class="table-responsive">';
    echo '<table class="table mb-0 table-bordered">';
    // Header tabel
    echo '<tr><td>NO</td><td>Item</td><td>Tid List</td><td>Frequent pattern</td><td>Support</td></tr>';

    $no = 1;

    // Isi tabel
    foreach ($uniqueBarang as $kodeBarang) {
        $tidList = array();
        $frequentPattern = 0;

        foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
            if (in_array($kodeBarang, $barangTransaksi)) {
                $tidList[] = $idTransaksi;
                $frequentPattern++;
            }
        }

        $support = $frequentPattern / count($transaksiGrouped);

        echo "<tr><td>$no</td><td>$kodeBarang</td><td>" . implode(', ', $tidList) . "</td><td>$frequentPattern</td><td>$support</td></tr>";
        $no++;
    }

    echo '</table>';
    echo '</div>';

    echo '</br>';

        // Mengelompokkan data transaksi berdasarkan kode barang
    // Menghitung Frequent Pattern
    // Mengganti kunci dengan indeks numerik
    $minFrequentCount = 1; // Ganti dengan nilai minimum frekuensi yang diinginkan
    $frequentPatterns = [];

    foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
        foreach ($barangTransaksi as $kodeBarang) {
            $count = array_count_values($barangTransaksi)[$kodeBarang] ?? 0;

            if ($count >= $minFrequentCount) {
                $found = false;
                foreach ($frequentPatterns as &$pattern) {
                    if ($pattern['Item'] == $kodeBarang) {
                        $found = true;
                        $pattern['TidList'][] = $idTransaksi;
                        $pattern['FrequentCount'] += $count;
                        break;
                    }
                }

                if (!$found) {
                    $frequentPatterns[] = [
                        'Item' => $kodeBarang,
                        'TidList' => [$idTransaksi],
                        'FrequentCount' => $count,
                        'Support' => 0,
                    ];
                }
            }
        }
    }

    // Menghitung dan menyimpan Support untuk setiap pattern
    $totalTransaksi = count($transaksiGrouped);
    foreach ($frequentPatterns as &$pattern) {
        $pattern['Support'] = ($pattern['FrequentCount'] / $totalTransaksi) * 100;
    }

    // Urutkan frequentPatterns berdasarkan Item
    usort($frequentPatterns, function ($a, $b) {
        return strcmp($a['Item'], $b['Item']);
    });

    // $sql = "SELECT barang, transaksi FROM your_table_name"; // Replace with your actual table name
    // $result = $koneksi->query($sql);
    $result = $link->query("SELECT * FROM transaksi");
    $transactions = array();

    while ($row = $result->fetch_assoc()) {
        $idTransaksi = $row['id_transaksi'];
        $kodeBarang = $row['kode_barang'];

        // Menambahkan barang ke transaksi
        if (!isset($transactions[$idTransaksi])) {
            $transactions[$idTransaksi] = array();
        }

        $transactions[$idTransaksi][] = $kodeBarang;
    }

    echo '</br>';
    echo '<h3>Penyilangan Barang</h3>';

    echo '<div class="table-responsive">';
    echo '<table class="table mb-0 table-bordered">';
    echo '<tr><td>No</td><td>Pasangan Barang</td><td>Transaksi</td></tr>';

    $no = 1;

    // Menghasilkan semua pasangan barang
    $allPairs = array();
    $uniqueBarangCount = count($uniqueBarang);

    foreach ($uniqueBarang as $i => $barangA) {
        for ($j = $i + 1; $j < $uniqueBarangCount; $j++) {
            // Check if array keys exist before accessing
            if (isset($uniqueBarang[$i], $uniqueBarang[$j])) {
                $barangB = $uniqueBarang[$j];
                $allPairs[] = array('barangA' => $barangA, 'barangB' => $barangB);
            }
        }
    }

    foreach ($allPairs as $pair) {
        $barangA = $pair['barangA'];
        $barangB = $pair['barangB'];

        // Menyimpan id transaksi yang mengandung kedua barang
        $transaksiMengandungKeduaBarang = array();
        foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
            if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi)) {
                $transaksiMengandungKeduaBarang[] = $idTransaksi;
            }
        }

        // Menampilkan hasil
        $transaksiStr = implode('-', $transaksiMengandungKeduaBarang);
        $pasanganBarang = "($barangA) - ($barangB)";
        echo "<tr><td>$no</td><td>$pasanganBarang</td><td>($transaksiStr)</td></tr>";
        $no++;
    }

    echo '</table>';
    echo '</div>';

    echo '</br>';



    echo '</br>';

    echo '<table class="table mb-0 table-bordered">';
    echo '<tr><td>No</td><td>Itemset</td><td>Transaksi</td><td>Frequent Pattern</td><td>Support</td></tr>';

    $no = 1;
    $minSupport = 2; // Ganti dengan ambang batas support yang diinginkan

    foreach ($allPairs as $pair) {
        $barangA = $pair['barangA'];
        $barangB = $pair['barangB'];

        // Menyimpan id transaksi yang mengandung kedua barang
        $transaksiMengandungKeduaBarang = array();
        foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
            if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi)) {
                $transaksiMengandungKeduaBarang[] = $idTransaksi;
            }
        }

        // Filter untuk transaksi dengan 2 nilai
        if (count($transaksiMengandungKeduaBarang) >= $minSupport) {
            // Hitung support
            $supportAB = count($transaksiMengandungKeduaBarang) / count($transaksiGrouped);

            // Menampilkan hasil
            $transaksiStr = implode('-', $transaksiMengandungKeduaBarang);
            $itemset = "($barangA) - ($barangB)";
            $jumlahTransaksi = count($transaksiMengandungKeduaBarang);

            echo "<tr><td>$no</td><td>$itemset</td><td>$transaksiStr</td><td>$jumlahTransaksi</td><td>$supportAB</td></tr>";
            $no++;
        }
    }
    
    echo '</table>';


// Fungsi untuk menghitung support
    function calculateSupport($barangA, $barangB, $transaksiGrouped)
    {
        $countAB = 0;
        $countA = 0;

        foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
            if ($barangA !== null && $barangB !== null) {
                if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi)) {
                    $countAB++;
                }
            } elseif ($barangA !== null) {
                if (in_array($barangA, $barangTransaksi)) {
                    $countA++;
                }
            } elseif ($barangB !== null) {
                if (in_array($barangB, $barangTransaksi)) {
                    $countAB++;
                }
            }
        }

        return $countAB;
    }


    echo '</br>';
    echo '<h3>Aturan Asosiasi</h3>';

    echo '<div class="table-responsive">';
    echo '<table id="associationRules" class="table mb-0 table-bordered">';
    echo '<thead><tr><td>No</td><td>Rule</td><td>Support</td><td>Confidence</td></tr></thead>';
    echo '<tbody>';

    $no = 1;
    // $minSupport = 2; // Ganti dengan ambang batas support yang diinginkan

    // Simpan hasil aturan asosiasi dalam array
    $associationRulesAtoB = array();
    $associationRulesBtoA = array();

   // ...

foreach ($allPairs as $pair) {
    $barangA = $pair['barangA'];
    $barangB = $pair['barangB'];

    // Menyimpan id transaksi yang mengandung kedua barang
    $transaksiMengandungKeduaBarang = array();
    foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
        if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi)) {
            $transaksiMengandungKeduaBarang[] = $idTransaksi;
        }
    }

    // Filter untuk transaksi dengan 2 nilai
    if (count($transaksiMengandungKeduaBarang) >= $minSupport) {
        // Hitung support
        $supportAB = count($transaksiMengandungKeduaBarang) / count($transaksiGrouped);

        // Hitung frequent pattern untuk setiap barang
        $frequentPatternA = count(array_filter($transaksiGrouped, function ($transaksi) use ($barangA) {
            return in_array($barangA, $transaksi);
        }));

        $frequentPatternB = count(array_filter($transaksiGrouped, function ($transaksi) use ($barangB) {
            return in_array($barangB, $transaksi);
        }));

        // Hitung confidence
        $confidenceAtoB = ($frequentPatternA != 0) ? ($supportAB / $frequentPatternA) : 0;
        $confidenceBtoA = ($frequentPatternB != 0) ? ($supportAB / $frequentPatternB) : 0;

        // Tambahkan aturan ke array
        $associationRulesAtoB[] = array(
            'Rule' => "Jika konsumen membeli $barangA maka membeli $barangB",
            'Support' => $supportAB,
            'Confidence' => $confidenceAtoB,
        );

        $associationRulesBtoA[] = array(
            'Rule' => "Jika konsumen membeli $barangB maka membeli $barangA",
            'Support' => $supportAB,
            'Confidence' => $confidenceBtoA,
        );

        // Menampilkan hasil
        echo "<tr><td>$no</td><td>Jika konsumen membeli $barangA maka membeli $barangB</td><td>$supportAB</td><td>$confidenceAtoB</td></tr>";
        echo "<tr><td></td><td>Jika konsumen membeli $barangB maka membeli $barangA</td><td>$supportAB</td><td>$confidenceBtoA</td></tr>";

        $no++;
    }
}

// ...


    echo '</tbody>';
    echo '</table>';
    echo '</div>';
  

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
        $barang[$row['nama_barang']] = $row['kode_barang'];
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

