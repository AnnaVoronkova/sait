<?php
session_start();
include 'db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Сообщения
$success = '';
$error = '';

// Обработка формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $login = trim($_POST['login']);
    $password = $_POST['password']; 
    $confirm_password = $_POST['confirm_password'];
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strpos($email, '@') === false) {
        $error = "Некорректный email.";
    } elseif ($password !== $confirm_password) {
        $error = "Пароли не совпадают.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO clients (first_name, last_name, login, password, email, phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $login, $hashed_password, $email, $phone);

        if ($stmt->execute()) {
            $success = "Регистрация успешна! <a href='login.php'>Войти</a>";
        } else {
            $error = "Ошибка при регистрации: " . $conn->error;
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация — Турагентство</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f8ff; text-align: center; }
        .menu a { margin: 10px; text-decoration: none; color: blue; font-weight: bold; }
        .container { max-width: 800px; margin: auto; }
        .captcha { font-size: 24px; font-weight: bold; letter-spacing: 5px; background: #e0e0ff; display: inline-block; padding: 10px 20px; margin: 10px auto; border: 1px solid #ccc; }
        .error { color: red; font-weight: bold; }
        .success { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Добро пожаловать в наше турагентство!</h1>
    <div class="menu">
        <a href="index.php">Главная</a>
        <a href="register.php">Регистрация</a>
        <a href="login.php">Вход</a>
        <a href="contacts.php">Контакты</a>
        <a href="stats.php">Статистика</a>
        <a href="tours.php">Список туров</a>
    </div>

    <div class="container">
        <h2>Регистрация</h2>

        <?php
        if (!empty($error)) echo "<p class='error'>$error</p>";
        if (!empty($success)) echo "<p class='success'>$success</p>";
        ?>

        <?php if (empty($success)): ?>
        <form method="POST">
            <label>Имя:</label>
            <input type="text" name="first_name" required><br><br>

            <label>Фамилия:</label>
            <input type="text" name="last_name" required><br><br>

            <label>Логин:</label>
            <input type="text" name="login" required><br><br>

            <label>Пароль:</label>
            <input type="password" name="password" required><br><br>

            <label>Подтверждение пароля:</label>
            <input type="password" name="confirm_password" required><br><br>

            <label>Email:</label>
            <input type="email" name="email" required><br><br>

            <label>Телефон:</label>
            <input type="text" name="phone"><br><br>

            <input type="submit" value="Зарегистрироваться">
        </form>
        <?php endif; ?>

        <h2>Фотографии отелей</h2>
        <img src="paris1.jpg" width="300">
        <img src="kair2.jpg" width="300">
    </div>
</body>
</html>