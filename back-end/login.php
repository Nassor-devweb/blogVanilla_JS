<?php

require_once('./Cors.php');
require_once('./class_ConnexionDb.php');

$pdo = ConnexionDb::connectDb();

$errors = [
    'password_user' => 'Veuillez saisir votre mot de passe',
    'email_user' => "L'adresse email est incorrect"
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $data = json_decode($data, true);
    $user_password = $data['user_password'];
    $user_email = $data['user_email'];


    $error = match (true) {
        trim($user_password) === false => $errors['password_user'],
        filter_var(trim($user_email), FILTER_VALIDATE_EMAIL) === false => $errors['email_user'],
        default => ''
    };

    if (!$error) {
        $data_user = filter_var_array($data, [
            'user_email' => FILTER_SANITIZE_EMAIL
        ]);
        $data_user['user_password'] = $user_password;
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
            if (password_verify($user_password, $dataUser['user_password'])) {
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
}
