<?php
session_start();
require_once __DIR__ . '/db.php';
 
$stmt = $pdo->query(
    'SELECT posts.body, users.name, posts.created_at
     FROM posts
     JOIN users ON posts.author_id = users.id
     ORDER BY posts.created_at DESC'
);
$messages = $stmt->fetchAll();

function timeAgo($datetime)
{
    $time = time() - strtotime($datetime);

    if ($time < 60) return 'только что';
    if ($time < 3600) return floor($time / 60) . ' мин назад';
    if ($time < 86400) return floor($time / 3600) . ' ч назад';
    if ($time < 172800) return 'вчера';

    return floor($time / 86400) . ' дн назад';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset="utf-8"><title>Boardy — Сообщения</title>
<link rel="stylesheet" href="/css/style.css"></head>
<body>
<?php include 'partials/nav.php'; ?>
<main class="container">
  <h2>Все посты</h2>
  <?php if (empty($messages)): ?>
    <p>Сообщений пока нет.</p>
<?php else: ?>
    <?php foreach ($messages as $msg): ?>
        <article class="post">
            <div class="post-header">
                <span class="post-author">
                    <?= htmlspecialchars($msg['name']) ?>
                </span>

                <span class="post-time">
                    <?= timeAgo($msg['created_at']) ?>
                </span>
            </div>

            <div class="post-body">
                <?= nl2br(htmlspecialchars($msg['body'])) ?>
            </div>
        </article>
    <?php endforeach; ?>
<?php endif; ?>
</main>
</body></html>
