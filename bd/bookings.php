<?php
include 'db.php';

// Получение значений фильтров из формы
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$tour_filter = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 0;
$hotel_filter = isset($_GET['hotel_id']) ? (int)$_GET['hotel_id'] : 0;

// Запрос для получения всех туров
$tours = $conn->query("SELECT tour_id, tour_name FROM tours");

// Запрос для получения всех отелей
$hotels = $conn->query("SELECT hotel_id, hotel_name FROM hotels");

// Формируем SQL-запрос с учетом фильтров
$query = "SELECT b.booking_id, c.first_name, c.last_name, t.tour_name, h.hotel_name, b.booking_date, b.status
          FROM bookings b
          JOIN clients c ON b.client_id = c.client_id
          JOIN tours t ON b.tour_id = t.tour_id
          JOIN hotels h ON t.hotel_id = h.hotel_id";

$conditions = [];

// Фильтр по статусу
if ($status_filter != 'all') {
    $conditions[] = "b.status = '$status_filter'";
}

// Фильтр по туру
if ($tour_filter > 0) {
    $conditions[] = "t.tour_id = $tour_filter";
}

// Фильтр по отелю
if ($hotel_filter > 0) {
    $conditions[] = "h.hotel_id = $hotel_filter";
}

// Добавляем условия в запрос
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " ORDER BY b.booking_date DESC";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Список бронирований</title>
</head>
<body>
    <h2>Список бронирований</h2>

    <!-- Форма фильтрации -->
    <form method="GET">
        <!-- Фильтр по статусу -->
        <label for="status">Фильтр по статусу:</label>
        <select name="status" id="status">
            <option value="all" <?= ($status_filter == 'all') ? 'selected' : '' ?>>Все</option>
            <option value="confirmed" <?= ($status_filter == 'confirmed') ? 'selected' : '' ?>>Подтверждено</option>
            <option value="pending" <?= ($status_filter == 'pending') ? 'selected' : '' ?>>В ожидании</option>
            <option value="canceled" <?= ($status_filter == 'canceled') ? 'selected' : '' ?>>Отменено</option>
        </select>

        <!-- Фильтр по туру -->
        <label for="tour_id">Фильтр по туру:</label>
        <select name="tour_id" id="tour_id">
            <option value="0">Все туры</option>
            <?php while ($row = $tours->fetch_assoc()) { ?>
                <option value="<?= $row['tour_id'] ?>" <?= ($tour_filter == $row['tour_id']) ? 'selected' : '' ?>>
                    <?= $row['tour_name'] ?>
                </option>
            <?php } ?>
        </select>

        <!-- Фильтр по отелю -->
        <label for="hotel_id">Фильтр по отелю:</label>
        <select name="hotel_id" id="hotel_id">
            <option value="0">Все отели</option>
            <?php while ($row = $hotels->fetch_assoc()) { ?>
                <option value="<?= $row['hotel_id'] ?>" <?= ($hotel_filter == $row['hotel_id']) ? 'selected' : '' ?>>
                    <?= $row['hotel_name'] ?>
                </option>
            <?php } ?>
        </select>

        <input type="submit" value="Применить">
    </form>

    <table border="1">
        <tr>
            <th>ID бронирования</th>
            <th>Клиент</th>
            <th>Тур</th>
            <th>Отель</th>
            <th>Дата бронирования</th>
            <th>Статус</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row["booking_id"] ?></td>
                <td><?= $row["first_name"] . " " . $row["last_name"] ?></td>
                <td><?= $row["tour_name"] ?></td>
                <td><?= $row["hotel_name"] ?></td>
                <td><?= $row["booking_date"] ?></td>
                <td><?= $row["status"] ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>