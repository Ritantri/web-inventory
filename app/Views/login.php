<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
    <link href="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>

<div class="login-container">

    <div class="login-box">

        <div class="login-header">
            <i class="fa-solid fa-box"></i>
            <h2>Inventory System</h2>
            <p>Silakan login untuk melanjutkan</p>
        </div>

        <form action="/auth/prosesLogin" method="post">

            <div class="input-group">
                <i class="fa fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>

            <div class="input-group">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button class="login-btn">Login</button>

        </form>

    </div>

</div>

</body>
</html>