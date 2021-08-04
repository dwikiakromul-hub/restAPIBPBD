<?php

/***
 * Portal API BPBD || BPBD DKI JAKARTA from version 0.1
 * About : Rest API BPBD DKI JAKARTA.
 * Last modified: 13 Mei 2021
 * Author : Dwiki Akromul H
 ***/


date_default_timezone_set('Asia/Jakarta');
//koneksi database
$db_hostname = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "db_kebencanaan";
define('ENVIRONMENT', 'Production'); //Production OR Devlopment

$serverName = "10.15.96.73";
$uid = "sa";
$pwd = "M1tracomm";
$connectionInfo = array(
    "UID" => $uid,
    "PWD" => $pwd,
    "Database" => "Dispatcher",
    "CharacterSet" => "UTF-8"
);
$conn = sqlsrv_connect($serverName, $connectionInfo);

//user static
define('USERNAME', 'BPBDBMKG!!');
define('PASSWORD', 'S4lamT4ngguh!#');



if (ENVIRONMENT == 'Production') {
    error_reporting(0);
}
function fetch_array($sql)
{
    $while = mysqli_fetch_array($sql);
    return $while;
}

function fetch_assoc($sql)
{
    $while = mysqli_fetch_assoc($sql);
    return $while;
}

function num_rows($sql)
{
    $while = mysqli_num_rows($sql);
    return $while;
}

function real_escape($value)
{
    return mysqli_real_escape_string(bukakoneksi(), $value);
}

function bukakoneksi()
{
    global $db_hostname, $db_username, $db_password, $db_name;
    $konektor = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("<font color=red><h3>Not Connected ..!!</h3></font>");
    return $konektor;
}

$sqlinjectionchars = array("=", "-", "'", "\"", "+"); //tambah sendiri


function safe_query($format)
{
    $args = array_slice(func_get_args(), 1);
    $args = array_map('mysqli_safe_string', $args);
    $query = vsprintf($format, $args);
    return mysqli_query(bukakoneksi(), $query);
}

function validangka($angka)
{
    if (!is_numeric($angka)) {
        return 0;
    } else {
        return $angka;
    }
}



function tutupkoneksi()
{
    return mysqli_close(bukakoneksi());
}

function bukaquery($sql)
{
    $result = mysqli_query(bukakoneksi(), $sql) or die(mysqli_error(bukakoneksi()) . "<br/><font color=red><b>hmmmmmmm.....??????????</b>");
    return $result;
}

function bukaquery2($sql)
{
    $result = mysqli_query(bukakoneksi(), $sql);
    return $result;
}

function read($sql)
{
    $result = mysqli_query(bukakoneksi(), $sql);
    return $result;
}

function bukainput($sql)
{
    $result = mysqli_query(bukakoneksi(), $sql)
        //or die(mysql_error()."<br/><font color=red><b>Gagal</b>, Ada data dengan primary key yang sama !");
        or die("<br/><script>alert('Gagal, Ada data dengan primary key yang sama !');window.location = '$_SERVER[HTTP_REFERER]'</script>");
    return $result;
}

function errorJson()
{
    $send_data = array();
    $send_data['state'] = 'error';
    echo json_encode($send_data);
}

function DuplicateJson()
{
    $send_data = array();
    $send_data['state'] = 'duplicate';
    echo json_encode($send_data);
}

function MassageJson($massage)
{
    $send_data = array();
    $send_data['state'] = $massage;
    echo json_encode($send_data);
}

function succsesJson()
{
    $send_data = array();
    $send_data['state'] = 'succses';
    echo json_encode($send_data);
}

function bukainputJson($sql)
{
    $result = mysqli_query(bukakoneksi(), $sql);
    return $result;
}

function JsonResult($status, $massage)
{
    $results = array("state" => $status, "massage" => $massage);
    exit(json_encode($results));
}

function bukainputval($sql)
{
    $result = mysqli_query(bukakoneksi(), $sql)
        or die("<br/><script>alert('Maaf ada beberapa data yang belum di validasi PJ, silakah hub Pj terkait !');window.location = '$_SERVER[HTTP_REFERER]'</script>");
    return $result;
}

function bukainput2($sql)
{
    $result = mysqli_query(bukakoneksi(), $sql)
        or die("<br/><script>alert('Gagal, Ada data dengan primary key yang sama !');window.location = '$_SERVER[HTTP_REFERER]'</script>");
    return $result;
}

