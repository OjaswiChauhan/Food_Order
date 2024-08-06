<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Order Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            margin-top: 20px;
            color: #f00;
        }

        a {
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to Food Order System</h2>
        <form method="post" action="login.php">
            <label for="loginEmail">Email:</label>
            <input type="email" id="loginEmail" name="loginEmail" required><br>
            <label for="loginPassword">Password:</label>
            <input type="password" id="loginPassword" name="loginPassword" required><br>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        <?php if(isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>
    </div>
</body>
</html>
