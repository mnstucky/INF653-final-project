<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once('../../config/Database.php');
    include_once('../../models/Category.php');

    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);

    $category->id = isset($_GET['id']) ? $_GET['id'] : die();

    $category->readOne();

    $categoryArray = array(
        'id' => $category->id,
        'category' => $category->category,
    );

    print_r(json_encode($categoryArray));