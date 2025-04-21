<?php
include 'db.php';

// Запрос для получения статистики бронирований по турам
$stats = $conn->query("SELECT t.tour_name, COUNT(b.booking_id) as total_bookings 
                       FROM bookings b
                       JOIN tours t ON b.tour_id = t.tour_id
                       GROUP BY t.tour_id
                       ORDER BY total_bookings DESC");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Турагентство</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            text-align: center;
        }
        .menu a {
            margin: 10px;
            text-decoration: none;
            color: blue;
            font-weight: bold;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        table {
            margin: 0 auto; /* Центрирование таблицы */
            border-collapse: collapse;
            width: 80%;
        }
        table th, table td {
            padding: 10px;
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
        <h2>Статистика бронирований</h2>
        <table border="1">
            <tr>
                <th>Название тура</th>
                <th>Количество бронирований</th>
            </tr>
            <?php while ($row = $stats->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['tour_name']) ?></td>
                    <td><?= $row['total_bookings'] ?></td>
                </tr>
            <?php } ?>
        </table>

        <h2>Фотографии отелей</h2>
        <img src="antalya.jpg" width="300">
        <img src="paris2.jpg" width="300">
    </div>
</body>
</html>