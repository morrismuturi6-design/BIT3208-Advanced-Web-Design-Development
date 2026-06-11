<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["customer_id"])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: menu.php");
    exit;
}

$customerId = $_SESSION["customer_id"];
$mealName = trim($_POST["meal_name"]);
$orderComment = trim($_POST["order_comment"]);

$statement = $connection->prepare("INSERT INTO orders (customer_id, meal_name, order_comment) VALUES (?, ?, ?)");
$statement->bind_param("iss", $customerId, $mealName, $orderComment);
$statement->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Received | La morrisio Luxe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="message-page">
    <main class="message-box">
        <p class="eyebrow">Order Received</p>
        <h1>Your table is glowing.</h1>
        <p>We saved your order for <?php echo htmlspecialchars($mealName); ?> and captured your comment.</p>
        <a href="menu.php">Return To Menu</a>
    </main>
</body>
</html>
