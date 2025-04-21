<?php
include 'db.php';

// Получаем список клиентов
$clients = $conn->query("SELECT client_id, first_name, last_name FROM clients");

// Получаем список туров
$tours = $conn->query("SELECT tour_id, tour_name FROM tours");

// Обработка формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id = $_POST["client_id"];
    $tour_id = $_POST["tour_id"];
    $booking_date = date("Y-m-d H:i:s");
    $status = "pending"; // По умолчанию "в ожидании"

    $sql = "INSERT INTO bookings (client_id, tour_id, booking_date, status) 
            VALUES ('$client_id', '$tour_id', '$booking_date', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Бронирование успешно добавлено!</p>";
    } else {
        echo "<p>Ошибка: " . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Добавить бронирование</title>
</head>
<body>
    <h2>Добавить новое бронирование</h2>
    <form method="POST">
        <label>Выберите клиента:</label>
        <select name="client_id">
            <?php while ($row = $clients->fetch_assoc()) { ?>
                <option value="<?= $row["client_id"] ?>">
                    <?= $row["first_name"] . " " . $row["last_name"] ?>
                </option>
            <?php } ?>
        </select>
        <br><br>

        <label>Выберите тур:</label>
        <select name="tour_id">
            <?php while ($row = $tours->fetch_assoc()) { ?>
                <option value="<?= $row["tour_id"] ?>">
                    <?= $row["tour_name"] ?>
                </option>
            <?php } ?>
        </select>
        <br><br>

        <input type="submit" value="Забронировать">
    </form>
</body>
</html>