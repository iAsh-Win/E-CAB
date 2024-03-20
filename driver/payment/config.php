<?php
define('merchant_id', 'ATMOSTUAT');
define('Salt_Key', '58a63b64-574d-417a-9214-066bee1e4caa');
$environment = "sandbox";
$api_url = ($environment === "sandbox") ? "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/" : "https://api.phonepe.com/apis/hermes/pg/v1/";
define('return_url', 'http://localhost/E-cab/Driver/payment/return.php');
define('salt_index', '1');
define('callback_url', 'http://localhost/E-cab/Driver/payment/error.php');

?>