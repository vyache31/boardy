<?php
session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'domain' => '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

require_once __DIR__ . '/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $future_password = $_POST['password'] ?? '';

    if (empty($email) || empty($future_password)) {
        $error = 'Все поля обязательны для заполнения';
    } else {
        $stmt = $pdo->prepare('SELECT id, name, password_hash FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($future_password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: /messages.php');
            exit;
        } else {
            $error = 'Неверный email или пароль';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Boardy - вход</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>
<main>
    <div class="auth-container">
        <h1>Вход</h1>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></d>
        <?php endif; ?>
        <form method="POST" action="">
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div >
                <label for="password">Пароль
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Войти</button>
        </form>
	
	<p style="margin-top: 15px;color: #666; text-decoration: none; text-align: center;">или</p>
	<div class="github-auth-btn">
	    <a href="/oauth-github.php">
		<img class="github-icon" src="https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/github-white-icon.png" alt="GitHub">
		Войти через GitHub
	    </a>
	</div>
        <div class="auth-footer">
            Нет аккаунта? <a href="/register.php">Зарегистрироваться</a>
	    
        </div>

	
    </div>
</main>
<?php include __DIR__ . '/partials/foot.php'; ?>
</body>
</html>
