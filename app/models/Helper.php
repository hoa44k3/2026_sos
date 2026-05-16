<?php

require_once "../core/Model.php";

class Helper extends Model
{
    public function joinSOS($sosId, $helperId)
    {
        $checkSql = "
            SELECT *
            FROM sos_helpers
            WHERE sos_id = :sos_id
            AND helper_id = :helper_id
        ";

        $checkStmt = $this->db->prepare($checkSql);

        $checkStmt->execute([
            ':sos_id' => $sosId,
            ':helper_id' => $helperId
        ]);

        $exists = $checkStmt->fetch();

        if ($exists) {
            return false;
        }

        $sql = "
            INSERT INTO sos_helpers(
                sos_id,
                helper_id
            )
            VALUES(
                :sos_id,
                :helper_id
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':sos_id' => $sosId,
            ':helper_id' => $helperId
        ]);
    }

    public function getHelpersBySOS($sosId)
    {
        $sql = "
            SELECT
                sos_helpers.*,
                users.name
            FROM sos_helpers

            JOIN users
            ON users.id = sos_helpers.helper_id

            WHERE sos_id = :sos_id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':sos_id' => $sosId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countHelpers($sosId)
    {
        $sql = "
            SELECT COUNT(*) as total
            FROM sos_helpers
            WHERE sos_id = :sos_id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':sos_id' => $sosId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}