<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS, post, get');
header("Access-Control-Max-Age", "3600");
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
header("Access-Control-Allow-Credentials", "true");


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
            echo $user_photo_path;
        };
    } else {
        http_response_code(400);
        $err = [];
        $err['erreur'] = $error;
        echo json_encode($err);
    }
}
