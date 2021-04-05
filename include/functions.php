<?php
function getcaptchareponse($token):bool{
    $apikey = "0x2c2a7110F346623cbe0b87cDDeee1d29a33bA23f";
    $verifyURL = 'https://hcaptcha.com/siteverify';

    $error = '';
    $data = [
        'skey' => $apikey,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];

    $curlcfg = [
      CURLOPT_URL => $verifyURL,
      CURLOPT_POST => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POSTFIELDS => $data
    ];
    $ch = curl_init();
    curl_setopt_array($ch,$curlcfg);
    $response = curl_exec($ch);
    $responseData = json_decode($response);
    if($responseData->success) {
        return true;
    }else{
        return false;
    }
}