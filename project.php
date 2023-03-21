<?php

require_once 'utils/.mysql.php';
require_once 'utils/.guard.php';

session_start();

$welcome = false;
$err = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Login check
    guard_redirect(isset($_SESSION['user_id']), 'login.php');

    // Referer check
    guard_die(isset($_SERVER['HTTP_REFERER']), 'Referer not found.');
    $referer = parse_url($_SERVER['HTTP_REFERER']);
    guard_die($referer['host'] === 'p5.test', 'Invalid referer.');

    // Token check
    guard_die(
        isset($_POST['csrf-token']) && $_POST['csrf-token'] === $_SESSION['token'],
        'CSRF token missing or invalid.'
    );

    // Param check
    guard_die(
        isset($_POST['id']) && is_numeric($_POST['id']),
        'Parameter(s) missing or invalid.'
    );

    $project_id = intval($_POST['id']);
    $member_id = $_SESSION['user_id'];

    $res = add_member_to_project($member_id, $project_id);

    if ($res) {
        $welcome = true;
    } else {
        $err = true;
    }
}

guard_redirect(isset($_REQUEST['id']) && is_numeric($_REQUEST['id']), '404.php');

$project_id = intval($_REQUEST['id']);
$project = get_project($project_id);

guard_redirect($project_id > 0, '404.php');

$leader = get_project_leader($project_id);
$members = get_project_members($project_id);
$member_full_names = array_map(function ($member) {
    return $member['first_name'] . ' ' . $member['last_name'];
}, $members);

$_SESSION['token'] = md5(uniqid(mt_rand(), true));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $project['title'] ?> - TeamUp</title>
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
                <?= $project['title'] ?>
            </h1>
        </div>
    </div>
</header>
<main>
    <div class="wrapper">
        <div class="cols">
            <div class="left col" id="vue-app">
                <?php if ($welcome): ?>
                    <div class="success row">
                        <ul>
                            <li>You have successfully joined the project.</li>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if ($err): ?>
                    <div class="errors row">
                        <ul>
                            <li>You are already in the project.</li>
                        </ul>
                    </div>
                <?php endif; ?>
                <p>
                    <strong>Leader:</strong> <?= $leader['first_name'] . ' ' . $leader['last_name'] ?>
                </p>
                <?php if ($member_full_names): ?>
                <p>
                    <strong>Members:</strong> <?= implode(', ', $member_full_names) ?>
                </p>
                <?php endif; ?>
                <p>
                    <?= $project['content'] ?>
                </p>
                <p>
                    <img src="<?= $project['image'] ?>" alt="<?= $project['title'] ?>">
                </p>
                <form action="project.php" method="post">
                    <input type="hidden" name="id" value="<?= $project['id'] ?>">
                    <input type="hidden" name="csrf-token" value="<?= $_SESSION['token'] ?>">
                    <input type="submit" value="I can help!" class="btn primary">
                </form>
            </div>
        </div>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
</body>
</html>