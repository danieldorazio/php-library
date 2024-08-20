<?php
// require_once './classes/DB.php';
// require_once './classes/Book.php';
require_once './inc/config.php';

$bookManager = new BookManager();


// Cattura il metodo HTTP (GET, POST, PUT, DELETE)
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Cattura eventuale ID dal query string
$id = $_GET['id'] ?? null;

// Cattura eventuale isbn dal query string
$isbn = $_GET['isbn'] ?? null;

// Gestisci le richieste
switch ($requestMethod) {
    case 'GET':
        if ($id) {
            //ottieni il singolo libro http://localhost/progetti/php-library/?resource=books&id=1
            $book = $bookManager->get((int) $id);
            echo json_encode($book);
            break;
        } elseif ($isbn) {
            //ottieni il singolo libro tramite isbn
            $book = $bookManager->getByIsbn((int) $isbn);
            echo json_encode($book);
            break;
        } else {
            //ottieni tutti i libri http://localhost/progetti/php-library/?resource=books
            $books = $bookManager->getAll();
            echo json_encode($books);
            break;
        }

    case 'POST':
        // Crea un nuovo libro o aumenta la quantità
        $input = json_decode(file_get_contents('php://input'), true);
        $result = $bookManager->createQuantityBooks($input);
        echo json_encode($result);
        break;

    case 'PUT':
        //Aggiorna un libro o riduci la quantità
        $input = json_decode(file_get_contents('php://input'), true);
        $result = $bookManager->removeQuantityBooks($input);
        echo json_encode($result);
        break;

    case 'DELETE':
        // Eleminare un libro
        if ($id) {
            $result = $bookManager->delete((int) $id);
            echo json_encode($result);
        } else {
            echo json_encode(["message" => "ID non fornito"]);
        }
        break;

    default:
        echo json_encode(["message" => "Metodo non supportato"]);
        break;
}