function bukainputcek($sql)
{
    $result = mysqli_query(bukakoneksi(), $sql)
        or die("<br/><script>alert('Gagal, Data Ini Sudah Pernah Di Buat !');window.location = '$_SERVER[HTTP_REFERER]'</script>");
    return $result;
}

function hapusinput($sql)
{
    $result = mysqli_query(bukakoneksi(), $sql)
        or die("<br/><script>alert('Gagal, Data masih dipakai di tabel lain !');window.location = '$_SERVER[HTTP_REFERER]'</script>");
    return $result;
}

function Tambah($tabelname, $attrib, $pesan)
{

    $command = bukainput("INSERT INTO " . $tabelname . " VALUES (" . $attrib . ")");
    echo "<img src='images/simpan.gif' />&nbsp;&nbsp; Data $pesan berhasil disimpan";
    return $command;
}

function Tambah2($tabelname, $attrib, $pesan)
{

    $command = bukainput("INSERT INTO " . $tabelname . " VALUES (" . $attrib . ")");
    echo "<img src='images/simpan.gif' />&nbsp;&nbsp; <font size='9'>Data $pesan berhasil disimpan</font>";
    return $command;
}

function InsertData($tabelname, $attrib)
{
    $command = bukaquery("INSERT INTO " . $tabelname . " VALUES (" . $attrib . ")");
    return $command;
}

function InsertData2($tabelname, $attrib)
{
    $command = bukaquery2("INSERT INTO " . $tabelname . " VALUES (" . $attrib . ")");
    return $command;
}

function EditData($tabelname, $attrib)
{
    $command = bukaquery("UPDATE " . $tabelname . " SET " . $attrib . " ");
    return $command;
}

function Ubah($tabelname, $attrib, $pesan)
{
    $command = bukaquery("UPDATE " . $tabelname . " SET " . $attrib . " ");
    echo "<img src='images/simpan.gif' />&nbsp;&nbsp; Data $pesan berhasil diubah";
    return $command;
}

function edit($sql)
{
    $result = mysqli_query(bukakoneksi(), $sql);
    return $result;
}

function Ubah2($tabelname, $attrib)
{
    $command = bukaquery("UPDATE " . $tabelname . " SET " . $attrib . " ");
    return $command;
}

function Hapus($tabelname, $param, $hal)
{
    $sql = "DELETE FROM " . $tabelname . " WHERE " . $param . " ";
    $command = hapusinput($sql);
    Zet($hal);
    return $command;
}

function Hapus2($tabelname, $param)
{
    $sql = "DELETE FROM " . $tabelname . " WHERE " . $param . " ";
    $command = hapusinput($sql);
    return $command;
}

function HapusAll($tabelname)
{
    $sql = "DELETE FROM " . $tabelname;
    $command = bukaquery($sql);
    return $command;
}

function deletegb($sql)
{
    $_sql = $sql;
    $hasil = bukaquery($_sql);
    $baris = mysqli_fetch_row($hasil);
    $gb = $baris[0];
    $hapus = unlink($gb);
}

function JSRedirect($url)
{
    echo "<html><head><title></title><meta http-equiv='refresh' content='1;URL=$url'></head><body></body></html>";
}

function Zet($url)
{
    echo "<html><head><title></title><meta http-equiv='refresh' content='0;URL=$url'></head><body></body></html>";
}

function JurusKibasNaga()
{
    $id = $_SERVER['REMOTE_ADDR'];
    $sql = bukaquery("DELETE FROM tmp WHERE ID='$id'");
    return $sql;
}

function konversiTgl($tanggal)
{
    list($thn, $bln, $tgl) = explode('-', $tanggal);
    $tmp = $tgl . "-" . $bln . "-" . $thn;
    return $tmp;
}

function konversiBulan($bln)
{
    switch ($bln) {
        case "01":
            $bulan = "Januari";
            break;
        case "02":
            $bulan = "Februari";
            break;
        case "03":
            $bulan = "Maret";
            break;
        case "04":
            $bulan = "April";
            break;
        case "05":
            $bulan = "Mei";
            break;
        case "06":
            $bulan = "Juni";
            break;
        case "07":
            $bulan = "Juli";
            break;
        case "08":
            $bulan = "Agustus";
            break;
        case "09":
            $bulan = "September";
            break;
        case "10":
            $bulan = "Oktober";
            break;
        case "11":
            $bulan = "Nopember";
            break;
        case "12":
            $bulan = "Desember";
            break;
        default:
            $bulan = "Tidak Sesuai";
    }
    return $bulan;
}

