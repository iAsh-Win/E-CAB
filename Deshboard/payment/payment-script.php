<?php
require('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);

    // Remove the following line if not needed for debugging
    // print_r($data);

    $name = isset($data['name']) ? $data['name'] : (isset($_POST['name']) ? $_POST['name'] : '');
    $email = isset($data['email']) ? $data['email'] : (isset($_POST['email']) ? $_POST['email'] : '');
    $amount = isset($data['amount']) ? $data['amount'] : (isset($_POST['amount']) ? $_POST['amount'] : '');

    $message = [];

    if (empty($name) || empty($email) || empty($amount)) {
        $message = ['error' => true, 'message' => 'All fields are requirement'];
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = ['error' => true, 'message' => 'Please provide a valid email'];
        } else {
            if (!ctype_digit(strval($amount))) {
                $message = ['error' => true, 'message' => 'Amount should contain digits only'];
            } else {
                $amount = (int) $amount; // Convert to an integer
                if ($amount <= 0) {
                    $message = ['error' => true, 'message' => 'Amount should be greater than 1'];
                } else {
                    $description = 'Shillong Tours Packages payment';

                    $payloadData = array(
                        'merchantId' => merchant_id,
                        'merchantTransactionId' => "MT7850590068188104",
                        "merchantUserId" => "MUID123",
                        'amount' => $amount * 100,
                        'redirectUrl' => return_url,
                        'redirectMode' => "POST",
                        'callbackUrl' => return_url,
                        "merchantOrderId" => uniqid(),
                        "message" => $description,
                        "email" => $email,
                        "shortName" => $name,
                        "paymentInstrument" => array(
                            "type" => "PAY_PAGE",
                        )
                    );

                    $jsonencode = json_encode($payloadData);
                    $payloadMain = base64_encode($jsonencode);
                    $salt_index = salt_index; //key index 1
                    $payload = $payloadMain . "/pg/v1/pay" . Salt_Key;
                    $sha256 = hash("sha256", $payload);
                    $final_x_header = $sha256 . '###' . $salt_index;
                    $request = json_encode(array('request' => $payloadMain));

                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => $api_url . 'pay',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $request,
                        CURLOPT_HTTPHEADER => [
                            "Content-Type: application/json",
                            "X-VERIFY: " . $final_x_header,
                            "accept: application/json"
                        ],
                    ]);

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    curl_close($curl);

                    if ($err) {
                        $message = ['error' => true, 'message' => 'cURL Error #' . $err];
                    } else {
                        $res = json_decode($response);
                        if ($res->success) {
                            $message = ['error' => false, 'message' => $res->message, 'payment_url' => $res->data->instrumentResponse->redirectInfo->url];
                        } else {
                            $message = ['error' => true, 'message1' => $res];
                        }
                    }
                }
            }
        }
    }

    // Send the JSON response
    echo json_encode($message);
} else {
    echo 'You are not allowed to access';
}
?>
