<?php

require_once "../core/Controller.php";
require_once "../app/models/User.php";

class AuthController extends Controller
{
    public function login()
    {
        require_once "../app/views/login.php";
    }

    public function register()
    {
        require_once "../app/views/register.php";
    }

    public function registerPost()
    {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (
            empty($name) ||
            empty($email) ||
            empty($password)
        ) {
            die("Vui lòng nhập đầy đủ");
        }

        $userModel = new User();

        $checkUser = $userModel->findByEmail($email);

        if ($checkUser) {
            die("Email đã tồn tại");
        }

        $hashedPassword = password_hash(
            $password,
            PASSWORD_BCRYPT
        );

        $userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword
        ]);

        header("Location: /2026_SOS/public/login");
    }

    public function loginPost()
    {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $userModel = new User();

        $user = $userModel->findByEmail($email);

        if (!$user) {
            die("Email không tồn tại");
        }

        if (!password_verify($password, $user['password'])) {
            die("Sai mật khẩu");
        }

        $_SESSION['user'] = [

    'id' => $user['id'],

    'name' => $user['name'],

    'email' => $user['email'],

    'role' => $user['role']

];

        
        if ($user['role'] == 'admin') {

    header("Location: /2026_SOS/public/admin");

} else {

    header("Location: /2026_SOS/public");
}
    }

    public function logout()
    {
        session_destroy();

        header("Location: /2026_SOS/public/login");
    }
}