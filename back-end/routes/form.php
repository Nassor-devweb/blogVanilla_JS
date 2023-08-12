<?php

require_once('./Cors.php');
require_once('../controllers/article.php');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = file_get_contents('php://input');
    $dataDecoded = json_decode($content, true);
    $data = sanatizeDataArticle($dataDecoded);
    saveArticle($data);
}

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $content = file_get_contents('php://input');
    $dataDecoded = json_decode($content, true);
    $data = sanatizeDataArticle($dataDecoded);
    updateArticle($data);
}
