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
            margin: 0;
            padding: 0;
        }

        .faq, .Support {
            width: 80%;
            margin: auto;
            padding: 20px;
        }

        .faq h2, .Support h2 {
            color: #333;
        }

        .question h3 {
            color: #007bff;
        }

        .answer {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="faq">
        <h2>Frequently Asked Questions</h2>

        <?php
        $faqs = [
            ["How does the transfer work?", "The user enters the amount that they want to request into the corresponding textbox. The person receiving the request will be notified about the request. From there, they will be able to approve or deny the request. If approved, the money will be sent from the sender's account to the requester."],
            ["Is it secure?", "Yes! The process is encrypted, so your money and information are completely protected."],
            // Add more questions and answers as needed
        ];

        foreach ($faqs as $faq) {
            echo '<div class="question">';
            echo '<h3>' . $faq[0] . '</h3>';
            echo '<div class="answer">' . $faq[1] . '</div>';
            echo '</div>';
        }
        ?>

    </div>

    <div class="Support">
        <h2>Contact Us</h2>
        <p>If you have any further questions or comments, feel free to contact us!</p>

        <?php
        $contacts = [
            ["David-Michael Davies", "daviesm4@mymail.nku.edu"],
            ["Cheyenne Korda", "kordac1@mymail.nku.edu"],
            ["Ramatoulaye Signate", "signater1@mymail.nku.edu"],
        ];

        foreach ($contacts as $contact) {
            echo '<a href="mailto:' . $contact[1] . '">' . $contact[0] . '</a><p></p>';
        }
        ?>

    </div>

    <script src="script.js"></script>
</body>
</html>
