<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'config.php';
require 'includes/functions.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP + Vue.js + Bootstrap</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    


<?php



$page = isset($_GET['page']) ? $_GET['page'] : 'login'; // Ако няма параметър, по подразбиране "home"
$page = basename($page); // Защита срещу директории

$file = "pages/$page.php";

if (file_exists($file)) {
    include $file;
} else {
    echo "Страницата не съществува!";
}
/*
if (!isset($_SESSION['user_id'])) {
  header("Location: ?login.php");
   exit;
}*/





?>

    <!-- Vue.js CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <!-- Bootstrap JS (optional) -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!-- Вашият Vue.js код -->
    <script>
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    message: 'Hello, PHP + Vue.js + Bootstrap!'
                };
            },
            methods: {
                showAlert() {
                    alert('Button clicked!');
                }
            }
        }).mount('#app');
    </script>
</body>
</html>