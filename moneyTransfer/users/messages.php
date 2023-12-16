<!DOCTYPE html>
<html>
<head>
    <title><?php echo "Message Board"; ?></title>
    <?php
    require_once('../functions.php');
    require_once('../theme/pageStyle.html');
    require_once('../theme/menu.html');
    ?>
    <style>
        .answer {
            margin-bottom: 20px;
            display: none;
        }
    </style>
</head>
<body>
    <h1><?php echo 'Messages'; ?></h1>
    <?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Retrieve the user information from the session
    $loggedInUser = $_SESSION['username'];

    // Check if the user is logged in
    if (!isset($_SESSION['username'])) {
        // Redirect to the login page if not logged in
        header("Location: ../../users/login.php");
        exit();
    }

    // The database connection and data retrieval PHP code
    $host = "localhost";
    $user = "admin";
    $pass = "admin";
    $db = "moneyTransfer";

    try {
        $connection = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve user information from the database
        $query2 = "SELECT role, username FROM users WHERE username = :loggedInUser";
        $stmt2 = $connection->prepare($query2);
        $stmt2->bindParam(':loggedInUser', $loggedInUser);
        $stmt2->execute();
        $userData = $stmt2->fetch(PDO::FETCH_ASSOC);

        // Check if the user has admin role
        if ($userData['role'] !== 'admin') {
            // Redirect to another page or display an access denied message
            echo '<h1>Access Denied</h1>';
            echo '<p>You do not have permission to view this page.</p>';
            exit();
        }

        // Retrieve messages from the database
        $query = "SELECT * FROM messages ORDER BY created_at DESC";
        $stmt = $connection->prepare($query);
        $stmt->execute();

        // Fetch the data as an associative array
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Process the messages as needed
        foreach ($messages as $i => $message) {
            $sender = $message['name'];
            $email = $message['email'];
            $content = $message['message'];
            $date = $message['created_at'];

            echo '<div class="notifications-container">';
            echo '  <div class="notif-item">';
            echo '      <div class="notification" onclick="toggleAnswer(' . $i . ')">' . "Message from " . $date . '</div>';
            echo '      <div class="message" id="message_' . $i . '" style="display:none;">';
            echo '          <p>Sender: ' . $sender . '</p>';
            echo '          <p>Email: ' . $email . '</p>';
            echo '          <p>Content: ' . $content . '</p>';
            echo '      </div>';
            echo '  </div>';
            echo '</div>';
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    // Close the PDO connection
    $connection = null;
    ?>

    <script>
        function toggleAnswer(index) {
            var messageElement = document.getElementById('message_' + index);
            messageElement.style.display = (messageElement.style.display === 'none') ? 'block' : 'none';
        }
    </script>

</body>
</html>
