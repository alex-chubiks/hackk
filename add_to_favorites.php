<?php
session_start();
require_once('connection.php');

if (isset($_SESSION['user']) && isset($_POST['product_id'])) {
    $userId = $_SESSION['user']['id'];
    $productId = intval($_POST['product_id']); // Приведение к целому

    // Проверка, существует ли уже этот товар в избранном
    $check_sql = "SELECT * FROM favorites WHERE user_id = ? AND product_id = ?";
    $stmt = $link->prepare($check_sql);
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows == 0) {
        // Если не существует, добавляем в избранное
        $sql = "INSERT INTO favorites (user_id, product_id) VALUES (?, ?)";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        if ($stmt->execute()) {
            echo "Товар добавлен в избранное!";
        } else {
            echo "Ошибка при добавлении товара в избранное.";
        }
    } else {
        echo "Этот товар уже в вашем избранном.";
    }

    $stmt->close();
} else {
    echo "Ошибка: вы не авторизованы или не указано ID товара.";
}

$link->close();
?>