function konversiTanggal($tanggal)
{
    list($thn, $bln, $tgl) = explode('-', $tanggal);
    $tmp = $tgl . " " . konversiBulan($bln) . " " . $thn;
    return $tmp;
}

function konversiTanggalStrip($tanggal)
{
    if ($tanggal == '-') {
        $tmp = $tanggal;
    } else {
        list($thn, $bln, $tgl) = explode('-', $tanggal);
        $tmp = $tgl . " " . konversiBulan($bln) . " " . $thn;
    }
    return $tmp;
}

function konversiBulanTahun($tanggal)
{
    list($thn, $bln, $tgl) = explode('-', $tanggal);
    $tmp = konversiBulan($bln) . " " . $thn;
    return $tmp;
}

function konversiTanggalBulan($tanggal)
{
    list($thn, $bln, $tgl) = explode('-', $tanggal);
    $tmp = $tgl . " " . konversiBulan($bln);
    return $tmp;
}

function formatDuit($duit)
{
    return "Rp. " . number_format($duit, 0, ",", ".") . ",-";
}

function formatDuit2($duit)
{
    return number_format($duit, 0, ",", ".") . "";
}

function formatDuit3($duit)
{
    return number_format($duit, 2, ",", ".") . "";
}

function formatDec($duit)
{
    return round($duit, 3);
}

function formatDecDigit($duit, $digit)
{
    return round($duit, $digit);
}

function formatPembulatan($duit)
{
    return round($duit, 0);
}

function formatDuit2Terbilang($duit)
{
    return round($duit, 0);
}

function formatRound($duit)
{
    return str_replace(".", ",", round($duit, 5));
}

function JumlahBaris($result)
{
    return mysqli_num_rows($result);
}

function getOne($sql)
{
    $hasil = bukaquery($sql);
    list($result) = fetch_array($hasil);
    return $result;
}

function kelamin($jk)
{
    if ($jk == 'P') {
        $result = "Perempuan";
    } else {
        $result = "Laki-Laki";
    }
    return $result;
}

function cekKosong($sql)
{
    $jum = mysqli_num_rows($sql);
    if ($jum == 0)
        return true;
    else
        return false;
}

function loadTgl()
{
    echo "<option>-&nbsp</option>";
    for ($tgl = 1; $tgl <= 31; $tgl++) {
        $tgl_leng = strlen($tgl);
        if ($tgl_leng == 1)
            $i = "0" . $tgl;
        else
            $i = $tgl;
        echo "<option value=$i>$i</option>";
    }
}

function loadTglnow()
{
    $tglsekarang = date('d');
    echo "<option>" . $tglsekarang . "</option>";
    for ($tgl = 1; $tgl <= 31; $tgl++) {
        $tgl_leng = strlen($tgl);
        if ($tgl_leng == 1)
            $i = "0" . $tgl;
        else
            $i = $tgl;
        echo "<option value=$i>$i</option>";
    }
}

function loadTgl2()
{
    //echo "<option>-&nbsp</option>";
    for ($tgl = 1; $tgl <= 31; $tgl++) {
        $tgl_leng = strlen($tgl);
        if ($tgl_leng == 1)
            $i = "0" . $tgl;
        else
            $i = $tgl;
        echo "<option value=$i>$i</option>";
    }
}

function loadBln($placeholder)
{
    echo "<option>$placeholder</option>";
    for ($bln = 1; $bln <= 12; $bln++) {
        $bln_leng = strlen($bln);
        if ($bln_leng == 1)
            $i = "0" . $bln;
        else
            $i = $bln;
        echo "<option value=$i>" . konversiBulan($i) . "</option>";
    }
}

function updateloadBln($x)
{
    for ($bln = 1; $bln <= 12; $bln++) {
        $bln_leng = strlen($bln);
        $i = "0" . $bln;
        if ($x == $i) {
            echo "<option value=" . $x . " selected=" . $x . ">" . konversiBulan($i) . "</option>";
        } else {
            //            $i = $bln;
            echo "<option value=" . $i . ">" . konversiBulan($i) . "</option>";
        }
    }
}

