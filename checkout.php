<?php
session_start();

// Function to calculate the grand total
function calculateGrandTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Handle the checkout process
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    // Calculate the grand total
    $grandTotal = calculateGrandTotal($_SESSION['cart']);
    
    // Store the cart in a session variable for the receipt
    $_SESSION['last_order'] = $_SESSION['cart'];
    $_SESSION['last_order_total'] = $grandTotal;
    
    // Clear the cart
    $_SESSION['cart'] = [];
    
    // Redirect to avoid form resubmission
    header("Location: checkout.php?success=1");
    exit;
}

// Calculate the grand total to display on the page
$grandTotal = calculateGrandTotal($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
        .cart, .receipt {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        .cart th, .cart td, .receipt th, .receipt td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .cart th, .receipt th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }
        .cart td, .receipt td {
            text-align: center;
        }
        .total {
            text-align: right;
            margin-top: 20px;
        }
        .success-message {
            text-align: center;
            margin: 20px 0;
            color: green;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1 && isset($_SESSION['last_order'])): ?>
            <p class="success-message">Thank you for your order! Here is your receipt:</p>
            <table class="receipt">
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
                <?php foreach ($_SESSION['last_order'] as $item_id => $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['price']) ?> /-</td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td><?= htmlspecialchars($item['price'] * $item['quantity']) ?> /-</td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="total">
                <p><strong>Grand Total: <?= $_SESSION['last_order_total'] ?> /-</strong></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['cart'])): ?>
            <table class="cart">
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
                <?php foreach ($_SESSION['cart'] as $item_id => $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['price']) ?> /-</td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td><?= htmlspecialchars($item['price'] * $item['quantity']) ?> /-</td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="total">
                <p><strong>Grand Total: <?= $grandTotal ?> /-</strong></p>
            </div>
            <form method="post" action="">
                <button type="submit" name="checkout">Place Order</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>
