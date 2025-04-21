
<?php
include 'db.php';

// Начинаем сессию, чтобы получить данные о пользователе
session_start();
?>

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
</body>
</html>


<?php
// Проверяем, что туры были выбраны
if (!isset($_POST['confirmed_tours']) || empty($_POST['confirmed_tours'])) {
    echo "<h2>Не выбраны туры для бронирования.</h2>";
    echo '<a href="cart.php">Вернуться в корзину</a>';
    exit;
}

// Сохраняем выбранные туры в переменную
$confirmed_tours = $_POST['confirmed_tours'];

// Получаем данные о туре из базы данных
$tour_ids = implode(',', array_map('intval', $confirmed_tours));
$query = "SELECT t.tour_id, t.tour_name, c.country_name, h.hotel_name, t.duration, t.price
          FROM tours t
          JOIN countries c ON t.country_id = c.country_id
          JOIN hotels h ON t.hotel_id = h.hotel_id
          WHERE t.tour_id IN ($tour_ids)";
$result = $conn->query($query);

// Проверяем, что туры найдены
if ($result->num_rows > 0) {
    // Проверяем, есть ли пользователь в сессии
    if (!isset($_SESSION['user_id'])) {
        echo "<h2>Вы не авторизованы. Пожалуйста, войдите в систему.</h2>";
        echo '<a href="login.php">Войти</a>';
        exit;
    }

    // Получаем данные пользователя (ID пользователя из сессии)
    $user_id = $_SESSION['user_id'];  // Получаем ID пользователя из сессии
    $order_date = date('Y-m-d H:i:s');
    $total_price = 0;

    // Открываем транзакцию
    $conn->begin_transaction();

    try {
        // Для каждого подтвержденного тура создаем заказ
        while ($row = $result->fetch_assoc()) {
            // Получаем данные тура
            $tour_id = $row['tour_id'];
            $price = $row['price'];
            $total_price += $price;

            // Вставляем заказ в таблицу бронирования
            $booking_date = date("Y-m-d H:i:s");
            $status = "pending";  // Статус по умолчанию - "в ожидании"

            $sql = "INSERT INTO bookings (client_id, tour_id, booking_date, status) 
                    VALUES ('$user_id', '$tour_id', '$booking_date', '$status')";
            if (!$conn->query($sql)) {
                throw new Exception("Ошибка при добавлении бронирования в базу данных.");
            }
        }

        // Фиксируем транзакцию
        $conn->commit();

        // Выводим сообщение об успешном бронировании
        echo "<h2>Бронирование успешно!</h2>";
        echo "<p>Вы успешно забронировали туры на сумму $total_price $. Спасибо за использование наших услуг!</p>";
        echo '<a href="index.php">Вернуться на главную</a>';

    } catch (Exception $e) {
        // Если произошла ошибка, откатываем транзакцию
        $conn->rollback();
        echo "<h2>Произошла ошибка при бронировании. Пожалуйста, попробуйте позже.</h2>";
        echo '<a href="cart.php">Вернуться в корзину</a>';
    }
} else {
    echo "<h2>Ошибка: Туры не найдены.</h2>";
    echo '<a href="cart.php">Вернуться в корзину</a>';
}

?>