function loadBlnnow($bulan_post)
{
    $blnsekarang = date('m');
    for ($bln = 1; $bln <= 12; $bln++) {
        $bln_leng = strlen($bln);
        if ($bln_leng == 1) {
            $i = "0" . $bln;
        } else {
            $i = $bln;
        }
        if ($i == $bulan_post) {
            echo "<option selected value=$bulan_post>$i</option>";
        } else {
            echo "<option  value=$i>$i</option>";
        }
    }
}

function loadBln2()
{
    //echo "<option>$placeholder</option>";
    for ($bln = 1; $bln <= 12; $bln++) {
        $bln_leng = strlen($bln);
        if ($bln_leng == 1)
            $i = "0" . $bln;
        else
            $i = $bln;
        echo "<option value=$i>$i</option>";
    }
}

function loadThn()
{
    $thnini = date('Y');
    echo "<option>-&nbsp</option>";
    for ($thn = $thnini; $thn >= 2017; $thn--) {
        $thn_leng = strlen($thn);
        if ($thn_leng == 1)
            $i = "0" . $thn;
        else
            $i = $thn;
        echo "<option value=$i>$i</option>";
    }
}

function loadThnnow($tahun_post)
{
    $thnini = date('Y');
    //echo "<option>-&nbsp</option>";
    for ($thn = $thnini; $thn >= 2017; $thn--) {
        if ($thn == $tahun_post) {
            echo "<option selected value=$tahun_post>$thn</option>";
        } else {
            echo "<option  value=$thn>$thn</option>";
        }
    }
}

function loadThn2()
{
    $thnini = date('Y');
    echo "<option>-&nbsp</option>";
    for ($thn = $thnini + 30; $thn >= 1960; $thn--) {
        $thn_leng = strlen($thn);
        if ($thn_leng == 1)
            $i = "0" . $thn;
        else
            $i = $thn;
        echo "<option value=$i>$i</option>";
    }
}

function loadThn3()
{
    $thnini = date('Y');
    //echo "<option>-&nbsp</option>";
    for ($thn = $thnini + 30; $thn >= 1960; $thn--) {
        $thn_leng = strlen($thn);
        if ($thn_leng == 1)
            $i = "0" . $thn;
        else
            $i = $thn;
        echo "<option value=$i>$i</option>";
    }
}

function loadThn4()
{
    $thnini = date('Y');
    //echo "<option>-&nbsp</option>";
    for ($thn = $thnini; $thn >= 1960; $thn--) {
        $thn_leng = strlen($thn);
        if ($thn_leng == 1)
            $i = "0" . $thn;
        else
            $i = $thn;
        echo "<option value=$i>$i</option>";
    }
}

function loadJam()
{
    //echo "<option selected>-----&nbsp</option>";
    for ($jam = 0; $jam <= 23; $jam++) {
        $jam_leng = strlen($jam);
        if ($jam_leng == 1)
            $i = "0" . $jam;
        else
            $i = $jam;
        echo "<option value=$i>$i</option>";
    }
}

function loadMenit()
{
    //echo "<option selected>-----&nbsp</option>";
    for ($menit = 0; $menit <= 60; $menit++) {
        $menit_leng = strlen($menit);
        if ($menit_leng == 1)
            $i = "0" . $menit;
        else
            $i = $menit;
        echo "<option value=$i>$i</option>";
    }
}

function autonomer($table, $field, $inisial, $panjang)
{
    $qry = bukaquery("SELECT max(" . $field . ") FROM " . $table);
    $row = fetch_array($qry);
    if ($row[0] == "") {
        $angka = 0;
    } else {
        $angka = substr($row[0], strlen($inisial));
    }
    $angka++;
    $angka = strval($angka);
    $tmp = "";
    for ($i = 1; $i <= ($panjang - strlen($inisial) - strlen($angka)); $i++) {
        $tmp = $tmp . "0";
    }
    return $inisial . $tmp . $angka;
}

function notiket($huruf, $id, $table)
{
    $tgl = "$huruf" . date('ymd');
    $query = "SELECT MAX(RIGHT($id, 3)) as max_id FROM $table Where $id LIKE '%$tgl%'";
    $id_max = getOne($query);
    $sort_num = $id_max;
    $sort_num++;
    $new_code = $tgl . sprintf("%03s", $sort_num++);
    return $new_code;
}

