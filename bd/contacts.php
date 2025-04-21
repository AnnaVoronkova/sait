<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $to = "agency@example.com"; // Замени на свою почту
    $subject = "Новое сообщение от $name";
    $headers = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";
    
    if (mail($to, $subject, $message, $headers)) {
        echo "<p>Сообщение отправлено!</p>";
    } else {
        echo "<p>Ошибка при отправке.</p>";
    }
}
?>

<?php include 'db.php'; ?>
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
        <h2>Свяжитесь с нами</h2>
    <form method="POST">
        <label>Имя:</label>
        <input type="text" name="name" required><br><br>
        <label>Email:</label>
        <input type="email" name="email" required><br><br>
        <label>Сообщение:</label><br>
        <textarea name="message" rows="5" required></textarea><br><br>
        <input type="submit" value="Отправить">
    </form>
        <h2>Фотографии отелей</h2>
        <img src="hotels1.jpg" width="300">
        <img src="hotel2.jpg" width="300">
    </div>
</body>
</html>