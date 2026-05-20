<?php
session_start();

// ====== НАСТРОЙКИ ======
$admin_password = 'admin123'; // ПОМЕНЯЙТЕ ПАРОЛЬ ЗДЕСЬ!
$file_path = 'links.json';
// =======================

// Выход из админки
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Проверка пароля
if (isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['logged_in'] = true;
    } else {
        $error = "Неверный пароль!";
    }
}

// Защита: если не авторизован - показываем форму входа
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Вход в админку</title>
        <style>
            body { font-family: sans-serif; background: #0a0a12; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
            form { background: rgba(255,255,255,0.05); padding: 30px; border-radius: 15px; text-align: center; }
            input { padding: 10px; border-radius: 5px; border: none; margin-bottom: 15px; width: 100%; box-sizing: border-box; }
            button { padding: 10px 20px; background: #00f5d4; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        </style>
    </head>
    <body>
        <form method="POST">
            <h2>Вход</h2>
            <?php if(isset($error)) echo "<p style='color:#ff6b6b;'>$error</p>"; ?>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
    </body>
    </html>
    <?php
    exit; // Останавливаем выполнение, чтобы не показать саму админку
}

// Сохранение новых ссылок в файл
if (isset($_POST['save_links'])) {
    $data = [
        "site_link" => $_POST['site_link'],
        "bot_link" => $_POST['bot_link'],
        "tg_link" => $_POST['tg_link'],
        "game2_link" => $_POST['game2_link'],
        "game5_link" => $_POST['game5_link']
    ];
    file_put_contents($file_path, json_encode($data, JSON_PRETTY_PRINT));
    $success = "Ссылки успешно обновлены!";
}

// Чтение текущих ссылок для отображения в форме
$current_links = json_decode(file_get_contents($file_path), true);
if (!$current_links) {
    $current_links = ["site_link"=>"", "bot_link"=>"", "tg_link"=>"", "game2_link"=>"", "game5_link"=>""];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления ссылками</title>
    <style>
        body { font-family: sans-serif; background: #0a0a12; color: white; padding: 20px; max-width: 600px; margin: 0 auto; }
        .box { background: rgba(255,255,255,0.05); padding: 20px; border-radius: 15px; }
        label { display: block; margin-top: 15px; color: #00f5d4; font-size: 14px; }
        input { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: none; box-sizing: border-box; background: rgba(255,255,255,0.1); color: white; }
        button { margin-top: 20px; padding: 12px 20px; background: #7b61ff; color: white; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 16px; font-weight: bold;}
        .logout { display: block; margin-top: 20px; text-align: center; color: #ff6b6b; text-decoration: none; }
    </style>
</head>
<body>
    <h2>Управление ссылками</h2>
    <div class="box">
        <?php if(isset($success)) echo "<p style='color:#00f5d4;'>$success</p>"; ?>
        <form method="POST">
            <label>ПЕРЕЙТИ НА САЙТ:</label>
            <input type="text" name="site_link" value="<?php echo htmlspecialchars($current_links['site_link']); ?>" required>

            <label>ОТКРЫТЬ БОТА:</label>
            <input type="text" name="bot_link" value="<?php echo htmlspecialchars($current_links['bot_link']); ?>" required>

            <label>TELEGRAM КАНАЛ:</label>
            <input type="text" name="tg_link" value="<?php echo htmlspecialchars($current_links['tg_link']); ?>" required>

            <label>MINE SLOT (Игра 1):</label>
            <input type="text" name="game2_link" value="<?php echo htmlspecialchars($current_links['game2_link']); ?>" required>

            <label>JUMPER (Игра 2):</label>
            <input type="text" name="game5_link" value="<?php echo htmlspecialchars($current_links['game5_link']); ?>" required>

            <button type="submit" name="save_links">СОХРАНИТЬ ИЗМЕНЕНИЯ</button>
        </form>
    </div>
    <a href="?logout=1" class="logout">Выйти из панели</a>
</body>
</html>