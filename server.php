<?php
// header('Content-Type: text/event-stream');
// header('Cache-Control: no-cache');
// header('Connection: keep-alive'); // Add this header for persistent connection

// // Simulate some data updates (replace with your actual data source)
// $messages = [
//   "This is message 1 (type: info).",
//   "This is message 2 (type: warning).",
//   "This is message 3 (type: success).",
// ];

// foreach ($messages as $message) {
//   // Extract message content and type (modify based on your data structure)
//   $parts = explode('(', $message);
//   $messageContent = trim($parts[0]);
//   $messageType = trim(str_replace(')', '', $parts[1] ?? ''));

//   // Clean type value and remove spaces for valid class names
//   $cleanType = str_replace(' ', '_', $messageType);

//   // Send data with additional "type" field in valid JSON format
//   echo "data: " . json_encode(['message' => $messageContent, 'type' => $cleanType]) . "\n\n";
//   ob_flush(); // Flush output buffer immediately
//   sleep(1); // Simulate a delay between messages
// }

// No need to close the connection explicitly with persistent connection header
?>


