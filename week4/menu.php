<?php
session_start();
require_once "db.php";
require_once "meals.php";

if (!isset($_SESSION["customer_id"])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La morrisi Luxe | Menu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="menu.php">morrisi deluxe menu</a>
        <nav>
            <a href="#menu">Menu</a>
            <a href="#quality">Quality</a>
            <a href="logout.php">Exit</a>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <p class="eyebrow">Welcome, <?php echo htmlspecialchars($_SESSION["customer_name"]); ?></p>
            <h1>Every plate earns its place.</h1>
            <p>Steak, seafood, pasta, lamb, greens, and dessert. All crafted with premium ingredients.</p>
            <a class="hero-button" href="#menu">Explore Dishes</a>
        </div>
    </section>

    <section class="quality-band" id="quality">
        <article><span>01</span><h2>Fresh First</h2><p>We choose produce with color, scent, and snap.</p></article>
        <article><span>02</span><h2>Cooked With Care</h2><p>Heat, timing, and seasoning are never rushed.</p></article>
        <article><span>03</span><h2>Luxury For Every Taste</h2><p>From meat to seafood to desserts, quality leads.</p></article>
    </section>

    <main class="menu-section" id="menu">
        <div class="section-heading">
            <p class="eyebrow">Signature Variety</p>
            <h2>Choose your craving.</h2>
            <p>Hover a meal. Learn its secret. Order your favorite.</p>
        </div>
        <div class="meal-grid">
            <?php foreach ($meals as $slug => $meal): ?>
                <article class="meal-card">
                    <img src="<?php echo htmlspecialchars($meal["image"]); ?>" alt="<?php echo htmlspecialchars($meal["name"]); ?>">
                    <div class="meal-info">
                        <p><?php echo htmlspecialchars($meal["tag"]); ?></p>
                        <h3><?php echo htmlspecialchars($meal["name"]); ?></h3>
                        <span><?php echo htmlspecialchars($meal["price"]); ?></span>
                    </div>
                    <div class="meal-hover">
                        <h3><?php echo htmlspecialchars($meal["name"]); ?></h3>
                        <p><?php echo htmlspecialchars($meal["short"]); ?></p>
                        <a href="meal.php?meal=<?php echo urlencode($slug); ?>">Learn How It Is Made</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </main>
    <footer><p>La morrisio Luxe. Crafted for guests who notice the details.</p></footer>
</body>
</html>
