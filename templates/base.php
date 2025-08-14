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

<header>
    <h1><a href="/">FilmStorage</a></h1>

    <div class="auth-buttons">
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
    <?php if (!empty($content)): ?>
        <?= $content ?>
    <?php else: ?>
        Something got wrong. Pls, contact the support.
    <?php endif; ?>
</main>

<footer style="margin-top:70px; text-align:center;">
    <p>&copy; <?= date('Y') ?> FilmStorage</p>
</footer>
</body>
</html>
