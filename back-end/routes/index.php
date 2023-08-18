<?php

require_once('../middlewares/Cors.php');
require_once('../controllers/article.php');
require_once('../middlewares/ClassAuth.php');

$isConnect = Auth::verifyIsConnect();

if ($isConnect) {
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
} else {
    http_response_code(401);
}
