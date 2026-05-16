<?php

require_once "../core/Model.php";

class SOS extends Model
{
    public function create($data)
    {
        $sql = "
            INSERT INTO sos_requests(
                user_id,
                message,
                latitude,
                longitude
            )
            VALUES(
                :user_id,
                :message,
                :latitude,
                :longitude
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':message' => $data['message'],
            ':latitude' => $data['latitude'],
            ':longitude' => $data['longitude']
        ]);
    }

    public function getNearbySOS($lat, $lng, $radius = 10)
{
    $sql = "
        SELECT
            sos_requests.*,
            users.name,
            (
                    SELECT COUNT(*)
                    FROM sos_helpers
                    WHERE sos_helpers.sos_id = sos_requests.id
            ) as helper_count,
            (
                6371 * acos(
                    cos(radians(:lat))
                    *
                    cos(radians(sos_requests.latitude))
                    *
                    cos(
                        radians(sos_requests.longitude)
                        - radians(:lng)
                    )
                    +
                    sin(radians(:lat))
                    *
                    sin(radians(sos_requests.latitude))
                )
            ) AS distance

        FROM sos_requests

        JOIN users
        ON users.id = sos_requests.user_id

        HAVING distance < :radius

        ORDER BY distance ASC
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':lat' => $lat,
        ':lng' => $lng,
        ':radius' => $radius
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function updateStatus($id, $status)
{
    $sql = "
        UPDATE sos_requests
        SET status = :status
        WHERE id = :id
    ";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        ':status' => $status,
        ':id' => $id
    ]);
}

public function findById($id)
{
    $sql = "
        SELECT *
        FROM sos_requests
        WHERE id = :id
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function countSOS()
{
    $sql = "
        SELECT COUNT(*) as total
        FROM sos_requests
        WHERE status != 'completed'
    ";

    $stmt = $this->db->query($sql);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function getAllSOS()
{
    $sql = "
        SELECT
            sos_requests.*,
            users.name
        FROM sos_requests

        JOIN users
        ON users.id = sos_requests.user_id

        ORDER BY sos_requests.id DESC
    ";

    $stmt = $this->db->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function deleteSOS($id)
{
    $sql = "
        DELETE FROM sos_requests
        WHERE id = :id
    ";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        ':id' => $id
    ]);
}

public function totalSOS()
{
    $sql = "
        SELECT COUNT(*) as total
        FROM sos_requests
    ";

    $stmt = $this->db->query($sql);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function activeSOS()
{
    $sql = "
        SELECT COUNT(*) as total
        FROM sos_requests
        WHERE status != 'completed'
    ";

    $stmt = $this->db->query($sql);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}