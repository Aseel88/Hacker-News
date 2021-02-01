<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';


if (isset($_POST['email'], $_POST['password'], $_POST['confirmPassword'])) {
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));


    $statement = $pdo->prepare('SELECT email FROM users WHERE email = :email');
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();
    $isEmail = $statement->fetch(PDO::FETCH_ASSOC);

    if ($isEmail) {
        errorMessage("Email already exists. Try to log in.");
        redirect('/login.php');
    } else {
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
}

redirect('/');
