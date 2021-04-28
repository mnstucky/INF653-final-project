<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require('../../config/Database.php');
    require('../../models/Category.php');

    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);
    $result = $category->read();
    $numResults = $result->rowCount();

    if ($numResults > 0) {
        $categoryArray = array();
        $categoryArray['data'] = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $categoryItem = array('id' => $id, 'category' => $category);
            array_push($categoryArray['data'], $categoryItem);
        }
        echo json_encode($categoryArray);
    } else {
        echo json_encode(
            array('message' => "No categories found.")
        );
    }