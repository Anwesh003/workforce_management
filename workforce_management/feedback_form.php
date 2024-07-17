<!-- This is feedback_form.php-->
<?php
// Function to generate random alphanumeric string of specified length
function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $servername = "localhost";
    $username = "root"; 
    $password = ""; 
    $dbname = "workforce_management"; 

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind parameters
    $stmt = $conn->prepare("INSERT INTO feedback (feedback_id, farmer_id, worker_id, task_id, rating, comment) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $feedback_id, $farmer_id, $worker_id, $task_id, $rating, $comment);

    // Set parameters
    $feedback_id = $_POST['feedback_id'];
    $farmer_id = $_POST['farmer_id'];
    $worker_id = $_POST['worker_id'];
    $task_id = $_POST['task_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Execute the prepared statement
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // Redirect to f_view_task.php
        header("Location: f_view_task.php");
        exit(); // Ensure script stops here to prevent further execution
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .success-message {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-top: 10px;
        }

        .rating {
            unicode-bidi: bidi-override;
            direction: rtl;
            text-align: center;
        }

        .rating > label {
            display: inline-block;
            position: relative;
            width: 1.1em;
            font-size: 2em;
            color: #ccc;
            cursor: pointer;
        }

        .rating > label:hover:before,
        .rating > label:hover ~ label:before,
        .rating > input:checked ~ label:before {
            content: "\2605";
            position: absolute;
            color: gold;
        }

        .rating > label:before {
            content: "\2606";
            position: absolute;
        }

        .rating > input {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Feedback Form</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="feedback_id">Feedback ID:</label><br>
            <input type="text" id="feedback_id" name="feedback_id" value="<?php echo generateRandomString(8); ?>" readonly><br>
            
            <label for="farmer_id">Farmer ID:</label><br>
            <input type="text" id="farmer_id" name="farmer_id" value="<?php echo isset($_GET['farmer_id']) ? htmlspecialchars($_GET['farmer_id']) : ''; ?>" readonly><br>
            
            <label for="worker_id">Worker ID:</label><br>
            <input type="text" id="worker_id" name="worker_id" value="<?php echo isset($_GET['worker_id']) ? htmlspecialchars($_GET['worker_id']) : ''; ?>" readonly><br>
            
            <label for="task_id">Task ID:</label><br>
            <input type="text" id="task_id" name="task_id" value="<?php echo isset($_GET['task_id']) ? htmlspecialchars($_GET['task_id']) : ''; ?>" readonly><br>
            
            <label for="rating">Rating:</label>
            <div class="rating">
                <input type="radio" id="star5" name="rating" value="5" required />
                <label for="star5"></label>
                <input type="radio" id="star4" name="rating" value="4" />
                <label for="star4"></label>
                <input type="radio" id="star3" name="rating" value="3" />
                <label for="star3"></label>
                <input type="radio" id="star2" name="rating" value="2" />
                <label for="star2"></label>
                <input type="radio" id="star1" name="rating" value="1" />
                <label for="star1"></label>
            </div>
            <br><br>
            
            <label for="comment">Comment:</label><br>
            <textarea id="comment" name="comment" rows="4" cols="50"></textarea><br><br>
            
            <input type="submit" name="submit" value="Submit">
        </form>
    </div>
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
        <div class="success-message">
            Feedback submitted successfully!
        </div>
    <?php endif; ?>

    <script>
        // JavaScript to handle star rating selection
        const starRating = document.getElementById('starRating');
        const stars = starRating.querySelectorAll('input[type="radio"]');

        stars.forEach((star, index) => {
            star.addEventListener('click', function() {
                for (let i = 0; i < stars.length; i++) {
                    if (i <= index) {
                        stars[i].checked = true;
                    } else {
                        stars[i].checked = false;
                    }
                }
            });
        });
    </script>
</body>
</html>

