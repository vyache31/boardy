<?php
require_once 'config.php';
session_start();
require_once __DIR__ . '/db.php';

if (($_GET['state'] ?? '') !== ($_SESSION['oauth_state'] ?? '')) {
    die('Invalid state - possible CSRF attack');
}

$client_id = 'Ov23liB9T2TUfU0ie3sA';
$client_secret = '7dc7d3096ef7303e4251a0c4137ac53931e5bf57';
$code = $_GET['code'];

$ch = curl_init('https://github.com/login/oauth/access_token');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $code
    ]),
    CURLOPT_HTTPHEADER => ['Accept: application/json'],
    CURLOPT_RETURNTRANSFER => true
]);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

$access_token = $response['access_token'] ?? null;
if (!$access_token) {
    die('GitHub token exchange failed');
}

$ch = curl_init('https://api.github.com/user');
curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $access_token",
        'User-Agent: Boardy'
    ],
    CURLOPT_RETURNTRANSFER => true
]);
$profile = json_decode(curl_exec($ch), true);
curl_close($ch);

$stmt = $pdo->prepare('SELECT id, name FROM users WHERE github_id = ?');
$stmt->execute([$profile['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $stmt = $pdo->prepare('INSERT INTO users (name, github_id) VALUES (?, ?)');
    $stmt->execute([$profile['login'], $profile['id']]);
    $user = [
        'id' => $pdo->lastInsertId(),
        'name' => $profile['login']
    ];
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['flash_success'] = 'Вы вошли через GitHub как ' . $user['name'];

header('Location: /messages.php');
exit;
