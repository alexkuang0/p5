<?php

require_once 'utils/.guard.php';
require_once 'utils/.mysql.php';

session_start();

guard_redirect(!isset($_SESSION['user_id']), 'index.php');

$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Referer check
    guard_die(isset($_SERVER['HTTP_REFERER']), 'Referer not found.');
    $referer = parse_url($_SERVER['HTTP_REFERER']);
    guard_die($referer['host'] === getenv('HOSTNAME'), 'Invalid referer.');

    // Token check
    guard_die(
        isset($_POST['csrf-token']) && $_POST['csrf-token'] === $_SESSION['token'],
        'CSRF token missing or invalid.'
    );

    // Param check
    guard_die(
        isset($_POST['email']) && isset($_POST['password']),
        'Parameter(s) missing.'
    );

    $user_id = verify_user($_POST['email'], $_POST['password']);
    if ($user_id > 0) {
        $_SESSION['user_id'] = $user_id;
        header('Location: index.php');
        die;
    } else {
        $error = true;
    }
}

$_SESSION['token'] = md5(uniqid(mt_rand(), true));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - TeamUp</title>
    <link rel="stylesheet" href="index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@400;700;900" rel="stylesheet">
    <script src="https://unpkg.com/vue@next"></script>
</head>
<body>
<header>
    <div class="wrapper">
        <?php include 'partials/nav.php'; ?>
        <div class="heading">
            <h1>
                Log In
            </h1>
        </div>
    </div>
</header>
<main>
    <div class="wrapper">
        <div class="cols">
            <div class="left col" id="vue-app">
                <form action="login.php" method="post">
                    <?php if ($error): ?>
                        <div class="errors row">
                            <ul>
                                <li>Username or password invalid.</li>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <input type="email" name="email" id="email" placeholder="E-mail" v-model.trim="email">
                    </div>
                    <div class="row">
                        <input type="password" placeholder="Password" v-model="password">
                    </div>
                    <input type="hidden" name="password" v-model="hashedPassword">
                    <input type="hidden" name="csrf-token" id="csrf-token" value="<?php echo $_SESSION['token']; ?>">
                    <div class="row btns">
                        <input type="submit" value="Log In" class="btn primary">
                    </div>
                </form>
            </div>
            <div class="right col">
                <a href="./register.php" class="secondary btn">Register ></a>
            </div>
        </div>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
<script src="hash.js"></script>
<script>
    const app = Vue.createApp({
        data() {
            return {
                email: '',
                password: ''
            }
        },
        computed: {
            hashedPassword(p) {
                return Sha256.hash(this.password)
            }
        }
    }).mount('#vue-app')
</script>
</body>
</html>