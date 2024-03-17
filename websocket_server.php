<?php

// // WebSocket server
// $server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
// socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);
// socket_bind($server, '127.0.0.1', 8080);
// socket_listen($server);

// $clients = [$server];

// echo "WebSocket server started\n";

// while (true) {
//     $read = $clients;
//     $write = $except = null;

//     if (socket_select($read, $write, $except, 0) < 1) {
//         continue;
//     }

//     if (in_array($server, $read)) {
//         $newSocket = socket_accept($server);
//         $clients[] = $newSocket;
//         $key = array_search($server, $read);
//         unset($read[$key]);
//     }

//     foreach ($read as $client) {
//         $data = @socket_read($client, 1024, PHP_BINARY_READ);
//         if ($data === false) {
//             $key = array_search($client, $clients);
//             unset($clients[$key]);
//             socket_close($client);
//             continue;
//         }

//         $data = trim($data);
//         if (!empty($data)) {
//             echo "Received: $data\n";
//             $response = "Server: You said: $data\n";
//             foreach ($clients as $sendClient) {
//                 if ($sendClient !== $server && $sendClient !== $client) {
//                     socket_write($sendClient, $response, strlen($response));
//                 }
//             }
//         }
//     }
// }

// // Close the server socket
// socket_close($server);
