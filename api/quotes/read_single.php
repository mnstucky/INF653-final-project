<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require('../../config/Database.php');
    require('../../models/Quote.php');

    $database = new Database();
    $db = $database->connect();

    $quote = new Quote($db);

    $quote->id = isset($_GET['id']) ? filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) : die();

    $quote->readOne();

    $quoteArray = array(
        'id' => $quote->id,
        'category' => $quote->category,
        'author' => $quote->author,
        'quote' => $quote->quote
    );

    echo json_encode($quoteArray);