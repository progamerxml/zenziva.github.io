<?php

$userkey = '73b17cd492f0';
$passkey = '6b6b0e3869ebacc02f5aa1e8';
$telepon = $_GET["nohp"];
$message = $_GET["pesan"];
$url = 'https://console.zenziva.net/wareguler/api/sendWA/';
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
    'message' => $message
));
$results = json_decode(curl_exec($curlHandle), true);
curl_close($curlHandle);

var_dump($results);

?>