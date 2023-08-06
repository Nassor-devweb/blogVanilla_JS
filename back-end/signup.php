<?php

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
    } else {
        http_response_code(400);
        $err = [];
        $err['erreur'] = $error;
        echo json_encode($err);
    }
}
