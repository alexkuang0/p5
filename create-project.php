<?php

require_once 'utils/.guard.php';
require_once 'utils/.mysql.php';

session_start();

guard_redirect(isset($_SESSION['user_id']), 'login.php');

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
        isset($_POST['title']) && isset($_POST['desc']) && isset($_POST['image']),
        'Parameter(s) missing.'
    );

    $project_id = add_project($_POST['title'], $_POST['desc'], $_POST['image'], $_SESSION['user_id']);

    header("Location: project.php?id=$project_id");
}

$_SESSION['token'] = md5(uniqid(mt_rand(), true));

?>
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
    <script src="https://unpkg.com/vue@next"></script>
</head>
<body>
<header>
    <div class="wrapper">
        <?php include 'partials/nav.php'; ?>
        <div class="heading">
            <h1>
                Create a project ...
            </h1>
        </div>
    </div>
</header>
<main>
    <div class="wrapper">
        <div class="cols">
            <div class="left col" id="vue-app">
                <form action="" method="post" @submit.prevent="handleSubmit">
                    <div class="errors row" v-if="errors.length !== 0">
                        <ul>
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="wide row">
                        <input type="text" name="title" id="title" placeholder="Title" v-model="title">
                    </div>
                    <div class="wide row">
                        <textarea name="desc" id="desc" placeholder="Project description" v-model="desc"></textarea>
                    </div>
                    <div class="wide row">
                        <input type="url" name="image" id="image" placeholder="Image URL" @input="handleInput" v-model="imgUrl">
                    </div>
                    <input type="hidden" name="csrf-token" id="csrf-token" value="<?php echo $_SESSION['token']; ?>">
                    <div class="row btns">
                        <input type="submit" value="Submit" class="btn primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
<script>
    function debounce(fn, delay = 1000) {
        let timer
        return function() {
            let that = this
            let args = arguments
            clearTimeout(timer)
            timer = setTimeout(() => {
                fn.apply(that, args)
            }, delay);
        }
    }

    const app = Vue.createApp({
        data() {
            return {
                title: '',
                desc: '',
                imgUrl: '',
                errors: [],
                acceptMimeTypes: ['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp']
            }
        },
        methods: {
            async handleSubmit(event) {
                this.errors = []

                this.guard(this.title.length > 5 && this.title.length <= 100, 'Title must be 5-100 letters long')
                this.guard(this.desc.length > 20 && this.desc.length <= 1000, 'Description must be 20-1000 letters long')

                try {
                    this.guardThrow(/https?:\/\/\w[\w.]*\w(:\d{1,5})?.*$/g.test(this.imgUrl), 'Image URL invalid.')
                    const res = await fetch(this.imgUrl)
                    const contentType = res.headers.get('Content-Type')
                    this.guardThrow(this.acceptMimeTypes.includes(contentType), 'Not an acceptable image format.')
                } catch (err) {
                    this.errors.push(err.message)
                }

                if (this.errors.length === 0) event.target.submit()
            },
            handleInput: debounce(async function () {
                this.errors = []

                try {
                    this.guardThrow(/https?:\/\/\w[\w.]*\w(:\d{1,5})?.*$/g.test(this.imgUrl), 'Image URL invalid.')
                    const res = await fetch(this.imgUrl)
                    const contentType = res.headers.get('Content-Type')
                    this.guardThrow(this.acceptMimeTypes.includes(contentType), 'Not an acceptable image format.')
                } catch (err) {
                    this.errors.push(err.message)
                }
            }, 1000),
            guard(condition, message) {
                if (!condition) this.errors.push(message)
            },
            guardThrow(condition, message) {
                if (!condition) throw new Error(message)
            }
        }
    }).mount('#vue-app')
</script>
</body>
</html>