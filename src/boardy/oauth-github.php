<?php
require_once 'config.php';
session_start();

$client_id = 'Ov23liB9T2TUfU0ie3sA';
$redirect_uri = 'https://vyache.space/oauth-callback.php';

$state = bin2hex(random_bytes(16));
$_SESSION['oauth_state'] = $state;

$params = http_build_query([
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'scope' => 'read:user',
    'state' => $state
]);

header("Location: https://github.com/login/oauth/authorize?$params");
exit;
