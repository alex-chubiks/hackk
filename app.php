<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .hello {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: flex-end; 
            align-items: flex-start;
            padding: 10px; 
        }
        .log {
            margin-left: 10px; 
        }
    </style>
</head>
<body>
    <?php
    session_start();
    require_once('connection.php');

    if (isset($_SESSION['user'])) {
        echo "<div class='Hello'>
            <span> Добро пожаловать, " . htmlspecialchars($_SESSION['user']['name']) . "! </span>";
        echo '<form action="logout.php" method="POST" class="log">
                <input type="submit" value="Выйти">
              </form>
              </div>';
        
        // Получаем избранные товары пользователя
        $userId = $_SESSION['user']['id'];
        $favorites_sql = "SELECT drills.* FROM favorites 
                          JOIN drills ON favorites.product_id = drills.id 
                          WHERE favorites.user_id = ?";
        
        $favorites_stmt = $link->prepare($favorites_sql);
        $favorites_stmt->bind_param("i", $userId);
        $favorites_stmt->execute();
        $favorites_result = $favorites_stmt->get_result();

        // Выводим избранные товары
        if ($favorites_result->num_rows > 0) {
            echo "<h2>Ваши избранные товары:</h2>";
            while ($row = $favorites_result->fetch_assoc()) {
                echo 'Результат: ' . htmlspecialchars($row['name']) . '<br>';
                echo 'Цена: ' . htmlspecialchars($row['price']) . ' руб.<br>';
            }
        } else {
            echo "<h2>У вас пока нет избранных товаров.</h2>";
        }

    } else {
        echo '<div class="log">
            <form action="logs.php">
                <input type="submit" value="Авторизоваться">
            </form>
        </div>';
    }
    ?>
    
    <div>
        <form action="app.php" method="GET" style="display:inline;">
            <input type="text" name="query" placeholder="Поиск..." style="padding: 5px 5px;">
            <input type="submit" value="Найти" style="padding: 5px;">
        </form>
    </div>
    
    <?php
    // Проверка, существует ли GET-параметр 'query' и не пуст ли он
    if (isset($_GET['query']) && !empty($_GET['query'])) {
        $query = $_GET['query'];

        // Экранирование специальных символов для предотвращения SQL-инъекций
        $query = $link->real_escape_string($query);

        // Выполнение SQL-запроса
        $sql = "SELECT * FROM drills WHERE name LIKE '%$query%'";
        $result = $link->query($sql);

        // Проверка и вывод результатов
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $_SESSION['poisk'][] = [
                    "name" => $row['name'],
                    "price" => $row['price']
                ];
                echo 'Результат: ' . htmlspecialchars($row['name']) . '<br>';
                echo 'Цена: ' . htmlspecialchars($row['price']) . ' руб.<br>';
                
                // Кнопка для добавления в избранное
                echo '<form action="add_to_favorites.php" method="POST" style="display:inline;">

                <input type="hidden" name="product_id" value="' . $row['id'] . '">
                <input type="submit" value="Добавить в избранное">
             </form><br>';
   }
} else {
   echo "Нет результатов.";
}
}
?>
</body>
</html>
