<?php

function base64UrlEncode($data) {
    // Standard Base64 encoding with URL-safe characters
    // replaces + with -, / with _, removes trailing =
    return rtrim(strtr(base64_encode($data), "+/", "-_"), "-");
}

function generateJWT($payload, $secretKey) {
    // JWT Header
    $header = [
        'alg' => 'HS256',
        'typ' => 'JWT'];

    // base64Url encode the header and payload
    $base64Header = base64UrlEncode(json_encode($header));
    $base64Payload = base64UrlEncode(json_encode($payload));

    // create the signature input
    $signatureInput = $base64Header . '.' . $base64Payload;

    // create the signature using HMAC with SHA-256
    $signature = hash_hmac('sha256', $signatureInput, $secretKey, true);

    // base64Url encode the signature
    $base64Signature = base64UrlEncode($signature);

    return $signatureInput . "." . $base64Signature;

}

?>