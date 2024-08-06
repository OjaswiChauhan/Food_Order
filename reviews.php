<?php
session_start();
$conn = new mysqli("localhost", "root", "", "food_order");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle review submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    $rating = (int)$_POST['rating'];
    $review = $conn->real_escape_string($_POST['review']);

    $stmt = $conn->prepare("INSERT INTO reviews (customer_name, rating, review) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sis", $customer_name, $rating, $review);
        if ($stmt->execute()) {
            $success_message = "Thank you for your review!";
        } else {
            $error_message = "Error submitting review: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Error preparing statement: " . $conn->error;
    }
}

// Fetch reviews from the database
$reviews = $conn->query("SELECT customer_name, rating, review, created_at FROM reviews ORDER BY created_at DESC");

// Check if there are reviews
if ($reviews === false) {
    $query_error_message = "Error fetching reviews: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head content -->
</head>
<body>
    <div class="container">
        <!-- Container content -->
        <h1>Submit Your Review</h1>
        <?php if (isset($success_message)): ?>
            <p class="message success"><?= $success_message ?></p>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <p class="message error"><?= $error_message ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <input type="text" name="customer_name" placeholder="Your Name" required>
            <div class="rating">
                <input type="radio" name="rating" id="star5" value="5" required><label for="star5">★</label>
                <input type="radio" name="rating" id="star4" value="4"><label for="star4">★</label>
                <input type="radio" name="rating" id="star3" value="3"><label for="star3">★</label>
                <input type="radio" name="rating" id="star2" value="2"><label for="star2">★</label>
                <input type="radio" name="rating" id="star1" value="1"><label for="star1">★</label>
            </div>
            <textarea name="review" rows="5" placeholder="Write your review here..." required></textarea>
            <button type="submit" name="submit_review">Submit Review</button>
        </form>
        
        <div class="reviews">
            <h1>Customer Reviews</h1>
            <?php if (isset($query_error_message)): ?>
                <p class="message error"><?= $query_error_message ?></p>
            <?php elseif ($reviews !== false && $reviews->num_rows > 0): ?>
                <?php while ($review = $reviews->fetch_assoc()): ?>
                    <div class="review">
                        <h2><?= htmlspecialchars($review['customer_name']) ?></h2>
                        <div class="rating">
                            <?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?>
                        </div>
                        <p><?= nl2br(htmlspecialchars($review['review'])) ?></p>
                        <div class="date">Reviewed on: <?= date("F j, Y, g:i a", strtotime($review['created_at'])) ?></div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No reviews yet. Be the first to leave a review!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html
