<?php

require_once "../core/Model.php";

class Message extends Model
{
    public function create($data)
    {
        $sql = "
            INSERT INTO messages(
                sos_id,
                sender_id,
                message
            )
            VALUES(
                :sos_id,
                :sender_id,
                :message
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([

            ':sos_id' => $data['sos_id'],

            ':sender_id' => $data['sender_id'],

            ':message' => $data['message']
        ]);
    }

    public function getMessages($sos_id)
    {
        $sql = "
            SELECT
                messages.*,
                users.name
            FROM messages

            JOIN users
            ON users.id = messages.sender_id

            WHERE sos_id = :sos_id

            ORDER BY messages.id ASC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':sos_id' => $sos_id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}