function nokiamat($id, $table)
{
    $tgl = date('ymd');
    $query = "SELECT MAX(RIGHT($id, 4)) as max_id FROM $table Where $id LIKE '%$tgl%'";
    $id_max = getOne($query);
    $sort_num = $id_max;
    $sort_num++;
    $new_code = $tgl . sprintf("%04s", $sort_num++);
    return $new_code;
}

function notahun($id, $table)
{
    $tgl = date('Y');
    $query = "SELECT MAX(RIGHT($id, 4)) as max_id FROM $table Where $id LIKE '%$tgl%'";
    $id_max = getOne($query);
    $sort_num = $id_max;
    $sort_num++;
    $new_code = $tgl . sprintf("%04s", $sort_num++);
    return $new_code;
}

function Terbilang($x)
{
    $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    if ($x < 12)
        return " " . $abil[$x];
    elseif ($x < 20)
        return Terbilang($x - 10) . " belas";
    elseif ($x < 100)
        return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
    elseif ($x < 200)
        return " seratus" . Terbilang($x - 100);
    elseif ($x < 1000)
        return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
    elseif ($x < 2000)
        return " seribu" . Terbilang($x - 1000);
    elseif ($x < 1000000)
        return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
    elseif ($x < 1000000000)
        return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
}

function penyebut($nilai)
{
    $nilai = abs($nilai);
    $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
        $temp = penyebut($nilai - 10) . " Belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai / 10) . " Puluh" . penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " Seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . " Ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " Seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . " Ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . " Juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . " Milyar" . penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . " Trilyun" . penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
}

function hariindo($x)
{
    $hari = FormatTgl("D", $x);

    switch ($hari) {
        case 'Sun':
            $hari_ini = "Minggu";
            break;

        case 'Mon':
            $hari_ini = "Senin";
            break;

        case 'Tue':
            $hari_ini = "Selasa";
            break;

        case 'Wed':
            $hari_ini = "Rabu";
            break;

        case 'Thu':
            $hari_ini = "Kamis";
            break;

        case 'Fri':
            $hari_ini = "Jumat";
            break;

        case 'Sat':
            $hari_ini = "Sabtu";
            break;

        default:
            $hari_ini = "Tidak di ketahui";
            break;
    }

    return $hari_ini;
}

function FormatTgl($format, $tanggal)
{
    return date($format, strtotime($tanggal));
}

function konversitanggalteks($tanggal)
{
    list($thn, $bln, $tgl) = explode('-', $tanggal);
    $tmp = "tanggal " . penyebut($tgl) . " " . "bulan " . konversiBulan($bln) . " " . "tahun " . penyebut($thn) . " ";
    return $tmp;
}

function enumDropdownRequired($table_name, $column_name, $echo = false, $placeholder)
{
    $selectDropdown = "<select class='form-control select2' data-placeholder='$placeholder' id='$column_name' style='width: 100%;' name='$column_name' required=''>";
    $selectDropdown .= "<option selected='selected' value=''>$placeholder</option>";
    $result = bukaquery("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
       WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'")
        or die(mysqli_error());

    $row = fetch_array($result);
    $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));

    foreach ($enumList as $value)
        $selectDropdown .= "<option value='$value'>$value</option>";

    $selectDropdown .= "</select>";

    if ($echo)
        echo $selectDropdown;

    return $selectDropdown;
}

function enumDropdownArray($table_name, $column_name, $column_name_array, $echo = false, $placeholder)
{
    $selectDropdown = "<select class='form-control select2' data-placeholder='$placeholder' id='$column_name' style='width: 100%;' name='$column_name_array' >";
    $selectDropdown .= "<option selected='selected' value=''>$placeholder</option>";
    $result = bukaquery("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
       WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'")
        or die(mysqli_error());

    $row = fetch_array($result);
    $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));

    foreach ($enumList as $value)
        $selectDropdown .= "<option value='$value'>$value</option>";

    $selectDropdown .= "</select>";

    if ($echo)
        echo $selectDropdown;

    return $selectDropdown;
}

