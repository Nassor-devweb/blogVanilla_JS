<?php

require_once('../middlewares/Cors.php');
require_once('../controllers/user.php');
require_once('../middlewares/ClassAuth.php');

$isConnect = Auth::verifyIsConnect();

if ($isConnect) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        getDataUser($isConnect);
    }
} else {
    http_response_code(401);
}
