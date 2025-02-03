<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db_connect.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=Monster', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Fel vid anslutning: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['id'];
    $namn = $_POST['namn'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $startDatum = $_POST['startDatum'];

    $sql = "INSERT INTO employees (id, namn, email, telefon, startDatum) VALUES (:id, :namn, :email, :telefon, :startDatum)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':namn', $namn);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefon', $telefon);
    $stmt->bindParam(':startDatum', $startDatum);

    if ($stmt->execute()) {
        echo "Anställd tillagd!";
    } else {
        echo "Det gick inte att lägga till den anställde.";
    }
}

try {
    $stmt = $pdo->query("SELECT * FROM employees");
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Fel vid hämtning: " . $e->getMessage());
}
?>

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

    <h2>Lägg till ny anställd</h2>
    <form method="POST" action="">
        
        <label for="id">Id:</label> <br>
        <input type="id" name="id" id="id" required><br>

        <label for="namn">Namn:</label><br>
        <input type="text" name="namn" id="namn" required><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" required><br>

        <label for="telefon">Telefon:</label><br>
        <input type="text" name="telefon" id="telefon" required><br>

        <label for="startDatum">Datum:</label><br>
        <input type="text" name="startDatum" id="startDatum" required><br>
    
    <button type="submit">Lägg till anställd</button>
        </form>
        
</body>
</html>
