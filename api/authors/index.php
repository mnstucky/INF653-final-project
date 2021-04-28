<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once('../../config/Database.php');
    include_once('../../models/Author.php');

    $database = new Database();
    $db = $database->connect();

    $author = new Author($db);
    $result = $author->read();
    $numResults = $result->rowCount();

    if ($numResults > 0) {
        $authorArray = array();
        $authorArray['data'] = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $authorItem = array('id' => $id, 'author' => $author);
            array_push($authorArray['data'], $authorItem);
        }
        echo json_encode($authorArray);
    } else {
        echo json_encode(
            array('message' => "No authors found.")
        );
    }