<?php
require_once('koneksi/conf.php');
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
    echo json_encode($konten, true);
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


    curl_close($curl);

    echo json_encode($decode);
}

function ticket()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://10.15.92.76:8080/rest-api-bpbd/public/liveCall-1',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'x-token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6IkJQQkRCTUtHISEiLCJwYXNzd29yZCI6IlM0bGFtVDRuZ2d1aCEjIiwiZGF0ZSI6IjIwMjEtMDYtMTMgMTQifQ.ZL85QOvSTQNJRQ6MiDQmsGPasbw523a7uZDbxJ7gBsQ',
            'x-username: BPBDBMKG!!',
            'x-password: S4lamT4ngguh!#'
        ),
    ));

    $response = curl_exec($curl);
    $decode = json_decode($response, true);

    curl_close($curl);
    echo json_encode($decode);
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
    for ($l = 0; $l < $k; $l++) {
        $update = "UPDATE cc_ticket set status_ticket='Closed' where ticket_kode = '$g[$l]'";
        mysqli_query(bukakoneksi(), $update);
    }




    // for ($l = 0; $l < $g; $l++) {
    //     $update = "UPDATE cc_ticket set status_ticket='Closed' where ticket_kode = '$g[$l]'";
    //     mysqli_query(bukakoneksi(), $update);
    // }








    // echo json_encode($o);
    //echo json_encode($g);

    //echo json_encode($data);

    // $cek = getOne("SELECT cc_ticket.ticket_kode FROM cc_ticket WHERE status_ticket != 'Closed' Order By tanggal Desc");
    // $sql = "UPDATE cc_ticket set status_ticket='Closed' where ticket_kode = '";

    //curl_close($curl);


    //echo json_encode($sql);
}


status();
