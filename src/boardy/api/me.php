<?php
require_once __DIR__ . '/../config.php';

session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'domain' => '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

function generate_jwt($user_id, $user_name, $secret_key) {
    $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
    $payload = json_encode([
        'user_id' => $user_id,
        'name' => $user_name,
        'exp' => time() + 3600
    ]);

    $base64UrlHeader = rtrim(strtr(base64_encode($header), '+/', '-_'), '=');
    $base64UrlPayload = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');

    $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $secret_key, true);
    $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

    return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
}

$jwt = generate_jwt($_SESSION['user_id'], $_SESSION['user_name'], $secret_key);

header('Content-Type: application/json');
echo json_encode(['token' => $jwt]);
