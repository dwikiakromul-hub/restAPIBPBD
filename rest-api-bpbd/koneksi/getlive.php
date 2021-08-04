<?php

/***
 * Portal API BPBD || BPBD DKI JAKARTA from version 0.1
 * About : Rest API BPBD DKI JAKARTA.
 * Last modified: 13 Mei 2021
 * Author : Dwiki Akromul H
 ***/

require_once('../koneksi/conf.php');

function bmkg()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://warningcuaca.bmkg.go.id/arcgis/rest/services/production/nowcasting___publik/MapServer/0/query?where=1=1&geometryType=esriGeometryEnvelope&%20spatialRel=esriSpatialRelIntersects&%20units=esriSRUnit_Foot&outFields=*&returnGeometry=true&returnTrueCurves=false&%20returnIdsOnly=false&returnCountOnly=false&%20returnZ=false&returnM=false&%20returnDistinctValues=false&%20returnExtentOnly=false&%20featureEncoding=esriDefault&f=pjson',
        CURLOPT_RETURNTRANSFER => true,
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
    $json = json_encode($konten, true);
    $dt = json_decode($json, true);
    $a = count($dt['data']);
    if ($a > 0) {
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
    }
    // echo $query;
    // echo json_encode($konten, true);
    curl_close($curl);
}

function call()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_PORT => "8080",
        CURLOPT_URL => "http://10.15.92.76:8080/rest-api-bpbd/cc/liveCall",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(

            "x-token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6IkJQQkRDQyIsInBhc3N3b3JkIjoic2FsYW1UYW5nZ3VoISEifQ.Q6aGhtFTkVdwzlEdyTVfzkjqlkwzFxR5r5VlpWwpaN8"
        ),
    ));

    $response = curl_exec($curl);
    $decode = json_decode($response, true);

    $json = json_encode($decode, true);
    $dt = json_decode($json, true);
    $a = count($dt['data']);
    if ($a > 0) {
        $cek = getOne("SELECT cc_call.ticket_kode FROM cc_call ORDER BY cc_call.tanggal DESC");
        $sql = "INSERT INTO cc_call (id,ticket_kode,no_pelapor,jenis_telpon,tanggal,id_pembuat,durasi_telpon) VALUES ";
        for ($b = 0; $b < $a; $b++) {
            if ($cek != $dt['data'][$b]['ticket_kode']) {
                $sql .= '("' . $dt['data'][$b]['ticket_kode'] . "/" . $dt['data'][$b]['no_pelapor'] . '",
            "' . $dt['data'][$b]['ticket_kode'] . '",
            "' . $dt['data'][$b]['no_pelapor'] . '", 
            "' . $dt['data'][$b]['jenis_telpon'] . '",
            "' . $dt['data'][$b]['tanggal'] . '", 
            "' . $dt['data'][$b]['id_pembuat'] . '",
            "' . $dt['data'][$b]['durasi_telpon'] . '"),';
            }
        }
        $query = rtrim($sql, ',');
        bukaquery2($query);
    }
    // echo $query;
    // echo json_encode($decode, true);
    curl_close($curl);
}

