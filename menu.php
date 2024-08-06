<?php
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "food_order");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize cart if not already
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $quantity = intval($_POST['quantity']);
    
    if ($quantity > 0) {
        if (isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id]['quantity'] += $quantity;
        } else {
            $stmt = $conn->prepare("SELECT * FROM menu_items WHERE id = ?");
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($item = $result->fetch_assoc()) {
                $_SESSION['cart'][$item_id] = [
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $quantity
                ];
            }
            $stmt->close();
        }
    }
}

// Fetch menu items from the database
$menu_items = $conn->query("SELECT * FROM menu_items");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        h1 {
            text-align: center;
            margin: 20px 0;
        }
        .menu {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 30%;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .item:hover {
            transform: scale(1.05);
        }
        .item img {
            width: 100%;
            border-bottom: 1px solid #ddd;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }
        .item h2 {
            font-size: 24px;
            color: #333;
        }
        .item p {
            color: #666;
            padding: 0 20px;
        }
        button {
            display: block;
            width: calc(100% - 40px);
            margin: 10px 20px;
            padding: 10px 0;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #218838;
        }
        input[type="number"] {
            width: 60px;
            margin: 10px 0;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }
        .checkout {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Menu</h1>
        <div class="menu">
            <?php if ($menu_items->num_rows > 0): ?>
                <?php while ($row = $menu_items->fetch_assoc()): ?>
                    <div class="item">
                        <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                        <h2><?= htmlspecialchars($row['name']) ?></h2>
                        <p><?= htmlspecialchars($row['description']) ?></p>
                        <p><?= htmlspecialchars($row['price']) ?> /-</p>
                        <form method="post" action="">
                            <input type="hidden" name="item_id" value="<?= htmlspecialchars($row['id']) ?>">
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" value="1" min="1" required>
                            <button type="submit" name="add_to_cart">Add to Cart</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No menu items available.</p>
            <?php endif; ?>
        </div>
        <div class="checkout">
            <form method="post" action="checkout.php">
                <button type="submit" name="checkout">Checkout</button>
            </form>
        </div>
    </div>
</body>
</html>
