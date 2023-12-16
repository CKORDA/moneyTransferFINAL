<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<style>
    .answer {
        margin-bottom: 20px;
        display: none;
    }
</style>

<body>
    <div id="notifications">
        <?php
        require_once('../functions.php');
        require_once('../theme/pageStyle.html');
        require_once('../theme/menu.html');
        ?>
    </div>
    <h1>Notifications Page</h1>
    <ul id="notifications-list">
        <!-- Notifications will be dynamically added here using JavaScript -->
    </ul>

    <?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['username'])) {
        // Redirect to the login page if not logged in
        header("Location: ../users/login.php");
        exit();
    }

    // Retrieve the user information from the session
    $loggedInUser = $_SESSION['username'];

    // Connect to your database using PDO
    $dsn = 'mysql:host=localhost;dbname=moneyTransfer';
    $usernameDB = 'admin';
    $passwordDB = 'admin';

    try {
        $pdo = new PDO($dsn, $usernameDB, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch notifications using PDO prepared statement
        $query = "SELECT * FROM transfers WHERE senderID = :loggedInUser OR recipientID = :loggedInUser ORDER BY sendDate DESC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':loggedInUser', $loggedInUser);
        $stmt->execute();
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Process the notifications as needed
        foreach ($notifications as $i => $value) {
            $recipientID = $notifications[$i]['recipientID'];
            $amount = $notifications[$i]['amount'];
            $senderID = $notifications[$i]['senderID'];
            $date = $notifications[$i]['sendDate'];

            echo '<div class="notifications-container">';
            echo '  <div class="notif-item">';
            echo '      <div class="notification" onclick="toggleAnswer(' . $i . ')">' . "New Transfer sent " . $date . '</div>';
            echo '      <div class="message" id="message_' . $i . '" style="display:none;">';
            echo '          <p>Recipient ID: ' . $recipientID . '</p>';
            echo '          <p>Amount: ' . $amount . '</p>';
            echo '          <p>Sender ID: ' . $senderID . '</p>';
            echo '      </div>';
            echo '  </div>';
            echo '</div>';
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        // Close the PDO connection
        $pdo = null;
    }
    ?>
    <script>
        function toggleAnswer(index) {
            var messageElement = document.getElementById('message_' + index);
            messageElement.style.display = (messageElement.style.display === 'none') ? 'block' : 'none';
        }
    </script>
</body>

</html>
