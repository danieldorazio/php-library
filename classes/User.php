<?php

class UserManager extends DBManager
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'users';
        $this->columns = ['id', 'name', 'email', 'password', 'created_at'];
    }

    public function login(string $email, string $password)
    {
        $query = $this->db->query("
            SELECT * 
            FROM users
            WHERE email = '$email'
            AND password = '$password';
            ");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        //LOGICA DI SESSIONE
        return $result;
    }
}
