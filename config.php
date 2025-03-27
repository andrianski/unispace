<?php
$host = 'localhost';
$dbname = 'unispace';
//include '../dbpass.php');
// сложи в горния файл

$dbusername = 'root'; // Замени с твоя MySQL потребител
$dbpassword = 'zaxscd12'; // Замени с парола, ако има

$username = 'root'; // Замени с твоя MySQL потребител
$password = ''; // Замени с парола, ако има

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Грешка при връзка с базата: " . $e->getMessage());
}
?>
 
