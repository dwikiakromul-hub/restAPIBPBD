

<?php
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
print_r($response);
