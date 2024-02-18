<?php 
    include 'connection.php'; 
    session_start(); 
    set_time_limit(300);
    if (!isset($_SESSION['id_user'])) { 
        header("Location: login.php"); 
    }

?>
<?php include 'layouts/head-main.php'; ?> <?php include 'layouts/config.php'; ?> <?php require 'vendor/autoload.php'; ?>
<head>
    <title>Analisa</title>
    <?php include 'layouts/head.php'; ?>
    <link href="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <?php include 'layouts/head-style.php'; ?>
</head>
<?php include 'layouts/body.php'; ?>
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
                        <label for="support" class="col-sm-3 col-form-label">Min Support %</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="support" name="support" placeholder="Enter Min Support">
                        </div>
                    </div>
                    <!-- <div class="row mb-4">
                        <label for="confidence" class="col-sm-3 col-form-label">Min Confidence %</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="confidence" name="confidence" placeholder="Enter Min Confidence">
                        </div>
                    </div> -->
                    <div class="row justify-content-end">
                        <div class="col-sm-9">
                            <div>
                                <button type="submit" class="btn btn-primary w-md">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="resultTable"></div>
                <?php
                // if (isset($_POST['support']) && isset($_POST['confidence'])) {
                if (isset($_POST['support'])) {
                    $minSupportPercentage = $_POST['support'];
                    // $minConfidencePercentage = $_POST['confidence'];
                    $minConfidencePercentage = 0;
                    $maxPercentage = 100; 
                    $minSupport = ($minSupportPercentage / $maxPercentage);
                    $minConfidence = ($minConfidencePercentage / $maxPercentage);
                   
                    calculateEclat($minSupport, $minConfidence);
                    global $minSupport;
                }
