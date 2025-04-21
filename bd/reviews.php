<?php
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$client_id = $user['client_id'];

// Добавление отзыва
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['review'])) {
    $review = $conn->real_escape_string($_POST['review']);
    $conn->query("INSERT INTO reviews (client_id, review) VALUES ('$client_id', '$review')");
    header("Location: reviews.php");
    exit();
}

// Получение всех отзывов
$reviews = $conn->query("SELECT r.review, r.created_at, c.first_name, c.last_name 
                         FROM reviews r 
                         JOIN clients c ON r.client_id = c.client_id 
                         ORDER BY r.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Отзывы</title>
</head>
<body>
    <h2>Оставьте отзыв</h2>
    <form method="POST">
        <textarea name="review" rows="4" required></textarea><br><br>
        <input type="submit" value="Оставить отзыв">
    </form>

    <h2>Отзывы клиентов</h2>
    <?php while ($row = $reviews->fetch_assoc()) { ?>
        <p><strong><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?>:</strong> <?= htmlspecialchars($row['review']) ?></p>
        <small>Дата: <?= $row['created_at'] ?></small>
        <hr>
    <?php } ?>

    <a href="index.php">На главную</a>
</body>
</html>