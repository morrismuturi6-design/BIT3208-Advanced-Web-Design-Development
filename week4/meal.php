<?php
session_start();
require_once "db.php";
require_once "meals.php";

if (!isset($_SESSION["customer_id"])) {
    header("Location: index.php");
    exit;
}

$slug = $_GET["meal"] ?? "";
if (!isset($meals[$slug])) {
    header("Location: menu.php");
    exit;
}
$meal = $meals[$slug];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($meal["name"]); ?> | morrisi deluxe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="menu.php">morrisi deluxe</a>
        <nav><a href="menu.php#menu">Menu</a><a href="logout.php">Exit</a></nav>
    </header>
    <main class="detail-layout">
        <section class="detail-image">
            <img src="<?php echo htmlspecialchars($meal["image"]); ?>" alt="<?php echo htmlspecialchars($meal["name"]); ?>">
        </section>
        <section class="detail-copy">
            <p class="eyebrow"><?php echo htmlspecialchars($meal["tag"]); ?></p>
            <h1><?php echo htmlspecialchars($meal["name"]); ?></h1>
            <p class="price"><?php echo htmlspecialchars($meal["price"]); ?></p>
            <p><?php echo htmlspecialchars($meal["made"]); ?></p>
            <form method="POST" action="order.php" class="order-form">
                <input type="hidden" name="meal_name" value="<?php echo htmlspecialchars($meal["name"]); ?>">
                <label for="order_comment">Comment After Order</label>
                <textarea id="order_comment" name="order_comment" rows="5" placeholder="Tell us how you want it served, or leave feedback after ordering." required></textarea>
                <button type="submit">Order This Dish</button>
            </form>
        </section>
    </main>
</body>
</html>
