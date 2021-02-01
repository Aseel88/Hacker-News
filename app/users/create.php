<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

// $msgs = [];
// $errors = [];

$statement = $pdo->query('SELECT email FROM users');
if (!$statement) {
    die(var_dump($pdo->errorInfo()));
}
$emails = $statement->fetchAll(PDO::FETCH_ASSOC);
// die(var_dump($emails));
if (isset($_POST['email'], $_POST['password'], $_POST['confirmPassword'])) {
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    // if (in_array($_POST['email'], $emails)) {
    //     echo 'This email is already registered';
    // } else {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    $hash = password_hash($password, PASSWORD_DEFAULT);

    if ($password !== $confirmPassword) {
        errorMessage("Please Check Your Password!");
        redirect('/create.php');
    }


    $query = 'INSERT INTO users (email, password) VALUES (:email, :hash)';
    $statement = $pdo->prepare($query);

    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }

    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->bindParam(':hash', $hash, PDO::PARAM_STR);


    $statement->execute();
    redirect('/welcome.php');
}



redirect('/');
