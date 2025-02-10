<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db_connect.php';
//----------------------------------------------
try {
    $stmt = $pdo->query("SELECT * FROM employees");
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Fel vid hämtning: " . $e->getMessage());
}?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Anställda</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Lista över anställda</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Id</th>
                <th>Namn</th>
                <th>Email</th>
                <th>Telefon</th>
                <th>Startdatum</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?= htmlspecialchars($employee['id']) ?></td>
                    <td><?= htmlspecialchars($employee['namn']) ?></td>
                    <td><?= htmlspecialchars($employee['email']) ?></td>
                    <td><?= htmlspecialchars($employee['telefon']) ?></td>
                    <td><?= htmlspecialchars($employee['startDatum']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<h2>Anställda mellan 2006-2009</h2>
    <ul>
        <?php foreach ($employees as $employee): ?>
            <?php 
                $year = date('Y', strtotime($employee['startDatum']));
                    if ($year >= '2006' && $year <= '2009'): 
                    ?>
                <p>id=<?= htmlspecialchars($employee['id']) ?> - <?= htmlspecialchars($employee['namn']) ?> - <?= htmlspecialchars($employee['email']) ?> - <?= htmlspecialchars($employee['telefon']) ?> - <?= htmlspecialchars($employee['startDatum']) ?></p>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        th, td {
            padding: 8px;
            text-align: center;
            background-color: #f9f9f9;
            border: 2px solid #ddd;
        }
        h1, h2 {
            color: #333;

    </style>