<!-- This is booking_process.php-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Process</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        /* Additional style for the button */
        .go-to-button {
            text-align: center;
            margin-top: 20px;
        }
        .go-to-button a {
            background-color: #008CBA;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
        }
        .go-to-button a:hover {
            background-color: #005f7b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enter Task Details</h2>
        <form action="submit_task.php" method="post">
            <?php
            // Retrieve worker and farm details from URL parameters
            if (isset($_GET['worker_id'])) {
                $worker_id = $_GET['worker_id'];
                // Pre-select worker_id
                echo "<input type='hidden' name='worker_id' value='$worker_id'>";
            }

            if (isset($_GET['farm_id'])) {
                $farm_id = $_GET['farm_id'];
                // Pre-select farm_id
                echo "<input type='hidden' name='farm_id' value='$farm_id'>";
            }

            // Generate a unique task ID
            $task_id = substr(md5(uniqid(mt_rand(), true)), 0, 6);

            // Get current date and add one day
            $next_day = date("Y-m-d", strtotime("+1 day"));
            ?>

            <!-- Task Details -->
            <label for="task_id">Task ID:</label>
            <input type="text" id="task_id" name="task_id" value="<?php echo $task_id; ?>" readonly required>

            <label for="task_name">Task Name:</label>
            <input type="text" id="task_name" name="task_name" required>

            <!-- Farm ID -->
            <label for="farm_id">Farm ID:</label>
            <input type="text" id="farm_id" name="farm_id" value="<?php echo isset($farm_id) ? $farm_id : ''; ?>" <?php echo isset($farm_id) ? 'disabled' : ''; ?> required>

            <?php
            // Display worker details if available
            if (isset($_GET['worker_id'])) {
                $worker_id = $_GET['worker_id'];
                // Display worker_id
                echo "<label for='worker_id'>Worker ID:</label>";
                echo "<input type='text' id='worker_id' name='worker_id' value='$worker_id' disabled>";
            }
            ?>

            <!-- Start Date -->
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo $next_day; ?>" required>

            <label for="due_date">Due Date:</label>
            <input type="date" id="due_date" name="due_date" required>

            <!-- Submit button for booking -->
            <input type="submit" name="book_task" value="Book">
        </form>
    </div>

    <!-- Button to go to book_worker.php -->
</body>
</html>