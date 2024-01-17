<?php

include 'connection.php';
session_start();
 if (!isset($_SESSION['id_user'])) {
     header("Location: login.php");
 }
include 'layouts/head-main.php';
include 'layouts/config.php';
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
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
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

                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // Tangkap nilai minimum support dan confidence dari form
                    $minSupport = isset($_POST['support']) ? intval($_POST['support']) : $minSupport;
                    $minConfidence = isset($_POST['confidence']) ? intval($_POST['confidence']) : $minConfidence;

                    // Panggil fungsi untuk menghitung Eclat
                    calculateEclat($minSupport, $minConfidence);
                }

                function displayResult($result)
                {
                    global $link;

                    if ($link === null) {
                        die("Database connection is not established");
                    }

                    if (empty($result['itemsetHorizontal'])) {
                        echo 'No results found.';
                        return;
                    }

                    $transaksiQuery = $link->query("SELECT * FROM transaksi");
                    $transaksiData = [];

                    while ($row = $transaksiQuery->fetch_assoc()) {
                        $transaksiData[] = $row;
                    }

                    $transaksi = [];
                    $transaksiGrouped = [];

                    foreach ($transaksiData as $item) {
                        $transaksiGrouped[$item['id_transaksi']][] = $item['kode_barang'];
                    }

                    $uniqueBarang = array_unique(array_column($transaksiData, 'kode_barang'));

                    echo '</br>';
                    echo '<h3>Itemset Vertikal</h3>';

                    echo '<div class="table-responsive">';
                    echo '<table class="table mb-0 table-bordered">';
                    echo '<tr><td>NO</td><td>Item</td><td>Tid List</td><td>Frequent pattern</td><td>Support</td></tr>';

                    $no = 1;

                    foreach ($uniqueBarang as $kodeBarang) {
                        $tidList = [];
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

                    $minFrequentCount = 1;
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

                    $totalTransaksi = count($transaksiGrouped);

                    foreach ($frequentPatterns as &$pattern) {
                        $pattern['Support'] = ($pattern['FrequentCount'] / $totalTransaksi) * 100;
                    }

                    usort($frequentPatterns, function ($a, $b) {
                        return strcmp($a['Item'], $b['Item']);
                    });

                    $result = $link->query("SELECT * FROM transaksi");
                    $transactions = [];

                    while ($row = $result->fetch_assoc()) {
                        $idTransaksi = $row['id_transaksi'];
                        $kodeBarang = $row['kode_barang'];

                        if (!isset($transactions[$idTransaksi])) {
                            $transactions[$idTransaksi] = [];
                        }

                        $transactions[$idTransaksi][] = $kodeBarang;
                    }

                    echo '</br>';
                    echo '<h3>Penyilangan Barang</h3>';

                    echo '<div class="table-responsive">';
                    echo '<table class="table mb-0 table-bordered">';
                    echo '<tr><td>No</td><td>Pasangan Barang</td><td>Transaksi</td></tr>';

                    $no = 1;
                    $allPairs = [];

                    foreach ($uniqueBarang as $i => $barangA) {
                        for ($j = $i + 1; $j < count($uniqueBarang); $j++) {
                            if (isset($uniqueBarang[$i], $uniqueBarang[$j])) {
                                $barangB = $uniqueBarang[$j];
                                $allPairs[] = ['barangA' => $barangA, 'barangB' => $barangB];
                            }
                        }
                    }

                    foreach ($allPairs as $pair) {
                        $barangA = $pair['barangA'];
                        $barangB = $pair['barangB'];

                        $transaksiMengandungKeduaBarang = [];

                        foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
                            if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi)) {
                                $transaksiMengandungKeduaBarang[] = $idTransaksi;
                            }
                        }

                        $transaksiStr = implode('-', $transaksiMengandungKeduaBarang);
                        $pasanganBarang = "($barangA) - ($barangB)";
                        echo "<tr><td>$no</td><td>$pasanganBarang</td><td>($transaksiStr)</td></tr>";
                        $no++;
                    }

                    echo '</table>';
                    echo '</div>';

                    echo '</br>';
                    echo '<table class="table mb-0 table-bordered">';
                    echo '<tr><td>No</td><td>Itemset</td><td>Transaksi</td><td>Frequent Pattern</td><td>Support</td></tr>';

                    $no = 1;

                    foreach ($allPairs as $pair) {
                        $barangA = $pair['barangA'];
                        $barangB = $pair['barangB'];
                        $transaksiMengandungKeduaBarang = [];

                        foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
                            if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi)) {
                                $transaksiMengandungKeduaBarang[] = $idTransaksi;
                            }
                        }

                        if (count($transaksiMengandungKeduaBarang) >= $minSupport) {
                            $supportAB = count($transaksiMengandungKeduaBarang) / count($transaksiGrouped);
                            $transaksiStr = implode('-', $transaksiMengandungKeduaBarang);
                            $itemset = "($barangA) - ($barangB)";
                            $jumlahTransaksi = count($transaksiMengandungKeduaBarang);

                            echo "<tr><td>$no</td><td>$itemset</td><td>$transaksiStr</td><td>$jumlahTransaksi</td><td>$supportAB</td></tr>";
                            $no++;
                        }
                    }

                    echo '</table>';

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
                    $associationRulesAtoB = [];
                    $associationRulesBtoA = [];

                    foreach ($allPairs as $pair) {
                        $barangA = $pair['barangA'];
                        $barangB = $pair['barangB'];
                        $transaksiMengandungKeduaBarang = [];

                        foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
                            if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi)) {
                                $transaksiMengandungKeduaBarang[] = $idTransaksi;
                            }
                        }

                        if (count($transaksiMengandungKeduaBarang) >= $minSupport) {
                            $supportAB = count($transaksiMengandungKeduaBarang) / count($transaksiGrouped);
                            $supportA = count($transaksiGrouped[$transaksiMengandungKeduaBarang[0]]);
                            $confidence = ($supportA != 0) ? ($supportAB / $supportA) : 0;

                            $associationRulesAtoB[] = [
                                'Rule' => "Jika konsumen membeli $barangA maka membeli $barangB",
                                'Support' => $supportAB,
                                'Confidence' => $confidence,
                            ];

                            $associationRulesBtoA[] = [
                                'Rule' => "Jika konsumen membeli $barangB maka membeli $barangA",
                                'Support' => $supportAB,
                                'Confidence' => $confidence,
                            ];

                            echo "<tr><td>$no</td><td>Jika konsumen membeli $barangA maka membeli $barangB</td><td>$supportAB</td><td>$confidence</td></tr>";
                            echo "<tr><td></td><td>Jika konsumen membeli $barangB maka membeli $barangA</td><td>$supportAB</td><td>$confidence</td></tr>";

                            $no++;
                        }
                    }

                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                }



                function calculateEclat($minSupport, $minConfidence)
                {
                    global $link;

                    if ($link === null) {
                        die("Database connection is not established");
                    }

                    $transaksiQuery = $link->query("SELECT * FROM transaksi");
                    $transaksi = [];
                    while ($row = $transaksiQuery->fetch_assoc()) {
                        $transaksi[$row['id_transaksi']][] = $row['kode_barang'];
                    }

                    $barangQuery = $link->query("SELECT * FROM barang");
                    $barang = [];
                    while ($row = $barangQuery->fetch_assoc()) {
                        $barang[$row['nama_barang']] = $row['kode_barang'];
                    }

                    if (empty($transaksi) || empty($barang)) {
                        die("No data found in transaksi or barang table.");
                    }

                    $result = performEclatCalculation($transaksi, $minSupport, $barang);

                    displayResult($result);
                }

                function performEclatCalculation($transaksi, $minSupport, $barang)
                {
                    $result = [
                        'transaksi' => $transaksi,
                        'itemsetHorizontal' => [],
                        'itemsetVertical' => [],
                        'intersection' => [],
                        'support' => [],
                        'associationRule' => []
                    ];

                    // ...

                    return $result;
                }

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

            </div>
        </div>

        <?php include 'layouts/footer.php'; ?>
    </div>

    <?php include 'layouts/vendor-scripts.php'; ?>

    <script src="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/dashboard.init.js"></script>
    <script src="assets/js/app.js"></script>

</body>

</html>
