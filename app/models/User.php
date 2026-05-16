<?php

require_once "../core/Model.php";

class User extends Model
{
    public function create($data)
    {
        $sql = "
            INSERT INTO users(
                name,
                email,
                password
            )
            VALUES(
                :name,
                :email,
                :password
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([

            ':name' => $data['name'],

            ':email' => $data['email'],

            ':password' => $data['password']
        ]);
    }

    public function findByEmail($email)
    {
        $sql = "
            SELECT *
            FROM users
            WHERE email = :email
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':email' => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function totalUsers()
    {
        $sql = "
            SELECT COUNT(*) as total
            FROM users
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}