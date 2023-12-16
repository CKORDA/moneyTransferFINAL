<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="../theme/pageStyle.css">
</head>
<body>

<?php
require_once('../functions.php');
require_once('../theme/pageStyle.html');
require_once('../theme/menu.html');

$servername = "localhost";
$username = "admin";
$password = "admin";
$dbname = "moneyTransfer";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle user deletion
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
        $delete_id = $_POST["delete_id"];
        $delete_sql = $conn->prepare("DELETE FROM users WHERE id = :delete_id");

        try {
            $delete_sql->bindParam(':delete_id', $delete_id);
            $delete_sql->execute();
            echo "<p>User deleted successfully!</p>";
        } catch (PDOException $e) {
            echo "Error deleting user: " . $e->getMessage();
        }
    }

    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$conn = null;
?>

<div class="container">
    <h2>User Management - Delete Users</h2>

    <h3>User Table</h3>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Delete</th>
        </tr>

        <?php
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["role"] . "</td>";
            echo "<td>";
            echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' onsubmit='return confirm(\"Are you sure you want to delete this user?\");'>";
            echo "<input type='hidden' name='delete_id' value='" . $row["id"] . "'>";
            echo "<button type='submit' class='btn btn-danger'>Delete</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
