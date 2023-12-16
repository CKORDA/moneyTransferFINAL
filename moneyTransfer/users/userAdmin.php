<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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

    // Handle form submission for adding a new user
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["username"];
        $email = $_POST["email"];
        $role = $_POST["role"];
        $pwd = $_POST["pwd"];
        $hash_pwd = password_hash($pwd, PASSWORD_DEFAULT);

        // Insert new user into the database using prepared statement
        $sql = $conn->prepare("INSERT INTO users (username, email, role, password) VALUES (:name, :email, :role, :hash_pwd)");
        $sql->bindParam(':name', $name);
        $sql->bindParam(':email', $email);
        $sql->bindParam(':role', $role);
        $sql->bindParam(':hash_pwd', $hash_pwd);

        $sql->execute(); // Execute the prepared statement
        echo "<p>New user added successfully!</p>";
    }

    // Handle user deletion
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
        $delete_id = $_POST["delete_id"];
        $delete_sql = $conn->prepare("DELETE FROM users WHERE id = :delete_id");
        $delete_sql->bindParam(':delete_id', $delete_id);
        $delete_sql->execute();
        echo "<p>User deleted successfully!</p>";
    }

    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$conn = null;
?>

<div class="container">
    <h2>User Management</h2>

    <h3>Add a New User</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="name">Name:</label>
        <input type="text" name="username" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="role">Role:</label>
        <input type="text" name="role" required><br>

        <label for="role">Issued Password:</label>
        <input type="password" name="pwd" required><br>

        <input type="submit" value="Add User">
    </form>

    <h3>User Table</h3>
    <table>
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
            echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            echo "<input type='hidden' name='delete_id' value='" . $row["id"] . "'>";
            echo "<input type='submit' value='Delete'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
