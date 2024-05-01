<?php

class JWTHandler {
    private $secretKey;

    public function __construct($secretKey) {
        $this->secretKey = $secretKey;
    }

    public function generateJWT($userData) {

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'];

        $payload = Array(
            "sub" => array($userData["userId"], $userData["username"], $userData["isAdmin"]),
            "iat" => time(),
            "exp" => time() + 3600
        );

        // base64Url encode the header and payload
        $base64Header = $this->base64UrlEncode(json_encode($header));
        $base64Payload = $this->base64UrlEncode(json_encode($payload));

        // create the signature input
        $signatureInput = $base64Header . '.' . $base64Payload;

        // create the signature using HMAC with SHA-256
        $hashedSignature = hash_hmac('sha256', $signatureInput, $this->secretKey, true);

        // base64Url encode the signature
        $base64Signature = $this->base64UrlEncode($hashedSignature);

        return $signatureInput . "." . $base64Signature;
    }

    public function decodeJWT($authHeader) {
        // Take the JWT from the header, and validate it
        if (strpos($authHeader, "Bearer") === false) {
            throw new Exception("Invalid Authorization header format.");
        }

        $JWT = substr($authHeader, 7); 
        // extract the data from the JWT
        list($inputHeader, $inputPayload, $inputSignature) = explode('.', $JWT);
    
        // ensure that the header and payload haven't been tampered with
        $signature = $inputHeader . '.' . $inputPayload;
        $expectedSignature = $this->base64UrlEncode(hash_hmac('sha256', $signature, $this->secretKey, true));
    
        // check if signature created from extracted header and payload match the sent one
        if ($expectedSignature !== $inputSignature) {
            throw new Exception("JWT is invalid.");
        }

        // JWT has been validated
    
        // re-create header and payload
        $header = json_decode($this->base64UrlDecode($inputHeader), true);
        $payload = json_decode($this->base64UrlDecode($inputPayload), true);
    
        // check if the time stamp is still valid
        if (time() >= $payload["exp"]) {
            throw new Exception("JWT has expired.");
        }
    
        // JWT is valid and not expired
        return $payload;
    
    }
    
    public function checkRole($isAdminRequired, $isAdmin) {
        if ($isAdminRequired && !$isAdmin) {
            throw new Exception("Not authorized for this endpoint.");
        }
    }

    private function base64UrlEncode($data) {
        // Standard Base64 encoding with URL-safe characters
        // replaces + with -, / with _, removes trailing =
        return rtrim(strtr(base64_encode($data), "+/", "-_"), "-");
    }

    private function base64UrlDecode($data) {
        $data = str_replace(["-", "_"], ["+", "/"], $data);
        return base64_decode(str_pad($data, strlen($data) % 4, "=", STR_PAD_RIGHT));
    }
}