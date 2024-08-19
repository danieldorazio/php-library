<?php

class DB
{
    private $pdo;
    private static $instance;


    // Singleton pattern: crea una sola istanza della classe DBF
    public function __construct()
    {
        // Costruisce la stringa DSN 
        $dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;
        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS);
            // Imposta PDO per lanciare eccezioni in caso di errore
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Gestisce l'eccezione in caso di fallimento della connessione
            die('Connessione al databese fallita:' . $e->getMessage());
        }
    }

    // Il metodo getInstance() garantisce che ci sia una sola istanza della classe in tutto il programma.
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    // restituisce la connessione PDO gestita da quella istanza.
    public function getConnection()
    {
        return $this->pdo;
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // CRUD methods
    public function select_all(string $tableName, array $columns = [])
    {
        $strCol = implode(', ', $columns);
        $query = "SELECT $strCol FROM $tableName";

        $stmt = $this->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function select_one(string $tableName, int $id, array $columns = [])
    {
        $strCol = implode(', ', $columns);
        $query = "SELECT $strCol FROM $tableName WHERE id = :id";

        $stmt = $this->query($query, ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete_one(string $tableName, int $id)
    {
        $query = "DELETE FROM $tableName WHERE id = :id";

        $stmt = $this->query($query, ['id' => $id]);
        return $stmt->rowCount();
    }

    public function update_one(string $tableName, int $id, array $columns = [])
    {
        $setStr = '';
        foreach ($columns as $colName => $colValue) {
            $setStr .= "$colName = :$colName,";
        }
        $setStr = trim($setStr, ",");

        $query = "UPDATE $tableName SET $setStr WHERE id = :id";
        $columns['id'] = $id;

        $stmt = $this->query($query, $columns);
        return $stmt->rowCount();
    }

    public function insert_one(string $tableName, array $columns = [])
    {
        $colNames = implode(',', array_keys($columns));
        $colPlaceholders = implode(',', array_fill(0, count($columns), '?'));

        $query = "INSERT INTO $tableName ($colNames) VALUE ($colPlaceholders)";

        $stmt = $this->query($query, array_values($columns));
        return $this->pdo->lastInsertId();
    }
}

class DBManager
{
    protected object $db;
    protected array $columns;
    protected string $tableName;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function get(int $id): object
    {
        return (object) $this->db->select_one($this->tableName, (int) $id, $this->columns);
    }

    public function getAll()
    {
        $results = $this->db->select_all($this->tableName, $this->columns);
        $array = [];
        foreach ($results as $result) {
            array_push($array, (object) $result);
        }
        return $array;
    }

    public function create(array $obj) 
    {
        return $this->db->insert_one($this->tableName, (array) $obj);
    }

    public function delete(int $id) 
    {
        return $this->db->delete_one($this->tableName, (int) $id);
    }

    public function update(array $obj, int $id)
    {
        return $this->db->update_one($this->tableName, (int) $id, (array) $obj);
    }
}
