<?php

require_once('../middlewares/class_ConnexionDb.php');
require_once('../middlewares/ClassAuth.php');
$pdo = ConnexionDb::connectDb();
$isConnect = Auth::verifyIsConnect();


function sanatizeDataArticle($data)
{
    $data_filter = filter_var_array($data, [
        'category_article' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'content_article' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    ]);
    return $data_filter;
}


function saveArticle(array $data)
{
    global $pdo;
    global $isConnect;
    print_r($isConnect);
    $date_created = date(DateTime::ATOM, time());
    $stmt = $pdo->prepare('INSERT INTO article VALUES(
        DEFAULT,
        :auteur_article,
        :category_article,
        :content_article,
        :date_created,
        :id_user
    )');
    $stmt->bindValue(':auteur_article', $isConnect['user_name']);
    $stmt->bindValue(':category_article', $data['category_article']);
    $stmt->bindValue(':content_article', $data['content_article']);
    $stmt->bindValue(':date_created', $date_created);
    $stmt->bindValue(':id_user', $isConnect['user_id']);
    $stmt->execute();
}

function updateArticle($data_article)
{
    global $pdo;
    $stmt = $pdo->prepare('UPDATE article SET 
    category_article = :category_article,
    content_article = :content_article
    WHERE id_article = :id_article');
    $stmt->bindValue(':category_article', $data_article['category_article']);
    $stmt->bindValue(':content_article', $data_article['content_article']);
    $stmt->bindValue(':id_article', $data_article['id_article']);
    $stmt->execute();
}

function deleteArticle($data_article)
{
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM article WHERE id_article = :id_article');
    $stmt->bindValue(':id_article', $data_article['id_article']);
    $stmt->execute();
}

function getAllArticle()
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT auteur_article, category_article, content_article, date_created,id_article,user.user_id, user.user_photo FROM article INNER JOIN user ON user.user_id = article.id_user  ORDER BY date_created DESC');
    $stmt->execute();
    $allArticle = $stmt->fetchAll();
    echo json_encode($allArticle);
}

function filterArticleAsc($data)
{
    global $pdo;
    $stmt = ($data['id_user']) ? $pdo->prepare('SELECT * FROM article WHERE id_user = :id_user ORDER BY date_created ASC') : $pdo->prepare('SELECT * FROM article ORDER BY date_created ASC');
    if ($data['id_user']) {
        $stmt->bindValue(':id_user', $data['id_user']);
    }
    $stmt->execute();
    $articles = $stmt->fechAll();
    echo json_encode($articles);
}

function filterArticleDesc($data)
{
    global $pdo;
    $stmt = ($data['id_user']) ? $pdo->prepare('SELECT * FROM article WHERE id_user = :id_user ORDER BY date_created DESC') : $pdo->prepare('SELECT * FROM article ORDER BY date_created DESC');
    if ($data['id_user']) {
        $stmt->bindValue(':id_user', $data['id_user']);
    }
    $stmt->execute();
    $articles = $stmt->fechAll();
    echo json_encode($articles);
}
