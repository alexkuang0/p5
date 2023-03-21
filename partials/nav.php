<?php

require_once 'utils/.mysql.php';

if (!isset($_SESSION)) session_start();

$logged_in = isset($_SESSION['user_id']);
if ($logged_in) $user_info = get_user_info($_SESSION['user_id']);

?>
<nav>
    <div class="logo">
        <a href="./">
            <img src="assets/logo.svg" alt="logo">
        </a>
    </div>
    <ul class="right-menu">
        <li><a href="./404.php">Discover People</a></li>
        <li><a href="./projects.php">Discover Projects</a></li>
        <?php if ($logged_in && $user_info): ?>
            <li><strong><?= $user_info['first_name'] . ' ' . $user_info['last_name'] ?></strong></li>
            <li><a href="./logout.php">Log Out</a></li>
        <?php else: ?>
            <li><a href="./register.php">Register</a></li>
            <li><a href="./login.php">Log In</a></li>
        <?php endif; ?>
    </ul>
</nav>