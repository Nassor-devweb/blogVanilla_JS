<?php

require_once('../middlewares/Cors.php');
require_once('../controllers/article.php');
require_once('../middlewares/ClassAuth.php');
require_once('../controllers/user.php');

$isConnect = Auth::verifyIsConnect();


if ($isConnect) {
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
    if ($_SERVER['REQUEST_METHOD']  === 'GET') {
        getDataUser($isConnect);
    }
} else {
    http_response_code(401);
    $erreur = ['erreur' => "Veuillez vous authentifier !!!"];
    echo json_encode($erreur);
}
