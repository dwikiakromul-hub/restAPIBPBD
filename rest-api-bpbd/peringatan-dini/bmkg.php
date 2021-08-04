
<?php

/***
 * Portal API BPBD || BPBD DKI JAKARTA from version 0.1
 * About : Rest API BPBD DKI JAKARTA.
 * Last modified: 13 Mei 2021
 * Author : Dwiki Akromul H
 ***/


require_once('../koneksi/conf.php');
$conn = sqlsrv_connect($serverName, $connectionInfo);
if ($conn === false) {
    echo "Koneksi Gagal</br>";
    die;
}

$curl = curl_init();


// ----------BMKG----------------//
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://warningcuaca.bmkg.go.id/arcgis/rest/services/production/nowcasting___publik/MapServer/0/query?where=1=1&geometryType=esriGeometryEnvelope&%20spatialRel=esriSpatialRelIntersects&%20units=esriSRUnit_Foot&outFields=*&returnGeometry=true&returnTrueCurves=false&%20returnIdsOnly=false&returnCountOnly=false&%20returnZ=false&returnM=false&%20returnDistinctValues=false&%20returnExtentOnly=false&%20featureEncoding=esriDefault&f=pjson',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_PROXY => '10.15.3.20',
    CURLOPT_PROXYPORT => '80',
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);
$decode = json_decode($response, true);
$konten = array();
$konten["data"] = array();
foreach ($decode['features'] as $row) {
    if (preg_match("/317/i", substr($row['attributes']['idkotakab'], 0, 3)) or preg_match("/3101/i", substr($row['attributes']['idkecamatan'], 0, 4))) {
        $row['attributes']['idlaporan'];
        array_push($konten["data"], $row['attributes']);
    }
}

//Peringatan dini
$json = json_encode($konten, true);
$dt = json_decode($json, true);
$a = count($dt['data']);
// if ($a > 0) {
$cek = getOne("SELECT bmkg.id_laporan FROM bmkg ORDER BY bmkg.waktu_dibuat DESC");
$sql = "INSERT INTO bmkg (id,id_laporan,analis,id_kec,nm_kec,id_kab,nm_kab,provinsi,dampak,area,indeksresiko,waktu_berlaku,waktu_berakhir,waktu_dibuat,info_asal) VALUES ";
for ($b = 0; $b < $a; $b++) {
    if ($cek != $dt['data'][$b]['idlaporan']) {
        $sql .= '("' . $dt['data'][$b]['idlaporan'] . "/" . $dt['data'][$b]['idkecamatan'] . '","' . $dt['data'][$b]['idlaporan'] . '","' . $dt['data'][$b]['namaanalis'] . '", "' . $dt['data'][$b]['idkecamatan'] . '", "' . $dt['data'][$b]['namakecamatan'] . '", "' . $dt['data'][$b]['idkotakab'] . '",
        "' . $dt['data'][$b]['namakotakab'] . '", "' . $dt['data'][$b]['namaprovinsi'] . '","' . $dt['data'][$b]['kategoridampak'] . '","' . $dt['data'][$b]['tipearea'] . '","' . $dt['data'][$b]['indeksrisiko'] . '",
        "' . date('Y-m-d H:i:s', $dt['data'][$b]['waktuberlaku'] / 1000) . '","' . date('Y-m-d H:i:s', $dt['data'][$b]['waktuberakhir'] / 1000) . '","' . date('Y-m-d H:i:s', $dt['data'][$b]['waktupembuatan'] / 1000) . '","' . $dt['data'][$b]['upt'] . '"),';
    }
}
$query = rtrim($sql, ',');
bukaquery2($query);
// }

// echo $query;
// echo json_encode($konten, true);
curl_close($curl);

?>
