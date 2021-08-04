<?php

/***
 * Portal API BPBD || BPBD DKI JAKARTA from version 0.1
 * About : Rest API BPBD DKI JAKARTA.
 * Last modified: 13 Mei 2021
 * Author : Dwiki Akromul H
 ***/


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

date_default_timezone_set('Asia/Jakarta');

require_once('../koneksi/conf.php');

$method = $_SERVER['REQUEST_METHOD'];
$url = isset($_GET['url']) ? $_GET['url'] : '/';
$url = explode("/", $url);

if ($method == 'POST') {
    switch ($url[0]) {

        default:
            echo "Selamat Datang di API BPBD Prov DKI Jakarta Call Center";

            break;


        case "auth":
            // $username = 'BPBDCC';
            // $password = 'salamTangguh!!';
            $konten = trim(file_get_contents("php://input"));
            $decode = json_decode($konten, true);
            $response = array();
            if ($decode['username'] == 'BPBDCC' && $decode['password'] == 'salamTangguh!!') {
                $response = array(
                    'response' => array(
                        'token' => getToken()
                    ),
                    'metadata' => array(
                        'message' => 'Ok',
                        'code' => 200
                    )
                );
            } else {
                $response = array(
                    'metadata' => array(
                        'message' => 'Access denied',
                        'code' => 401
                    )
                );
            }
            echo json_encode(array("response" => $response));

            break;

        case "liveTicket":
            $header = apache_request_headers();
            $konten = trim(file_get_contents("php://input"));
            $decode = json_decode($konten, true);
            if ($header['x-token'] == getToken()) {
                $sql = "SELECT 
                        Ds_history.ticket_code,
                        Ds_history.history_fullname,
                        Ds_history.history_inumber,
                        Ds_transaction.trans_addrtypo,
                        Ds_transaction.trans_addr,
                        Ds_transaction.trans_desc,
                        DsCase_category.category_caseid, 
                        DsCase_category.category_casename,
                        Ds_transaction.subcategory_caseid,
                        DsCase_Subcategory.subcategory_casename,
                        DS_CaseType.type_casename,
                        Ds_transaction.kecamatan_id,                
                        Ds_kecamatan.kecamatan_name,
                        Ds_city.city_id,
                        Ds_city.city_name,
                        Ds_transaction.trans_status,
                        Ds_transaction.trans_for, 
                        Ds_transaction.trans_lat,
                        Ds_transaction.trans_long,
                        Ds_history.history_createby,
                        Ds_transaction.trans_createdate,
                        Ds_history.history_duration
                        FROM Ds_transaction
                        JOIN Ds_history  ON  Ds_transaction.ticket_code = Ds_history.ticket_code 
                        JOIN Ds_Status ON Ds_history.status_id = Ds_Status.status_id
                        JOIN DsCase_Subcategory ON Ds_transaction.subcategory_caseid = DsCase_Subcategory.subcategory_caseid
                        JOIN DsCase_category ON DsCase_Subcategory.subcategory_casecategoryid = DsCase_category.category_caseid
                        JOIN DS_CaseType ON DsCase_category.category_casetypeid = DS_CaseType.type_caseid
                        JOIN Ds_kecamatan ON Ds_transaction.kecamatan_id = Ds_kecamatan.kecamatan_id
                        JOIN Ds_city ON Ds_kecamatan.city_id = Ds_city.city_id
                        where Ds_history.history_createdate >= DATEADD(HOUR, -1, GETDATE())
                        order by trans_createdate desc";

                $result = sqlsrv_query($conn, $sql);
                if ($result === false) {
                    echo "Error in executing query.</br>";
                    die;
                }
                $json = [];
                $i = 1;
                do {
                    while ($row = sqlsrv_fetch_array($result)) {
                        $json[] = [
                            'ticket_kode' => $row["ticket_code"],
                            'status_ticket' => $row["trans_status"],
                            'nama_pelapor' => $row["history_fullname"],
                            'no_pelapor' => $row["history_inumber"],
                            'alamat_manual' => $row["trans_addrtypo"],
                            'alamat_auto' => $row["trans_addr"],
                            'permasalahan' => $row["trans_desc"],
                            'id_kategori' => $row["category_caseid"],
                            'kategori_permasalahan' => $row["category_casename"],
                            'id_subkategori' => $row["subcategory_caseid"],
                            'subkategori_permasalahan' => $row["subcategory_casename"],
                            'jenis_permasalahan' => $row["type_casename"],
                            'id_kecamatan' => $row["kecamatan_id"],
                            'kecamatan' => $row["kecamatan_name"],
                            'id_kabupaten' => $row["city_id"],
                            'kabupaten' => $row["city_name"],
                            'tipe_permasalahan' => $row["trans_for"],
                            'latitude' => $row["trans_lat"],
                            'longitude' => $row["trans_long"],
                            'id_pembuat' => $row["history_createby"],
                            'tanggal' => $row["trans_createdate"]->format('Y-m-d H:i:s'),
                            'durasi_telpon' => $row["history_duration"] . ' detik'
                        ];
                        $i = $i + 1;
                    }
                } while (sqlsrv_next_result($result));
            } else {
                $json = array(
                    'metadata' => array(
                        'message' => 'Access denied',
                        'code' => 401
                    )
                );
            }
            $d = array('data' => $json);
            echo json_encode($d, true);
            break;


            //get data call 3jam terakhir
        case "liveCall":
            $header = apache_request_headers();
            $konten = trim(file_get_contents("php://input"));
            $decode = json_decode($konten, true);
            if ($header['x-token'] == getToken()) {
                $sql = "SELECT
                    Ds_history.ticket_code, 
                    Ds_history.history_inumber, 
                    Ds_Status.status_name, 
                    Ds_history.history_createdate,
                    Ds_history.history_createby,
                    Ds_history.history_duration
                    FROM Ds_history
                    JOIN Ds_Status ON Ds_history.status_id = Ds_Status.status_id
                    where Ds_history.history_createdate >= DATEADD(HOUR, -1, GETDATE()) 
                    order by history_createdate desc";

                $result = sqlsrv_query($conn, $sql);
                if ($result === false) {
                    echo "Error in executing query.</br>";
                    die;
                }
                $json;
                $i = 1;
                do {
                    while ($row = sqlsrv_fetch_array($result)) {
                        $json[] = [
                            'ticket_kode' => $row["ticket_code"],
                            'no_pelapor' => $row["history_inumber"],
                            'jenis_telpon' => $row["status_name"],
                            'tanggal' => $row["history_createdate"]->format('Y-m-d H:i:s'),
                            'id_pembuat' => $row["history_createby"],
                            'durasi_telpon' => $row["history_duration"] . ' detik'
                        ];
                        $i = $i + 1;
                    }
                } while (sqlsrv_next_result($result));
            } else {
                $json = array(
                    'metadata' => array(
                        'message' => 'Access denied',
                        'code' => 401
                    )
                );
            }

            $d = array('data' => $json);
            echo json_encode($d, true);
            break;

        case "liveTicket-status":
            $header = apache_request_headers();
            $konten = trim(file_get_contents("php://input"));
            $decode = json_decode($konten, true);
            if ($header['x-token'] == getToken()) {
                $sql = "SELECT 
                Ds_transaction.ticket_code,
                Ds_transaction.trans_createdate,
                Ds_transaction.trans_status
                FROM Ds_transaction
                where trans_status = 'Closed' and trans_createdate >= DATEADD(day, -10, GETDATE())
                order by trans_createdate desc";

                $result = sqlsrv_query($conn, $sql);
                if ($result === false) {
                    echo "Error in executing query.</br>";
                    die;
                }
                $json = [];
                $i = 1;
                do {
                    while ($row = sqlsrv_fetch_array($result)) {
                        $json[] = [
                            'ticket_kode' => $row["ticket_code"]

                        ];
                        $i = $i + 1;
                    }
                } while (sqlsrv_next_result($result));
            } else {
                $json = array(
                    'metadata' => array(
                        'message' => 'Access denied',
                        'code' => 401
                    )
                );
            }
            $d = array('data' => $json);
            echo json_encode($d, true);
            break;
    }
} else {
    //echo "Selamat Datang di API BPBD Prov DKI Jakarta Call Center ";
    header("location:../welcome");
}
