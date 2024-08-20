<?php

class BookManager extends DBManager
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'books';
        $this->columns = ['id', 'title', 'author', 'genre', 'published_year', 'isbn', 'quantity', 'created_at'];
    }



    // metodo di ricerca libro tramite isbn
    public function getByIsbn(int $isbn)
    {
        $strCol = implode(', ', $this->columns);
        $query = "SELECT $strCol FROM $this->tableName WHERE isbn = :isbn";

        $stmt = $this->db->query($query, ['isbn' => $isbn]);
        return (array) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // metodo di incremento copie o creazione nuovo libro, da usare in caso di restituzione
    public function createQuantityBooks(array $arr)
    {
        // ricerca libro gia esistente tramite isbn
        $getQuery = $this->getByIsbn($arr['isbn']);

        // funzione protetta di DBManager per settare l'ora attuale
        $arr['created_at'] = $this->setCurrentData();

        // logica di incremento quantità libro o creazione nuovo libro 
        if (array_key_exists('quantity', $getQuery)) {
            $newQuantity = $getQuery['quantity'] + $arr['quantity'];
            return $this->update(['quantity' => $newQuantity], $getQuery['id']);
        } else {
            return $this->create($arr);
        }
    }

    // metodo di decremento copie o segnalazione copie rimanenti, da usare in caso di prestito
    public function removeQuantityBooks(array $arr)
    {
        // ricerca copie disponibili
        $getQuery = $this->getByIsbn($arr['isbn']);

        // logica di decremento quantità o segnalazione quantità rimanente
        if ($getQuery['quantity'] >= $arr['quantity']) {
            $newQuantity = $getQuery['quantity'] - $arr['quantity'];
            return $this->update(['quantity' => $newQuantity], $getQuery['id']);
        } else {
            echo "numero copie non disponibili, copie restanti " . $getQuery['quantity'];
        }
    }
}
