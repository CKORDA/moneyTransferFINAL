<?php
require_once('../functions.php');
require_once('../theme/pageStyle.html');
require_once('../theme/menu.html');

function displayMessage($message, $link = null, $linkText = null) {
    echo $message;
    if ($link !== null) {
        echo ' <a href="' . $link . '">' . $linkText . '</a>.';
    }
}

function checkSession() {
    if (isset($_SESSION['email'])) {
        displayMessage('You are already signed in, please sign out if you want to create a new account.', '../users/logout.php', 'Logout');
        session_destroy();
        return false;
    }
    return true;
}

function processRegistration($username, $password, $email) {
    $hash_pwd = password_hash($password, PASSWORD_DEFAULT);

    try {
        $conn = new PDO("mysql:host=localhost;dbname=moneyTransfer", 'admin', 'admin');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $checkEmailQuery = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($checkEmailQuery);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            displayMessage('Email address already exists. Please Try Again.', '../users/registration.php', 'Register');
            return false;
        } else {
            $sql = "INSERT INTO users (username, password, email) VALUES (:username, :hash_pwd, :email)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':hash_pwd', $hash_pwd);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                displayMessage('Transaction recorded successfully!');
                displayMessage('Your account has been created, proceed to the', '../users/login.php', 'Sign in page');
                return false;
            } else {
                displayMessage('Error: ' . $stmt->errorInfo()[2]);
            }
        }
    } catch (PDOException $e) {
        displayMessage('Connection failed: ' . $e->getMessage());
    }

    return true;
}

if (checkSession()) {
    $showForm = true;

    if (count($_POST) > 0) {
        if (isset($_POST['email'][0]) && isset($_POST['password'][0])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];

            processRegistration($username, $password, $email);
            $showForm = false;
        } else {
            displayMessage('Email and password are missing');
        }
    }

    if ($showForm) {
        ?>
        <h1><?= 'Register' ?></h1>
        <form method="POST">
            <?= 'Email' ?><br />
            <input type="email" name="email" required /><br /><br />
            <?= 'User Name' ?><br />
            <input type="name" name="username" required /><br /><br />
            <?= 'Password' ?><br />
            <input type="password" name="password" required /><br /><br />
            <button type="submit"><?= 'Sign up' ?></button>
        </form>
        <form method="POST" action="../users/login.php">
            <button type="link"><?= 'Login' ?></button>
        </form>
        <?php
    }
}
?>
