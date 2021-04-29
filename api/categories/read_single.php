<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require('../../config/Database.php');
    require('../../models/Category.php');

    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);

    $category->id = isset($_GET['id']) ? filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) : die();

    $category->readOne();

    $categoryArray = array(
        'id' => $category->id,
        'category' => $category->category,
    );

    if (empty($categoryArray['category'])) {
        echo json_encode(
            array('message' => "No category found.")
        );
    } else {
        echo json_encode($categoryArray);
    }