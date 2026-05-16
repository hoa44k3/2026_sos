<?php

require_once "../core/Controller.php";
require_once "../app/models/SOS.php";
require_once "../app/models/Helper.php";
class SOSController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {

            header("Location: /2026_SOS/public/login");
            exit;
        }

        $this->view('sos');
    }

    public function store()
    {
        if (!isset($_SESSION['user'])) {
            die("Please login");
        }

        $message = trim($_POST['message']);

        $latitude = $_POST['latitude'];

        $longitude = $_POST['longitude'];

        $sosModel = new SOS();

        $sosModel->create([
            'user_id' => $_SESSION['user']['id'],
            'message' => $message,
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);

        header("Location: /2026_SOS/public/sos");
    }

    public function getSOSList()
    {
        $lat = $_GET['lat'];

        $lng = $_GET['lng'];

        $sosModel = new SOS();

        $sosList = $sosModel->getNearbySOS($lat, $lng);

        require "../app/views/sos_list.php";
    }
   public function joinSOS()
{
    if (!isset($_SESSION['user'])) {
        die("Please login");
    }

    $sosId = $_POST['sos_id'];

    $helperModel = new Helper();

    $helperModel->joinSOS(
        $sosId,
        $_SESSION['user']['id']
    );

    $sosModel = new SOS();

    $sosModel->updateStatus(
        $sosId,
        'helping'
    );

    header("Location: /2026_SOS/public/sos");
}

public function completeSOS()
{
    if (!isset($_SESSION['user'])) {
        die("Please login");
    }

    $sosId = $_POST['sos_id'];

    $sosModel = new SOS();

    $sos = $sosModel->findById($sosId);

    if (
        $sos['user_id']
        != $_SESSION['user']['id']
    ) {
        die("Unauthorized");
    }

    $sosModel->updateStatus(
        $sosId,
        'completed'
    );

    header("Location: /2026_SOS/public/sos");
}
public function countSOS()
{
    $sosModel = new SOS();

    $count = $sosModel->countSOS();

    echo json_encode($count);
}
}