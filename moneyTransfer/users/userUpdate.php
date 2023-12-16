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
	
    // Handle form submission for adding or updating a user
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["update_user"])) {
            // Updating an existing user
            $user_id = $_POST["user_id"];
            $name = $_POST["username"];
            $email = $_POST["email"];
            $role = $_POST["role"];

            // Update user information in the database using prepared statement
            $update_sql = $conn->prepare("UPDATE users SET username = :name, email = :email, role = :role WHERE id = :user_id");
            $update_sql->bindParam(':name', $name);
            $update_sql->bindParam(':email', $email);
            $update_sql->bindParam(':role', $role);
            $update_sql->bindParam(':user_id', $user_id);

            $update_sql->execute(); // Execute the prepared statement
            echo "<p>User updated successfully!</p>";
        }
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Display the user table
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
$conn = null;
?>

<div class="container">
    <h2>User Management - Update a User</h2>

    <h3>User Table</h3>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>

        <?php
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["role"] . "</td>";
			 
			  if ($row["username"] != $loggedInUser) {
            echo "<td>
                    <form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
                        <input type='hidden' name='user_id' value='" . $row["id"] . "'>
                        <input type='text' name='username' value='" . $row["username"] . "' required>
                        <input type='email' name='email' value='" . $row["email"] . "' required>
		                	<select name='role' required>
                    			<option value='admin' " . ($row["role"] == 'admin' ? 'selected' : '') . ">Admin</option>
                    			<option value='user' " . ($row["role"] == 'user' ? 'selected' : '') . ">User</option>
                			</select>						
                        <input type='submit' name='update_user' value='Update'>
                    </form>
                </td>";
            echo "</tr>";
				  } else {
            echo "<td>
                    <form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
                        <input type='hidden' name='user_id' value='" . $row["id"] . "'>
                        <input type='text' name='username' value='" . $row["username"] . "' required>
                        <input type='email' name='email' value='" . $row["email"] . "' required>
								<select name='role' required>
                    			<option value='NULL_POINTER' " . ($row["role"] == 'DENIED' ? 'selected' : '') . ">DENIED</option>
                			</select>
                        <input type='hidden' name='role' value='" . $row["role"] . "' required>
                        <input type='submit' name='update_user' value='Update'>
                    </form>
                </td>";
            echo "</tr>";
				  
			  }
        }
        ?>
    </table>
</div>

</body>
</html>
