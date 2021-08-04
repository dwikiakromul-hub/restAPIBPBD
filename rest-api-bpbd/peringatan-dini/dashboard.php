<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php require_once('../koneksi/getlive.php'); ?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="refresh" content="<?= '1300' ?>;URL='<?= $_SERVER['PHP_SELF']; ?>'">
    <title>BPBD Prov DKI Jakarta</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

</head>

<body>

    <div id="wrapper" style="background-color: white; padding: 15px 15px; height:100%;  width:100%; ">
        <div id="page-inner" style="background-color: white; padding: 15px 15px; height:100%;  width:100%;  ">
            <div class="row">
                <div class="col-md-12">
                    <p style="text-align: right; font-weight: bold;" id="tanggalwaktu"></p>
                    <img src="assets/img/logo.jpg" class="user-image img-responsive" />
                    <center>
                        <h1 style='color: red'>PERINGATAN DINI BMKG </h1>
                    </center>
                </div>
            </div>
            <hr />
            <div id="konten">
                <div class="row">
                    <div class="row" style="min-height: 100%; min-width: 100%;">
                        <div class="col-md-3 col-sm-12 col-xs-12 " style="height:100%; width:16.6%; min-height:300px; min-width:300px;">
                            <div class="panel">
                                <div class="main-temp-back">
                                    <div class="panel-body" style="background-color:#a5a5ef;">
                                        <div class="col-xs-6"> <i class="fas fa-cloud-showers-heavy fa-3x"></i> Kota JakartaPusat</div>
                                        <div class="col-xs-6"> <i class="fas fa-search-location fa-3x"></i> </div>
                                        <?php
                                        $kondisi = fetch_array(bukaquery("select COALESCE(sum( CASE WHEN nm_kab ='Kota Jakarta Pusat' THEN 1 ELSE 0 END ),0) AS pusat, 
                                    COALESCE(sum(CASE WHEN nm_kab ='Kota Jakarta Timur' then 1 else 0 end),0) as timur,
                                    COALESCE(sum(CASE WHEN nm_kab ='Kota Jakarta Barat' then 1 else 0 end),0) as barat,
                                    COALESCE(sum(CASE WHEN nm_kab ='Kota Jakarta Selatan' then 1 else 0 end),0) as selatan,
                                    COALESCE(sum(CASE WHEN nm_kab ='Kota Jakarta Utara' then 1 else 0 end),0) as utara, 
                                    COALESCE(sum(CASE WHEN nm_kab ='Kabupaten Kepulauan Seribu' then 1 else 0 end),0) as seribu FROM bmkg WHERE NOW() <= waktu_berakhir "));

                                        $sql = bukaquery("SELECT COUNT(nm_kab) as c,nm_kab, bmkg.nm_kab,bmkg.dampak,left(right(bmkg.waktu_berlaku,8),5) as a ,left(right(bmkg.waktu_berakhir,8),5) as b FROM bmkg WHERE nm_kab ='Kota Jakarta Pusat' AND NOW() <= waktu_berakhir GROUP BY waktu_dibuat order BY waktu_dibuat  DESC limit 1");
                                        $no = 1;
                                        while ($row = fetch_array($sql)) {
                                            if ($kondisi['pusat'] > 0) { ?>
                                                <div class="panel panel-primary text-center" style="background-color:#a5a5ef; border-color:#a5a5ef;">
                                                    <div class="panel-body">

                                                        <div style="text-align: center;">
                                                            <p><?= $row['c']; ?> Kecamatan</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <p style="text-decoration: underline;">Mulai</p><?= $row['a']; ?>
                                                </div>
                                                <div class="col-xs-6">
                                                    <p style="text-decoration: underline;">Berakhir</p><?= $row['b']; ?>
                                                </div>
                                                <div style="text-align: center">
                                                    <p style="text-decoration: underline;">Dampak</p>
                                                </div>
                                                <div style="tect-align: center"><?= str_replace("'", " ", $row['dampak']); ?> </div>
                                            <?php } else { ?>

                                                <div style="text-align: center">
                                                    <p style="text-decoration: underline;">Tidak Ada Peringatan Dini</p>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading" style="background-color:#a5a5ef;">
                                    <div style="color: white;">Kecamatan</div>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Kecamatan</th>
                                                    <th>Potensi</th>
                                                    <th>Waktu Awal</th>
                                                    <th>Waktu Berakhir</th>
                                                </tr>
                                            </thead>
                                            <?php if ($kondisi['pusat'] > 0) { ?>
                                                <tbody>
                                                    <?php
                                                    $sql = bukaquery("SELECT nm_kec,area,left(right(bmkg.waktu_berlaku,8),5) as a ,
                                                left(right(bmkg.waktu_berakhir,8),5) as b  FROM bmkg  WHERE nm_kab ='Kota Jakarta Pusat' AND NOW() <= waktu_berakhir ");
                                                    //$no = 1;
                                                    while ($row = fetch_array($sql)) { ?>
                                                        <tr class="danger">
                                                            <td><?= $row['nm_kec']; ?></td>
                                                            <td><?= $row['area']; ?></td>
                                                            <td><?= $row['a']; ?></td>
                                                            <td><?= $row['b']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            <?php } else { ?>
                                                <tbody>
                                                    <tr class="danger">
                                                        <td colspan="4" class="text-center">Tidak Ada Peringatan Dini</td>
                                                    </tr>
                                                </tbody>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-6" style=" height:100%; width:16.6%; min-height: 300px; min-width: 300px; ">
                            <div class="panel">
                                <div class="main-temp-back">
                                    <div class="panel-body" style="background-color:#f2e175;">
                                        <div class="col-xs-6" style=" color: black; "> <i style=" color: black;" class="fas fa-cloud-showers-heavy fa-3x"></i>Kota JakartaBarat</div>
                                        <div style=" color: black;" class="col-xs-6"> <i class="fas fa-search-location fa-3x"></i> </div>
                                        <?php
                                        $sql = bukaquery("SELECT COUNT(nm_kab) as c,bmkg.nm_kab,bmkg.dampak,left(right(bmkg.waktu_berlaku,8),5) as a ,
                                    left(right(bmkg.waktu_berakhir,8),5) as b  
                                    FROM bmkg  WHERE nm_kab ='Kota Jakarta Barat' AND NOW() <= waktu_berakhir GROUP BY waktu_dibuat order BY waktu_dibuat  DESC limit 1");
                                        $no = 1;
                                        while ($row = fetch_array($sql)) {
                                            if ($kondisi['barat'] > 0) { ?>

                                                <div class="panel panel-primary text-center" style="background-color:#f2e175; border-color:#f2e175;">
                                                    <div class="panel-body">

                                                        <div style=" color: black; ">
                                                            <p><?= $row['c']; ?> Kecamatan</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-6" style=" color: black;">
                                                    <p style="text-decoration: underline; color: black;">Mulai</p><?= $row['a']; ?>
                                                </div>
                                                <div class="col-xs-6" style=" color: black;">
                                                    <p style="text-decoration: underline; color: black;">Berakhir</p><?= $row['b']; ?>
                                                </div>
                                                <div style="text-align: center; color: black;">
                                                    <p style="text-decoration: underline;">Dampak</p>
                                                </div>
                                                <div style="tect-align: center; color: black;"><?= str_replace("'", " ", $row['dampak']); ?> </div>
                                            <?php } else { ?>

                                                <div style="text-align: center">
                                                    <p style="text-decoration: underline; color: black;">Tidak Ada Peringatan Dini</p>
                                                </div>

                                            <?php } ?>

                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" style="background-color:#f2e175;">
                                    Kecamatan
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Kecamatan</th>
                                                    <th>Potensi</th>
                                                    <th>Waktu Awal</th>
                                                    <th>Waktu Berakhir</th>
                                                </tr>
                                            </thead>
                                            <?php if ($kondisi['barat'] > 0) { ?>
                                                <tbody>
                                                    <?php
                                                    $sql = bukaquery("SELECT nm_kec,area,left(right(bmkg.waktu_berlaku,8),5) as a ,
                                                left(right(bmkg.waktu_berakhir,8),5) as b  FROM bmkg  WHERE nm_kab ='Kota Jakarta Barat' AND NOW() <= waktu_berakhir ");
                                                    $no = 1;
                                                    while ($row = fetch_array($sql)) { ?>
                                                        <tr class="danger">
                                                            <td><?= $row['nm_kec']; ?></td>
                                                            <td><?= $row['area']; ?></td>
                                                            <td><?= $row['a']; ?></td>
                                                            <td><?= $row['b']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            <?php } else { ?>
                                                <tbody>
                                                    <tr class="danger">
                                                        <td colspan="4" class="text-center">Tidak Ada Peringatan Dini</td>
                                                    </tr>
                                                </tbody>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-6" style=" height:100%; width:16.6%; min-height: 300px; min-width: 300px; ">
                            <div class="panel">
                                <div class="main-temp-back">
                                    <div class="panel-body" style="background-color:#ec9991;">
                                        <div class="col-xs-6"> <i class="fas fa-cloud-showers-heavy fa-3x"></i> Kota JakartaTimur</div>
                                        <div class="col-xs-6"> <i class="fas fa-search-location fa-3x"></i> </div>
                                        <?php
                                        $sql = bukaquery("SELECT COUNT(nm_kab) as c,bmkg.nm_kab,bmkg.dampak,left(right(bmkg.waktu_berlaku,8),5) as a ,
                                        left(right(bmkg.waktu_berakhir,8),5) as b FROM bmkg  WHERE nm_kab ='Kota Jakarta Timur' AND NOW() <= waktu_berakhir GROUP BY waktu_dibuat order BY waktu_dibuat  DESC limit 1");
                                        $no = 1;
                                        while ($row = fetch_array($sql)) { ?>
                                            <?php if ($kondisi['timur'] > 0) { ?>
                                                <div class="panel panel-primary text-center" style="background-color:#ec9991; border-color:#ec9991;">
                                                    <div class="panel-body">

                                                        <div style="text-align: center;">
                                                            <p><?= $row['c']; ?> Kecamatan</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <p style="text-decoration: underline;">Mulai</p><?= $row['a']; ?>
                                                </div>
                                                <div class="col-xs-6">
                                                    <p style="text-decoration: underline;">Berakhir</p><?= $row['b']; ?>
                                                </div>
                                                <div style="text-align: center">
                                                    <p style="text-decoration: underline;">Dampak</p>
                                                </div>
                                                <div style="text-align: center;"><?= str_replace("'", " ", $row['dampak']); ?> </div>

                                            <?php } else { ?>
                                                <br></br>
                                                <br></br>
                                                <div style="text-align: center">
                                                    <p style="text-decoration: underline;">Tidak Ada Peringatan Dini</p>
                                                </div>
                                                <br></br>
                                            <?php } ?>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" style="background-color:#ec9991;">
                                    <div style="color: white;">Kecamatan</div>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Kecamatan</th>
                                                    <th>Potensi</th>
                                                    <th>Waktu Awal</th>
                                                    <th>Waktu Berakhir</th>
                                                </tr>
                                            </thead>
                                            <?php if ($kondisi['timur'] > 0) { ?>
                                                <tbody>
                                                    <?php
                                                    $sql = bukaquery("SELECT nm_kec,area,left(right(bmkg.waktu_berlaku,8),5) as a ,
                                                left(right(bmkg.waktu_berakhir,8),5) as b  FROM bmkg  WHERE nm_kab ='Kota Jakarta Timur' AND NOW() <= waktu_berakhir");
                                                    $no = 1;
                                                    while ($row = fetch_array($sql)) { ?>
                                                        <tr class="danger">
                                                            <td><?= $row['nm_kec']; ?></td>
                                                            <td><?= $row['area']; ?></td>
                                                            <td><?= $row['a']; ?></td>
                                                            <td><?= $row['b']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            <?php } else { ?>
                                                <tbody>
                                                    <tr class="danger">
                                                        <td colspan="4" class="text-center">Tidak Ada Peringatan Dini</td>
                                                    </tr>
                                                </tbody>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-6" style=" height:100%; width:16.6%; min-height: 300px; min-width: 300px; ">
                            <div class="panel">
                                <div class="main-temp-back">
                                    <div class="panel-body" style="background-color:#a1ecab;">
                                        <div class="col-xs-6" style=" color: black; "> <i style=" color: black;" class="fas fa-cloud-showers-heavy fa-3x"></i> Kota JakartaSelatan</div>
                                        <div style=" color: black;" class="col-xs-6"> <i class="fas fa-search-location fa-3x"></i> </div>
                                        <?php
                                        $sql = bukaquery("SELECT COUNT(nm_kab) as c,bmkg.nm_kab,bmkg.dampak,left(right(bmkg.waktu_berlaku,8),5) as a , left(right(bmkg.waktu_berakhir,8),5) as b FROM bmkg  WHERE nm_kab ='Kota Jakarta Selatan' AND NOW() <= waktu_berakhir GROUP BY waktu_dibuat order BY waktu_dibuat  DESC limit 1");
                                        $no = 1;
                                        while ($row = fetch_array($sql)) { ?>
                                            <?php if ($kondisi['selatan'] > 0) { ?>
                                                <div class="panel panel-primary text-center" style="background-color:#a1ecab; border-color:#a1ecab;">
                                                    <div class="panel-body">

                                                        <div style=" color: black; ">
                                                            <p><?= $row['c']; ?> Kecamatan</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-6" style=" color: black;">
                                                    <p style="text-decoration: underline; color: black;">Mulai</p><?= $row['a']; ?>
                                                </div>
                                                <div class="col-xs-6" style=" color: black;">
                                                    <p style="text-decoration: underline; color: black;">Berakhir</p><?= $row['b']; ?>
                                                </div>
                                                <div style="text-align: center; color: black;">
                                                    <p style="text-decoration: underline;">Dampak</p>
                                                </div>
                                                <div style="text-align: center; color: black;"><?= str_replace("'", " ", $row['dampak']); ?> </div>
                                            <?php } else { ?>
                                                <br></br>
                                                <br></br>
                                                <div style="text-align: center">
                                                    <p style="text-decoration: underline; color: black;">Tidak Ada Peringatan Dini</p>
                                                </div>
                                                <br></br>
                                            <?php } ?>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" style="background-color:#a1ecab;">
                                    Kecamatan
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Kecamatan</th>
                                                    <th>Potensi</th>
                                                    <th>Waktu Awal</th>
                                                    <th>Waktu Berakhir</th>
                                                </tr>
                                            </thead>
                                            <?php if ($kondisi['selatan'] > 0) { ?>
                                                <tbody>
                                                    <?php
                                                    $sql = bukaquery("SELECT nm_kec,area,left(right(bmkg.waktu_berlaku,8),5) as a ,
                                                left(right(bmkg.waktu_berakhir,8),5) as b  FROM bmkg  WHERE nm_kab ='Kota Jakarta Selatan' AND NOW() <= waktu_berakhir");
                                                    $no = 1;
                                                    while ($row = fetch_array($sql)) { ?>
                                                        <tr class="danger">
                                                            <td><?= $row['nm_kec']; ?></td>
                                                            <td><?= $row['area']; ?></td>
                                                            <td><?= $row['a']; ?></td>
                                                            <td><?= $row['b']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            <?php } else { ?>
                                                <tbody>
                                                    <tr class="danger">
                                                        <td colspan="4" class="text-center">Tidak Ada Peringatan Dini</td>
                                                    </tr>
                                                </tbody>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3 col-sm-6 col-xs-6" style=" height:100%; width:16.6%; min-height: 300px; min-width: 300px; ">
                            <div class="panel">
                                <div class="main-temp-back">
                                    <div class="panel-body" style="background-color:#3ca9d0;">
                                        <div class="col-xs-6"> <i class="fas fa-cloud-showers-heavy fa-3x"></i> Kota JakartaUtara</div>
                                        <div class="col-xs-6"> <i class="fas fa-search-location fa-3x"></i> </div>

                                        <?php
                                        $sql = bukaquery("SELECT COUNT(nm_kab) as c,bmkg.nm_kab,bmkg.dampak,left(right(bmkg.waktu_berlaku,8),5) as a ,left(right(bmkg.waktu_berakhir,8),5) as b 
                                    FROM bmkg  WHERE nm_kab ='Kota Jakarta Utara' AND NOW() <= waktu_berakhir GROUP BY waktu_dibuat order BY waktu_dibuat  DESC limit 1");
                                        $no = 1;
                                        while ($row = fetch_array($sql)) { ?>

                                            <?php if ($kondisi['utara'] > 0) { ?>
                                                <div class="panel panel-primary text-center" style="background-color:#3ca9d0; border-color:#3ca9d0;">
                                                    <div class="panel-body">

                                                        <div style="text-align: center;">
                                                            <p><?= $row['c']; ?> Kecamatan</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <p style="text-decoration: underline;">Mulai</p><?= $row['a']; ?>
                                                </div>
                                                <div class="col-xs-6">
                                                    <p style="text-decoration: underline;">Berakhir</p><?= $row['b']; ?>
                                                </div>
                                                <div style="text-align: center">
                                                    <p style="text-decoration: underline;">Dampak</p>
                                                </div>
                                                <div style="text-align: center;"><?= str_replace("'", " ", $row['dampak']); ?> </div>

                                            <?php } else { ?>
                                                <br></br>
                                                <br></br>
                                                <div style="text-align: center">
                                                    <p style="text-decoration: underline;">Tidak Ada Peringatan Dini</p>
                                                </div>
                                                <br></br>
                                            <?php } ?>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" style="background-color:#3ca9d0;">
                                    <div style="color: white;">Kecamatan</div>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Kecamatan</th>
                                                    <th>Potensi</th>
                                                    <th>Waktu Awal</th>
                                                    <th>Waktu Berakhir</th>
                                                </tr>
                                            </thead>
                                            <?php if ($kondisi['utara'] > 0) { ?>
                                                <tbody>
                                                    <?php
                                                    $sql = bukaquery("SELECT nm_kec,area,left(right(bmkg.waktu_berlaku,8),5) as a ,
                                                left(right(bmkg.waktu_berakhir,8),5) as b  FROM bmkg  WHERE nm_kab ='Kota Jakarta Utara' AND NOW() <= waktu_berakhir");
                                                    $no = 1;
                                                    while ($row = fetch_array($sql)) { ?>
                                                        <tr class="danger">
                                                            <td><?= $row['nm_kec']; ?></td>
                                                            <td><?= $row['area']; ?></td>
                                                            <td><?= $row['a']; ?></td>
                                                            <td><?= $row['b']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            <?php } else { ?>
                                                <tbody>
                                                    <tr class="danger">
                                                        <td colspan="4" class="text-center">Tidak Ada Peringatan Dini</td>
                                                    </tr>
                                                </tbody>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-6" style=" height:100%; width:16.6%; min-height: 300px; min-width: 300px; ">
                            <div class="panel">
                                <div class="main-temp-back">
                                    <div class="panel-body" style="background-color:#cc86c2;">
                                        <div class="col-xs-6" style=" color: black; "> <i style=" color: black;" class="fas fa-cloud-showers-heavy fa-3x"></i>Kab KepulauanSeribu</div>
                                        <div style=" color: black;" class="col-xs-6"> <i class="fas fa-search-location fa-3x"></i> </div>
                                        <?php
                                        $sql = bukaquery("SELECT COUNT(nm_kab) as c,bmkg.nm_kab,bmkg.dampak,left(right(bmkg.waktu_berlaku,8),5) as a , left(right(bmkg.waktu_berakhir,8),5) as b FROM bmkg  WHERE nm_kab ='Kabupaten Kepulauan Seribu' AND NOW() <= waktu_berakhir GROUP BY waktu_dibuat order BY waktu_dibuat  DESC limit 1");
                                        $no = 1;
                                        while ($row = fetch_array($sql)) { ?>
                                            <?php if ($kondisi['seribu'] > 0) { ?>
                                                <div class="panel panel-primary text-center" style="background-color:#cc86c2; border-color:#cc86c2;">
                                                    <div class="panel-body">

                                                        <div style=" color: black; ">
                                                            <p><?= $row['c']; ?> Kecamatan</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-6" style=" color: black;">
                                                    <p style="text-decoration: underline; color: black;">Mulai</p><?= $row['a']; ?>
                                                </div>
                                                <div class="col-xs-6" style=" color: black;">
                                                    <p style="text-decoration: underline; color: black;">Berakhir</p><?= $row['b']; ?>
                                                </div>
                                                <div style="text-align: center; color: black;">
                                                    <p style="text-decoration: underline;">Dampak</p>
                                                </div>
                                                <div style="text-align: center; color: black;"><?= str_replace("'", " ", $row['dampak']); ?> </div>
                                            <?php } else { ?>
                                                <br></br>
                                                <br></br>
                                                <div style="text-align: center">
                                                    <p style="text-decoration: underline; color: black;">Tidak Ada Peringatan Dini</p>
                                                </div>
                                                <br></br>
                                            <?php } ?>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" style="background-color:#cc86c2;">
                                    Kecamatan
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Kecamatan</th>
                                                    <th>Potensi</th>
                                                    <th>Waktu Awal</th>
                                                    <th>Waktu Berakhir</th>
                                                </tr>
                                            </thead>
                                            <?php if ($kondisi['seribu'] > 0) { ?>
                                                <tbody>
                                                    <?php
                                                    $sql = bukaquery("SELECT nm_kec,area,left(right(bmkg.waktu_berlaku,8),5) as a ,
                                                left(right(bmkg.waktu_berakhir,8),5) as b  FROM bmkg  WHERE nm_kab ='Kabupaten Kepulauan Seribu' AND NOW() <= waktu_berakhir");
                                                    $no = 1;
                                                    while ($row = fetch_array($sql)) { ?>
                                                        <tr class="danger">
                                                            <td><?= $row['nm_kec']; ?></td>
                                                            <td><?= $row['area']; ?></td>
                                                            <td><?= $row['a']; ?></td>
                                                            <td><?= $row['b']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            <?php } else { ?>
                                                <tbody>
                                                    <tr class="danger">
                                                        <td colspan="4" class="text-center">Tidak Ada Peringatan Dini</td>
                                                    </tr>
                                                </tbody>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.metisMenu.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script>
        $(document).ready(function() {
            setInterval(function() {
                getData()
            }, 10000); // panggil setiap 10 detik

        });

        function getData() {
            $.ajax({
                url: "bmkg.php",
                type: "GET",
                success: function() {

                }
            })
        }
    </script>
    <script>
        var tw = new Date();
        if (tw.getTimezoneOffset() == 0)(a = tw.getTime() + (7 * 60 * 60 * 1000))
        else(a = tw.getTime());
        tw.setTime(a);
        var tahun = tw.getFullYear();
        var hari = tw.getDay();
        var bulan = tw.getMonth();
        var tanggal = tw.getDate();
        var hariarray = new Array("Minggu,", "Senin,", "Selasa,", "Rabu,", "Kamis,", "Jum'at,", "Sabtu,");
        var bulanarray = new Array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember");
        document.getElementById("tanggalwaktu").innerHTML = hariarray[hari] + " " + tanggal + " " + bulanarray[bulan] + " " + tahun;
    </script>
    <script src="assets/js/custom.js"></script>
</body>

</html>