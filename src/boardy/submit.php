<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
$error_notification = "";
require_once __DIR__ . '/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = trim($_POST['body'] ?? '');
    $title = trim($_POST['title'] ?? '');    
	
    echo '<pre>';
    var_dump($_POST);
    echo '</pre>';
    var_dump($title);


    if (empty($body) || empty($title)) {
        $error_notification = 'Добавьте текст во все поля!';
    } else {
        $stmt = $pdo->prepare('INSERT INTO posts (title, body, author_id) VALUES (?, ?, ?)');
        $stmt->execute([$title, $body, $_SESSION['user_id']]);
        header('Location: /messages.php');
	exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Boardy - пост</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>
<main>
    <div class="submit-container">
        <?php if ($error_notification): ?>
            <div class="error"><?= htmlspecialchars($error_notification) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
	    <input
                 class="title-input"
                 type="text"
                 name="title"
                 placeholder="Новый пост"
                 value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                 required
            >

	    <div>
                <label for="body">Текст поста</label>
                <textarea id="body" name="body" required><?= htmlspecialchars($_POST['body'] ?? '') ?></textarea>
            </div>
            <button type="submit">Опубликовать</button>
	    <a href="/messages.php" style="margin-left: 15px;">
		Отмена
	    </a>
        </form>
    </div>
</main>
<?php include __DIR__ . '/partials/foot.php'; ?>
