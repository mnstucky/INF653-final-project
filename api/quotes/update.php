<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once('../../config/Database.php');
    include_once('../../models/Quote.php');

    $database = new Database();
    $db = $database->connect();

    $quote = new Quote($db);

    $data = json_decode(file_get_contents('php://input'));

    if (!isset($data->categoryId) || !isset($data->authorId) || !isset($data->quote) || !isset($data->id)) {
        echo json_encode(
            array('message' => 'Quote Not Updated')
        );
        die();
    }

    $quote->categoryId = $data->categoryId;
    $quote->authorId = $data->authorId;
    $quote->quote = $data->quote;
    $quote->id = $data->id;

    if ($quote->update()) {
        echo json_encode(
            array('message' => 'Quote Updated')
        );
    } else {
        echo json_encode(
            array('message' => 'Quote Not Updated')
        );
    }