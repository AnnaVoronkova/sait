<?php
include 'db.php';

// Инициализируем сессию
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем логин и пароль из формы
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Используем подготовленный запрос для безопасности
    $stmt = $conn->prepare("SELECT * FROM clients WHERE login = ? AND password = ?");
    $stmt->bind_param("ss", $login, $password);  // "ss" — два строковых параметра
    $stmt->execute();
    $result = $stmt->get_result();

    // Если пользователь найден
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Сохраняем информацию о пользователе в сессии
        $_SESSION['user_id'] = $user['client_id'];  // Например, сохраняем ID клиента
        $_SESSION['user'] = $user;  // Сохраняем весь объект пользователя

        // Перенаправляем на страницу профиля
        header("Location: profile.php");
        exit();
    } else {
        // Если логин или пароль неверны
        echo "<p style='color: red;'>Неверный логин или пароль!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Турагентство</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f8ff; text-align: center; }
        .menu a { margin: 10px; text-decoration: none; color: blue; font-weight: bold; }
        .container { max-width: 800px; margin: auto; }
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
        <h2>Авторизация</h2>
        <form method="POST">
            <label>Логин:</label>
            <input type="text" name="login" required><br><br>
            <label>Пароль:</label>
            <input type="password" name="password" required><br><br>
            <input type="submit" value="Войти">
        </form>

        <h2>Фотографии отелей</h2>
        <img src="hotels1.jpg" width="300">
        <img src="hotel2.jpg" width="300">
    </div>
</body>
</html>