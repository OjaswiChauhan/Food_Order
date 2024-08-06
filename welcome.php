<?php
session_start();

// Check if user is logged in and session variables are set
if (!isset($_SESSION['email']) || !isset($_SESSION['name'])) {
    header("Location: index.php");
    exit();
}

// Logout logic
if (isset($_POST['logout'])) {
    // Unset all session variables
    session_unset();
    // Destroy the session
    session_destroy();
    // Redirect to login page
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Food Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #ff6f61;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        nav ul li {
            margin: 0 10px;
        }
        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        nav ul li a:hover {
            color: #ff6f61;
        }
        .logout-btn {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #555;
        }
        .success-message {
            background-color: #4caf50;
            color: #fff;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Welcome to Food Website</h1>
        </div>
    </header>

    <nav class="navbar">
        <div class="container">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="reviews.php">Reviews</a></li>
                <li><a href="my_profile.php">My Profile</a></li>
                <li><a href="#">Contact Us</a></li>
                <li>
                    <form method="post" action="">
                        <button type="submit" name="logout" class="logout-btn">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="content">
            <?php if (isset($_GET['registration']) && $_GET['registration'] == 'success') : ?>
                <div class="success-message">Registration complete! Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>!</div>
            <?php endif; ?>
            <h2>Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>!</h2>
            <p>Explore our delicious menu and place your order online.</p>
        </div>
    </div>
</body>
</html>