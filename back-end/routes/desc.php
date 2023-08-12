<?php
require_once('../controllers/article.php');
require_once('../middlewares/ClassAuth.php');

$isConnect = Auth::verifyIsConnect();


if ($isConnect) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        filterArticleDesc($data);
    }
} else {
    http_response_code(401);
    $erreur = ['erreur' => "Veuillez vous authentifier !!!"];
    echo json_encode($erreur);
}
