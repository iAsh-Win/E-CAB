<?php
define('merchant_id', 'PGTESTPAYUAT');
define('Salt_Key', '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399');
$environment = "sandbox";
$api_url = ($environment === "sandbox") ? "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/" : "https://api.phonepe.com/apis/hermes/pg/v1/";
define('return_url', 'http://localhost/E-cab/Deshboard/payment/return.php');
define('salt_index', '1');
define('callback_url', 'http://localhost/E-cab/Deshboard/payment/error.php');

?>