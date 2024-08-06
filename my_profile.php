MY_PROFILE:

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        // Code to delete user's entry
        $servername = "localhost";
        $username = "root";
        $password = ""; // Your database password if any
        $dbname = "food_order";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $email = $_SESSION['email'];

        // SQL to delete a record
        $sql = "DELETE FROM users WHERE email='$email'";

        if ($conn->query($sql) === TRUE) {
            // Unset all session variables
            session_unset();
            // Destroy the session
            session_destroy();
            // Redirect to index page after deletion
            header("Location: index.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }

        $conn->close();
    } elseif (isset($_POST['update'])) {
        // Code to update user's profile
        $servername = "localhost";
        $username = "root";
        $password = ""; // Your database password if any
        $dbname = "food_order";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $email = $_SESSION['email'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        // SQL to update user's profile
        $sql = "UPDATE users SET name='$name', password='$password', phone='$phone', address='$address' WHERE email='$email'";

        if ($conn->query($sql) === TRUE) {
            // Redirect back to welcome.php after updating
            header("Location: welcome.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }

        $conn->close();
    }
}

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = ""; // Your database password if any
$dbname = "food_order";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];

$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

$name = '';
$email = '';
$password = '';
$phone = '';
$address = '';

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row["name"];
    $email = $row["email"];
    $password = $row["password"];
    $phone = $row["phone"];
    $address = $row["address"];
} else {
    echo "0 results";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <style>
        /* Internal CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-container {
            text-align: right;
        }
        .btn-update,
        .btn-delete {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-update:hover,
        .btn-delete:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<h1>My Profile</h1>
<div class="container">
    <form id="profile-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <label for='name'>Name:</label>
        <input id='name' type='text' name='name' value='<?php echo $name; ?>' required><br>
        <label for='email'>Email:</label>
        <input id='email' type='email' name='email' value='<?php echo $email; ?>' required disabled><br>
        <label for='password'>Password:</label>
        <input id='password' type='text' name='password' value='<?php echo $password; ?>' required><br>
        <label for='phone'>Phone:</label>
<input id='phone' type='tel' name='phone' value='<?php echo $phone; ?>' pattern="\d{10}" required><br>

        <label for='address'>Address:</label>
        <input id='address' type='text' name='address' value='<?php echo $address; ?>' required><br>
        <!-- Update and Delete buttons -->
        <div class="btn-container">
            <button class="btn-update" type="submit" name="update" onclick="return confirm('Are you sure you want to update your profile?')">Update</button>
            <button class="btn-delete" type="submit" name="delete" onclick="return confirm('Are you sure you want to delete your profile?')">Delete</button>
        </div>
    </form>
</div>
</body>
</html>