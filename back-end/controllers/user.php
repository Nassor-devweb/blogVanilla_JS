<?php

require_once('../middlewares/class_ConnexionDb.php');

$pdo = ConnexionDb::connectDb();


function loginUser($data_user)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM user WHERE user_email = :user_email');
    $stmt->bindValue(':user_email', $data_user['user_email']);
    $errQuerry = null;
    $dataUser = null;
    try {
        $stmt->execute();
        $dataUser = $stmt->fetch();
        $error = new PDOException("Vous n'êtes pas inscris");
        if (gettype($dataUser) !== 'array') {
            throw $error;
        };
    } catch (PDOException $err) {
        $errQuerry = $err->getMessage();
    }
    if (is_null($errQuerry)) {
        if (password_verify($data_user['user_password'], $dataUser['user_password'])) {
            $stmtSession = $pdo->prepare('INSERT INTO session VALUES(
                    DEFAULT,
                    :id_user
                )');
            $stmtSession->bindValue(':id_user', $dataUser['user_id']);
            $stmtSession->execute();
            $id_session = $pdo->lastInsertId();
            setcookie('session', $id_session, time() + 60 * 60 * 24, "", "", false, true);
            //echo json_encode($dataUser);
        } else {
            http_response_code(401);
            $err = ['erreur' => "Le mot de passe est incorrect"];
            echo json_encode($err);
        }
    } else {
        http_response_code(401);
        $err = ['erreur' => "Vous n'êtes pas inscrit, veuillez vous inscrir"];
        echo json_encode($err);
    }
}

function saveUser($data_user)
{
    if (isset($_FILES['user_photo'])) {
        $path_photo = 'images/' . time() . '-' . basename($_FILES['user_photo']['name']);
        move_uploaded_file($_FILES['user_photo']['tmp_name'], $path_photo);
        $user_photo_path = 'http://localhost:3000/' . $path_photo;
        $data_user['user_photo'] = $user_photo_path;

        $stmt = $pdo->prepare('INSERT INTO user VALUES(
            DEFAULT,
            :user_name,
            :user_email,
            :user_password,
            :user_photo
        )');
        $stmt->bindValue(':user_name', $data_user['user_name']);
        $stmt->bindValue(':user_email', $data_user['user_email']);
        $stmt->bindValue(':user_password', $data_user['user_password']);
        $stmt->bindValue(':user_photo', $data_user['user_photo']);

        $errQuerry = null;

        try {
            $stmt->execute();
        } catch (PDOException $err) {
            $errQuerry = $err;
        }

        if (is_null($errQuerry)) {
            $id_user = $pdo->lastInsertId();
            $userStmt = $pdo->prepare('SELECT * FROM user WHERE user_id = :user_id');
            $userStmt->bindValue(':user_id', $id_user);
            $userStmt->execute();
            $dataUser = $userStmt->fetch();
            $response = json_encode($dataUser);
            http_response_code(201);
            echo $response;
        } else {
            http_response_code(401);
            $err = ['erreur' => 'Vous êtes déjà inscrit !!!'];
            echo json_encode($err);
        }
    };
}
