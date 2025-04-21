<?php
include 'db.php';
session_start();

if (!isset($_POST['selected_tours']) || empty($_POST['selected_tours'])) {
    echo "<h2>Вы не выбрали ни одного тура.</h2>";
    echo '<a href="tours.php">Вернуться к списку туров</a>';
    exit;
}

$tour_ids = array_map('intval', $_POST['selected_tours']);
$id_list = implode(',', $tour_ids);

// Получаем информацию о выбранных турах
$query = "SELECT t.tour_id, t.tour_name, c.country_name, h.hotel_name, t.duration, t.price
          FROM tours t
          JOIN countries c ON t.country_id = c.country_id
          JOIN hotels h ON t.hotel_id = h.hotel_id
          WHERE t.tour_id IN ($id_list)";

$result = $conn->query($query);

$total_price = 0;
$tours = [];
while ($row = $result->fetch_assoc()) {
    $tours[] = $row;
    $total_price += $row['price'];
}
?>



<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина - Подтверждение бронирования</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; text-align: center; }
        .menu a { margin: 10px; text-decoration: none; color: blue; font-weight: bold; }
        table { margin: auto; border-collapse: collapse; width: 80%; margin-top: 30px; }
        th, td { border: 1px solid #ccc; padding: 10px; }
        th { background-color: #e0e0e0; }
        .total { font-weight: bold; }
        .back-link, .confirm-btn { margin-top: 20px; display: inline-block; }
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
    <h1>Подтверждение бронирования</h1>

    <table>
        <tr>
            <th>Название тура</th>
            <th>Страна</th>
            <th>Отель</th>
            <th>Длительность</th>
            <th>Цена ($)</th>
        </tr>
        <?php foreach ($tours as $tour): ?>
        <tr>
            <td><?= htmlspecialchars($tour['tour_name']) ?></td>
            <td><?= htmlspecialchars($tour['country_name']) ?></td>
            <td><?= htmlspecialchars($tour['hotel_name']) ?></td>
            <td><?= htmlspecialchars($tour['duration']) ?> дней</td>
            <td><?= htmlspecialchars($tour['price']) ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="4" class="total">Общая стоимость:</td>
            <td class="total"><?= $total_price ?> $</td>
        </tr>
    </table>

    <form method="POST" action="confirm_booking.php">
        <?php foreach ($tour_ids as $id): ?>
            <input type="hidden" name="confirmed_tours[]" value="<?= $id ?>">
        <?php endforeach; ?>
        <br>
        <input type="submit" value="Подтвердить бронирование" class="confirm-btn">
    </form>

    <div class="back-link">
        <a href="tours.php">Вернуться к списку туров</a>
    </div>
</body>
</html>