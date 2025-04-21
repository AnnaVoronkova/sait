<?php
include 'db.php';

// Создание временной таблицы
$conn->query("CREATE TEMPORARY TABLE temp_clients AS SELECT * FROM clients LIMIT 3");

// Добавление данных
if (isset($_POST['add'])) {
    $conn->query("INSERT INTO temp_clients (first_name, last_name, email, phone) VALUES ('Тест', 'Тестов', 'test@mail.com', '123456789')");
}

// Удаление данных
if (isset($_POST['delete'])) {
    $conn->query("DELETE FROM temp_clients WHERE client_id = (SELECT MAX(client_id) FROM temp_clients)");
}

// Выборка данных
$result = $conn->query("SELECT * FROM temp_clients");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Временная таблица</title>
</head>
<body>
    <h2>Временная таблица клиентов</h2>
    <form method="POST">
        <input type="submit" name="add" value="Добавить клиента">
        <input type="submit" name="delete" value="Удалить последнего клиента">
    </form>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Email</th>
            <th>Телефон</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row["client_id"] ?></td>
                <td><?= $row["first_name"] ?></td>
                <td><?= $row["last_name"] ?></td>
                <td><?= $row["email"] ?></td>
                <td><?= $row["phone"] ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>