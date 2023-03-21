<?php

require_once 'utils/.mysql.php';
require_once 'utils/.guard.php';

session_start();

guard_redirect(isset($_GET['page']) && is_numeric($_GET['page']), 'projects.php?page=1');

$page_num = intval($_GET['page']);
['data' => $projects, 'total_pages' => $total_pages] = get_projects(5, $page_num);

guard_redirect($projects, 'projects.php?page=1');

$_SESSION['token'] = md5(uniqid(mt_rand(), true));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover Projects - TeamUp</title>
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
                Discover Projects
            </h1>
        </div>
        <div class="filters">
            <div class="left filter">
                <input type="text" name="search" id="search" placeholder="Search ...">
            </div>
            <div class="right filter">
                <select name="page-num" id="page-num">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <option value="<?= $i ?>"<?= $page_num === $i ? ' selected' : '' ?>><?= "Page $i" ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
    </div>
</header>
<main>
    <div class="wrapper">
        <div class="projects">
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
        </div>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
<script>
    const pageNumSelect = document.getElementById('page-num')

    pageNumSelect.addEventListener('change', (e) => {
        window.location.href = `projects.php?page=${e.target.value}`
    })
</script>
</body>
</html>