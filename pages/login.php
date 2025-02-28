<?php
session_start();

$pageTitle = "Login";

require('../components/header.php');
?>
<nav>
    <ul>
        <li><a href="../index.php">Home</a></li>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <li><a href="./login.php">Login</a></li>
            <li><a href="./register.php">Register</a></li>
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
require('../components/login.html');
require('../components/footer.html');

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        $inputString = "SELECT password FROM userdetails WHERE email = ?|$email";
        $escapedInputString = escapeshellarg($inputString);

        $pythonScript = realpath("../python/sqlHandler.py");

        if (!file_exists($pythonScript)) {
            echo "<div class='alert alert-danger'>Python script not found at: $pythonScript</div>";
        }

        $command = "python \"$pythonScript\" $escapedInputString 2>&1";

        $output = shell_exec($command);

        if ($output === null) {
            echo "<div class='alert alert-danger'>Error executing command.</div>";
        } else {
            $storedPasswordHash = trim($output);

            if (password_verify($password, $storedPasswordHash)) {
                $_SESSION['user_id'] = $email;
                echo "<div class='alert alert-success'>Logged in Successfully</div>";
                header("Location: ../index.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Invalid email or password.</div>";
            }
        }
    } else {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>