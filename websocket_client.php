<?php

// $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

// if ($socket === false) {
//     echo "Failed to create socket: " . socket_strerror(socket_last_error()) . "\n";
//     exit;
// }

// $result = socket_connect($socket, '127.0.0.1', 8080);

// if ($result === false) {
//     echo "Failed to connect to server: " . socket_strerror(socket_last_error($socket)) . "\n";
//     exit;
// }

// echo "Connected to server\n";

// // Sending data to server
// $data = "Hello, WebSocket Server!";
// socket_write($socket, $data, strlen($data));

// // Receiving data from server
// $response = socket_read($socket, 1024);
// echo "Server response: " . $response . "\n";

// socket_close($socket);
