<?php
require_once('../functions.php');
require_once('../theme/pageStyle.html');
require_once('../theme/menu.html');

session_start();

if (isset($_SESSION['username'])) {
    echo 'Already Signed in. Please go to the <a href="../pages/dashboard.php">My Dashboard</a>.<br />';
    echo 'If you wish to logout: <form method="POST" action="../users/logout.php">';
    echo '<input type="submit" value="Logout">';
    echo '</form>';
    session_destroy();
}

$showForm = true;

if (count($_POST) > 0) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Create a PDO connection
        $dsn = 'mysql:host=localhost;dbname=moneyTransfer';
        $usernameDB = 'admin';
        $passwordDB = 'admin';

        try {
            $pdo = new PDO($dsn, $usernameDB, $passwordDB);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query = "SELECT id, username, password FROM users WHERE username = :username";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && password_verify($password, $row['password'])) {
                // Sign the user in
                $_SESSION['username'] = $row['username'];
                $_SESSION['ID'] = $row['id'];

                header("Location: ../pages/dashboard.php");
                // echo 'Successful Login. Please go to the <a href="../pages/dashboard.php">My Dashboard</a>.';
                $showForm = false;
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }

        // The credentials are wrong
        if ($showForm) {
            echo 'Your credentials are wrong';
        }
    } else {
        echo 'Username and password are missing';
    }
}

if ($showForm) {
?>
    <h1>Login</h1>
    <form method="POST">
        User Name<br />
        <input type="text" name="username" required /><br /><br />
        Password<br />
        <input type="password" name="password" required /><br /><br />
        <button type="submit">Sign in</button>
    </form>
    <form method="POST" action="../users/registration.php">
        <button type="link">Register</button>
    </form>
<?php
}
?>
