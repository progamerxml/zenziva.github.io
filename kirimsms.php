<?php
    $namaServer = "localhost";
    $database = "report_zenziva";
    $username = "root";
    $password = "";
    $konek = mysqli_connect($namaServer, $username, $password, $database);
    if (!$konek) {
        die("koneksi gagal : ".mysqli_connect_error());
    }
    $userkey = '73b17cd492f0';
    $passkey = '6b6b0e3869ebacc02f5aa1e8';
    $telepon = $_GET['nohp'] ? $_GET['nohp'] : '';
    $OTPmessage = $_GET['pesan'] ? $_GET['pesan'] : '';
    // $url = 'https://console.zenziva.net/reguler/api/sendsms/';
    $url = 'https://console.zenziva.net/masking/api/sendOTP/';
    // $url = 'https://console.zenziva.net/masking/api/sendsms/';
    // $url = 'https://console.zenziva.net/wareguler/api/sendWA/';
    $kode = 11333356;
    $curlHandle = curl_init();
    curl_setopt($curlHandle, CURLOPT_URL, $url);
    curl_setopt($curlHandle, CURLOPT_HEADER, 0);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
    curl_setopt($curlHandle, CURLOPT_POST, 1);
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
        'userkey' => $userkey,
        'passkey' => $passkey,
        'to' => $telepon,
        'message' => $OTPmessage
    ));
    $results = json_decode(curl_exec($curlHandle), true);
    curl_close($curlHandle);
    // var_dump($results);
    $sintaks = "INSERT INTO `history_trx` (`id_pesan`, `no_tujuan`, `pesan`, `status`, `biaya`, `waktu`) VALUES ('$results[messageId]', '$results[to]', '$OTPmessage', $results[status], $results[cost], current_time());";
    // var_dump($sintaks);
    mysqli_query($konek, $sintaks);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Report Zenziva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  </head>
  <body>
    <div class="container mt-5 border py-2 rounded border-2 shadow">
        <h2 class="h2">Report Transaksi Zenziva</h2>
    <table id="contoh" class="table table-borderless table-dark rounded">
        <thead>
            <tr>
                <th scope="col" style="width:10%">ID Pesan</th>
                <th scope="col" style="width:15%">No Tujuan</th>
                <th scope="col" style="width:50%">Pesan</th>
                <th scope="col" style="width:8%">Status</th>
                <th scope="col" style="width:7%">Biaya</th>
                <th scope="col" style="width:10%">Waktu</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $datatrx = "select * from history_trx order by waktu desc";
                $xdatatrx = mysqli_query($konek, $datatrx);
                while($rdatatrx = mysqli_fetch_object($xdatatrx)) { if($rdatatrx->status == 1) {$status = "<button class='btn btn-sm btn-success'>sukses</button>";} else {$status = "<button class='btn btn-sm btn-danger'>sukses</button>";}
            ?>
            <tr>
                <th scope="row"><?= $rdatatrx->id_pesan ?></th>
                <td><?= $rdatatrx->no_tujuan ?></td>
                <td><?= $rdatatrx->pesan ?></td>
                <td><?= $status ?></td>
                <td><?= $rdatatrx->biaya ?></td>
                <td><?= $rdatatrx->waktu ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#contoh').DataTable();
        } );
    </script>
  </body>
</html>