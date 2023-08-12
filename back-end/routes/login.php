<?php

require_once('../middlewares/Cors.php');
require_once('../controllers/user.php');

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

        loginUser($data_user);
    }
}
