<?php
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$client_id = $user['client_id'];
$tour_id = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 0;

// Получаем информацию о выбранном туре
$tour_query = $conn->query("SELECT * FROM tours WHERE tour_id = $tour_id");
$tour = $tour_query->fetch_assoc();

// Обработка бронирования
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_date = date("Y-m-d H:i:s");
    $status = "pending"; // По умолчанию "в ожидании"

    $sql = "INSERT INTO bookings (client_id, tour_id, booking_date, status) 
            VALUES ('$client_id', '$tour_id', '$booking_date', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Бронирование успешно! <a href='profile.php'>Перейти в личный кабинет</a></p>";
        exit();
    } else {
        echo "<p>Ошибка: " . $conn->error . "</p>";
    }
}
?>

<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Бронирование</title>
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
    <h2>Бронирование тура</h2>
    
    <?php if ($tour) { ?>
        <p><strong>Тур:</strong> <?= $tour['tour_name'] ?></p>
        <p><strong>Длительность:</strong> <?= $tour['duration'] ?> дней</p>
        <p><strong>Цена:</strong> <?= $tour['price'] ?> $</p>

        <form method="POST">
            <input type="submit" value="Подтвердить бронирование">
        </form>
    <?php } else { ?>
        <p>Тур не найден!</p>
    <?php } ?>

    <a href="tours.php">Вернуться к списку туров</a>
    </div>
</body>
</html>