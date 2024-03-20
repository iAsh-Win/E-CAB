<?php
require('config.php');


$merchent_id = $_POST['merchantId'];
$merchent_trans_id = $_POST['transactionId'];

// Call check status API
$string = "/pg/v1/status/" . $merchent_id . "/" . $merchent_trans_id . Salt_Key;
$sha256 = hash('sha256', $string);
$salt_index = salt_index;
$final_header = $sha256 . "###" . $salt_index;

$curl = curl_init();
curl_setopt_array(
  $curl,
  array(
    CURLOPT_URL => $api_url . 'status/' . $merchent_id . '/' . $merchent_trans_id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'Accept: application/json',
      'Content-Type: application/json',
      'X-VERIFY:' . $final_header,
      'X-MERCHANT-ID: ' . $merchent_id
    ),
  )
);

$response = curl_exec($curl);
curl_close($curl);

$res = json_decode($response);
session_start();
// echo '<pre>';
// print_r($res);
// echo '</pre>';
// Check payment status
if ($res->success) {
  // Redirect to payFunction with success data as GET parameters
  header('location: payFunction.php?success=true&code=' . urlencode($res->code) . '&transactionId=' . urlencode($res->data->transactionId) . '&amount=' . urlencode($res->data->amount) . '&type=' . urlencode($res->data->paymentInstrument->type));
} else {
  // Redirect to payFunction with error data as GET parameters
  header('location: payFunction.php?success=false&code=' . urlencode($res->code) . '&declined_description=' . urlencode($res->data->responseCodeDescription));
}



?>