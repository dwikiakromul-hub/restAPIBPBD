<?php

/***
 * Portal API BPBD || BPBD DKI JAKARTA from version 0.1
 * About : Rest API BPBD DKI JAKARTA.
 * Last modified: 13 Mei 2021
 * Author : Dwiki Akromul H
 ***/


require_once '../koneksi/conf.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$url = isset($_GET['url']) ? $_GET['url'] : '/';
$url = explode("/", $url);
$header = apache_request_headers();
$method = $_SERVER['REQUEST_METHOD'];

//metode GET
if ($method == 'GET' && !empty($header['x-username'])) {
    $hash_user = hash_pass($header['x-username'], 12);
    $hash_pass = hash_pass($header['x-password'], 12);
    switch ($url[0]) {

        case "auth":
            //verivikasi user dan pass
            if (!empty($header['x-password'])) {
                if (password_verify(USERNAME, $hash_user) and password_verify(PASSWORD, $hash_pass)) {
                    $response = array(
                        'response' => array(
                            'token' => getTokenPublic()
                        ),
                        'metaData' => array(
                            'message' => 'Ok',
                            'code' => 200
                        )
                    );
                } else {
                    $response = array(
                        'metaData' => array(
                            'message' => 'Access denied',
                            'code' => 401
                        )
                    );
                }
            }
            break;

        case "peringatan-dini":
            if (password_verify(USERNAME, $hash_user) and $header['x-token'] == getTokenPublic()) {
                $y['tmp'] = array();
                $sql = bukaquery("SELECT bmkg.id_laporan, bmkg.id_kec, bmkg.id_kab,bmkg.analis,bmkg.nm_kec,bmkg.nm_kab,bmkg.provinsi,
                    bmkg.dampak,bmkg.area,bmkg.indeksresiko,bmkg.waktu_berlaku,bmkg.waktu_berakhir,bmkg.waktu_dibuat,bmkg.info_asal
                    FROM bmkg WHERE NOW() <= waktu_berakhir");
                while ($row = fetch_array($sql)) {
                    $z = array(
                        'nm_analis' => $row['analis'],
                        'idKec' => $row['id_kec'],
                        'namaKec' => $row['nm_kec'],
                        'idKab' => $row['id_kab'],
                        'namaKab' => $row['nm_kab'],
                        'provinsi' => $row['provinsi'],
                        'dampak' => $row['dampak'],
                        'area' => $row['area'],
                        'indeksresiko' => $row['indeksresiko'],
                        'waktu_berlaku' => strtotime($row['waktu_berlaku']) * 1000,
                        'waktu_berakhir' => strtotime($row['waktu_berakhir']) * 1000,
                        'waktu_dibuat' => strtotime($row['waktu_dibuat']) * 1000,
                        'sumber' => $row['info_asal']
                    );
                    array_push($y['tmp'], $z);
                }
                if (count($y['tmp']) > 0) {
                    $response = array(
                        'data' => $y['tmp'],
                        'metaData' => array(
                            'message' => 'Ok',
                            'code' => 200
                        )
                    );
                } else {
                    $response = array(
                        'metaData' => array(
                            'message' => 'Maaf Belum Terdapat Peringatan Dini',
                            'code' => 404
                        )
                    );
                }
            } else {
                $response = array(
                    'metadata' => array(
                        'message' => 'Access denied',
                        'code' => 401
                    )
                );
            }
            break;

            // get data tiket 3 jam terakhir Emergency
        case "liveTicket-1":
            if (password_verify(USERNAME, $hash_user) and $header['x-token'] == getTokenPublic()) {
                $y['tmp'] = array();
                $sql = bukaquery("SELECT ticket_kode,nama_pelapor,no_pelapor,alamat_auto,permasalahan,kategori_permasalahan,kecamatan,kabupaten,tanggal,jenis_permasalahan
                FROM cc_ticket WHERE tanggal > DATE_SUB(NOW(), INTERVAL 3 HOUR) AND jenis_permasalahan = 'Emergency' ORDER BY tanggal DESC");
                while ($row = fetch_array($sql)) {
                    $z = array(
                        'ticket_kode' => $row['ticket_kode'],
                        'nama_pelapor' => substr($row['nama_pelapor'], 0, 7) . 'xxx',
                        'no_pelapor' => substr($row['no_pelapor'], 0, 7) . 'xxx',
                        'alamat_auto' => $row['alamat_auto'],
                        'kategori_permasalahan' => $row['permasalahan'],
                        'kecamatan' => $row['kecamatan'],
                        'kabupaten' => $row['kabupaten'],
                        'tanggal' => $row['tanggal'],
                        'jenis_permasalahan' => $row['jenis_permasalahan']
                    );
                    array_push($y['tmp'], $z);
                }
                if (count($y['tmp']) > 0) {
                    $response = array(
                        'data' => $y['tmp'],
                        'metaData' => array(
                            'message' => 'Ok',
                            'code' => 200
                        )
                    );
                } else {
                    $response = array(
                        'metaData' => array(
                            'message' => 'Maaf belum ada ticket emergency masuk',
                            'code' => 404
                        )
                    );
                }
            } else {
                $response = array(
                    'metadata' => array(
                        'message' => 'Access denied',
                        'code' => 401
                    )
                );
            }
            break;

            // get data tiket 3 jam terakhir Non Emergency
        case "liveTicket-2":
            if (password_verify(USERNAME, $hash_user) and $header['x-token'] == getTokenPublic()) {
                $y['tmp'] = array();
                $sql = bukaquery("SELECT ticket_kode,nama_pelapor,no_pelapor,alamat_auto,permasalahan,kategori_permasalahan,kecamatan,kabupaten,tanggal,jenis_permasalahan
                FROM cc_ticket WHERE tanggal > DATE_SUB(NOW(), INTERVAL 3 HOUR) AND jenis_permasalahan = 'Non Emergency' ORDER BY tanggal DESC");
                while ($row = fetch_array($sql)) {
                    $z = array(
                        'ticket_kode' => $row['ticket_kode'],
                        'nama_pelapor' => substr($row['nama_pelapor'], 0, 7) . 'xxx',
                        'no_pelapor' => substr($row['no_pelapor'], 0, 7) . 'xxx',
                        'alamat_auto' => $row['alamat_auto'],
                        'kategori_permasalahan' => $row['permasalahan'],
                        'kecamatan' => $row['kecamatan'],
                        'kabupaten' => $row['kabupaten'],
                        'tanggal' => $row['tanggal'],
                        'jenis_permasalahan' => $row['jenis_permasalahan']
                    );
                    array_push($y['tmp'], $z);
                }
                if (count($y['tmp']) > 0) {
                    $response = array(
                        'data' => $y['tmp'],
                        'metaData' => array(
                            'message' => 'Ok',
                            'code' => 200
                        )
                    );
                } else {
                    $response = array(
                        'metaData' => array(
                            'message' => 'Maaf belum ada ticket emergency masuk',
                            'code' => 404
                        )
                    );
                }
            } else {
                $response = array(
                    'metadata' => array(
                        'message' => 'Access denied',
                        'code' => 401
                    )
                );
            }
            break;

            // get data call 3 jam terakhir Sucsesfully Call
        case "liveCall-1":
            if (password_verify(USERNAME, $hash_user) and $header['x-token'] == getTokenPublic()) {
                $y['tmp'] = array();
                $sql = bukaquery("SELECT ticket_kode,jenis_telpon,no_pelapor,tanggal
                FROM cc_call WHERE tanggal > DATE_SUB(NOW(), INTERVAL 3 HOUR) AND jenis_telpon = 'Successfully Call' ORDER BY tanggal DESC");
                while ($row = fetch_array($sql)) {
                    $z = array(
                        'no_pelapor' => substr($row['no_pelapor'], 0, 7) . 'xxx',
                        'tanggal' => $row['tanggal'],
                        'jenis_telpon' => $row['jenis_telpon']
                    );
                    array_push($y['tmp'], $z);
                }
                if (count($y['tmp']) > 0) {
                    $response = array(
                        'data' => $y['tmp'],
                        'metaData' => array(
                            'message' => 'Ok',
                            'code' => 200
                        )
                    );
                } else {
                    $response = array(
                        'metaData' => array(
                            'message' => 'Maaf belum ada Sucsesfully Call masuk',
                            'code' => 404
                        )
                    );
                }
            } else {
                $response = array(
                    'metadata' => array(
                        'message' => 'Access denied',
                        'code' => 401
                    )
                );
            }
            break;

            // get data call 3 jam terakhir Drop Call
        case "liveCall-2":
            if (password_verify(USERNAME, $hash_user) and $header['x-token'] == getTokenPublic()) {
                $y['tmp'] = array();
                $sql = bukaquery("SELECT ticket_kode,jenis_telpon,no_pelapor,tanggal
                FROM cc_call WHERE tanggal > DATE_SUB(NOW(), INTERVAL 3 HOUR) AND jenis_telpon = 'Drop Call' ORDER BY tanggal DESC");
                while ($row = fetch_array($sql)) {
                    $z = array(
                        'no_pelapor' => substr($row['no_pelapor'], 0, 7) . 'xxx',
                        'tanggal' => $row['tanggal'],
                        'jenis_telpon' => $row['jenis_telpon']
                    );
                    array_push($y['tmp'], $z);
                }
                if (count($y['tmp']) > 0) {
                    $response = array(
                        'data' => $y['tmp'],
                        'metaData' => array(
                            'message' => 'Ok',
                            'code' => 200
                        )
                    );
                } else {
                    $response = array(
                        'metaData' => array(
                            'message' => 'Maaf belum ada Drop Call masuk',
                            'code' => 404
                        )
                    );
                }
            } else {
                $response = array(
                    'metadata' => array(
                        'message' => 'Access denied',
                        'code' => 401
                    )
                );
            }
            break;


            // get data call 3 jam terakhir Prank Call
        case "liveCall-3":
            if (password_verify(USERNAME, $hash_user) and $header['x-token'] == getTokenPublic()) {
                $y['tmp'] = array();
                $sql = bukaquery("SELECT ticket_kode,jenis_telpon,no_pelapor,tanggal
                FROM cc_call WHERE tanggal > DATE_SUB(NOW(), INTERVAL 3 HOUR) AND jenis_telpon = 'Prank Call' ORDER BY tanggal DESC");
                while ($row = fetch_array($sql)) {
                    $z = array(
                        'no_pelapor' => substr($row['no_pelapor'], 0, 7) . 'xxx',
                        'tanggal' => $row['tanggal'],
                        'jenis_telpon' => $row['jenis_telpon']
                    );
                    array_push($y['tmp'], $z);
                }
                if (count($y['tmp']) > 0) {
                    $response = array(
                        'data' => $y['tmp'],
                        'metaData' => array(
                            'message' => 'Ok',
                            'code' => 200
                        )
                    );
                } else {
                    $response = array(
                        'metaData' => array(
                            'message' => 'Maaf belum ada Prank Call masuk',
                            'code' => 404
                        )
                    );
                }
            } else {
                $response = array(
                    'metadata' => array(
                        'message' => 'Access denied',
                        'code' => 401
                    )
                );
            }
            break;
    }
}

if (!empty($response)) {
    header("Content-Type: application/json");
    echo json_encode($response);
} else {
    header("location:../welcome");
}
