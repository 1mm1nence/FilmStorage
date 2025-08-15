<h1 style="text-align:center; margin-bottom:20px;">Login</h1>

<div style="display:flex; justify-content:center;">
    <form method="post" action="/login" style="display:flex; flex-direction:column; gap:10px; width:250px;">
        <label>Username:
            <input type="text" name="username" required style="width:100%; padding:5px; box-sizing:border-box;">
        </label>
        <label>Password:
            <input type="password" name="password" required style="width:100%; padding:5px; box-sizing:border-box;">
        </label>
        <button type="submit" style="width:100%; padding:5px; box-sizing:border-box;">Login</button>
    </form>
</div>