function enumDropdown($table_name, $column_name, $echo = false, $placeholder)
{
    $selectDropdown = "<select class='form-control select2' data-placeholder='$placeholder' id='$column_name' style='width: 100%;' name='$column_name' >";
    $selectDropdown .= "<option selected='selected' value=''>$placeholder</option>";
    $result = bukaquery("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
       WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'")
        or die(mysqli_error());

    $row = fetch_array($result);
    $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));

    foreach ($enumList as $value)
        $selectDropdown .= "<option value='$value'>$value</option>";

    $selectDropdown .= "</select>";

    if ($echo)
        echo $selectDropdown;

    return $selectDropdown;
}

function UpdateEnumDropdown($table_name, $column_name, $record, $echo = false)
{
    $result = bukaquery("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
       WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'")
        or die(mysqli_error());

    $row = fetch_array($result);
    $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));

    $selectDropdown = "<select class='form-control select2' data-placeholder='' style='width: 100%;' name='$column_name' id='$record' required=''>";

    foreach ($enumList as $value)
        if ($value == $record) {
            $selectDropdown .= "<option value='$value' selected=" . $record . ">$value</option>";
        } else {
            $selectDropdown .= "<option value='$value'>$value</option>";
        }

    $selectDropdown .= "</select>";

    if ($echo)
        echo $selectDropdown;

    return $selectDropdown;
}

function TanggalAkhirBulan()
{
    $tampil = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    return date('Y-m-t', $tampil);
}

function TanggalAwalBulan()
{
    $tampil = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    return date('Y-m-01', $tampil);
}

function BulanKemarin()
{
    $tampil = mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"));
    return date('m-Y', $tampil);
}

function BulanSekarang()
{
    $tampil = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    return date('m-Y', $tampil);
}

function BulanDepan()
{
    $tampil = mktime(0, 0, 0, date("m") + 1, date("d"), date("Y"));
    return date('m-Y', $tampil);
}

function TahunDepan()
{
    $tampil = mktime(0, 0, 0, date("m"), date("d"), date("Y") + 1);
    return date('Y', $tampil);
}

function TanggalAkhirBulanKemarin()
{
    $tampil = mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"));
    return date('Y-m-t', $tampil);
}

function TanggalAwalBulanKemarin()
{
    $tampil = mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"));
    return date('Y-m-01', $tampil);
}

function HitungHari($tanggal1, $tanggal2)
{
    $dt1 = strtotime($tanggal1);
    $dt2 = strtotime($tanggal2);
    $diff = abs($dt2 - $dt1);
    $hari = $diff / 86400; // 86400 detik sehari
    return $hari;
}

function HitungHariMinggu($tanggal1, $tanggal2)
{
    $dari = $tanggal1;
    $sampai = $tanggal2;
    while (strtotime($dari) < strtotime($sampai)) {
        $dari = mktime(0, 0, 0, date("m", strtotime($dari)), date("d", strtotime($dari)) + 1, date("Y", strtotime($dari)));
        $dari = date("Y-m-d ", $dari);
        $hari = hariindo($dari);
        if ($hari == 'Minggu') {
            $nilai = 1;
        } else {
            $nilai = 0;
        }
        $jumlah[] = $nilai;
    }
    return array_sum($jumlah);
}

function serverSideJson($sql)
{
    $resultset = bukaquery($sql);
    $data = array();
    while ($rows = fetch_assoc($resultset)) {
        $data[] = $rows;
    }
    $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData" => $data
    );
    echo json_encode($results);
}

function tryCatch($run)
{
    $send_data = array();
    if (!$run) {
        $send_data['state'] = 'error';
    } else {
        $send_data['state'] = 'success';
    }
    echo json_encode($send_data);
}

function tryCatchMassage($run, $massage)
{
    $send_data = array();
    if (!$run) {
        $send_data['state'] = 'error';
    } else {
        $send_data['state'] = $massage;
    }
    echo json_encode($send_data);
}

function menuClickDH($judul, $aksi, $link_detail, $link_hapus, $count)
{
    if ($count > 0) {
        $html = $judul;
    } else {
        $html = $judul . " <div class='btn-group'>
                <button type='button' class='btn-xs btn-success dropdown-toggle' data-toggle='dropdown'>
                    <span class='fas fa-list-alt'></span>
                </button>
                <div class='dropdown-menu'>
                    <a class='dropdown-item delete-link' href='$link_hapus'>Hapus</a>
                </div>
            </div>";
    }
    return $html;
}

