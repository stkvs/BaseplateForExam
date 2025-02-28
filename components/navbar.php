<nav>
    <ul>
        <li><a href="./index.php">Home</a></li>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <li><a href="./pages/login.php">Login</a></li>
            <li><a href="./pages/register.php">Register</a></li>
        <?php else: ?>
            <li>
                <form action="./php/logout.php" method="post">
                    <input type="submit" value="Log Out">
                </form>
            </li>
        <?php endif; ?>
    </ul>
</nav>