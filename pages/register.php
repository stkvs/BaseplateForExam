<?php
    require('../components/header.html');
    require('../components/register.html');
    require('../components/footer.html');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"] ?? "";
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";
        $confirmPassword = $_POST["confirmPassword"] ?? "";

        $errors = [];
        
        if (empty($username)) {
            $errors[] = "Username is required";
        }
        
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        
        if (empty($password)) {
            $errors[] = "Password is required";
        }
        
        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match";
        }

        if (empty($errors)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $inputArray = [
                "sql" => "INSERT INTO userdetails (email, password) VALUES (?, ?)",
                "params" => [$email, $hashedPassword]
            ];
            
            $jsonInputArray = json_encode($inputArray);
            $command = escapeshellcmd("python ../python/handleInput.py '" . $jsonInputArray . "'");
            $output = shell_exec($command);

            echo $output;

            echo "<div class='alert alert-success'>Registration successful!</div>";
        } else {
            // Display errors
            echo "<div class='alert alert-danger'>";
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
            echo "</div>";
        }
    }
?>