function ticket()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_PORT => "8080",
        CURLOPT_URL => "http://10.15.92.76:8080/rest-api-bpbd/cc/liveTicket",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(

            "x-token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6IkJQQkRDQyIsInBhc3N3b3JkIjoic2FsYW1UYW5nZ3VoISEifQ.Q6aGhtFTkVdwzlEdyTVfzkjqlkwzFxR5r5VlpWwpaN8"
        ),
    ));

    $response = curl_exec($curl);
    $decode = json_decode($response, true);


    $json = json_encode($decode, true);
    $dt = json_decode($json, true);
    $a = count($dt['data']);
    if ($a > 0) {
        $cek = getOne("SELECT cc_ticket.ticket_kode FROM cc_ticket ORDER BY cc_ticket.tanggal DESC");
        $sql = "INSERT INTO cc_ticket (id,ticket_kode,status_ticket,nama_pelapor,no_pelapor,alamat_manual,alamat_auto,permasalahan,id_kategori,
    kategori_permasalahan,id_subkategori,subkategori_permasalahan,jenis_permasalahan,id_kecamatan,
    kecamatan,id_kabupaten,kabupaten,tipe_permasalahan,latitude,longitude,id_pembuat,tanggal,durasi_telpon) VALUES ";
        for ($b = 0; $b < $a; $b++) {
            if ($cek != $dt['data'][$b]['ticket_kode']) {
                $sql .= '("' . $dt['data'][$b]['ticket_kode'] . "/" . $dt['data'][$b]['no_pelapor'] . '",
            "' . $dt['data'][$b]['ticket_kode'] . '",
            "' . $dt['data'][$b]['status_ticket'] . '",
            "' . $dt['data'][$b]['nama_pelapor'] . '", 
            "' . $dt['data'][$b]['no_pelapor'] . '",
            "' . $dt['data'][$b]['alamat_manual'] . '", 
            "' . $dt['data'][$b]['alamat_auto'] . '",
            "' . $dt['data'][$b]['permasalahan'] . '",
            "' . $dt['data'][$b]['id_kategori'] . '",
            "' . $dt['data'][$b]['kategori_permasalahan'] . '",
            "' . $dt['data'][$b]['id_subkategori'] . '",
            "' . $dt['data'][$b]['subkategori_permasalahan'] . '",
            "' . $dt['data'][$b]['jenis_permasalahan'] . '",
            "' . $dt['data'][$b]['id_kecamatan'] . '",
            "' . $dt['data'][$b]['kecamatan'] . '",
            "' . $dt['data'][$b]['id_kabupaten'] . '",
            "' . $dt['data'][$b]['kabupaten'] . '",
            "' . $dt['data'][$b]['tipe_permasalahan'] . '",
            "' . $dt['data'][$b]['latitude'] . '",
            "' . $dt['data'][$b]['longitude'] . '",
            "' . $dt['data'][$b]['id_pembuat'] . '",
            "' . $dt['data'][$b]['tanggal'] . '",
            "' . $dt['data'][$b]['durasi_telpon'] . '"),';
            }
        }
        $query = rtrim($sql, ',');
        bukaquery2($query);
    }
    // echo $query;
    // echo json_encode($konten, true);
    curl_close($curl);
}

function status()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_PORT => "8080",
        CURLOPT_URL => "http://10.15.92.76:8080/rest-api-bpbd/cc/liveTicket-status",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(

            "x-token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6IkJQQkRDQyIsInBhc3N3b3JkIjoic2FsYW1UYW5nZ3VoISEifQ.Q6aGhtFTkVdwzlEdyTVfzkjqlkwzFxR5r5VlpWwpaN8"
        ),
    ));

    $response = curl_exec($curl);
    $decode = json_decode($response);
    $data = $decode->data;
    $d = array("triger" => 1);
    $b = [];
    $sql = bukaquery("SELECT ticket_kode,status_ticket FROM cc_ticket WHERE status_ticket != 'Closed' Order By tanggal Desc");
    while ($row = fetch_array($sql)) {
        $z = array(
            "ticket_kode" => $row['ticket_kode']
        );
        array_push($data, $z);
        $json = array_count_values(array_filter(array_column($data, "ticket_kode")));
        $b = array(
            'ticket_kode' => $cutoff = array_diff($json, $d),
            'ticket_kode' => array_keys($cutoff)
        );
        foreach ($b as $row) {
            $g = $row;
        }
    }
    $k = count($g);
    $sql1 = "UPDATE cc_ticket set status_ticket='Closed' where ticket_kode= '0' ";
    for ($i = 0; $i < $k; $i++) {
        $sql1 .= "or ticket_kode= '$g[$i]'";
    }
    $query = rtrim($sql1, ',');
    bukainputJson($query);
}

status();
call();
ticket();
bmkg();
