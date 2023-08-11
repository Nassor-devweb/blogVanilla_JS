<?php

require_once('./Cors.php');

function sanatizeData($data)
{
    $data_filter = filter_var_array($data, [
        'content_article' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'content_article' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    ]);
    return $data_filter;
}


function saveArticle(array $data)
{
    global $pdo;
    global $isConnect;

    $date_created = date(DateTime::ATOM, time());
    $stmt = $pdo->prepare('INSERT INTO article VALUES(
        DEFAULT,
        :auteur_article,
        :category_article,
        :content_article,
        :date_created,
        :id_user,
    )');
    $stmt->bindValue(':auteur_article', $isConnect['user_name']);
    $stmt->bindValue(':category_article', $data['category_article']);
    $stmt->bindValue(':content_article', $data['content_article']);
    $stmt->bindValue(':date_created', $date_created);
    $stmt->bindValue(':id_user', $isConnect['id_user']);
    $stmt->execute();
}




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = file_get_contents('php://input');
    $dataDecoded = json_decode($content, true);
    $data = sanatizeData($dataDecoded);
    saveArticle($data);
}
