<?php
header("Content-Type: application/json");

// Questo codice cattura la risorsa richiesta tramite la query string (?resource=books) e reindirizza al file API appropriato
$requestMethod = $_SERVER['REQUEST_METHOD'];
$resource = $_GET['resource'] ?? null;
$id = $_GET['id'] ?? null;

switch ($resource) {
    case 'books':
        require_once './api/books.php';
        break;
    default:
        echo json_encode(["message" => "Risorsa non trovata"]);
        break;
}
