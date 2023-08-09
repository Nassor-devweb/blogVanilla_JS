<?php

include_once('./Cors.php');
include_once('./class_ConnexionDb.php');


$pdo = ConnexionDb::connectDb();

$errors = [
    'name_user' => "Le nom d'utilisateur est incorrect 2 lettre minimale et 8 maximale",
    'password_user' => 'Le mots de passe est incorrect 5 lettre minimale et 14 maximale',
    'email_user' => "L'adresse email est incorrect"
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_password = $_POST['user_password'];
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];

    $error = match (true) {
        mb_strlen(trim($user_password)) < 5 || mb_strlen(trim($user_password)) > 14 => $errors['password_user'],
        mb_strlen(trim($user_name)) < 2 || mb_strlen(trim($user_name)) > 8 => $errors['name_user'],
        filter_var($user_email, FILTER_VALIDATE_EMAIL) === false => $errors['email_user'],
        default => ''
    };

    if (!$error) {
        $data_user = filter_var_array($_POST, [
            'user_name' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'user_email' => FILTER_SANITIZE_EMAIL,
        ]);
        $data_user['user_password'] = password_hash($user_password, PASSWORD_ARGON2I);
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
    } else {
        http_response_code(400);
        $err = [];
        $err['erreur'] = $error;
        echo json_encode($err);
    }
}
