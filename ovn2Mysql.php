<link rel="stylesheet" href="./css/style.css">
<link rel="stylesheet" href="./css/loading.css">
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require "db_connect.php";
//----------------------------------------------

try {
    $stmt = $pdo->query("SELECT * FROM members");
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Fel vid hämtning: " . $e->getMessage());
}
?>
<br>
<div class="employed">
<?php foreach ($members as $employee): ?>
    <tr>
        <td>id=<?= htmlspecialchars($employee["id"])?> -</td>
        <td><?= htmlspecialchars($employee["firstname"])?></td>
        <td><?= htmlspecialchars($employee["lastname"])?> -</td>
        <td><?= htmlspecialchars($employee["birthday"])?> -</td>
        <td><?= htmlspecialchars($employee["phone"])?> -</td>
        <td><?= htmlspecialchars($employee["email"])?></td>     <br>
    </tr>
<?php endforeach; ?>
</div>

<?php //---------------------------------------------- sql logic for member management
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'] ?? null;
    $lastname = $_POST['lastname'] ?? null;
    $email = $_POST['email'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $birthday = $_POST['birthday'] ?? null;

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $sql = "INSERT INTO members (id, firstname, lastname, email, phone, birthday) VALUES (:id, :firstname, :lastname, :email, :phone, :birthday)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':birthday', $birthday);
                header("Refresh:2");
                break;
            case 'manage':
                $sql = "UPDATE members SET ";
                $params = [];
                if (!empty($firstname)) {
                    $sql .= "firstname = :firstname, ";
                    $params[':firstname'] = $firstname;
                }
                if (!empty($lastname)) {
                    $sql .= "lastname = :lastname, ";
                    $params[':lastname'] = $lastname;
                }
                if (!empty($email)) {
                    $sql .= "email = :email, ";
                    $params[':email'] = $email;
                }
                if (!empty($phone)) {
                    $sql .= "phone = :phone, ";
                    $params[':phone'] = $phone;
                }
                if (!empty($birthday)) {
                    $sql .= "birthday = :birthday, ";
                    $params[':birthday'] = $birthday;
                }
                $sql = rtrim($sql, ", ");
                $sql .= " WHERE id = :id";
                $params[':id'] = $id;
                $stmt = $pdo->prepare($sql);
                foreach ($params as $param => $value) {
                    $stmt->bindParam($param, $value);
                }
                break;
                header("Refresh:2");
            case 'remove':
                $sql = "DELETE FROM members WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id);
                header("Refresh:2");
                break;
            default:
                echo "Invalid action.";
                exit;
        }

        if ($stmt->execute()) {
            echo "Action executed successfully.";
        } else {
            echo "Error executing action.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $change = $_POST['change'];

    if (isset($_POST['action2'])) {
        $field = $_POST['action2'];
        $allowedFields = ['firstname', 'lastname', 'email', 'phone', 'birthday'];

        if (in_array($field, $allowedFields)) {
            $sql = "UPDATE members SET $field = :change WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':change', $change);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                echo "Field updated successfully.";
                header("Refresh:2");
            } else {
                echo "Error updating field.";
            }
        } else {
            echo "Invalid field.";
        }
    }
}
?>

<div class="container">
    <h2>Add a new member</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="firstname">Firstname:</label>
            <input type="text" name="firstname" id="firstname" required>
        </div>
        <div class="form-group">
            <label for="lastname">Lastname:</label>
            <input type="text" name="lastname" id="lastname" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone num:</label>
            <input type="text" name="phone" id="phone" required>
        </div>
        <div class="form-group">
            <label for="birthday">Date of birth:</label>
            <input type="text" name="birthday" id="birthday" required>
        </div>
        <div class="form-group">
            <button type="submit" name="action" value="add">Add</button>
        </div>
        </form>


    <div class="container">
        <h2>Manage Members</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="id">Id:</label>
                <input type="text" name="id" id="id" required>
            </div>
            <div class="form-group">
                <label for="change">Change to:</label>
                <input type="text" name="change" id="change" required>
            </div>
            <div class="form-group2">
                <button type="submit" name="action2" value="firstname">Firstname</button>
                <button type="submit" name="action2" value="lastname">Lastname</button>
                <button type="submit" name="action2" value="email">Email</button>
                <button type="submit" name="action2" value="phone">Phone</button>
                <button type="submit" name="action2" value="birthday">Birthday</button>
            </div>
        </form>
    </div>

    <div class="container">
        <h2>Remove a Member</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="id">Id:</label>
                <input type="text" name="id" id="id" required>
            </div>
            <div class="form-group">
            <button type="submit" name="action" value="remove">Remove</button>
            </div>
        </form>
    </div>
    
</div>