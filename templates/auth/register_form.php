<h1 style="text-align:center; margin-bottom:20px;">User Registration</h1>

<?php if (!empty($error)): ?>
    <p style="color:red; text-align:center; margin-bottom:10px;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<div style="display:flex; justify-content:center;">
    <form method="post" action="/register" style="display:flex; flex-direction:column; gap:10px; width:250px;">
        <label>Username:
            <input type="text" name="username" required style="width:100%; padding:5px; box-sizing:border-box;">
        </label>
        <label>Password:
            <input type="password" name="password" required style="width:100%; padding:5px; box-sizing:border-box;">
        </label>
        <button type="submit" style="width:100%; padding:5px; box-sizing:border-box;">Register</button>
    </form>
</div>

<p style="text-align:center; margin-top:15px;">
    <a href="/login">Already have an account?</a>
</p>
