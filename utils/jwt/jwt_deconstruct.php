<?php

function decodeJWT($JWT, $secretKey) {
    $decodeResponse = Array(
        "success" => false,
        "message" => ""
    );

    // extract the data from the JWT
    list($inputHeader, $inputPayload, $inputSignature) = explode('.', $JWT);

    // ensure that the header and payload haven't been tampered with
    $signature = $inputHeader . '.' . $inputPayload;
    $expectedSignature = base64UrlEncode(hash_hmac('sha256', $signature, $secretKey, true));

    // check if signature created from extracted header and payload match the sent one
    if ($expectedSignature !== $inputSignature) {
        throw new Exception("JWT is invalid.");
        // $decodeResponse["message"] = "JWT is invalid";
        // return $decodeResponse;
    }
    // JWT has been validated

    // re-create header and payload
    $header = json_decode(base64UrlDecode($inputHeader), true);
    $payload = json_decode(base64UrlDecode($inputPayload), true);

    // check if the time stamp is still valid
    $expiration = $payload["exp"];
    $currentTime = time();
    if ($currentTime >= $expiration) {
        throw new Exception("JWT has expired.");
        // $decodeResponse["message"] = "JWT has expired";
        // return $decodeResponse;
    }

    // JWT is valid and not expired
    $decodeResponse["success"] = true;
    $decodeResponse["payload"] = $payload;
    return $decodeResponse;

}

function base64UrlDecode($data) {
    $data = str_replace(["-", "_"], ["+", "/"], $data);
    return base64_decode(str_pad($data, strlen($data) % 4, "=", STR_PAD_RIGHT));
}

?>