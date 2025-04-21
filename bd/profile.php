<?php
include 'db.php';

session_start();


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$client_id = $user['client_id'];

// Отмена бронирования
if (isset($_GET['cancel_booking'])) {
    $booking_id = (int)$_GET['cancel_booking'];
    $conn->query("UPDATE bookings SET status = 'canceled' WHERE booking_id = $booking_id AND client_id = $client_id");
    header("Location: profile.php");
    exit();
}

// Получаем список бронирований пользователя
$bookings = $conn->query("SELECT b.booking_id, t.tour_name, h.hotel_name, b.booking_date, b.status 
                          FROM bookings b
                          JOIN tours t ON b.tour_id = t.tour_id
                          JOIN hotels h ON t.hotel_id = h.hotel_id
                          WHERE b.client_id = $client_id
                          ORDER BY b.booking_date DESC");
?>

<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
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
    <h2>Личный кабинет</h2>
    <p>Имя: <?= htmlspecialchars($user['first_name']) ?></p>
    <p>Фамилия: <?= htmlspecialchars($user['last_name']) ?></p>
    <p>Логин: <?= htmlspecialchars($user['login']) ?></p>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    <p>Телефон: <?= htmlspecialchars($user['phone']) ?></p>

    <h3>Ваши бронирования</h3>
    <table border="1">
        <tr>
            <th>Тур</th>
            <th>Отель</th>
            <th>Дата бронирования</th>
            <th>Статус</th>
            <th>Действие</th>
        </tr>
        <?php while ($row = $bookings->fetch_assoc()) { ?>
            <tr>
                <td><?= $row["tour_name"] ?></td>
                <td><?= $row["hotel_name"] ?></td>
                <td><?= $row["booking_date"] ?></td>
                <td><?= $row["status"] ?></td>
                <td>
                    <?php if ($row["status"] == "pending") { ?>
                        <a href="profile.php?cancel_booking=<?= $row['booking_id'] ?>" onclick="return confirm('Отменить бронирование?')">Отменить</a>
                    <?php } else { ?>
                        Недоступно
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>

    <a href="tours.php">Выбрать тур</a> | <a href="logout.php">Выйти</a>
    </div>
</body>
</html>