<?php
function sendSMS($phone, $message) {
    // Example API integration (replace with actual SMS service)
    $apiKey = 'your_api_key';
    $apiUrl = 'https://sms-service.com/send';
    
    $data = [
        'api_key' => $apiKey,
        'to' => $phone,
        'message' => $message,
    ];
    
    $options = [
        'http' => [
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    
    $context  = stream_context_create($options);
    $result = file_get_contents($apiUrl, false, $context);
    
    return $result;
}
?>

