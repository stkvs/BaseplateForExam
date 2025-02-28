<?php
session_start();

$pageTitle = "Register";

require('../components/header.php');
?>
<nav>
    <ul>
        <li><a href="../index.php">Home</a></li>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <li><a href="../pages/login.php">Login</a></li>
            <li><a href="../pages/register.php">Register</a></li>
        <?php else: ?>
            <li>
                <form action="../php/logout.php" method="post">
                    <input type="submit" value="Log Out">
                </form>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php
require('../components/register.html');
require('../components/footer.html');

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? "";
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirmPassword"] ?? "";

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $inputString = "INSERT INTO userdetails (email, password) VALUES (?, ?)|$email|$hashedPassword";
        $escapedInputString = escapeshellarg($inputString);

        $pythonScript = realpath("../python/sqlHandler.py");
        
        if (!file_exists($pythonScript)) {
            echo "<div class='alert alert-danger'>Python script not found at: $pythonScript</div>";
        }
        
        $command = "python \"$pythonScript\" $escapedInputString 2>&1";
        
        // echo "<div class='alert alert-info'>Executing command: $command</div>";

        $output = shell_exec($command);
        
        // echo "<div class='alert alert-info'>Command output: <pre>$output</pre></div>";

        if ($output === null) {
            echo "<div class='alert alert-danger'>Error executing command.</div>";
        } else {
            // Start session and set user ID
            $_SESSION['user_id'] = $email;
            echo "<div class='alert alert-success'>Registered Successfully</div>";
            header("Location: ../index.php");
            exit();
        }
    } else {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>
