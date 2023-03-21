<?php

require_once 'utils/.mysql.php';

session_start();

$projects = get_projects(3)['data'];

$_SESSION['token'] = md5(uniqid(mt_rand(), true));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUp</title>
    <link rel="stylesheet" href="index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@400;700;900" rel="stylesheet">
</head>
<body>
<header>
    <div class="wrapper">
        <?php include 'partials/nav.php'; ?>
        <div class="hero">
            <div class="slogan">
                Looking for a teammate?<br>
                Or a project to test your skillset?<br><br>
                <span>We got you.</span>
            </div>
            <a href="./register.php" id="register" class="btn primary">Register</a>
            <a href="./login.php" id="log-in" class="btn secondary">Log In</a>
            <img src="assets/hero.svg" alt="Hero Image">
        </div>
    </div>
</header>
<main>
    <div class="wrapper">
        <div class="how-it-works">
            <h2>How It Works</h2>
            <div class="row">
                <div class="left">
                    <h3>For Team Leaders ...</h3>
                    <div class="desc">
                        Have a great project idea?<br>
                        Looking for partners who share your<br>
                        passion and complement your skills?
                    </div>
                    <a href="./create-project.php" class="btn primary small">Create A Project</a>
                    <a href="./404.php" class="btn secondary small">Discover People</a>
                    <img src="assets/coop.svg" alt="Team Cooperation">
                </div>
                <div class="right">
                    <h3>For Project Scouts ...</h3>
                    <div class="desc">
                        Looking to gain some real experience<br>
                        from project-based learning?<br>
                        Meet the next billion-dollar idea here.
                    </div>
                    <a href="./projects.php" class="btn primary small">Discover Projects</a>
                </div>
            </div>
        </div>
        <div class="recent-projects">
            <h2>Featured Projects</h2>
            <?php foreach ($projects as $p): ?>
                <div class="project">
                    <img src="<?= $p['image'] ?>" alt="<?= $p['title'] ?>" class="left">
                    <div class="right">
                        <h3><?= $p['title'] ?></h3>
                        <div class="desc"><?= $p['content'] ?></div>
                        <form action="project.php" method="post" style="display: inline">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <input type="hidden" name="csrf-token" value="<?= $_SESSION['token'] ?>">
                            <input type="submit" value="I can help!" class="btn primary">
                        </form>
                        <a href="project.php?id=<?= $p['id'] ?>" class="btn secondary">Learn more</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="more">
                <a href="./projects.php">Discover more projects ...</a>
            </div>
        </div>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
</body>
</html>