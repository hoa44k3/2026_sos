<?php

require_once "../core/Controller.php";

require_once "../app/models/SOS.php";

require_once "../app/models/User.php";

class AdminController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {

            die("Please login");
        }

        if (
            $_SESSION['user']['role']
            != 'admin'
        ) {

            die("Access denied");
        }

        $sosModel = new SOS();

        $userModel = new User();

        $sosList = $sosModel->getAllSOS();

        $totalSOS = $sosModel->totalSOS();

        $activeSOS = $sosModel->activeSOS();

        $totalUsers = $userModel->totalUsers();
require_once "../app/views/admin.php";
     
    }

    public function deleteSOS()
    {
        if (
            $_SESSION['user']['role']
            != 'admin'
        ) {

            die("Access denied");
        }

        $id = $_POST['id'];

        $sosModel = new SOS();

        $sosModel->deleteSOS($id);

        header("Location: /2026_SOS/public/admin");
    }
}