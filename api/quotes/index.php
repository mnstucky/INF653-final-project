<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once('../../config/Database.php');
    include_once('../../models/Quote.php');

    $database = new Database();
    $db = $database->connect();

    $quote = new Quote($db);

    if (isset($_GET['authorId']) && isset($_GET['categoryId'])) {
        $quote->authorId = $_GET['authorId'];
        $quote->categoryId = $_GET['categoryId'];
        $result = $quote->readByAuthorAndCategory();
    } else if (isset($_GET['authorId'])) {
        $quote->authorId = $_GET['authorId'];
        $result = $quote->readByAuthor();
    } else if (isset($_GET['categoryId'])) {
        $quote->categoryId = $_GET['categoryId'];
        $result = $quote->readByCategory();
    } else if (isset($_GET['limit'])) {
        $quote->limit = $_GET['limit'];
        $result = $quote->readWithLimit();
    } else {
        $result = $quote->read();
    }

    $numResults = $result->rowCount();

    if ($numResults > 0) {
        $quotesArray = array();
        $quotesArray['data'] = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $quoteItem = array('id' => $id, 'category' => $category, 'author' => $author, 'quote' => $quote);
            array_push($quotesArray['data'], $quoteItem);
        }
        echo json_encode($quotesArray);
    } else {
        echo json_encode(
            array('message' => "No quotes found.")
        );
    }