function displayResult($result)
{
    global $link, $minSupport;
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
    $transaksiGrouped = [];
    foreach ($transaksiData as $item) {
        $transaksiGrouped[$item['id_transaksi']][] = $item['kode_barang'];
    }
    $uniqueBarang = array_unique(array_column($transaksiData, 'kode_barang'));
    echo '</br>';
    echo '<h3>frequent 1-itemsets</h3>';
    echo '<div class="table-responsive">';
    echo '<table class="table mb-0 table-bordered">';
    echo '<tr><td>NO</td><td>Item</td><td>Tid List</td><td>Frequent pattern</td><td>Support</td></tr>';
    $no = 1;
    sort($uniqueBarang);
    foreach ($uniqueBarang as $kodeBarang) {
        $tidList = array();
        $frequentPattern = 0;
    
        foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
            if (in_array($kodeBarang, $barangTransaksi)) {
                $tidList[] = $idTransaksi;
                $frequentPattern++;
            }
        }
    
        $support2 = $frequentPattern / count($transaksiGrouped);
        $support = $support2;
    
        echo "<tr><td>$no</td><td>$kodeBarang</td><td>" . implode(', ', $tidList) . "</td><td>$frequentPattern</td><td>$support</td></tr>";
        $no++;
    }
    echo '</table>';echo '</div>';echo '</br>';echo '</br>';echo '<h3>frequent 1-itemsets dg minsup</h3>';echo '<div class="table-responsive">';echo '<table class="table mb-0 table-bordered">';echo '<tr><td>NO</td><td>Item</td><td>Tid List</td><td>Frequent pattern</td><td>Support</td></tr>';
    $no = 1;
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
        if ($support >= $minSupport) {
            echo "<tr><td>$no</td><td>$kodeBarang</td><td>" . implode(', ', $tidList) . "</td><td>$frequentPattern</td><td>$support</td></tr>";
            $no++;
        }
    }
    echo '</table>';echo '</div>';echo '</br>';
    
    $minFrequentCount = 1; 
    // Ganti dengan nilai minimum frekuensi yang diinginkan
    $frequentPatterns = [];
    foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
        foreach ($barangTransaksi as $kodeBarang) {
            $count = array_count_values($barangTransaksi)[$kodeBarang] ?? 0;
            // if ($count >= $minFrequentCount) {
            if ($count >= $minSupport) {
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
    $transactions = array();
    while ($row = $result->fetch_assoc()) {
        $idTransaksi = $row['id_transaksi'];
        $kodeBarang = $row['kode_barang'];
        if (!isset($transactions[$idTransaksi])) {
            $transactions[$idTransaksi] = array();
        }
        $transactions[$idTransaksi][] = $kodeBarang;
    }

    echo '</br>';echo '<h3>frequent 2-itemsets</h3>';echo '<div class="table-responsive">';echo '<table class="table mb-0 table-bordered">';echo '<tr><td>No</td><td>Pasangan Barang</td><td>Transaksi</td><td>Frequent Pattern</td><td>Support</td></tr>';
    $no = 1;
    $frequent1Items = array();
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
        if ($support >= $minSupport) {
            $frequent1Items[] = array('kodeBarang' => $kodeBarang, 'tidList' => $tidList, 'frequentPattern' => $frequentPattern, 'support' => $support);
        }
    }
    foreach ($frequent1Items as $i => $itemA) {
        $barangA = $itemA['kodeBarang'];
        $tidListA = $itemA['tidList'];
        $frequentPatternA = $itemA['frequentPattern'];
        $supportA = $frequentPatternA / count($transaksiGrouped);
        for ($j = $i + 1; $j < count($frequent1Items); $j++) {
            $itemB = $frequent1Items[$j];
            $barangB = $itemB['kodeBarang'];
            $tidListB = $itemB['tidList'];
            $frequentPatternB = $itemB['frequentPattern'];
            $transaksiMengandungKeduaBarang = array_intersect($tidListA, $tidListB);
            $transaksiStr = implode('-', $transaksiMengandungKeduaBarang);
            $pasanganBarang = "($barangA) - ($barangB)";
            $ff = count($transaksiMengandungKeduaBarang);
            $support = $ff / count($transaksiGrouped);
            echo "<tr><td>$no</td><td>$pasanganBarang</td><td>($transaksiStr)</td><td>".$ff."</td><td>".$support."</td></tr>";
            $no++;
        }
    }
    echo '</table>'; echo '</div>'; 
    echo '</br>'; echo '</br>';
    echo '<h3>frequent 2-itemsets dengan minsup</h3>';echo '<div class="table-responsive">';echo '<table class="table mb-0 table-bordered">';echo '<tr><td>No</td><td>Pasangan Barang</td><td>Transaksi</td><td>Frequent Pattern</td><td>Support</td></tr>';
    $no = 1;
    $allPairs = array();
    $uniqueBarangCount = count($uniqueBarang);
    for ($i = 0; $i < $uniqueBarangCount - 1; $i++) {
        for ($j = $i + 1; $j < $uniqueBarangCount; $j++) {
            $barangA = $uniqueBarang[$i];
            $barangB = $uniqueBarang[$j];
            $allPairs[] = array('barangA' => $barangA, 'barangB' => $barangB);
        }
    }
    $frequent2ItemsetsData = array();
    foreach ($allPairs as $pair) {
        $barangA = $pair['barangA'];
        $barangB = $pair['barangB'];
        $transaksiMengandungKeduaBarang = array();
        foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
            if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi)) {
                $transaksiMengandungKeduaBarang[] = $idTransaksi;
            }
        }
        $frequentPattern = count($transaksiMengandungKeduaBarang);
        $support = $frequentPattern / count($transaksiGrouped);
        if ($support >= $minSupport) {
            echo "<tr><td>$no</td><td>($barangA) - ($barangB)</td><td>(" . implode('-', $transaksiMengandungKeduaBarang) . ")</td><td>$frequentPattern </td><td>$support</td></tr>";
            $no++;
            $frequent2ItemsetsData[] = array(
                'barangA' => $barangA,
                'barangB' => $barangB,
                'transaksi' => $transaksiMengandungKeduaBarang,
                'frequentPattern' => $frequentPattern,
                'support' => $support 
            );
        }
    }
    echo '</table>';echo '</div>';echo '</br>';
    $uniqueItems = array_unique(array_merge(array_column($frequent2ItemsetsData, 'barangA'), array_column($frequent2ItemsetsData, 'barangB')));
    function combinations($items, $k) {
        if ($k == 0) {
            return [[]];
        }

        if (count($items) < $k) {
            return [];
        }

        if (count($items) == $k) {
            return [$items];
        }

        $result = [];
        $first = $items[0];

        // Generate combinations including the first element
        $restCombinations = combinations(array_slice($items, 1), $k - 1);
        foreach ($restCombinations as $comb) {
            array_unshift($comb, $first);
            $result[] = $comb;
        }

        // Generate combinations excluding the first element
        $result = array_merge($result, combinations(array_slice($items, 1), $k));

        return $result;
    }
    $tableName = 'transaksi';
    $uniqueItemCodes = "'" . implode("','", $uniqueItems) . "'";
    $query = "SELECT kode_barang, id_transaksi FROM $tableName WHERE kode_barang IN ($uniqueItemCodes)";
    $result = mysqli_query($link, $query);
    if ($result) {
        $groupedResults = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $kode_barang = $row['kode_barang'];
            $id_transaksi = $row['id_transaksi'];
            if (!isset($groupedResults[$kode_barang])) {
                $groupedResults[$kode_barang] = array();
            }
            $groupedResults[$kode_barang][] = $id_transaksi;
        }
        ksort($groupedResults);
    } else {
        echo 'Error executing query: ' . mysqli_error($link);
    }
    echo '</br>';echo '<h3>frequent 3-itemsets</h3>';echo '<div class="table-responsive">';echo '<table class="table mb-0 table-bordered">';echo '<tr><td>No</td><td>Itemsets</td><td>Tid List</td><td>Frequent Pattern</td><td>Support</td></tr>';
    $no = 1;
    $allTripleCombinations = combinations(array_keys($groupedResults), 3);
    foreach ($allTripleCombinations as $tripleComb) {
        $barangA = $tripleComb[0];
        $barangB = $tripleComb[1];
        $barangC = $tripleComb[2];
        $tidListABC = array();
        $frequentPatternABC = 0;
        foreach ($transaksiData as $item) {
            $tidA = $groupedResults[$barangA];
            $tidB = $groupedResults[$barangB];
            $tidC = $groupedResults[$barangC];
            $tidListA = array_intersect($tidA, [$item['id_transaksi']]);
            $tidListB = array_intersect($tidB, [$item['id_transaksi']]);
            $tidListC = array_intersect($tidC, [$item['id_transaksi']]);
            if (!empty($tidListA) && !empty($tidListB) && !empty($tidListC)) {
                $tidListABC = array_merge($tidListABC, $tidListA, $tidListB, $tidListC);
            }
        }
        $frequentPatternABC = count(array_unique($tidListABC));
        $supportABC = $frequentPatternABC / count($transaksiGrouped);
            $tidListStr = implode(', ', array_unique($tidListABC));
            $itemsets = "$barangA, $barangB, $barangC";
            echo "<tr><td>$no</td><td>$itemsets</td><td>($tidListStr)</td><td>$frequentPatternABC</td><td>$supportABC</td></tr>";
            $no++;
    }
    echo '</table>';echo '</div>';echo '</br>';echo '</br>';
    echo '<h3>Frequent 3 Itemset dengan minisup</h3>';
    echo '<table class="table mb-0 table-bordered">';
    echo '<tr><td>No</td><td>Itemset</td><td>Transaksi</td><td>Frequent Pattern</td><td>Support</td></tr>';
    $no = 1;
    $allTriplets = array();
    $uniqueBarangCount = count($uniqueBarang);
    foreach ($uniqueBarang as $i => $barangA) {
        for ($j = $i + 1; $j < $uniqueBarangCount; $j++) {
            for ($k = $j + 1; $k < $uniqueBarangCount; $k++) {
                $barangB = $uniqueBarang[$j];
                $barangC = $uniqueBarang[$k];
                $allTriplets[] = array('barangA' => $barangA, 'barangB' => $barangB, 'barangC' => $barangC);
            }
        }
    }
    foreach ($allTriplets as $triplet) {
        $barangA = $triplet['barangA'];
        $barangB = $triplet['barangB'];
        $barangC = $triplet['barangC'];
        $transaksiMengandungTigaBarang = array();
        foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
            if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi) && in_array($barangC, $barangTransaksi)) {
                $transaksiMengandungTigaBarang[] = $idTransaksi;
            }
        }
        $frequentPattern = count($transaksiMengandungTigaBarang);
        $support = $frequentPattern / count($transaksiGrouped);
        if ($support >= $minSupport) {
            $supportABC = count($transaksiMengandungTigaBarang) / count($transaksiGrouped);
            $transaksiStr = implode('-', $transaksiMengandungTigaBarang);
            $itemset = "($barangA) - ($barangB) - ($barangC)";
            $jumlahTransaksi = count($transaksiMengandungTigaBarang);
            echo "<tr><td>$no</td><td>$itemset</td><td>$transaksiStr</td><td>$jumlahTransaksi</td><td>$supportABC</td></tr>";
            $no++;
        }
    }
    echo '</table>';echo '</br>';
    function calculateSupport($barangA, $barangB, $transaksiGrouped)
    {
        $countAB = 0;$countA = 0;
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
    echo '<h3>Aturan Asosiasi</h3>';echo '<div class="table-responsive">';echo '<table id="associationRules" class="table mb-0 table-bordered">'; echo '<thead><tr><td>No</td><td>Rule</td><td>Support</td><td>Confidence</td></tr></thead>';echo '<tbody>';
    $no = 1;
    $associationRulesAtoB = array();$associationRulesBtoA = array();
    foreach ($allPairs as $pair) {
        $barangA = $pair['barangA'];
        $barangB = $pair['barangB'];
        $transaksiMengandungKeduaBarang = array();
        foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
            if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi)) {
                $transaksiMengandungKeduaBarang[] = $idTransaksi;
            }
        }
        $frequentPattern = count($transaksiMengandungKeduaBarang);
        $support = $frequentPattern / count($transaksiGrouped);
        if ($support >= $minSupport) {    
            $frequentPatternAB = count($transaksiMengandungKeduaBarang);
            $supportAB = count($transaksiMengandungKeduaBarang) / count($transaksiGrouped);
            $frequentPatternA = count(array_filter($transaksiGrouped, function ($transaksi) use ($barangA) {
                return in_array($barangA, $transaksi);
            }));
            $frequentPatternB = count(array_filter($transaksiGrouped, function ($transaksi) use ($barangB) {
                return in_array($barangB, $transaksi);
            }));
            $confidenceAtoB = ($frequentPatternA != 0) ? ($frequentPatternAB / $frequentPatternA) : 0;
            $confidenceBtoA = ($frequentPatternB != 0) ? ($frequentPatternAB / $frequentPatternB) : 0;
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
       
            echo "<tr><td>$no</td><td>Jika konsumen membeli $barangA maka membeli $barangB</td><td>$supportAB</td><td>$confidenceAtoB</td></tr>";
            echo "<tr><td></td><td>Jika konsumen membeli $barangB maka membeli $barangA</td><td>$supportAB</td><td>$confidenceBtoA</td></tr>";
            $no++;
        }
    }
    


    echo '</tbody>';
    echo '</table>';
    echo '</div>';

    function getSupport($itemA, $itemB, $frequent2ItemsetsData) {
        foreach ($frequent2ItemsetsData as $itemset) {
            if (($itemset['barangA'] == $itemA && $itemset['barangB'] == $itemB) || ($itemset['barangA'] == $itemB && $itemset['barangB'] == $itemA)) {
                return $itemset['support'];
            }
        }
        return 0; // Return 0 jika tidak ditemukan
    }
    
  
    echo '</br>';
    echo '<h3>Aturan Asosiasi 3-Itemset</h3>';
    echo '<table class="table mb-0 table-bordered">';
    echo '<tr><td>No</td><td>Association Rules</td><td>Support</td><td>Confidence</td></tr>';
    
    $no = 1;
    
    foreach ($allTriplets as $triplet) {
        $barangA = $triplet['barangA'];
        $barangB = $triplet['barangB'];
        $barangC = $triplet['barangC'];
    
        // Menyimpan id transaksi yang mengandung ketiga barang
        $transaksiMengandungTigaBarang = array();
        foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
            if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi) && in_array($barangC, $barangTransaksi)) {
                $transaksiMengandungTigaBarang[] = $idTransaksi;
            }
        }
        $frequentPattern = count($transaksiMengandungTigaBarang);
        $support = $frequentPattern / count($transaksiGrouped);
    
        // Filter untuk transaksi dengan 3 nilai
        if ($support >= $minSupport) {
            // Hitung support
            $frequentPatternABC = count($transaksiMengandungTigaBarang);
            $supportABC = count($transaksiMengandungTigaBarang) / count($transaksiGrouped);
            // echo $supportABC;
    
            // Dapatkan data support dari tabel frequent 2-itemsets
            $supportAB = getSupport($barangA, $barangB, $frequent2ItemsetsData);
            $supportBC = getSupport($barangB, $barangC, $frequent2ItemsetsData);
            $supportAC = getSupport($barangA, $barangC, $frequent2ItemsetsData);
    
            // echo $supportAB;
            $confidenceAB = $supportABC / $supportAB;
            $confidenceBC = $supportABC / $supportBC;
            $confidenceAC = $supportABC / $supportAC;
    
            // Menampilkan hasil
            $transaksiStr = implode('-', $transaksiMengandungTigaBarang);
            $itemsetAB = "$barangA dan $barangB";
            $itemsetBC = "$barangB dan $barangC";
            $itemsetAC = "$barangA dan $barangC";
    
            echo "<tr><td>$no</td><td>Jika konsumen membeli barang $itemsetAB maka membeli $barangC</td><td>$supportABC</td><td>$confidenceAB</td></tr>";
            $no++;
    
            echo "<tr><td>$no</td><td>Jika konsumen membeli barang $itemsetBC maka membeli $barangA</td><td>$supportABC</td><td>$confidenceBC</td></tr>";
            $no++;
    
            echo "<tr><td>$no</td><td>Jika konsumen membeli barang $itemsetAC maka membeli $barangB</td><td>$supportABC</td><td>$confidenceAC</td></tr>";
            $no++;

            $assosiasi3_1[] = array(
                'Rule' => "Jika konsumen membeli $itemsetAB maka membeli $barangC",
                'Support' => $supportABC,
                'Confidence' => $confidenceAB,
            );
            $assosiasi3_2[] = array(
                'Rule' => "Jika konsumen membeli $itemsetBC maka membeli $barangA",
                'Support' => $supportABC,
                'Confidence' => $confidenceBC,
            );
            $assosiasi3_3[] = array(
                'Rule' => "Jika konsumen membeli $itemsetAC maka membeli $barangB",
                'Support' => $supportABC,
                'Confidence' => $confidenceAC,
            );
        }
    }
    
    echo '</table>';
    echo '</br>';
    echo '</br><button id="saveButton" class="btn btn-success" onclick="saveData()">Save</button>';
    echo '</br>';
    echo '</br>';
    
