<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FilmStorage</title>
    <style>
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #333;
            color: white;
            padding: 10px;
        }
        header a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .auth-buttons button {
            margin-left: 10px;
        }
    </style>
</head>
<body>
<!--<header>-->
<!--    <a href="/">FilmStorage</a>-->
<!--    <input type="text" placeholder="Search...">-->
<!--    <div class="auth-buttons">-->
<!--        --><?php //if (!empty($_SESSION['username'])): ?>
<!--            <span>--><?php //= htmlspecialchars($_SESSION['username']) ?><!--</span>-->
<!--            <form method="POST" action="/logout" style="display:inline;">-->
<!--                <input type="hidden" name="csrf_token" value="--><?php //= htmlspecialchars($_SESSION['csrf_token']) ?><!--">-->
<!--                <button type="submit">Logout</button>-->
<!--            </form>-->
<!--        --><?php //else: ?>
<!--            <a href="/login"><button>Login</button></a>-->
<!--            <a href="/register"><button>Register</button></a>-->
<!--        --><?php //endif; ?>
<!--    </div>-->
<!--</header>-->

<header>
    <h1><a href="/">FilmStorage</a></h1>

    <div>
        <input type="text" placeholder="Search...">

        <?php if (!empty($_SESSION['username'])): ?>
            <span><?= htmlspecialchars($_SESSION['username']) ?></span>

            <form method="POST" action="/logout" style="display:inline;">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <button type="submit">Logout</button>
            </form>
        <?php else: ?>
            <a href="/login"><button>Login</button></a>
            <a href="/register"><button>Register</button></a>
        <?php endif; ?>
    </div>
</header>

<main>
    <?= $content ?>
</main>

<footer style="margin-top:50px; text-align:center;">
    <p>&copy; <?= date('Y') ?> FilmStorage</p>
</footer>
</body>
</html>
