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

session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: ../users/login.php");
    exit();
}

$loggedInUser = $_SESSION['username'];

$servername = "localhost";
$username = "admin";
$password = "admin";
$dbname = "moneyTransfer";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve user information from the database
    $sql = $conn->prepare("SELECT role FROM users WHERE username = :loggedInUser");
    $sql->bindParam(':loggedInUser', $loggedInUser);
    $sql->execute();

    $userData = $sql->fetch(PDO::FETCH_ASSOC);

    // Check if the user has admin role
    if ($userData['role'] !== 'admin') {
        // Redirect to another page or display an access denied message
        echo '<h1>Access Denied</h1>';
        echo '<p>You do not have permission to view this page.</p>';
        exit();
    }

    // Handle form submission for adding a new user
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["username"];
        $email = $_POST["email"];
        $role = $_POST["role"];
        $pwd = $_POST["pwd"];
        $hash_pwd = password_hash($pwd, PASSWORD_DEFAULT);

        // Insert new user into the database using prepared statement
        $insert_sql = $conn->prepare("INSERT INTO users (username, email, role, password) VALUES (:name, :email, :role, :hash_pwd)");
        $insert_sql->bindParam(':name', $name);
        $insert_sql->bindParam(':email', $email);
        $insert_sql->bindParam(':role', $role);
        $insert_sql->bindParam(':hash_pwd', $hash_pwd);

        $insert_sql->execute(); // Execute the prepared statement
        echo "<p>New user added successfully!</p>";
    }

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
</div>

</body>
</html>