?>
<script>
    var encodedAssociationRulesAtoB = <?php echo  json_encode($associationRulesAtoB); ?>;
    var encodedAssociationRulesBtoA = <?php echo  json_encode($associationRulesBtoA); ?>;
    var encodedAssosiasi3_1 = <?php echo  json_encode($assosiasi3_1); ?>;
    var encodedAssosiasi3_2 = <?php echo  json_encode($assosiasi3_2); ?>;
    var encodedAssosiasi3_3 = <?php echo  json_encode($assosiasi3_3); ?>;
    </script>
<?php }

        function calculateEclat($minSupport, $minConfidence)
        {
            global $link; // Memastikan variabel global $link dapat digunakan di dalam fungsi
            if ($link === null) { die("Database connection is not established"); }
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
                'transaksi' => $transaksi,  // Ta
                'itemsetHorizontal' => [],
                'itemsetVertical' => [],
                'intersection' => [],
                'support' => [],
                'associationRule' => []
            ];
            $supportCount = [];
            foreach ($transaksi as $transaction) {
                foreach ($transaction as $item) {
                    if (!isset($supportCount[$item])) {
                        $supportCount[$item] = 0;
                    }
                    $supportCount[$item]++;
                }
            }
            $frequentItemset = array_filter($supportCount, function ($count) use ($minSupport) {
                return $count >= $minSupport;
            });
            $frequentItemset = array_keys($frequentItemset);
            $combinations = generateCombinations($frequentItemset, 2);
            foreach ($combinations as $combination) {
                $itemset1 = trim($combination[0]);
                $itemset2 = trim($combination[1]);
                $intersectionCount = 0;
                foreach ($transaksi as $transaction) {
                    if (in_array($itemset1, $transaction) && in_array($itemset2, $transaction)) {
                        $intersectionCount++;
                    }
                }
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var associationRulesAtoB = [];  
        var associationRulesBtoA = [];  
        var association3_1 = [];  
        var association3_2 = [];  
        var association3_3 = [];  
        const saveButton = document.getElementById('saveButton');
        console.log('Save Button:', saveButton);
        if (saveButton) {
            console.log('Button Disabled:', saveButton.disabled);
        }   
        // Event listener for form submission
        document.getElementById("eclatForm").addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent the default form submission
            saveData(); // Call the saveData function when the form is submitted
        });
    });

    async function saveData() {
        var minSupportFromPHP = <?php echo json_encode($minSupport); ?>;
        var minConfidence = <?php echo json_encode($minConfidence); ?>;

        var data = {
            support: minSupportFromPHP,
            confidence: minConfidence
        };

        try {
            const response = await fetch('save_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const textData = await response.text();
            console.log('Data hasil dari server:', textData);

            if (textData.trim() === '') {
                console.error('Empty response from the server.');
                return;
            }

            const parsedData = JSON.parse(textData);
            console.log('Data hasil berhasil di-parse:', parsedData);

            var associationRulesAtoB = encodedAssociationRulesAtoB || [];
            var associationRulesBtoA = encodedAssociationRulesBtoA || [];
            var association3_1 = encodedAssosiasi3_1 || [];
            var association3_2 = encodedAssosiasi3_2 || [];
            var association3_3 = encodedAssosiasi3_3 || [];
            document.getElementById('saveButton').disabled = true;

            saveDetailHasil(parsedData.last_id, associationRulesAtoB, associationRulesBtoA, association3_1, association3_2, association3_3);
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function saveDetailHasil(idHasil, associationRulesAtoB, associationRulesBtoA, association3_1, association3_2, association3_3) {
        var detailDataArray = [];

        associationRulesAtoB.forEach(rule => {
            var detailData = {
                id_hasil: idHasil,
                id_rule: rule.Rule,         // Use 'id_rule' instead of 'rule'
                support: rule.Support,
                confidence: rule.Confidence,
            };
            detailDataArray.push(detailData);
        });

        associationRulesBtoA.forEach(rule => {
            var detailData = {
                id_hasil: idHasil,
                id_rule: rule.Rule,         // Use 'id_rule' instead of 'rule'
                support: rule.Support,
                confidence: rule.Confidence,
            };
            detailDataArray.push(detailData);
        });
        association3_1.forEach(rule => {
            var detailData = {
                id_hasil: idHasil,
                id_rule: rule.Rule,         // Use 'id_rule' instead of 'rule'
                support: rule.Support,
                confidence: rule.Confidence,
            };
            detailDataArray.push(detailData);
        });
        association3_2.forEach(rule => {
            var detailData = {
                id_hasil: idHasil,
                id_rule: rule.Rule,         // Use 'id_rule' instead of 'rule'
                support: rule.Support,
                confidence: rule.Confidence,
            };
            detailDataArray.push(detailData);
        });
        association3_3.forEach(rule => {
            var detailData = {
                id_hasil: idHasil,
                id_rule: rule.Rule,         // Use 'id_rule' instead of 'rule'
                support: rule.Support,
                confidence: rule.Confidence,
            };
            detailDataArray.push(detailData);
        });

        console.log(detailDataArray);

        fetch('save_detail_hasil.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ detailDataArray }),
        })
        .then(response => response.json())  
        .then(data => {
            console.log('Data detail hasil successfully saved:', data);
            // Add your logic or response handling here
            setTimeout(function() {
                document.getElementById('saveButton').disabled = true;
            }, 100); // Adjust the delay as needed

        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

</script>
</body>
</html>