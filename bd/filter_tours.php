<?php
include 'db.php';

// Получаем список стран
$countries = $conn->query("SELECT * FROM countries");

// Определяем выбранную страну (по умолчанию - все)
$selected_country = isset($_GET['country_id']) ? (int)$_GET['country_id'] : 0;

// Формируем SQL-запрос
$query = "SELECT t.tour_name, c.country_name, h.hotel_name, t.duration, t.price 
          FROM tours t
          JOIN countries c ON t.country_id = c.country_id
          JOIN hotels h ON t.hotel_id = h.hotel_id";
if ($selected_country) {
    $query .= " WHERE t.country_id = $selected_country";
}

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Фильтр туров</title>
</head>
<body>
    <h2>Выберите страну</h2>
    <form method="GET">
        <input type="radio" name="country_id" value="0" <?= ($selected_country == 0) ? 'checked' : '' ?>> Все страны <br>
        <?php while ($row = $countries->fetch_assoc()) { ?>
            <input type="radio" name="country_id" value="<?= $row['country_id'] ?>" <?= ($row['country_id'] == $selected_country) ? 'checked' : '' ?>>
            <?= $row['country_name'] ?><br>
        <?php } ?>
        <br>
        <input type="submit" value="Фильтровать">
    </form>

    <h2>Список туров</h2>
    <table border="1">
        <tr>
            <th>Название тура</th>
            <th>Страна</th>
            <th>Отель</th>
            <th>Длительность</th>
            <th>Цена ($)</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row["tour_name"] ?></td>
                <td><?= $row["country_name"] ?></td>
                <td><?= $row["hotel_name"] ?></td>
                <td><?= $row["duration"] ?> дней</td>
                <td><?= $row["price"] ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>