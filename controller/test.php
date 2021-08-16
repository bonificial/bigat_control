<?php

// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/hwwk-bigat/messages:send');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"message\": {\n    \"topic\" : \"foo-bar\",\n    \"notification\": {\n      \"body\": \"This is a Firebase Cloud Messaging Topic Message!\",\n      \"title\": \"FCM Message\"\n    }\n  }\n}");

$headers = array();
$headers[] = 'Authorization: Bearer ya29.ElqKBGN2Ri_Uz...HnS_uNreA';
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);