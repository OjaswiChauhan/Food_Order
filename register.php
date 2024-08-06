<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = ""; // Your database password if any
    $dbname = "food_order";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Process registration form
    if (isset($_POST['register'])) {
        $name = $_POST['registerName'];
        $email = $_POST['registerEmail'];
        $password = $_POST['registerPassword']; // Store the password as is
        $address = $_POST['registerAddress'];
        $phone = $_POST['registerPhone'];

        // Insert the new user into the database using prepared statements
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, address, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $password, $address, $phone);

        if ($stmt->execute()) {
            // Set session variables
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            // Redirect to welcome page with success message
            header("Location: welcome.php?registration=success");
            exit();
        } else {
            $message = "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }

    // Close database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="registerName">Name:</label>
            <input type="text" id="registerName" name="registerName" required><br><br>
            <label for="registerEmail">Email:</label>
            <input type="email" id="registerEmail" name="registerEmail" required><br><br>
            <label for="registerPassword">Password:</label>
            <input type="password" id="registerPassword" name="registerPassword" required><br><br>
            <label for="registerAddress">Address:</label>
            <input type="text" id="registerAddress" name="registerAddress" required><br><br>
            <label for="registerPhone">Phone:</label>
            <input type="text" id="registerPhone" name="registerPhone" placeholder="Enter phone number" required><br><br>
            <button type="submit" name="register">Register</button>
        </form>
        <p>Already have an account? <a href="index.php">Login here</a>.</p>
        <?php if(isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>
    </div>
</body>
</html>