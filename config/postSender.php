<?php
function sendPost($targetUrl,$postData){
    //$targetUrl = 'https://example.com/destination.php';

// Define the POST data you want to send
//    $postData = array(
//        'key1' => 'value1',
//        'key2' => 'value2',
//        // Add more key-value pairs as needed
//    );

// Initialize cURL session
    $ch = curl_init();

// Set cURL options for the POST request
    curl_setopt($ch, CURLOPT_URL, $targetUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

// Execute the cURL request
    $response = curl_exec($ch);


// Check for cURL errors
    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    }
}?>