<?php
session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $phone = trim($_POST["phone"]);
    $email = trim($_POST["email"]);
    $gender = trim($_POST["gender"]);
    $password = $_POST["password"] ?? "";
    $passwordIsStrong = strlen($password) >= 8
        && preg_match("/[a-z]/", $password)
        && preg_match("/[A-Z]/", $password)
        && preg_match("/[0-9]/", $password)
        && preg_match("/[^A-Za-z0-9]/", $password);

    if ($username !== "" && $phone !== "" && filter_var($email, FILTER_VALIDATE_EMAIL) && $gender !== "" && $passwordIsStrong) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $statement = $connection->prepare("INSERT INTO customers (username, phone, email, gender, password_hash) 
        VALUES (?, ?, ?, ?, ?)");
        $statement->bind_param("sssss", $username, $phone, $email, $gender, $passwordHash);
        $statement->execute();

        $_SESSION["customer_id"] = $statement->insert_id;
        $_SESSION["customer_name"] = $username;

        header("Location: menu.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Maison Luxe | Guest Access</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="entry-page">
    <main class="entry-shell">
        <section class="entry-copy">
            <p class="eyebrow">La Maison Luxe</p>
            <h1>Fine food. Pure pleasure.</h1>
            <p>Step into a table of rich flavors, careful craft, and warm service.</p>
            <div class="pattern-strip"></div>
        </section>

        <section class="login-panel">
            <h2>Reserve Your Taste</h2>
            <p>Share your details to unlock our menu.</p>
            <form method="POST" action="index.php" id="guestForm" novalidate>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Your name" required>
                <small class="field-error" id="usernameError"></small>
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="0712 345 678" required>
                <small class="field-error" id="phoneError"></small>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="guest@example.com" required>
                <small class="field-error" id="emailError"></small>
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="">Choose one</option>
                    <option value="Female">Female</option>
                    <option value="Male">Male</option>
                    <option value="Other">Other</option>
                    <option value="Prefer not to say">Prefer not to say</option>
                </select>
                <small class="field-error" id="genderError"></small>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Create a strong password" required>
                <div class="password-meter" aria-hidden="true">
                    <span id="strengthBar"></span>
                </div>
                <p class="password-hint" id="strengthText">Use 8+ characters, upper and lower case, a number, and a symbol.</p>
                <small class="field-error" id="passwordError"></small>
                <button type="submit">Enter The Dining Room</button>
            </form>
        </section>
    </main>
    <script>
        const form = document.getElementById("guestForm");
        const username = document.getElementById("username");
        const phone = document.getElementById("phone");
        const email = document.getElementById("email");
        const gender = document.getElementById("gender");
        const password = document.getElementById("password");
        const strengthBar = document.getElementById("strengthBar");
        const strengthText = document.getElementById("strengthText");

        const errors = {
            username: document.getElementById("usernameError"),
            phone: document.getElementById("phoneError"),
            email: document.getElementById("emailError"),
            gender: document.getElementById("genderError"),
            password: document.getElementById("passwordError")
        };

        function setError(field, message) {
            errors[field].textContent = message;
        }

        function getPasswordScore(value) {
            let score = 0;

            if (value.length >= 8) score++;
            if (/[a-z]/.test(value)) score++;
            if (/[A-Z]/.test(value)) score++;
            if (/[0-9]/.test(value)) score++;
            if (/[^A-Za-z0-9]/.test(value)) score++;

            return score;
        }

        function updateStrength() {
            const score = getPasswordScore(password.value);
            const labels = ["Too weak", "Weak", "Fair", "Good", "Strong", "Excellent"];
            const classes = ["", "weak", "fair", "good", "strong", "excellent"];

            strengthBar.className = classes[score];
            strengthBar.style.width = `${Math.max(score, 1) * 20}%`;
            strengthText.textContent = password.value ? `Password strength: ${labels[score]}` : "Use 8+ characters, upper and lower case, a number, and a symbol.";
        }

        function validateForm() {
            let isValid = true;
            const phonePattern = /^[0-9+\s-]{7,20}$/;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            Object.keys(errors).forEach((field) => setError(field, ""));

            if (username.value.trim().length < 3) {
                setError("username", "Username must be at least 3 characters.");
                isValid = false;
            }

            if (!phonePattern.test(phone.value.trim())) {
                setError("phone", "Enter a valid phone number.");
                isValid = false;
            }

            if (!emailPattern.test(email.value.trim())) {
                setError("email", "Enter a valid email address.");
                isValid = false;
            }

            if (!gender.value) {
                setError("gender", "Please select your gender.");
                isValid = false;
            }

            if (getPasswordScore(password.value) < 4) {
                setError("password", "Use a stronger password before entering.");
                isValid = false;
            }

            return isValid;
        }

        password.addEventListener("input", updateStrength);
        form.addEventListener("submit", (event) => {
            updateStrength();

            if (!validateForm()) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
