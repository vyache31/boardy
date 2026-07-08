<?php
// partials/nav.php
$is_logged = !empty($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
?>

<nav style="
    background:#1A5276;
    width:100%;
    padding:12px 24px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-sizing:border-box;
">
    <div style="display:flex; align-items:center; gap:20px;">
        <a href="/" style="
            color:white;
            text-decoration:none;
            font-size:24px;
            font-weight:bold;
        ">
            Boardy
        </a>

        <a href="/messages.php" style="
            color:white;
            text-decoration:none;
	    padding: 0px 24px;
        ">
            Все посты
        </a>

	
    </div>

    <div style="display:flex; align-items:center; gap:15px;">
        <?php if ($is_logged): ?>
           <a href="/submit.php" style="
            color:white;
            text-decoration:none;
            padding: 0px 24px;
           ">
    	       Добавить пост
            </a>


	    <span style="
	    color: white;
	    ">
                Привет, <?= htmlspecialchars($user_name) ?>!
            </span>

            <a href="/logout.php" style="
                color:white;
                text-decoration:none;
            ">
                Выйти
            </a>
        <?php else: ?>
            <a href="/login.php" style="
                color:white;
                text-decoration:none;
		padding: 0px 24px;
            ">
                Вход
            </a>

            <a href="/register.php" style="
                color:white;
                text-decoration:none;
            ">
                Регистрация
            </a>
        <?php endif; ?>
    </div>
</nav>
