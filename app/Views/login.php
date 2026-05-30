<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login ERP</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;

            /* 🔵 BACKGROUND BIRU TUA */
            background: linear-gradient(135deg, #1e3c72, #2a5298);

            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            width: 320px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 20px;
            color: #1e3c72;
        }

        .login-box input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-box button {
            width: 100%;
            padding: 10px;
            background: #1e3c72;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .login-box button:hover {
            background: #16325c;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login Admin</h2>

    <!-- ERROR MESSAGE -->
    <?php if(session()->getFlashdata('error')): ?>
        <div class="error">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="/proses-login">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>