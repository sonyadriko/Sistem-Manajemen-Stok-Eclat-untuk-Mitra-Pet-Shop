<?php 
include 'connection.php';
session_start();
 if (!isset($_SESSION['id_user'])) {
     header("Location: login.php");
 }
?>
<?php include 'layouts/head-main.php'; ?>
<?php include 'layouts/config.php'; ?>

<?php
require 'vendor/autoload.php';

?>
<head>
    <title>Analisa</title>

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
                        <label for="support" class="col-sm-3 col-form-label">Min Support %</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="support" name="support" placeholder="Enter Min Support">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="confidence" class="col-sm-3 col-form-label">Min Confidence %</label>
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
    $minSupportPercentage = $_POST['support'];
    $minConfidencePercentage = $_POST['confidence'];

    // Konversi nilai persen ke nilai absolut
    $maxPercentage = 100; // Nilai maksimum persentase
    $minSupport = ($minSupportPercentage / $maxPercentage);
    $minConfidence = ($minConfidencePercentage / $maxPercentage);

    // Panggil fungsi untuk menghitung Eclat
    calculateEclat($minSupport, $minConfidence);

    global $minSupport;
}

function displayResult($result)
{
    global $link, $minSupport; // Memastikan variabel global $link dapat digunakan di dalam fungsi

    // Periksa apakah koneksi database sudah ada atau belum
    if ($link === null) {
        die("Database connection is not established");
    }
    // Cek apakah ada data hasil Eclat
    if (empty($result['itemsetHorizontal'])) {
        echo 'No results found.';
        return;
    }
        
        // // Data transaksi dari database
        // $transaksiQuery = $link->query("SELECT * FROM transaksi");
        // $transaksiData = [];
        // while ($row = $transaksiQuery->fetch_assoc()) {
        //     $transaksiData[] = $row;
        // }

        // $transaksi = []; 
        // // Mengelompokkan data transaksi berdasarkan id_transaksi
        // $transaksiGrouped = [];
        // foreach ($transaksiData as $item) {
        //     $transaksiGrouped[$item['id_transaksi']][] = $item['kode_barang'];
        // }

        // // Mengumpulkan semua kode barang yang unik
        // $uniqueBarang = array_unique(array_column($transaksiData, 'kode_barang'));

        // Data transaksi dari database
    $transaksiQuery = $link->query("SELECT * FROM transaksi");
    $transaksiData = [];
    while ($row = $transaksiQuery->fetch_assoc()) {
        $transaksiData[] = $row;
    }

    $transaksiGrouped = [];
    // Mengelompokkan data transaksi berdasarkan id_transaksi
    foreach ($transaksiData as $item) {
        $transaksiGrouped[$item['id_transaksi']][] = $item['kode_barang'];
    }

    // Mengumpulkan semua kode barang yang unik
    $uniqueBarang = array_unique(array_column($transaksiData, 'kode_barang'));
    // echo $uniqueBarang;



    echo '</br>';
    echo '<h3>frequent 1-itemsets</h3>';
    
    echo '<div class="table-responsive">';
    echo '<table class="table mb-0 table-bordered">';
    // Header tabel
    echo '<tr><td>NO</td><td>Item</td><td>Tid List</td><td>Frequent pattern</td><td>Support</td></tr>';
    
    $no = 1;
    
    // Sort the $uniqueBarang array alphabetically
    sort($uniqueBarang);
    
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
    
        $support2 = $frequentPattern / count($transaksiGrouped);
        $support = $support2;
    
        echo "<tr><td>$no</td><td>$kodeBarang</td><td>" . implode(', ', $tidList) . "</td><td>$frequentPattern</td><td>$support</td></tr>";
        $no++;
    }
    
    echo '</table>';
    echo '</div>';
    

    echo '</br>';

    echo '</br>';
    echo '<h3>frequent 1-itemsets dg minsup</h3>';
    
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

        // Check if the support is greater than or equal to the minimum support
        if ($support >= $minSupport) {
            echo "<tr><td>$no</td><td>$kodeBarang</td><td>" . implode(', ', $tidList) . "</td><td>$frequentPattern</td><td>$support</td></tr>";
            $no++;
        }
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
    echo '<h3>frequent 2-itemsets</h3>';

    echo '<div class="table-responsive">';
    echo '<table class="table mb-0 table-bordered">';
    echo '<tr><td>No</td><td>Pasangan Barang</td><td>Transaksi</td><td>Frequent Pattern</td><td>Support</td></tr>';
    $no = 1;

    // Get items with support greater than or equal to the minimum support from frequent 1-itemsets
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

        // Check if the support is greater than or equal to the minimum support
        if ($support >= $minSupport) {
            $frequent1Items[] = array('kodeBarang' => $kodeBarang, 'tidList' => $tidList, 'frequentPattern' => $frequentPattern, 'support' => $support);
        }
    }

    // Generate pairs using items from frequent 1-itemsets
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

            // Menyimpan id transaksi yang mengandung kedua barang
            $transaksiMengandungKeduaBarang = array_intersect($tidListA, $tidListB);

            // Menampilkan hasil
            $transaksiStr = implode('-', $transaksiMengandungKeduaBarang);
            $pasanganBarang = "($barangA) - ($barangB)";
            // $support = ($frequentPatternA + $frequentPatternB) / count($transaksiGrouped);

            $ff = count($transaksiMengandungKeduaBarang);
            $support = $ff / count($transaksiGrouped);


            echo "<tr><td>$no</td><td>$pasanganBarang</td><td>($transaksiStr)</td><td>".$ff."</td><td>".$support."</td></tr>";
            $no++;
        }
    }

    echo '</table>';
    echo '</div>';

    echo '</br>';


    // echo '</br>';
    // echo '<h3>Frequent 2 Itemset dengan minisup</h3>';
    // echo '<table class="table mb-0 table-bordered">';
    // echo '<tr><td>No</td><td>Itemset</td><td>Transaksi</td><td>Frequent Pattern</td><td>Support</td></tr>';

    // $no = 1;
    // $minSupport = 2; // Ganti dengan ambang batas support yang diinginkan

    // foreach ($allPairs as $pair) {
    //     $barangA = $pair['barangA'];
    //     $barangB = $pair['barangB'];

    //     // Menyimpan id transaksi yang mengandung kedua barang
    //     $transaksiMengandungKeduaBarang = array();
    //     foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
    //         if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi)) {
    //             $transaksiMengandungKeduaBarang[] = $idTransaksi;
    //         }
    //     }

    //     // Filter untuk transaksi dengan 2 nilai
    //     if (count($transaksiMengandungKeduaBarang) >= $minSupport) {
    //         // Hitung support
    //         $supportAB = count($transaksiMengandungKeduaBarang) / count($transaksiGrouped);

    //         // Menampilkan hasil
    //         $transaksiStr = implode('-', $transaksiMengandungKeduaBarang);
    //         $itemset = "($barangA) - ($barangB)";
    //         $jumlahTransaksi = count($transaksiMengandungKeduaBarang);

    //         echo "<tr><td>$no</td><td>$itemset</td><td>$transaksiStr</td><td>$jumlahTransaksi</td><td>$supportAB</td></tr>";
    //         $no++;
    //     }
    // }
    
    // echo '</table>';
    // echo '</br>';



    //     echo '</br>';
    //     echo '<h3>frequent 2-itemsets dengan minsup</h3>';
        
    //     echo '<div class="table-responsive">';
    //     echo '<table class="table mb-0 table-bordered">';
    //     echo '<tr><td>No</td><td>Pasangan Barang</td><td>Transaksi</td><td>Frequent Pattern</td><td>Support</td></tr>';
    //     $no = 1;
        
    //     // Menghasilkan semua pasangan barang
    //     $allPairs = array();
    //     $uniqueBarangCount = count($uniqueBarang);
        
    //     foreach ($uniqueBarang as $i => $barangA) {
    //         for ($j = $i + 1; $j < $uniqueBarangCount; $j++) {
    //             // Check if array keys exist before accessing
    //             if (isset($uniqueBarang[$i], $uniqueBarang[$j])) {
    //                 $barangB = $uniqueBarang[$j];
    //                 $allPairs[] = array('barangA' => $barangA, 'barangB' => $barangB);
    //             }
    //         }
    //     }
        
    //     foreach ($allPairs as $pair) {
    //         $barangA = $pair['barangA'];
    //         $barangB = $pair['barangB'];
        
    //         // Menyimpan id transaksi yang mengandung kedua barang
    //         $transaksiMengandungKeduaBarang = array();
    //         foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
    //             if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi)) {
    //                 $transaksiMengandungKeduaBarang[] = $idTransaksi;
    //             }
    //         }
        
    //         // Menampilkan hasil
    //         $transaksiStr = implode('-', $transaksiMengandungKeduaBarang);
    //         $pasanganBarang = "($barangA) - ($barangB)";
            
    //         // Calculate frequent pattern and support
    //         $frequentPattern = count($transaksiMengandungKeduaBarang);
    //         $support = $frequentPattern / count($transaksiGrouped);
        
    //         // Check if the support is greater than or equal to the minimum support
    //         if ($support >= $minSupport) {
    //             echo "<tr><td>$no</td><td>$pasanganBarang</td><td>($transaksiStr)</td><td>$frequentPattern</td><td>$support</td></tr>";
    //             $no++;
    //         }

    //         $frequent2ItemsetsData = array();
    //         foreach ($allPairs as $pair) {
    //             $barangA = $pair['barangA'];
    //             $barangB = $pair['barangB'];

    //             // Menyimpan id transaksi yang mengandung kedua barang
    //             $transaksiMengandungKeduaBarang = array();
    //             foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
    //                 if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi)) {
    //                     $transaksiMengandungKeduaBarang[] = $idTransaksi;
    //                 }
    //             }

    //             // Calculate frequent pattern and support
    //             $frequentPattern = count($transaksiMengandungKeduaBarang);
    //             $support = $frequentPattern / count($transaksiGrouped);

    //             // Check if the support is greater than or equal to the minimum support
    //         if ($support >= $minSupport) {
    //             $frequent2ItemsetsData[] = array(
    //                 'barangA' => $barangA,
    //                 'barangB' => $barangB,
    //                 'transaksi' => $transaksiMengandungKeduaBarang
    //             );
    //         }
    //     }
    // }
        
    //     echo '</table>';
    //     echo '</div>';
        
    //     echo '</br>';
        
    // ...

    echo '</br>';
    echo '<h3>frequent 2-itemsets dengan minsup</h3>';

    echo '<div class="table-responsive">';
    echo '<table class="table mb-0 table-bordered">';
    echo '<tr><td>No</td><td>Pasangan Barang</td><td>Transaksi</td><td>Frequent Pattern</td><td>Support</td></tr>';
    $no = 1;

    // Menghasilkan semua pasangan barang
    $allPairs = array();
    $uniqueBarangCount = count($uniqueBarang);

    // Generate all pairs of items
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

        // Menyimpan id transaksi yang mengandung kedua barang
        $transaksiMengandungKeduaBarang = array();
        foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
            if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi)) {
                $transaksiMengandungKeduaBarang[] = $idTransaksi;
            }
        }

        // Calculate frequent pattern and support
        $frequentPattern = count($transaksiMengandungKeduaBarang);
        $support = $frequentPattern / count($transaksiGrouped);

        // Check if the support is greater than or equal to the minimum support
        if ($support >= $minSupport) {
            echo "<tr><td>$no</td><td>($barangA) - ($barangB)</td><td>(" . implode('-', $transaksiMengandungKeduaBarang) . ")</td><td>$frequentPattern </td><td>$support</td></tr>";
            $no++;

            // Store the data in a structured array
            $frequent2ItemsetsData[] = array(
                'barangA' => $barangA,
                'barangB' => $barangB,
                'transaksi' => $transaksiMengandungKeduaBarang,
                'frequentPattern' => $frequentPattern 
            );
        }
    }

    echo '</table>';
    echo '</div>';

    echo '</br>';
    // Extract unique items from frequent2ItemsetsData
    $uniqueItems = array_unique(array_merge(array_column($frequent2ItemsetsData, 'barangA'), array_column($frequent2ItemsetsData, 'barangB')));

    // print_r($uniqueItems);


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

    // Generate a comma-separated string of unique item codes for SQL query
    $uniqueItemCodes = "'" . implode("','", $uniqueItems) . "'";

    // SQL query to retrieve data from the 'transaksi' table
    $query = "SELECT kode_barang, id_transaksi FROM $tableName WHERE kode_barang IN ($uniqueItemCodes)";
    // echo "SQL Query: $query";
    // Execute the query
    $result = mysqli_query($link, $query);

    // Check if the query was successful
    if ($result) {
        // Group results by kode_barang
        $groupedResults = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $kode_barang = $row['kode_barang'];
            $id_transaksi = $row['id_transaksi'];

            // Group by kode_barang
            if (!isset($groupedResults[$kode_barang])) {
                $groupedResults[$kode_barang] = array();
            }

            $groupedResults[$kode_barang][] = $id_transaksi;
        }

        ksort($groupedResults);
        // Display the grouped results in a table, adjust as needed
        // echo '<table border="1">';
        // echo '<tr><td>kode_barang</td><td>id_transaksi</td></tr>';

        // foreach ($groupedResults as $kode_barang => $id_transaksis) {
        //     echo '<tr>';
        //     echo '<td>' . $kode_barang . '</td>';
        //     echo '<td>' . implode(', ', $id_transaksis) . '</td>';
        //     echo '</tr>';
        // }

        // echo '</table>';
    } else {
        // Handle the case where the query was not successful
        echo 'Error executing query: ' . mysqli_error($link);
    }

    echo '</br>';
    echo '<h3>frequent 3-itemsets</h3>';

    echo '<div class="table-responsive">';
    echo '<table class="table mb-0 table-bordered">';
    echo '<tr><td>No</td><td>Itemsets</td><td>Tid List</td><td>Frequent Pattern</td><td>Support</td></tr>';
    $no = 1;

    // Generate triples using items from grouped results
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

            // Mencari transaksi yang mengandung ketiga barang
            $tidListA = array_intersect($tidA, [$item['id_transaksi']]);
            $tidListB = array_intersect($tidB, [$item['id_transaksi']]);
            $tidListC = array_intersect($tidC, [$item['id_transaksi']]);

            // Mencari transaksi yang mengandung ketiga barang
            if (!empty($tidListA) && !empty($tidListB) && !empty($tidListC)) {
                $tidListABC = array_merge($tidListABC, $tidListA, $tidListB, $tidListC);
            }
        }

        // Count the frequency of the triple in tidListABC
        $frequentPatternABC = count(array_unique($tidListABC));

        $supportABC = $frequentPatternABC / count($transaksiGrouped);

        // Check if the support is greater than or equal to the minimum support
        // if ($frequentPatternABC > 0) {
            $tidListStr = implode(', ', array_unique($tidListABC));
            $itemsets = "$barangA, $barangB, $barangC";
            echo "<tr><td>$no</td><td>$itemsets</td><td>($tidListStr)</td><td>$frequentPatternABC</td><td>$supportABC</td></tr>";
            $no++;
        // }
    }

    echo '</table>';
    echo '</div>';

    echo '</br>';




    //     echo '</br>';
    // echo '<h3>Frequent 2 Itemset dengan minisup</h3>';
    // echo '<table class="table mb-0 table-bordered">';
    // echo '<tr><td>No</td><td>Itemset</td><td>Transaksi</td><td>Frequent Pattern</td><td>Support</td></tr>';

    // $no = 1;
    // $minSupport = 2; // Ganti dengan ambang batas support yang diinginkan

    // $seenPairs = array(); // Digunakan untuk menghindari pasangan yang sama

    // foreach ($allPairs as $pair) {
    //     $barangA = $pair['barangA'];
    //     $barangB = $pair['barangB'];

    //     // Mengecek apakah pasangan ini sudah ditampilkan
    //     $pairKey = implode('-', [$barangA, $barangB]);
    //     if (isset($seenPairs[$pairKey])) {
    //         continue;
    //     }

    //     // Menyimpan id transaksi yang mengandung kedua barang
    //     $transaksiMengandungKeduaBarang = array();
    //     foreach ($transaksiGrouped as $idTransaksi => $barangTransaksi) {
    //         if (in_array($barangA, $barangTransaksi) && in_array($barangB, $barangTransaksi)) {
    //             $transaksiMengandungKeduaBarang[] = $idTransaksi;
    //         }
    //     }

    //     // Filter untuk transaksi dengan 2 nilai
    //     if (count($transaksiMengandungKeduaBarang) >= $minSupport) {
    //         // Hitung support
    //         $supportAB = count($transaksiMengandungKeduaBarang) / count($transaksiGrouped);

    //         // Menampilkan hasil
    //         $transaksiStr = implode('-', $transaksiMengandungKeduaBarang);
    //         $itemset = "($barangA) - ($barangB)";
    //         $jumlahTransaksi = count($transaksiMengandungKeduaBarang);

    //         echo "<tr><td>$no</td><td>$itemset</td><td>$transaksiStr</td><td>$jumlahTransaksi</td><td>$supportAB</td></tr>";
    //         $no++;

    //         // Menandai pasangan ini sudah ditampilkan
    //         $seenPairs[$pairKey] = true;
    //     }
    // }




    // echo '</br>';
    // echo '<h3>Frequent 3 Itemset dari Frequent 2 Itemset dengan Min Sup</h3>';
    // echo '<table class="table mb-0 table-bordered">';
    // echo '<tr><td>No</td><td>Itemset</td><td>Transaksi</td><td>Frequent Pattern</td><td>Support</td></tr>';
    // $no = 1;

    // // Generate frequent 3-itemsets from frequent 2-itemsets with min sup
    // foreach ($frequent2ItemsetsData as $itemA) {
    //     $barangA = $itemA['barangA'];
    //     $tidListA = $itemA['transaksi'];

    //     foreach ($frequent2ItemsetsData as $itemB) {
    //         $barangB = $itemB['barangB'];
    //         $tidListB = $itemB['transaksi'];

    //         foreach ($frequent2ItemsetsData as $itemC) {
    //             $barangC = $itemC['barangB'];
    //             $tidListC = $itemC['transaksi'];

    //             if ($barangA != $barangB && $barangB != $barangC && $barangA != $barangC) {
    //                 // Menyimpan id transaksi yang mengandung ketiga barang
    //                 $transaksiMengandungTigaBarang = array_intersect($tidListA, $tidListB, $tidListC);

    //                 // Menyimpan pasangan barang beserta tidList
    //                 $frequentPattern = count($transaksiMengandungTigaBarang);
    //                 $support = $frequentPattern / count($transaksiGrouped);

    //                 // Periksa keberadaan pasangan barang pada itemset 3
    //                 if ($frequentPattern > 0) {
    //                     $itemset = "($barangA) - ($barangB) - ($barangC)";
    //                     echo "<tr><td>$no</td><td>$itemset</td><td>" . implode(', ', $transaksiMengandungTigaBarang) . "</td><td>$frequentPattern</td><td>$support</td></tr>";
    //                     $no++;
    //                 }
    //             }
    //         }
    //     }
    // }

    // echo '</table>';
    // echo '</br>';


    
    

    echo '</br>';
    echo '<h3>Frequent 3 Itemset dengan minisup</h3>';
    echo '<table class="table mb-0 table-bordered">';
    echo '<tr><td>No</td><td>Itemset</td><td>Transaksi</td><td>Frequent Pattern</td><td>Support</td></tr>';

    $no = 1;
   
    // $minSupport = 2; // Ganti dengan ambang batas support yang diinginkan

    // Menghasilkan semua triplet barang
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
            $supportABC = count($transaksiMengandungTigaBarang) / count($transaksiGrouped);

            // Menampilkan hasil
            $transaksiStr = implode('-', $transaksiMengandungTigaBarang);
            $itemset = "($barangA) - ($barangB) - ($barangC)";
            $jumlahTransaksi = count($transaksiMengandungTigaBarang);

            echo "<tr><td>$no</td><td>$itemset</td><td>$transaksiStr</td><td>$jumlahTransaksi</td><td>$supportABC</td></tr>";
            $no++;
        }
    }

    echo '</table>';
    echo '</br>';


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
    // echo "Nilai minSupport: $minSupport";
    // $minSupport = 2; // Ganti dengan ambang batas support yang diinginkan
    // global $link, $minSupport; // Memastikan variabel global $link dapat digunakan di dalam fungsi

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

        

        $frequentPattern = count($transaksiMengandungKeduaBarang);
        $support = $frequentPattern / count($transaksiGrouped);

        // Filter untuk transaksi dengan 2 nilai
        if ($support >= $minSupport) {

            // foreach ($frequent2ItemsetsData as $itemset) {
            //     $barangA = $itemset['barangA'];
            //     $barangB = $itemset['barangB'];
            //     $transaksi = $itemset['transaksi'];
            //     $frequent2itemminsup = $itemset['frequentPattern'];
    
            //     // echo $frequent2itemminsup . "<br>";
            //     // frequentPattern
            //     // Output frequent pattern information
            //     // echo "pasangan: ($barangA) - ($barangB)\n";
            //     // echo "Transactions: (" . implode('-', $transaksi) . ")\n";
            //     // echo "frequentPattern: $frequent2itemminsup\n";
            //     // echo "\n";
            // }
    
            $frequentPatternAB = count($transaksiMengandungKeduaBarang);
            // Hitung support
            $supportAB = count($transaksiMengandungKeduaBarang) / count($transaksiGrouped);

            // Hitung frequent pattern untuk setiap barang
            $frequentPatternA = count(array_filter($transaksiGrouped, function ($transaksi) use ($barangA) {
                return in_array($barangA, $transaksi);
            }));

            $frequentPatternB = count(array_filter($transaksiGrouped, function ($transaksi) use ($barangB) {
                return in_array($barangB, $transaksi);
            }));
            // echo $frequent2itemminsup;
            // echo $frequentPatternAB;
            // Hitung confidence
            $confidenceAtoB = ($frequentPatternA != 0) ? ($frequentPatternAB / $frequentPatternA) : 0;
            $confidenceBtoA = ($frequentPatternB != 0) ? ($frequentPatternAB / $frequentPatternB) : 0;
            // echo $frequentPatternB . "<br>";

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
            echo "<tr><td>$no</td><td>Jika konsumen membeli $barangA maka membeli $barangB</td><td>$supportAB</td><td>$confidenceAtoB%</td></tr>";
            echo "<tr><td></td><td>Jika konsumen membeli $barangB maka membeli $barangA</td><td>$supportAB</td><td>$confidenceBtoA%</td></tr>";

            $no++;
        }
    }

    // ...


    echo '</tbody>';
    echo '</table>';
    echo '</div>';
  
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
            // $whre  = array_intersect($transaksiMengandungTigaBarang, $groupedResults[$barangB], $groupedResults[$barangC]);
            // echo count($whre);
            // Hitung Confidence untuk setiap kombinasi
            $confidenceAB = count(array_intersect($transaksiMengandungTigaBarang, $groupedResults[$barangA], $groupedResults[$barangB]));
            $confidenceBC = count(array_intersect($transaksiMengandungTigaBarang, $groupedResults[$barangB], $groupedResults[$barangC]));
            $confidenceAC = count(array_intersect($transaksiMengandungTigaBarang, $groupedResults[$barangA], $groupedResults[$barangC]));

            $hconfidenceAB = $confidenceAB/$frequentPatternABC;
            $hconfidenceBC = $confidenceBC/$frequentPatternABC;
            $hconfidenceAC = $confidenceAC/$frequentPatternABC;
    
            // Menampilkan hasil
            $transaksiStr = implode('-', $transaksiMengandungTigaBarang);
            $itemsetAB = "$barangA dan $barangB";
            $itemsetBC = "$barangB dan $barangC";
            $itemsetAC = "$barangA dan $barangC";
            
            echo "<tr><td>$no</td><td>Jika konsumen membeli barang $itemsetAB maka membeli $barangC</td><td>$supportABC</td><td>$hconfidenceAB%</td></tr>";
            $no++;
            
            echo "<tr><td>$no</td><td>Jika konsumen membeli barang $itemsetBC maka membeli $barangA</td><td>$supportABC</td><td>$hconfidenceBC%</td></tr>";
            $no++;
            
            echo "<tr><td>$no</td><td>Jika konsumen membeli barang $itemsetAC maka membeli $barangB</td><td>$supportABC</td><td>$hconfidenceAC%</td></tr>";
            $no++;
        }
    }
    
    echo '</table>';
    echo '</br>';
    

    

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

