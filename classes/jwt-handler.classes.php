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
        $hashedSignature = hash_hmac('sha256', $signatureInput, $secretKey, true);

        // base64Url encode the signature
        $base64Signature = $this->base64UrlEncode($hashedSignature);

        return $signatureInput . "." . $base64Signature;
    }

    public function validateJWT($authHeader) {

        if (!$authHeader) {
            throw new Exception("No authorization header found.");
        }

        if (strpos($authHeader, "Bearer") === false) {
            throw new Exception("Invalid Authorization header format.");
        }

        $JWT = substr($authHeader, 7);

        // decodedJWT = { success: boolean, message: string, payload: { userId: int, isAdmin: bool [0 or 1] } }
        $decodedJWT = decodeJWT($JWT, $this->secretKey);

        return $decodedJWT;
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
}