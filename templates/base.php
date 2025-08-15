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

        .flash-message {
            padding: 12px 20px;
            margin: 15px 0;
            border-radius: 6px;
            font-size: 16px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease;
        }

        /* Message types */
        .flash-message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .flash-message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .flash-message.info {
            background-color: #cce5ff;
            color: #004085;
            border: 1px solid #b8daff;
        }

        /* optional fade-in */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
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
    <?php if(!empty($_SESSION['flash_message'])):
        $flash = $_SESSION['flash_message'];
        ?>
        <div class="flash-message <?= htmlspecialchars($flash['type']) ?>">
            <?= htmlspecialchars($flash['text']) ?>
        </div>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

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
