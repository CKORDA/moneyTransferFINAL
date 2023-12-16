<!DOCTYPE html>
<html>

<head>
    <title><?= "User Dashboard" ?></title>

    <script>
        function confirmSubmit() {
            var confirmation = window.confirm("Are you sure you want to submit the transaction?");

            if (confirmation) {
                // User clicked OK, proceed with the form submission
                document.getElementById("transactionForm").submit();
            } else {
                // User clicked Cancel, reload page
                window.location.reload();
            }
        }
    </script>
</head>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: ../users/login.php");
    exit();
}

// Retrieve the user information from the session
$loggedInUser = $_SESSION['username'];

require_once('../theme/pageStyle.html');
require_once('../theme/menu.html');

// The database connection and data insertion PHP code
$servername = "localhost";
$username = "admin";
$password = "admin";
$dbname = "moneyTransfer";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve usernames from the database excluding the logged-in user
    $usernamesQuery = "SELECT username FROM users WHERE username != :loggedInUser";
    $usernamesStmt = $conn->prepare($usernamesQuery);
    $usernamesStmt->bindParam(':loggedInUser', $loggedInUser);
    $usernamesStmt->execute();
    $usernames = $usernamesStmt->fetchAll(PDO::FETCH_COLUMN);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $recipientName = $_POST["recipientID"];
        $amountToSend = $_POST["amount"];
        $receivedAmount = $_POST["received_amount"];

        // Check if the recipient is the same as the logged-in user
        if ($recipientName === $loggedInUser) {
            echo "Error: You cannot send money to yourself.";
        } else {
            // Check if the recipient exists in the database
            if (!in_array($recipientName, $usernames)) {
                echo "Error: Recipient not found in the database.";
            } else {
                // Recipient exists, proceed with the transaction
                $sql = "INSERT INTO transfers (recipientID, amount, senderID) VALUES (:recipientName, :amountToSend, :loggedInUser)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':recipientName', $recipientName);
                $stmt->bindParam(':amountToSend', $amountToSend);
                $stmt->bindParam(':loggedInUser', $loggedInUser);

                if ($stmt->execute()) {
                    echo "Transaction recorded successfully!";
                } else {
                    echo "Error: " . $stmt->errorInfo()[2];
                }
            }
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$conn = null;
?>

<body>
    <h1>User Profile</h1>
    <table>
        <!-- Your existing HTML form -->
        <h2>Send money to </h2>
        <form id="transactionForm" method="POST">
            Recipient<br />
            <!-- Use a drop-down list for recipients -->
            <select name="recipientID" required>
                <?php
                foreach ($usernames as $username) {
                    echo "<option value='$username'>$username</option>";
                }
                ?>
            </select><br />
            <!-- ... other form fields ... -->
            Amount to Send<br />
            <input type="text" name="amount" required /><br /><br />
            <input type="hidden" name="received_amount" value="amount" /><br /><br />
            <button type="button" onclick="confirmSubmit()">Send</button>
        </form>
    </table>
    <a href="">
        <img src="../data/money_transfer.avif" alt="Money being transferred">
    </a>
</body>

</html>
