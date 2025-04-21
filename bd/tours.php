<?php
include 'db.php';

$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
$hotel_filter = isset($_GET['hotel_id']) ? (int)$_GET['hotel_id'] : 0;
$country_filter = isset($_GET['country_id']) ? (int)$_GET['country_id'] : 0;
$min_duration = isset($_GET['min_duration']) ? (int)$_GET['min_duration'] : 0;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 0;

// Получаем список отелей и стран для фильтров
$hotels = $conn->query("SELECT hotel_id, hotel_name FROM hotels");
$countries = $conn->query("SELECT country_id, country_name FROM countries");

// Формируем SQL-запрос
$query = "SELECT t.tour_id, t.tour_name, c.country_name, h.hotel_name, t.duration, t.price 
          FROM tours t
          JOIN countries c ON t.country_id = c.country_id
          JOIN hotels h ON t.hotel_id = h.hotel_id";

$conditions = [];

if ($hotel_filter > 0) {
    $conditions[] = "t.hotel_id = $hotel_filter";
}
if ($country_filter > 0) {
    $conditions[] = "t.country_id = $country_filter";
}
if ($min_duration > 0) {
    $conditions[] = "t.duration >= $min_duration";
}
if ($max_price > 0) {
    $conditions[] = "t.price <= $max_price";
}

if (count($conditions) > 0) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

$query .= " ORDER BY t.price $order";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список туров</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f8ff; text-align: center; }
        .menu a { margin: 10px; text-decoration: none; color: blue; font-weight: bold; }
        .container { max-width: 800px; margin: auto; }
        form { margin-bottom: 20px; }
        label, select, input[type="number"] { margin: 5px; }
        table { width: 100%; margin-top: 15px; border-collapse: collapse; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 10px; }
        a.button-link { padding: 6px 12px; background: #007BFF; color: #fff; border-radius: 4px; text-decoration: none; }
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
        <h2>Список туров</h2>

        <!-- Форма фильтрации -->
        <form method="GET">
            <label for="hotel_id">Отель:</label>
            <select name="hotel_id" id="hotel_id">
                <option value="0">Все отели</option>
                <?php
                mysqli_data_seek($hotels, 0);
                while ($row = $hotels->fetch_assoc()) { ?>
                    <option value="<?= $row['hotel_id'] ?>" <?= ($hotel_filter == $row['hotel_id']) ? 'selected' : '' ?>>
                        <?= $row['hotel_name'] ?>
                    </option>
                <?php } ?>
            </select>

            <label for="country_id">Страна:</label>
            <select name="country_id" id="country_id">
                <option value="0">Все страны</option>
                <?php
                mysqli_data_seek($countries, 0);
                while ($row = $countries->fetch_assoc()) { ?>
                    <option value="<?= $row['country_id'] ?>" <?= ($country_filter == $row['country_id']) ? 'selected' : '' ?>>
                        <?= $row['country_name'] ?>
                    </option>
                <?php } ?>
            </select>

            <label for="min_duration">Мин. длительность (дней):</label>
            <input type="number" name="min_duration" id="min_duration" value="<?= htmlspecialchars($min_duration) ?>">

            <label for="max_price">Макс. цена ($):</label>
            <input type="number" name="max_price" id="max_price" value="<?= htmlspecialchars($max_price) ?>">

            <input type="submit" value="Фильтровать">
            <a href="tours.php" style="margin-left: 10px;">
                <button type="button">Сбросить фильтры</button>
            </a>
        </form>

        <a href="tours.php?order=ASC">Сортировать по возрастанию цены</a> |
        <a href="tours.php?order=DESC">Сортировать по убыванию цены</a>

        <form method="POST" action="cart.php">
        <table>
            <tr>
                <th>Выбрать</th>
                <th>Название тура</th>
                <th>Страна</th>
                <th>Отель</th>
                <th>Длительность</th>
                <th>Цена ($)</th>
            </tr>
            <?php
            // Массив соответствия tour_id -> файл
            $tourPages = [
                1 => 'tour_kair.php',
                2 => 'tour_antalya.php',
                3 => 'tour_kair.php',
                4 => 'tour_paris.php'
            ];

            while ($row = $result->fetch_assoc()) {
                $tourPage = isset($tourPages[$row['tour_id']]) ? $tourPages[$row['tour_id']] : '#';
            ?>
            <tr>
                <td><input type="checkbox" name="selected_tours[]" value="<?= $row['tour_id'] ?>"></td>
                <td><a href="<?= $tourPage ?>"><?= htmlspecialchars($row["tour_name"]) ?></a></td>
                <td><?= htmlspecialchars($row["country_name"]) ?></td>
                <td><?= htmlspecialchars($row["hotel_name"]) ?></td>
                <td><?= htmlspecialchars($row["duration"]) ?> дней</td>
                <td><?= htmlspecialchars($row["price"]) ?></td>
            </tr>
            <?php } ?>
        </table>
        <br>
        <input type="submit" value="Перейти к бронированию выбранных туров">
        </form>

        <h2>Фотографии отелей</h2>
        <img src="antalya3.jpg" width="300">
        <img src="hotel2.jpg" width="300">
    </div>
</body>
</html>