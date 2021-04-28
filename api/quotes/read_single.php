<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once('../../config/Database.php');
    include_once('../../models/Quote.php');

    $database = new Database();
    $db = $database->connect();

    $quote = new Quote($db);

    $quote->id = isset($_GET['id']) ? $_GET['id'] : die();

    $quote->readOne();

    $quoteArray = array(
        'id' => $quote->id,
        'category' => $quote->category,
        'author' => $quote->author,
        'quote' => $quote->quote
    );

    print_r(json_encode($quoteArray));