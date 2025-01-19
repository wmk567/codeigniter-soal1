<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <form action="/api/user/register" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" value="" required>
        <br>
        <button type="submit">Register</button>
    </form>
    <?php if (session()->getFlashdata('error')): ?>
        <div>
            <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>
    <p>Already have an account? <a href="/user/login">Login here</a>.</p>
</body>
</html>
