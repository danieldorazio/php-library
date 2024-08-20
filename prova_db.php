<?php 

include './inc/config.php';

$db = new DB();

$query = $db->query("SELECT * FROM users");
$result = $query->fetchAll(PDO::FETCH_ASSOC);
// var_dump($result);


$query = $db->select_all("users", ["*"]);
// var_dump($query);

$query = $db->select_one("users", 2, ["*"]);
// var_dump($query);

// restituisce il numero di righe cancellate 
$query = $db->delete_one("users", 3);
// var_dump($query);

$query = $db->update_one("users", 4, ['name' => 'luca', 'email' => 'luca@mail.it']);
// var_dump($query);

$query = $db->insert_one("users", ['name' => 'daniel', 'email' => 'daniel@mail.it', 'password' => 'password', 'created_at' => '2020-12-10 12:00:00']);
// var_dump($query);

$UserM = new UserManager();

$query = $UserM->login("luca@mail.it", "password");
// var_dump($query);

$book = new BookManager();

// $book->createBooks(['title' => 'test2',
// 'author' => 'Harper Lee',
// 'genre' => 'Drammatico',
// 'published_year' => 1960,
// 'isbn' => '2',
// 'quantity' => 10]);

// $book->removeQuantityBooks(['title' => 'test2',
//  'author' => 'Harper Lee',
//  'genre' => 'Drammatico',
//  'published_year' => 1960,
//  'isbn' => '2',

var_dump($book->get(1));
?>