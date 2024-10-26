
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <form action="login.php" method="POST">
        <input type="text" name="login" placeholder="Введите логин...">
        <input type="password" name="password" placeholder="Введите пароль"> 
        <input type="submit" value="автор">
    </form>

    <?php
    session_start();
    require_once('connection.php');
    if(isset($_SESSION['user'])){
        echo "<span> Добро пожаловать, " . $_SESSION['user']['name'] . "! </span>";
    }
?>
</body>
</html>
