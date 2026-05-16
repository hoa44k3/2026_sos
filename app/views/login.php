<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <title>Login</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial;
        }

        body{
            background:#f1f5f9;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .card{
            width:400px;
            background:white;
            padding:40px;
            border-radius:15px;
            box-shadow:0 0 20px rgba(0,0,0,0.1);
        }

        h1{
            text-align:center;
            margin-bottom:30px;
            color:red;
        }

        input{
            width:100%;
            padding:14px;
            margin-bottom:20px;
            border:1px solid #ddd;
            border-radius:8px;
        }

        button{
            width:100%;
            padding:14px;
            border:none;
            background:red;
            color:white;
            font-size:16px;
            border-radius:8px;
            cursor:pointer;
        }

        button:hover{
            background:darkred;
        }

        .link{
            text-align:center;
            margin-top:20px;
        }

        a{
            text-decoration:none;
            color:red;
        }

    </style>

</head>
<body>

<div class="card">

    <h1>🚨 LOGIN</h1>

    <form method="POST" action="/2026_SOS/public/login">

        <input
            type="email"
            name="email"
            placeholder="Enter email"
        >

        <input
            type="password"
            name="password"
            placeholder="Enter password"
        >

        <button type="submit">
            LOGIN
        </button>

    </form>

    <div class="link">

        <a href="/2026_SOS/public/register">
            Create account
        </a>

    </div>

</div>

</body>
</html>