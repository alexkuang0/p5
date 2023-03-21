<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - TeamUp</title>
    <link rel="stylesheet" href="index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@400;700;900" rel="stylesheet">
</head>
<body>
<header>
    <div class="wrapper">
        <?php include 'partials/nav.php'; ?>
        <div class="heading">
            <h1>
                Contact Us
            </h1>
        </div>
    </div>
</header>
<main>
    <div class="wrapper">
        <div class="cols">
            <div class="left col">
                <p>
                    <ul>
                        <li>My name is <strong>Ning "Alex" Kuang</strong></li>
                        <li><a style="text-decoration: underline" href="https://alex0.dev">Personal Website</a></li>
                        <li><a style="text-decoration: underline" href="https://summer-2021.cs.utexas.edu/cs329e-mitra/nkuang/">Course Homepage</a></li>
                    </ul>
                </p>
                <p>Video by <a href="https://www.pexels.com/@follow-art-850903">Follow Art.</a></p>
                <video src="assets/demo.mp4" width="400" controls autoplay></video>
            </div>
            <div class="right col">
                <a href="./login.php" class="secondary btn">Log In ></a>
                <a href="./register.php" class="secondary btn">Register ></a>
            </div>
        </div>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
</body>
</html>