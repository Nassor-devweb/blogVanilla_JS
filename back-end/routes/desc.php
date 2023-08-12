<?php
require_once('../controllers/article.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = file_get_contents('php://input');
    $data = json_decode($data, true);
    filterArticleDesc($data);
}
