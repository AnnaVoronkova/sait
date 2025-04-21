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
        .image-container {
            position: relative;
            display: inline-block;
            margin: 10px; /* Отступ между изображениями */
        }
        .image-container img {
            width: 300px; /* Установите желаемую ширину изображения */
            height: auto;
        }
        .overlay {
            position: absolute;
            bottom: 10px; /* Расположение текста по вертикали */
            left: 10px;   /* Расположение текста по горизонтали */
            color: white; /* Цвет текста */
            background-color: rgba(0, 0, 0, 0.7); /* Полупрозрачный фон */
            padding: 5px; /* Отступ вокруг текста */
            opacity: 0;   /* Начальная прозрачность текста */
            transition: opacity 0.3s ease; /* Плавный переход */
        }
        .image-container:hover .overlay {
            opacity: 1; /* Прозрачность текста при наведении */
        }
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
        <h2>О нас</h2>
        <p>Мы предлагаем лучшие туры по всему миру. Выберите идеальное путешествие!</p>
        <h2>Фотографии отелей</h2>
        <div class="image-container">
            <img src="hotels1.jpg" alt="Отель 1">
            <div class="overlay">Sunset Resort</div>
        </div>
        <div class="image-container">
            <img src="hotel2.jpg" alt="Отель 2">
            <div class="overlay">Gran Hotel</div>
        </div>
    </div>
</body>
</html>