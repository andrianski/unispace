<?php
$host = 'localhost';
$dbname = 'unispace';
//include '../dbpass.php');
// сложи в горния файл
<<<<<<< HEAD
$dbusername = 'root'; // Замени с твоя MySQL потребител
$dbpassword = 'zaxscd12'; // Замени с парола, ако има
=======
$username = 'root'; // Замени с твоя MySQL потребител
$password = ''; // Замени с парола, ако има
>>>>>>> b88b95f3f7605737b28ccda95077e48f015d481c
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Грешка при връзка с базата: " . $e->getMessage());
}
?>
 
