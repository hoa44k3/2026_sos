<?php

require_once "../core/Controller.php";

require_once "../app/models/Message.php";

class MessageController extends Controller
{
    public function index()
    {
        if (!isset($_GET['sos_id'])) {

            die("SOS ID required");
        }

        $sos_id = $_GET['sos_id'];

        require_once "../app/views/chat.php";
    }

    public function send()
    {
        if (!isset($_SESSION['user'])) {

            die("Please login");
        }

        $messageModel = new Message();

        $messageModel->create([

            'sos_id' => $_POST['sos_id'],

            'sender_id' => $_SESSION['user']['id'],

            'message' => $_POST['message']
        ]);

        echo json_encode([
            'success' => true
        ]);
    }

    public function getMessages()
    {
        $sos_id = $_GET['sos_id'];

        $messageModel = new Message();

        $messages = $messageModel->getMessages($sos_id);

        echo json_encode($messages);
    }
}