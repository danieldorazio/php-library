<?php

class BookManager extends DBManager
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'books';
        $this->columns = ['id', 'title', 'author', 'genre', 'published_year', 'isbn', 'quantity', 'created_at'];
    }

    public function getByIsbn(int $isbn)
    {
        $strCol = implode(', ', $this->columns);
        $query = "SELECT $strCol FROM $this->tableName WHERE isbn = :isbn";

        $stmt = $this->db->query($query, ['isbn' => $isbn]);
        return (array) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createBooks(array $arr)
    {
        $getQuery = $this->getByIsbn($arr['isbn']);

        if (array_key_exists('quantity',$getQuery)) {
            $newQuantity = $getQuery['quantity'] + $arr['quantity'];
            return $this->update(['quantity' => $newQuantity], $getQuery['id']);
        } else {
            return $this->create($arr);
        }
    }

}
