<?php
$host = 'localhost';
$dbname = 'unispace';
//include '../dbpass.php');
// сложи в горния файл
$username = 'root'; // Замени с твоя MySQL потребител
$password = 'zaxscd12'; // Замени с парола, ако има
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Грешка при връзка с базата: " . $e->getMessage());
}
?>
 
