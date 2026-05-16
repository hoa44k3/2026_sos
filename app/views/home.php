<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <title>HOME</title>

    <style>

        body{
            font-family:Arial;
            background:#f5f5f5;
            padding:50px;
        }

        .box{
            background:white;
            padding:30px;
            border-radius:10px;
        }

        h1{
            color:red;
        }

        .btn{
            display:inline-block;
            padding:12px 20px;
            background:red;
            color:white;
            text-decoration:none;
            border-radius:8px;
            margin-top:20px;
        }

    </style>

</head>
<body>

<div class="box">

<?php if(isset($_SESSION['user'])): ?>

    <h1>
        🚨 SOS SYSTEM
    </h1>

    <h3>
        Xin chào:
        <?= $_SESSION['user']['name']; ?>
    </h3>

    <p>
        <?= $_SESSION['user']['email']; ?>
    </p>

    <a class="btn" href="/2026_SOS/public/sos">
        VÀO TRANG SOS
    </a>

    <br><br>

    <a href="/2026_SOS/public/logout">
        Logout
    </a>

<?php else: ?>

    <h2>
        Bạn chưa đăng nhập
    </h2>

    <a href="/2026_SOS/public/login">
        Login
    </a>

<?php endif; ?>

</div>

</body>
</html>