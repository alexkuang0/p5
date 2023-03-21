<?php

require_once 'utils/.guard.php';
require_once 'utils/.mysql.php';

session_start();

guard_redirect(!isset($_SESSION['user_id']), 'index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        isset($_POST['first-name']) && isset($_POST['last-name']) && isset($_POST['email']) && isset($_POST['password']),
        'Parameter(s) missing.'
    );

    add_user($_POST['first-name'], $_POST['last-name'], $_POST['email'], $_POST['password']);

    header('Location: login.php');
    die;
}

$_SESSION['token'] = md5(uniqid(mt_rand(), true));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TeamUp</title>
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
                Register
            </h1>
        </div>
    </div>
</header>
<main>
    <div class="wrapper">
        <div class="cols">
            <div class="left col" id="vue-app">
                <form action="register.php" method="post" @submit.prevent="validate">
                    <div class="errors row" v-if="errors.length !== 0">
                        <ul>
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="row">
                        <input type="text" name="first-name" id="first-name" placeholder="First Name" v-model.trim="firstName"><input type="text" name="last-name" id="last-name" placeholder="Last Name" v-model.trim="lastName">
                    </div>
                    <div class="row">
                        <input type="email" name="email" id="email" placeholder="E-mail" v-model.trim="email">
                    </div>
                    <div class="row">
                        <input type="password" placeholder="Password" v-model="password">
                    </div>
                    <div class="row">
                        <input type="password" placeholder="Confirm password" v-model="confirmPassword">
                    </div>
                    <input type="hidden" name="password" v-model="hashedPassword">
                    <input type="hidden" name="csrf-token" id="csrf-token" value="<?php echo $_SESSION['token']; ?>">
                    <div class="row btns">
                        <input type="submit" value="Register" class="btn primary">
                    </div>
                </form>
            </div>
            <div class="right col">
                <a href="./login.php" class="secondary btn">Log In ></a>
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
                firstName: '',
                lastName: '',
                email: '',
                confirmPassword: '',
                password: '',
                errors: []
            }
        },
        methods: {
            validate(e) {
                this.errors = []

                this.guard(this.firstName.length > 1 && this.firstName.length <= 50, 'First name must be 2-50 letters long')
                this.guard(this.lastName.length > 1 && this.lastName.length <= 50, 'Last name must be 2-50 letters long')
                this.guard(/^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/g.test(this.email), 'E-mail address is invalid.')
                this.guard(this.email.length <= 100, 'E-mail address is too long. (limit 100 letters)')
                this.guard(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$/g.test(this.password), 'Password must be 8-20 letters long, and contain uppercase (A-Z), lowercase (a-z) and number (0-9).')
                this.guard(this.password === this.confirmPassword, 'Passwords do not match.')

                if (this.errors.length === 0) {
                    e.target.submit()
                }
            },
            guard(condition, message) {
                if (!condition) this.errors.push(message)
            }
        },
        computed: {
            hashedPassword() {
                return Sha256.hash(this.password)
            }
        }
    }).mount('#vue-app')
</script>
</body>
</html>