function menuClickH($judul, $aksi, $link_hapus, $count)
{
    if ($count > 0) {
        $html = $judul;
    } else {
        $html = $judul . " <div class='btn-group'>
                <button type='button' class='btn-xs btn-warning dropdown-toggle' data-toggle='dropdown'>
                    <span class='fas fa-list-alt'></span>
                </button>
                <div class='dropdown-menu'>
                    <a class='dropdown-item delete-link' href='$link_hapus'>Hapus</a>
                </div>
            </div>";
    }
    return $html;
}

function menuClick($judul, $aksi, $link_detail, $link_hapus, $modal_add, $id, $count)
{
    if ($count > 0) {
        $html = $judul . " <div class='btn-group'>
                <button type='button' class='btn-xs btn-primary dropdown-toggle' data-toggle='dropdown'>
                    <span class='fas fa-list-alt'></span>
                </button>
                <div class='dropdown-menu'>
                    <a class='dropdown-item' data-toggle='modal' data-target='#$modal_add' data-whatever='" . paramEncrypt($id) . "'>Tambah</a>
                </div>
            </div>";
    } else {
        $html = $judul . " <div class='btn-group'>
                <button type='button' class='btn-xs btn-primary dropdown-toggle' data-toggle='dropdown'>
                    <span class='fa fa-list-alt'></span>
                </button>
                <div class='dropdown-menu'>
                    <a class='dropdown-item' data-toggle='modal' data-target='#$modal_add' data-whatever='" . paramEncrypt($id) . "'>Tambah</a>
                    <div class='dropdown-divider'></div>
                    <a class='dropdown-item delete-link' href='$link_hapus'>Hapus</a>
                </div>
            </div>";
    }
    return $html;
}


function FormatKosong($data, $format, $warna)
{
    if ($data == 0 or $data == '') {
        $nilai = $format;
    } else {
        $nilai = "<span class='badge badge-$warna right'>" . $data . "</span>";
    }
    return $nilai;
}

function CekAkses()
{
    session_start();
    //cek apakah user sudah login
    if (!isset($_SESSION['userid']) || !isset($_SESSION['tahun'])) {
        exit(json_encode(['error' => 'Invalid Access']));
    }
}

function LimitSession($login, $menit, $title)
{
    $timeout = $menit * 60; // menit ke detik
    if (isset($_SESSION['start_session'])) {
        $elapsed_time = time() - $_SESSION['start_session'];
        if ($elapsed_time >= $timeout) {
            session_destroy();
            echo "<script type='text/javascript'>alert('$title');window.location='$login'</script>";
        }
    }
    $_SESSION['start_session'] = time();
}

function GenerateKey()
{
    $timeout = 1 * 60; // menit ke detik
    if (isset($_SESSION['start_session'])) {
        $elapsed_time = time() - $_SESSION['start_session'];
        if ($elapsed_time >= $timeout) {
            echo md5(date('YmdHi'));
        }
    }
    $_SESSION['start_session'] = time();
}

function csrf_token()
{
    unset($_SESSION['csrf_token']);
    return $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(16));
}

function cek_csrf_token($token)
{
    if (isset($_SESSION['csrf_token']) && $token == $_SESSION['csrf_token']) {
        return true;
    } else {
        unset($_SESSION['csrf_token']);
        return false;
    }
}

function hash_pass($pass, $int)
{
    $options = [
        'cost' => $int,
    ];
    return password_hash($pass, PASSWORD_DEFAULT, $options);
}

function getToken()
{
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

    // Create token payload as a JSON string
    $payload = json_encode(['username' => 'BPBDCC', 'password' => 'salamTangguh!!']);

    // Encode Header to Base64Url String
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

    // Encode Payload to Base64Url String
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

    // Create Signature Hash
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'S4L4mT499uH', true);

    // Encode Signature to Base64Url String
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    // Create JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    return $jwt;
}

function getTokenPublic()
{
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    // Create token payload as a JSON string
    $payload = json_encode(['username' => USERNAME, 'password' => PASSWORD, 'date' => date('Y-m-d H')]);
    // Encode Header to Base64Url String
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    // Encode Payload to Base64Url String
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    // Create Signature Hash
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'AIRA&DANIA', true);
    // Encode Signature to Base64Url String
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    // Create JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    return $jwt;
}
