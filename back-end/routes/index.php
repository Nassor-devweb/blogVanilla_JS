<?php

require_once('../controllers/article.php');


//------------------------GET----------------------

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = file_get_contents('php://input');
    $data = json_decode($data, true);
    getAllArticle();
}


//------------------------DELETE----------------------

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = file_get_contents('php://input');
    $data = json_decode($data, true);
    deleteArticle($data);
}