<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Page</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 20;
            padding: 20;
        }
    </style>
</head>
<body>
<?php

$json_file_path = 'contacts.json';

// Check if the file exists
if (file_exists($json_file_path)) {
    $json_data = file_get_contents($json_file_path);
    $contacts = json_decode($json_data, true);
    foreach ($contacts as $contact) {
        list($name, $email, $message) = $contact;
        echo "Name: $name<br>";
        echo "Email: $email<br>";
        echo "Message: $message<br>";
        echo "------------------------<br>";
    }
} else {
    echo "Contacts file not found.\n";
}
?>
</body>
