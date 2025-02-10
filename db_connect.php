<?php
$dbname = "Crudkontakter";

$host = "localhost";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    echo "<span style='color: green; font-size: 24px; font-weight: bold;'>Connection success!</span>";
} catch (PDOException $e) {
    die("<span style='color: red; font-size: 24px; font-weight: bold;'>Connection failed with error: " . $e->getMessage() . "</span>");
}
?>
